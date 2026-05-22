<!DOCTYPE html>
<html>
<head>
    <title>Статистика посещений</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { margin-bottom: 20px; color: #333; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px; }
        .stat-card { background: white; border-radius: 12px; padding: 20px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .stat-number { font-size: 36px; font-weight: bold; color: #4CAF50; }
        .stat-label { color: #666; margin-top: 8px; font-size: 14px; }
        .charts-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .chart-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .chart-card h3 { margin-bottom: 20px; color: #333; }
        canvas { max-height: 400px; width: 100%; }
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr; }
            .charts-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Статистика посещений</h1>
        
        <div class="stats-grid" id="totalStats"></div>
        
        <div class="charts-grid">
            <div class="chart-card">
                <h3>📈 Посещения по часам</h3>
                <canvas id="hourlyChart"></canvas>
            </div>
            
            <div class="chart-card">
                <h3>🌍 Распределение по городам</h3>
                <canvas id="cityChart"></canvas>
            </div>
        </div>
    </div>
    
    <script>
        let hourlyChart, cityChart;
        
        async function loadStats() {
            try {
                const response = await fetch('/api/stats');
                const data = await response.json();
                
                if (data.success) {
                    updateTotalStats(data.total);
                    updateHourlyChart(data.hourly);
                    updateCityChart(data.cities);
                }
            } catch (error) {
                console.error('Failed to load stats:', error);
            }
        }
        
        function updateTotalStats(total) {
            const container = document.getElementById('totalStats');
            container.innerHTML = `
                <div class="stat-card">
                    <div class="stat-number">${total.total_visits}</div>
                    <div class="stat-label">Всего визитов</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${total.unique_visitors}</div>
                    <div class="stat-label">Уникальных посетителей</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${total.today_visits}</div>
                    <div class="stat-label">Визитов сегодня</div>
                </div>
            `;
        }
        
        function updateHourlyChart(data) {
            const hours = data.map(item => `${item.hour}:00`);
            const uniqueVisitors = data.map(item => item.unique_visitors);
            
            if (hourlyChart) hourlyChart.destroy();
            
            const ctx = document.getElementById('hourlyChart').getContext('2d');
            hourlyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: hours,
                    datasets: [{
                        label: 'Уникальные посетители',
                        data: uniqueVisitors,
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'top' } },
                    scales: { y: { beginAtZero: true, title: { display: true, text: 'Количество' } } }
                }
            });
        }
        
        function updateCityChart(data) {
            const cities = data.map(item => item.city || 'Неизвестно');
            const counts = data.map(item => item.unique_visitors);
            
            if (cityChart) cityChart.destroy();
            
            const ctx = document.getElementById('cityChart').getContext('2d');
            cityChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: cities,
                    datasets: [{
                        data: counts,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'right' } }
                }
            });
        }
        
        loadStats();
        setInterval(loadStats, 30000);
    </script>
</body>
</html>
