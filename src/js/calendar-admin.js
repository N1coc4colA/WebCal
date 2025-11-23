// Used for the year & month mainly.
let selectedDate = new Date();
let upcomingEvents = new Array();

const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

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

function truncate(s, len)
{
    if (s.length <= len) {
        return s;
    }

    return s.substring(0, len);
}

function setupUpcomingEvents(date)
{
    const begString = formatDate(new Date(date.getFullYear(), date.getMonth(), 1));
    const endString = formatDate(new Date(date.getFullYear(), date.getMonth()+1, 0));

    document.getElementById("monthYear").innerHTML = monthNames[date.getMonth()] + " " + date.getFullYear();

    const path = "query/schedule-admin.php?upcoming&beg-date=" + begString + "&end-date=" + endString;
    fetch(path).then(response => {
        if (!response.ok) {
            document.getElementById("mod-evResponseError").classList.remove("hidden-full");
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        return response.json();
    })
    .then(data => {
        if (!isSameYearMonth(date, selectedDate)) {
            return;
        }

        let htmlResult = "";
        for (const elem of data) {
            htmlResult += buildEventCard(new Date(Date.parse(elem["beg_date"])), elem["beg_time"], elem["end_time"], truncate(elem["msg"], 50), elem["id"]);
        }

        document.getElementById("upcoming-body").innerHTML = htmlResult;

        if (data.length == 0) {
            document.getElementById("upcoming-body-noEvent").classList.remove("hidden-full");
            document.getElementById("upcoming-body").classList.add("hidden-full");
        } else {
            document.getElementById("upcoming-body").classList.remove("hidden-full");
            document.getElementById("upcoming-body-noEvent").classList.add("hidden-full");
        }
    })
    .catch(error => {
        document.getElementById("upcoming-body-evResponseError").classList.remove("hidden-full");
        console.error('Error fetching data:', error);
    });
}

function buildEventCard(date, beg, end, message, eventId)
{
    const monthName = monthNames[date.getMonth()];
    const day = date.getDate().toString();

    if (message.length == 0) {
        message = "Aucun message n'a été laissé.";
    }

    code = " \
        <div class=\"d-flex flex-column\"> \
            <div class=\"upcoming-entry d-flex flex-row\"> \
                <div class=\"d-flex flex-column mrg-l-5\">";
    code += "       <p class=\"rdv-month text-center m-0\">";
    code +=         monthName;
    code += "       </p> \
                    <p class=\"rdv-date text-center m-0\">";
    code +=         day;
    code += "       </p> \
                    <p class=\"rdv-time text-center m-0\">";
    code +=         truncate(beg, 5) + " - " + truncate(end, 5);
    code += "       </p> \
                </div> \
                <div class=\"upcoming-sep\"></div> \
                <div class=\"entry-content\"> \
                    <p class=\"rdv-title\">";
    code +=         message;
    code += "       </p> \
                </div>";
    code += "</div> \
         </div>";

    return code;
}

function setupCalendar()
{
    // Calendar header
    document.getElementById("nextMonth").addEventListener('click', function() {
        selectedDate.setMonth(selectedDate.getMonth() + 1);
        setupUpcomingEvents(selectedDate);
    });
    document.getElementById("prevMonth").addEventListener('click', function() {
        selectedDate.setMonth(selectedDate.getMonth() - 1);
        setupUpcomingEvents(selectedDate);
    });
}

function downloadUpcomingEvents()
{
    const begString = formatDate(new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1));
    const endString = formatDate(new Date(selectedDate.getFullYear(), selectedDate.getMonth()+1, 0));

    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Date;Heure;Message\n";

    const path = "query/schedule-admin.php?upcoming&beg-date=" + begString + "&end-date=" + endString;
    fetch(path).then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        return response.json();
    })
    .then(data => {
        for (const event of data) {
            const date = event["beg_date"];
            const time = event["beg_time"] + " - " + event["end_time"];
            const message = event["msg"].replace(/"/g, '""').replace(/;/g, '\\;'); // Escape double quotes and replace semicolons
            csvContent += `${date};${time};${message};\n`;
        }

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "upcoming_events.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });
}

setupCalendar();

const urlParams = new URLSearchParams(window.location.search);
setupUpcomingEvents(urlParams.has("date") ? new Date(urlParams.get("date")) : new Date());

setTimeout(() => {
    downloadUpcomingEvents();
}, 1500);
