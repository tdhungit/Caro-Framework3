$(document).ready(function() {
    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();

    var view = 'agendaWeek';
    if (getCookie('calendarView')) {
        view = getCookie('calendarView');
    }

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        buttonText: {
            today: 'today',
            month: 'month',
            week: 'week',
            day: 'day'
        },
        defaultView: view,
        selectable: true,
        selectHelper: true,
        select: function(start, end) {
            var start_tz_now = start.format();
            var end_tz_now = end.format();
            var date_start = moment(start_tz_now).tz('UTC').format('YYYY-MM-DD HH:mm:ss');
            var date_end = moment(end_tz_now).tz('UTC').format('YYYY-MM-DD HH:mm:ss');

            addEvent(date_start, date_end);
        },
        editable: true,
        eventAfterAllRender: function (view) {
            setCookie('calendarView', view.type);
        },
        eventDrop: function (event, delta, revertFunc) {
            console.log(event);
        },
        eventResize: function(event, delta, revertFunc) {
            console.log(event);
        }
    });

    // load events
    $.get(base_url + '/activities/calendar_events', function (data) {
        $('#calendar').fullCalendar('addEventSource', data);
    });
});

function addEvent(start, end) {
    $.get(base_url + '/activities/quick_create?date_start=' + start + '&date_end=' + end + '&submit_callback=reload_page', function (data) {
        openModal(data);
        init();
    });
}
