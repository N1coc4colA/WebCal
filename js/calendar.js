// Used for the year & month mainly.
let selectedDate = new Date();
// Used for slot reservation dialog.
let activeDate = new Date();

// Map of the days that have a slot reserved by the user for the currently selected month.
let currentDays = new Map();
// List of the upcoming events.
let upcomingEvents = new Array();

const slotReservationModal = new bootstrap.Modal(document.getElementById("mod-reservation-popup"));


function daysInMonth(date)
{
    const daysInMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
    return daysInMonth;
}

function isSameDate(d1, d2)
{
    console.log(d1.getFullYear() + "-" + d1.getMonth() + "-" + d1.getDate() + " | " +
                d2.getFullYear() + "-" + d2.getMonth() + "-" + d2.getDate());

    return  d1.getFullYear() === d2.getFullYear() &&
            d1.getMonth() === d2.getMonth() &&
            d1.getDate() === d2.getDate();
}

function isSameYearMonth(d1, d2)
{
    return  d1.getFullYear() === d2.getFullYear() &&
            d1.getMonth() === d2.getMonth();
}

function formatDate(d)
{
    return d.getFullYear().toString() + "-" + (d.getMonth() +1).toString().padStart(2, '0') + "-" + d.getDate().toString().padStart(2, '0');
}

function setupCalendar()
{
    // Calendar header
    document.getElementById("nextMonth").addEventListener('click', function() {
        selectedDate.setMonth(selectedDate.getMonth() + 1);
        setupMonth(selectedDate);
    });
    document.getElementById("prevMonth").addEventListener('click', function() {
        selectedDate.setMonth(selectedDate.getMonth() - 1);
        setupMonth(selectedDate);
    });

    // Main calendar body
    let entries = document.getElementsByClassName("day-entry");
    let tickets = document.getElementsByClassName("day-tickets");
    let days = document.getElementsByClassName("day-text");

    for (let i = 0; i < entries.length; ++i) {
        entries[i].id = "day-entry-" + i.toString();
    }
    for (let i = 0; i < tickets.length; ++i) {
        tickets[i].id = "day-ticket-" + i.toString();
    }
    for (let i = 0; i < days.length; ++i) {
        days[i].id = "day-text-" + i.toString();
    }

    // 0s & 6s are closed days.
    for (let i = 0; i < 5; ++i) {
        entries[i*7].classList.add("striped-day");
        entries[i*7+6].classList.add("striped-day");
    }

    for (let i = 0; i < entries.length; ++i) {
        entries[i].addEventListener('click', function() {
            const firstDayOfMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
            const startDay = firstDayOfMonth.getDay();

            // If it is grayed, switch month and go to new date.
            let elem = document.getElementById("day-entry-" + i.toString());
            if (elem.classList.contains("unused-day")) {
                selectedDate.setMonth(selectedDate.getMonth() + (i < startDay ? -1 : 1));
                setupMonth(selectedDate); // Reload UI
                activeDate = selectedDate;
            } else {
                activeDate = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), i - startDay +1);
            }

            popupReservationModal();
        });
    }
}

