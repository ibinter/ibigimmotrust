<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin'])) { die("Accès refusé"); }
include __DIR__ . '/menu.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Agenda RDV – IBIG IMMO TRUST</title>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js"></script>
<style>
body{
    font-family:system-ui,sans-serif;
    background:#f4f6fb;
}
#calendar{
    max-width:1100px;
    margin:20px auto;
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}
</style>
</head>
<body>

<div id="calendar"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const calendarEl = document.getElementById('calendar');

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'fr',
    editable: true,
    eventSources: [{
        url: 'calendar_events.php'
    }],
    eventDrop: function(info){
        fetch('calendar_update.php', {
            method:'POST',
            headers:{'Content-Type':'application/x-www-form-urlencoded'},
            body:'id='+encodeURIComponent(info.event.id)+'&date='+encodeURIComponent(info.event.startStr)
        });
    },
    eventClick: function(info){
        window.location.href = 'rdv_view.php?id=' + info.event.id;
    }
  });

  calendar.render();
});
</script>

</body>
</html>
