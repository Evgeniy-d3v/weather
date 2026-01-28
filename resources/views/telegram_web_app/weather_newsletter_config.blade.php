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
        }
        .grid {
            display: grid;
            grid-auto-flow: column;
            grid-template-rows: repeat(6, 1fr);
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }
        button { padding: 10px; border: 1px solid #ccc; border-radius: 10px; background: #fff; }
        button.active { border-color: #000; font-weight: 700; }
        .row { display:flex; gap:8px; flex-wrap:wrap; }
        .day { padding: 8px 10px; }
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
        h3 {
            color: #1c8ed6;
            font-weight: 600;
        }
        .day { padding: 8px 10px; position: relative; }

        .day.filled::after {
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
        {id: 1, name: 'Пн'}, {id: 2, name: 'Вт'}, {id: 3, name: 'Ср'}, {id: 4, name: 'Чт'},
        {id: 5, name: 'Пт'}, {id: 6, name: 'Сб'}, {id: 7, name: 'Вс'},
    ];

    const schedule = {};
    let currentDay = 1;

    function ensureDay(d) {
        const key = String(d);
        if (!schedule[key]) schedule[key] = [];
    }

    function toggleHour(d, h) {
        ensureDay(d);
        const key = String(d);
        const idx = schedule[key].indexOf(h);
        if (idx === -1) schedule[key].push(h);
        else schedule[key].splice(idx, 1);
        schedule[key].sort((a,b)=>a-b);
    }
    function renderDays() {
        const el = document.getElementById('days');
        el.innerHTML = '';
        dayLabels.forEach(d => {
            const b = document.createElement('button');

            const key = String(d.id);
            const isActive = d.id === currentDay;
            const isFilled = (schedule[key]?.length ?? 0) > 0;

            b.className = 'day' + (isActive ? ' active' : '') + (isFilled ? ' filled' : '');
            b.textContent = d.name;

            b.onclick = () => { currentDay = d.id; renderDays(); renderHours(); };
            el.appendChild(b);
        });
    }

    function renderHours() {
        ensureDay(currentDay);
        const selected = new Set(schedule[String(currentDay)]);
        const el = document.getElementById('hours');
        el.innerHTML = '';
        for (let h=0; h<24; h++) {
            const b = document.createElement('button');
            b.textContent = String(h).padStart(2,'0') + ':00';
            if (selected.has(h)) b.classList.add('active');
            b.onclick = () => { toggleHour(currentDay, h); renderHours(); };
            el.appendChild(b);
        }
    }

    document.getElementById('clear').onclick = () => {
        schedule[String(currentDay)] = [];
        renderHours();
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
