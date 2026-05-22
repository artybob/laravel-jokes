(function() {
    'use strict';

    const CONFIG = {
        selectSelector: 'select[name="тип"]',
        fieldsContainerSelector: '.fields-container',
        fieldSelector: 'input, select, textarea',
        attributeToCheck: 'name',
        debug: false
    };

    let typeSelect = null;
    let allFields = [];

    function getSelectedType() {
        if (!typeSelect) return '';
        return typeSelect.value.trim().toLowerCase();
    }

    function shouldFieldBeVisible(field, selectedType) {
        if (!selectedType) return true;
        const fieldName = field.getAttribute(CONFIG.attributeToCheck);
        if (!fieldName) return false;
        return fieldName.toLowerCase().includes(selectedType);
    }

    function updateFieldsVisibility() {
        const selectedType = getSelectedType();
        let visibleCount = 0;

        allFields.forEach(field => {
            const shouldShow = shouldFieldBeVisible(field, selectedType);

            if (shouldShow) {
                field.style.display = '';
                if (field.dataset.requiredBackup === 'true') {
                    field.required = true;
                }
                visibleCount++;
            } else {
                if (field.required) {
                    field.dataset.requiredBackup = 'true';
                    field.required = false;
                }
                field.style.display = 'none';
            }
        });

        if (CONFIG.debug) {
            console.log(`[DynamicFields] Selected: "${selectedType}", Visible: ${visibleCount}/${allFields.length}`);
        }

        const event = new CustomEvent('fieldsUpdated', {
            detail: { selectedType, visibleCount }
        });
        document.dispatchEvent(event);
    }

    function refreshFieldsList() {
        const container = document.querySelector(CONFIG.fieldsContainerSelector) || document.body;
        const fields = Array.from(container.querySelectorAll(CONFIG.fieldSelector));
        allFields = fields.filter(field => field.hasAttribute(CONFIG.attributeToCheck));

        if (CONFIG.debug) {
            console.log(`[DynamicFields] Found ${allFields.length} fields with name attribute`);
        }
    }

    function init() {
        typeSelect = document.querySelector(CONFIG.selectSelector);

        if (!typeSelect) {
            console.warn('[DynamicFields] Select element not found:', CONFIG.selectSelector);
            return;
        }

        refreshFieldsList();
        typeSelect.addEventListener('change', updateFieldsVisibility);
        updateFieldsVisibility();

        const observer = new MutationObserver(() => {
            refreshFieldsList();
            updateFieldsVisibility();
        });

        const container = document.querySelector(CONFIG.fieldsContainerSelector) || document.body;
        observer.observe(container, { childList: true, subtree: true });

        if (CONFIG.debug) {
            console.log('[DynamicFields] Initialized successfully');
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.DynamicFields = {
        refresh: updateFieldsVisibility,
        getVisibleFields: () => allFields.filter(f => f.style.display !== 'none'),
        setDebug: (enabled) => { CONFIG.debug = enabled; }
    };
})();