function setupMonth(date)
{
    // Day names
    const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

    // Set month name and year first.
    document.getElementById("monthYear").innerHTML = monthNames[date.getMonth()] + " " + date.getFullYear();

    // All other calendar stuff updates.
    // First day of current month
    const firstDayOfMonth = new Date(date.getFullYear(), date.getMonth(), 1);
    // Week day index
    const startDay = firstDayOfMonth.getDay() -1;
    // Days in current month
    const monthDays = daysInMonth(date);

    let entries = document.getElementsByClassName("day-entry");
    let tickets = document.getElementsByClassName("day-tickets");
    let days = document.getElementsByClassName("day-text");

    // Current month
    let i = 1;
    for (; i <= monthDays; ++i) {
        if (days[i+startDay]) {
            days[i+startDay].innerHTML = i.toString();
            entries[i+startDay].classList.remove("unused-day");
            tickets[i+startDay].classList.remove("hidden");
        }
    }

    // After month
    for (let j = 1; (i+startDay) < days.length; ++i, ++j) {
        days[i+startDay].innerHTML = j.toString();
        entries[i+startDay].classList.add("unused-day");
        tickets[i+startDay].classList.add("hidden");
    }

    // Before month
    const prevMonth = new Date(date);
    prevMonth.setMonth(date.getMonth() - 1);
    const prevDays = daysInMonth(prevMonth);
    for (let j = 0; j <= startDay; ++j) {
        days[j].innerHTML = (prevDays - startDay + j).toString();
        entries[j].classList.add("unused-day");
        tickets[j].classList.add("hidden");
    }

    // Highlight current date
    let enabledDays = document.getElementsByClassName("current-day");
    for (let i = 0; i < enabledDays.length; ++i) {
        enabledDays[i].classList.remove("current-day");
    }
    const now = new Date();
    if (isSameYearMonth(now, date)) {
        entries[now.getDate() + startDay].classList.add("current-day");
    }

    const begDate = new Date(date.getFullYear(), date.getMonth());
    const endDate = new Date(date.getFullYear(), date.getMonth(), daysInMonth(date)+1);
    const begDateString = formatDate(begDate);
    const endDateString = formatDate(endDate);

    // Get reserved dates
    const path = "http://localhost/query/schedule.php?user&beg-date=" + begDateString + "&end-date=" + endDateString + "&beg-time=00:00:00&end-time=23:59:59";
    fetch(path).then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        return response.json();
    })
    .then(data => {
        let days = [];
        for (let i = 0; i < data.length; ++i) {
            const curr = data[i]["beg_date"];

            if (!days[curr]) {
                days[curr] = 0;
            }
            if (!currentDays[curr]) {
                currentDays[curr] = [];
            }

            days[curr] += 1;
            currentDays[curr].push(data[i]);
        }

        for (let i = 1; i <= monthDays+1; ++i) {
            if (tickets[i+startDay]) {
                const vDate = new Date(date.getFullYear(), date.getMonth(), i+1);
                const curr = formatDate(vDate);
    
                if (!days[curr] || days[curr] == 0) {
                    tickets[i+startDay].classList.add("hidden");
                } else {
                    tickets[i+startDay].classList.remove("hidden");
                    tickets[i+startDay].innerHTML = days[curr].toString();
                }
            }
        }
    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });
}

function popupReservationModal()
{
    // Now make popup for the date.
    slotReservationModal.show();

    // Popuplate the form.
    const dateString = formatDate(activeDate);

    document.getElementById("mod-dateSelect").value = dateString;
    document.getElementById("mod-resTitle").innerHTML = "Réserver un créneau le " + activeDate.getDate().toString();

    // Get reserved dates
    const path = "http://localhost/query/schedule.php?available&beg-date=" + dateString + "&end-date=" + dateString + "&beg-time=00:00:00&end-time=23:59:59";
    fetch(path).then(response => {
        if (!response.ok) {
            for (const child of document.getElementById("mod-reservation-body").children) {
                child.classList.add("hidden-full");
            }
            document.getElementById("mod-responseError").classList.remove("hidden-full");
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        return response.json();
    })
    .then(data => {
        let htmlResult = "";
        let days = [];
        for (let i = 0; i < data.length; ++i) {
            const curr = data[i]["beg_time"];
            htmlResult += "<option value=\"" + curr + "\">" + curr + " - " + data[i]["end_time"] + "</option>";
        }

        document.getElementById("mod-timeSelect").innerHTML = htmlResult;

        for (const child of document.getElementById("mod-reservation-body").children) {
            child.classList.add("hidden-full");
        }
        if (data.length == 0) {
            document.getElementById("mod-nothingAvailable").classList.remove("hidden-full");
        } else {
            document.getElementById("mod-responseOk").classList.remove("hidden-full");
        }
    })
    .catch(error => {
        for (const child of document.getElementById("mod-reservation-body").children) {
            child.classList.add("hidden-full");
        }
        document.getElementById("mod-responseError").classList.remove("hidden-full");
        console.error('Error fetching data:', error);
    });
}

setupCalendar();

const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has("date")) {
    selectedDate = new Date(urlParams.get("date"));
    setupMonth(selectedDate);
} else {
    setupMonth(new Date());
}
