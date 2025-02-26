let selectedDate = new Date();
let currentDate = new Date();
let activeDate = new Date();

let currentDays = new Map();
let soonDays = new Array();

const slotReservationModal = new bootstrap.Modal(document.getElementById("mod-reservation-popup"));


function daysInMonth(date)
{
    const daysInMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
    return daysInMonth;
}

function setupCalendar()
{
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
        entries[i*7].classList.toggle("striped-day");
        entries[i*7 +6].classList.toggle("striped-day");
    }

    for (let i = 0; i < entries.length; ++i) {
        entries[i].addEventListener('click', function() {
            const firstDayOfMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
            const startDay = firstDayOfMonth.getDay() % 7 -1;

            // If it is grayed, switch month and go to new date.
            let elem = document.getElementById("day-entry-" + i.toString());
            if (elem.classList.contains("unused-day")) {
                selectedDate.setMonth(selectedDate.getMonth() + (i < startDay ? -1 : 1));
                setupMonth(selectedDate); // Reload UI
                activeDate = selectedDate;
            } else {
                activeDate = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), i - startDay);
            }

            popupReservationModal();
        });
    }
}

function setupMonth(date)
{
    // Day names
    const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    // First day of current month
    const firstDayOfMonth = new Date(date.getFullYear(), date.getMonth(), 1);
    // Week day index
    const startDay = firstDayOfMonth.getDay() % 7 -1;
    // Days in current month
    const monthDays = daysInMonth(date);

    let entries = document.getElementsByClassName("day-entry");
    let tickets = document.getElementsByClassName("day-tickets");
    let days = document.getElementsByClassName("day-text");

    // Current month
    let i = 1;
    for (; i <= monthDays; ++i) {
        days[i+startDay].innerHTML = i.toString();
    }

    // After month
    for (let j = 1; (i+startDay) < days.length; ++i, ++j) {
        days[i+startDay].innerHTML = j.toString();
        entries[i+startDay].classList.toggle("unused-day");
        tickets[i+startDay].classList.toggle("hidden");
    }

    // Before month
    const prevMonth = new Date(date);
    prevMonth.setMonth(date.getMonth() - 1);
    const prevDays = daysInMonth(prevMonth);
    for (let j = 0; j <= startDay; ++j) {
        days[j].innerHTML = (prevDays - startDay + j).toString();
        entries[j].classList.toggle("unused-day");
        tickets[j].classList.toggle("hidden");
    }

    // Highlight current date
    entries[date.getDate() + startDay].classList.toggle("current-day");

    const begDate = new Date(date.getFullYear(), date.getMonth());
    const endDate = new Date(date.getFullYear(), date.getMonth(), daysInMonth(date)+1);
    const begDateString = begDate.toISOString().split('T')[0];
    const endDateString = endDate.toISOString().split('T')[0];

    // Get reserved dates
    const path = "http://localhost/query/schedule.php?available&beg-date=" + begDateString + "&end-date=" + endDateString + "&beg-time=00:00:00&end-time=23:59:59";
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
            const vDate = new Date(date.getFullYear(), date.getMonth(), i+1);
            const curr = vDate.toISOString().split('T')[0];

            if (!days[curr] || days[curr] == 0) {
                tickets[i+startDay].classList.add("hidden");
            } else {
                tickets[i+startDay].classList.remove("hidden");
                tickets[i+startDay].innerHTML = days[curr].toString();
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
    const dateString = activeDate.toISOString().split('T')[0];

    // Get reserved dates
    const path = "http://localhost/query/schedule.php?available&beg-date=" + dateString + "&end-date=" + dateString + "&beg-time=00:00:00&end-time=23:59:59";
    fetch(path).then(response => {
        if (!response.ok) {
            for (const child of document.getElementById("mod-reservation-body").children) {
                child.classList.add("hidden-full");
            }
            document.getElementById("mod-responseError").classList.toggle("hidden-full");
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        return response.json();
    })
    .then(data => {
        console.log(data); // Display the retrieved data

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
            document.getElementById("mod-nothingAvailable").classList.toggle("hidden-full");
        } else {
            document.getElementById("mod-responseOk").classList.toggle("hidden-full");
        }
    })
    .catch(error => {
        for (const child of document.getElementById("mod-reservation-body").children) {
            child.classList.add("hidden-full");
        }
        document.getElementById("mod-responseError").classList.toggle("hidden-full");
        console.error('Error fetching data:', error);
    });
}

setupCalendar();
setupMonth(currentDate);
