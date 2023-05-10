$(document).ready(function () {
    // Initialize the calendar
    $("#calendar").fullCalendar({
        header: {
            left: "prev,next today",
            center: "title",
            right: "month,agendaWeek,agendaDay",
        },
        selectable: true,
        selectHelper: true,
        editable: true,
        eventLimit: true, // Allow "more" link when too many events
        events: [],
        select: function (start, end) {
            // Prompt the user to enter an event title
            var title = prompt("Enter a title for your event:");
            if (title) {
                // Add the event to the calendar
                $("#calendar").fullCalendar("renderEvent", {
                    title: title,
                    start: start,
                    end: end,
                    allDay: false,
                });
            }
            // Unselect the date range
            $("#calendar").fullCalendar("unselect");
        },
    });
});
