jQuery(document).ready(function ($) {
    $('#fClendar').fullCalendar({
        theme: true,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,listYear'
        },
        displayEventTime: false, // don't show the time column in list view
        // THIS KEY WON'T WORK IN PRODUCTION!!!
        // To make your own Google API key, follow the directions here:
        // http://fullcalendar.io/docs/google_calendar/
        googleCalendarApiKey: fullcalendar.api_key,
        // US Holidays
        events: fullcalendar.gcalendarId,
        eventClick: function (event) {
            // opens events in a popup window
            window.open(event.url, 'gcalevent', 'width=700,height=600');
            return false;
        },
        loading: function (bool) {
            $('#fcalLoading').toggle(bool);
        }
    });
});