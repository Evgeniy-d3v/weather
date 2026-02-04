<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Расписание</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>

    <style>
        body {
            font-family: system-ui;
            margin: 12px;
            padding-bottom: 90px;
            background: #0f1b26;
            color: #fff;
        }

        h3 {
            color: #1c8ed6;
            font-weight: 600;
        }

        .row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .grid {
            display: grid;
            grid-auto-flow: column;
            grid-template-rows: repeat(6, 1fr);
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }

        button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background: #fff;
            color: #000;
            cursor: pointer;
        }

        button.active {
            border-color: #000;
            font-weight: 700;
        }

        .day {
            position: relative;
            padding: 8px 10px;
        }

        .hour {
            position: relative;
        }

        .filled::after {
            content: "✓";
            position: absolute;
            top: -6px;
            right: -6px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 1px solid #000;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
            line-height: 1;
        }

        .actions {
            position: fixed;
            right: 12px;
            bottom: 12px;
            display: flex;
            gap: 10px;
            z-index: 100;
        }

        .actions button {
            padding: 14px 16px;
            border-radius: 14px;
            font-size: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>

<h3>Выбери день</h3>
<div class="row" id="days"></div>

<h3>Часы (0–23)</h3>
<div class="grid" id="hours"></div>

<div class="actions">
    <button id="clear">🧹 Очистить день</button>
    <button id="save">💾 Сохранить</button>
</div>

<script>
    const tg = window.Telegram.WebApp;
    tg.expand();

    const dayLabels = [
        {id: 1, name: 'Пн'},
        {id: 2, name: 'Вт'},
        {id: 3, name: 'Ср'},
        {id: 4, name: 'Чт'},
        {id: 5, name: 'Пт'},
        {id: 6, name: 'Сб'},
        {id: 7, name: 'Вс'},
    ];

    const schedule = {};
    let currentDay = 1;

    function ensureDay(day) {
        const key = String(day);
        if (!schedule[key]) schedule[key] = [];
    }

    function toggleHour(day, hour) {
        ensureDay(day);
        const list = schedule[String(day)];
        const idx = list.indexOf(hour);
        if (idx === -1) list.push(hour);
        else list.splice(idx, 1);
        list.sort((a, b) => a - b);
    }

    function renderDays() {
        const el = document.getElementById('days');
        el.innerHTML = '';

        dayLabels.forEach(d => {
            const key = String(d.id);
            const btn = document.createElement('button');

            btn.classList.add('day');
            if (d.id === currentDay) btn.classList.add('active');
            if ((schedule[key]?.length ?? 0) > 0) btn.classList.add('filled');

            btn.textContent = d.name;
            btn.onclick = () => {
                currentDay = d.id;
                renderDays();
                renderHours();
            };

            el.appendChild(btn);
        });
    }

    function renderHours() {
        ensureDay(currentDay);
        const selected = new Set(schedule[String(currentDay)]);
        const el = document.getElementById('hours');
        el.innerHTML = '';

        for (let h = 0; h < 24; h++) {
            const btn = document.createElement('button');
            btn.classList.add('hour');
            btn.textContent = String(h).padStart(2, '0') + ':00';

            if (selected.has(h)) {
                btn.classList.add('active', 'filled');
            }

            btn.onclick = () => {
                toggleHour(currentDay, h);
                renderHours();
                renderDays();
            };

            el.appendChild(btn);
        }
    }

    document.getElementById('clear').onclick = () => {
        schedule[String(currentDay)] = [];
        renderHours();
        renderDays();
    };

    document.getElementById('save').onclick = () => {
        tg.sendData(JSON.stringify({
            type: 'schedule',
            schedule: schedule,
        }));
        tg.close();
    };

    renderDays();
    renderHours();
</script>
</body>
</html>
