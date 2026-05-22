(function() {
    'use strict';

    const API_URL = window.location.origin + '/api/track';

    function generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    function getVisitorId() {
        let visitorId = localStorage.getItem('_visitor_id');
        if (!visitorId) {
            visitorId = generateUUID();
            localStorage.setItem('_visitor_id', visitorId);
        }
        return visitorId;
    }

    function getDeviceInfo() {
        const ua = navigator.userAgent;

        let deviceType = 'desktop';
        if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
            deviceType = 'tablet';
        } else if (/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
            deviceType = 'mobile';
        }

        let browser = 'Unknown';
        if (ua.includes('Chrome')) browser = 'Chrome';
        else if (ua.includes('Firefox')) browser = 'Firefox';
        else if (ua.includes('Safari')) browser = 'Safari';
        else if (ua.includes('Edge')) browser = 'Edge';
        else if (ua.includes('MSIE') || ua.includes('Trident')) browser = 'Internet Explorer';

        let os = 'Unknown';
        if (ua.includes('Windows')) os = 'Windows';
        else if (ua.includes('Mac')) os = 'MacOS';
        else if (ua.includes('Linux')) os = 'Linux';
        else if (ua.includes('Android')) os = 'Android';
        else if (ua.includes('iOS') || ua.includes('iPhone') || ua.includes('iPad')) os = 'iOS';

        return { deviceType, browser, os };
    }

    async function getGeoInfo() {
        try {
            const response = await fetch('https://ipapi.co/json/');
            const data = await response.json();
            return {
                city: data.city || 'Unknown',
                country: data.country_name || 'Unknown'
            };
        } catch (error) {
            console.warn('[Tracker] GeoIP failed, using default');
            return { city: 'Unknown', country: 'Unknown' };
        }
    }

    async function sendTrackingData(geoData, deviceInfo) {
        const data = {
            visitor_id: getVisitorId(),
            city: geoData.city,
            country: geoData.country,
            device_type: deviceInfo.deviceType,
            browser: deviceInfo.browser,
            os: deviceInfo.os,
            page_url: window.location.href,
            referrer: document.referrer || null
        };

        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                console.log('[Tracker] Visit tracked');
            }
        } catch (error) {
            console.error('[Tracker] Failed:', error);
        }
    }

    async function init() {
        const deviceInfo = getDeviceInfo();
        const geoData = await getGeoInfo();
        await sendTrackingData(geoData, deviceInfo);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
