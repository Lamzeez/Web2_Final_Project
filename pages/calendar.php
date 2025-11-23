<?php
include_once '../includes/header.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<h2>Calendar Peace</h2>

<div id='calendar'></div>

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: 'get_events.php', // URL to your events data source
            eventClick: function(info) {
                // Optional: handle event click
                alert('Event: ' + info.event.title);
                // You can redirect to the note or to-do page here
                // e.g., if (info.event.extendedProps.type === 'note') { ... }
            }
        });
        calendar.render();
    });
</script>

<?php
include_once '../includes/footer.php';
?>
