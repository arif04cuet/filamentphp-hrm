<x-filament::card>
    <div class="wrapper">

        <div class="container-calendar">
            <h3 id="monthAndYear"></h3>

            <div class="button-container-calendar">
                <button id="previous" onclick="previous()">&#8249;</button>
                <button id="next" onclick="next()">&#8250;</button>
            </div>

            <table class="table-calendar" id="calendar" data-lang="bn">
                <thead id="thead-month"></thead>
                <tbody id="calendar-body"></tbody>
            </table>

            <div class="footer-container-calendar">
                <label for="month"><span>Jump To : </span></label>
                <select id="month" onchange="jump()">
                    <option value=0>Jan</option>
                    <option value=1>Feb</option>
                    <option value=2>Mar</option>
                    <option value=3>Apr</option>
                    <option value=4>May</option>
                    <option value=5>Jun</option>
                    <option value=6>Jul</option>
                    <option value=7>Aug</option>
                    <option value=8>Sep</option>
                    <option value=9>Oct</option>
                    <option value=10>Nov</option>
                    <option value=11>Dec</option>
                </select>
                <select id="year" onchange="jump()"></select>
            </div>

        </div>
    </div>
</x-filament::card>

<style>
    html {
        font-size: 15px;
        line-height: 1.4;
        /* color: #444; */
    }

    body {
        margin: 0;
        /* background: #504f4f; */
        font-size: smaller;
    }

    .wrapper {
        margin: 15px auto;
        max-width: 1100px;
    }

    .container-calendar {
        background: transparent;
        padding: 15px;
        max-width: auto;
        margin: 0 auto;
        overflow: auto;
    }

    .button-container-calendar button {
        cursor: pointer;
        display: inline-block;
        zoom: 1;
        background: #f59e0b;
        color: #fff;
        border: 1px solid #f59e0b;
        border-radius: 4px;
        padding: 5px 10px;
    }

    .table-calendar {
        border-collapse: collapse;
        width: 100%;
    }

    .table-calendar td,
    .table-calendar th {
        padding: 3px;
        border: 2px solid #dedede;
        text-align: center;
        vertical-align: top;
        /* color: #444; */
        width: 10%;
    }

    .date-picker.selected {
        font-weight: bold;
        outline: 1px dashed #f59e0b;
    }

    .date-picker.selected span {
        border-bottom: 2px solid currentColor;
    }

    /* sunday */
    /* .date-picker:nth-child(1) {
        color: red;
    } */

    #monthAndYear {
        text-align: center;
        margin-top: 0;
    }

    .button-container-calendar {
        position: relative;
        margin-bottom: 1em;
        overflow: hidden;
        clear: both;
    }

    #previous {
        float: left;
    }

    #next {
        float: right;
    }

    .footer-container-calendar {
        margin-top: 1em;
        border-top: 1px solid #dadada;
        padding: 10px 0;
    }

    .footer-container-calendar select {
        cursor: pointer;
        display: inline-block;
        zoom: 1;
        background: #ffffff;
        color: #585858;
        border: 1px solid #bfc5c5;
        border-radius: 3px;
        padding: 5px 1em;
    }

    .tooltip {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        bottom: 100%;
        left: 50%;
        margin-left: -60px;
        opacity: 0;
        transition: opacity 1s;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }

    /* The Modal (background) */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    /* Modal Content */
    .modal-content {
        position: relative;
        background-color: #fefefe;
        margin: auto;
        padding: 0;
        width: 50%;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        -webkit-animation-name: animatetop;
        -webkit-animation-duration: 0.4s;
        animation-name: animatetop;
        animation-duration: 0.4s
    }

    /* Add Animation */
    @-webkit-keyframes animatetop {
        from {
            top: -300px;
            opacity: 0
        }

        to {
            top: 0;
            opacity: 1
        }
    }

    @keyframes animatetop {
        from {
            top: -300px;
            opacity: 0
        }

        to {
            top: 0;
            opacity: 1
        }
    }

    /* The Close Button */
    .close {
        color: black;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: gray;
        text-decoration: none;
        cursor: pointer;
    }

    .modal-header {
        padding: 2px 16px;
        background-color: rgb(245, 158, 11);
        color: white;
    }

    .modal-body {
        padding: 2px 16px;
    }

    .modal-footer {
        padding: 2px 16px;
        /* background-color: #5cb85c; */
        /* color: white; */
    }

    .buttonSave {
        background-color: rgb(245, 158, 11);
        border: none;
        border-radius: 12px;
        color: white;
        padding: 10px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
    }

    .buttonAction {
        background-color: white;
        border: none;
        border-radius: 12px;
        color: black;
        padding: 2px 10px 2px 10px;
        cursor: pointer;
    }

    .buttonAction:hover {
        background-color: black;
        color: white;
    }

    .buttonApprove {
        background-color: #e4ffa5;
        border: none;
        border-radius: 12px;
        color: #444;
        padding: 2px 10px 2px 10px;
    }

    .buttonPending {
        background-color: #fffab4;
        border: none;
        border-radius: 12px;
        color: #444;
        padding: 2px 10px 2px 10px;
    }

    .buttonPending:hover {
        background-color: black;
        color: white;
    }    

    .txt-black{
        color: black;
    }

</style>

<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function generate_year_range(start, end) {
        var years = "";
        for (var year = start; year <= end; year++) {
            years += "<option value='" + year + "'>" + year + "</option>";
        }
        return years;
    }

    today = new Date();
    currentMonth = today.getMonth();
    currentYear = today.getFullYear();
    selectYear = document.getElementById("year");
    selectMonth = document.getElementById("month");

    createYear = generate_year_range(currentYear - 3, currentYear + 3);

    document.getElementById("year").innerHTML = createYear;

    var calendar = document.getElementById("calendar");
    var lang = calendar.getAttribute('data-lang');

    var months = "";
    var days = "";

    var monthDefault = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    var dayDefault = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    // var dayDefault = ["Sat", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri"];

    if (lang == "en") {
        months = monthDefault;
        days = dayDefault;
    } else if (lang == "bn") {
        months = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'অগাস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
        days = ["রবি", "সোম", "মঙ্গল", "বুধ", "বৃহঃ", "শুক্র", "শনি"];
        // days = ["শনি", "রবি", "সোম", "মঙ্গল", "বুধ", "বৃহঃ", "শুক্র"];
    } else {
        months = monthDefault;
        days = dayDefault;
    }

    var $dataHead = "<tr>";
    for (dhead in days) {
        $dataHead += "<th data-days='" + days[dhead] + "'>" + days[dhead] + "</th>";
    }
    $dataHead += "</tr>";

    document.getElementById("thead-month").innerHTML = $dataHead;

    monthAndYear = document.getElementById("monthAndYear");
    showCalendar(currentMonth, currentYear);

    function next() {
        currentYear = (currentMonth === 11) ? currentYear + 1 : currentYear;
        currentMonth = (currentMonth + 1) % 12;
        showCalendar(currentMonth, currentYear);
    }

    function previous() {
        currentYear = (currentMonth === 0) ? currentYear - 1 : currentYear;
        currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
        showCalendar(currentMonth, currentYear);
    }

    function jump() {
        currentYear = parseInt(selectYear.value);
        currentMonth = parseInt(selectMonth.value);
        showCalendar(currentMonth, currentYear);
    }

    function showCalendar(month, year) {

        var firstDay = (new Date(year, month)).getDay();

        tbl = document.getElementById("calendar-body");


        tbl.innerHTML = "";


        monthAndYear.innerHTML = months[month] + " " + year;
        selectYear.value = year;
        selectMonth.value = month;

        // creating all cells
        var date = 1;

        var status = [];
        var time = [];
        var color = [];
        var jsdata = [];
        var ym = year + "-" + (month + 1);
        var url = '{{ route("search-attendance", ":ym") }}';
        var dot = "";
        url = url.replace(':ym', ym);

        // result collect from database
        $.ajax({
            'async': false,
            'global': false,
            type: 'POST',
            data: {
                ym: ym,
                _token: '{{csrf_token()}}'
            },
            url: url,
            success: function(data) {
                jsdata = data;
            }
        });
        // console.log(jsdata);

        for (var i = 0; i < 6; i++) {

            var row = document.createElement("tr");

            for (var j = 0; j < 7; j++) {
                if (i === 0 && j < firstDay) {
                    cell = document.createElement("td");
                    cellText = document.createTextNode("");
                    cell.style.backgroundColor = jsdata.empty_cell;
                    cell.appendChild(cellText);
                    row.appendChild(cell);
                } else if (date > daysInMonth(month, year)) {
                    break;
                } else {
                    cell = document.createElement("td");
                    cell.setAttribute("data-date", date);
                    cell.setAttribute("data-month", month + 1);
                    cell.setAttribute("data-year", year);
                    cell.setAttribute("data-month_name", months[month]);
                    cell.className = "date-picker";

                    // selected cell for currented date
                    if (date === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                        cell.className = "date-picker selected";
                    }

                    // cell values
                    if (jsdata.statuses[date - 1] == "LATE") {
                        cell.classList.add('bg-rmv');
                        cell.classList.add('txt-black');
                        if (jsdata.causes[date - 1] && !jsdata.approves[date - 1]) {
                            cell.innerHTML = "<h6>" + date + "</h6><p>" + jsdata.statuses[date - 1] + "</p><p>" + jsdata.times[date - 1] + "</p><button class='modal-button buttonPending' href='#modal" + date + "-" + (month + 1) + "-" + year + "'>Pending</button><div id='modal" + date + "-" + (month + 1) + "-" + year + "' class='modal'><div class='modal-content'><div class='modal-header'><span class='close'>&times;</span></div><div class='modal-body'><div><p>Date: " + date + "-" + (month + 1) + "-" + year + "</p><form action='{{route('setcause')}}' method='get'><input type='hidden' name='date' value=" + year + "-" + (month + 1) + "-" + date + "><input type='text' name='late_cause' id='late_cause' style='width: 100%; color:black' placeholder='Write Late Cause Here' required minlength='5' maxlength='100' value='" + jsdata.causes[date - 1] + "'><br><div style='text-align: center;'><button type='submit' class='buttonSave'>Update</button></div></form></div></div></div></div>";
                        } else if (jsdata.causes[date - 1] && jsdata.approves[date - 1]) {
                            cell.innerHTML = "<h6>" + date + "</h6><p>" + jsdata.statuses[date - 1] + "</p><p>" + jsdata.times[date - 1] + "</p><button class='buttonApprove'>Approved</button>"
                        } else {
                            cell.innerHTML = "<h6>" + date + "</h6><p>" + jsdata.statuses[date - 1] + "</p><p>" + jsdata.times[date - 1] + "</p><button class='modal-button buttonAction' href='#modal" + date + "-" + (month + 1) + "-" + year + "'>Write Cause</button><div id='modal" + date + "-" + (month + 1) + "-" + year + "' class='modal'><div class='modal-content'><div class='modal-header'><span class='close'>&times;</span></div><div class='modal-body'><div><p>Date: " + date + "-" + (month + 1) + "-" + year + "</p><form action='{{route('setcause')}}' method='get'><input type='hidden' name='date' value=" + year + "-" + (month + 1) + "-" + date + "><input type='text' name='late_cause' id='late_cause' style='width: 100%; color:black' placeholder='Write Late Cause Here' required minlength='5' maxlength='100'><br><div style='text-align: center;'><button type='submit' class='buttonSave'>Save</button></div></form></div></div></div></div>";
                        }
                    } else if (jsdata.statuses[date - 1] == "PRESENT")
                        cell.innerHTML = "<h6>" + date + "</h6><p style='color:green'>" + jsdata.statuses[date - 1] + "</p><p style='color:green'>" + jsdata.times[date - 1] + "</p><br>";
                    else if (jsdata.statuses[date - 1] == "ABSENT") {
                        cell.classList.add('bg-rmv');
                        cell.innerHTML = "<h6>" + date + "</h6><p style='color:red'>" + jsdata.statuses[date - 1] + "</p><p><br><br>";
                    } else if (jsdata.statuses[date - 1] == "WEEKEND") {
                        cell.classList.add('txt-black');
                        cell.innerHTML = "<h6>" + date + "</h6><h6>" + jsdata.statuses[date - 1] + "</h6><p>" + jsdata.times[date - 1] + "</p><br><br>";
                    } else if (jsdata.statuses[date - 1] == "") {
                        cell.innerHTML = "<h6>" + date + "</h6><p>" + jsdata.statuses[date - 1] + "</p><p>" + jsdata.times[date - 1] + "</p><br><br><br>";
                    } else {
                        // if (jsdata.statuses[date - 1].length > 13) {
                        //     dot = "...";
                        // }
                        // cell.innerHTML = "<h6>" + date + "</h6><div class='tooltip'>" + jsdata.statuses[date - 1].slice(0, 12) + "" + dot + "<span class='tooltiptext'>" + jsdata.statuses[date - 1] + "</span></div><br><br>";
                        cell.classList.add('txt-black');
                        cell.innerHTML = "<h6>" + date + "</h6><h6>" + jsdata.statuses[date - 1] + "</h6>";
                    }

                    // cell color
                    if (jsdata.statuses[date - 1] == "LATE") {
                        cell.style.backgroundColor = jsdata.late_cell; // late status color override
                    } else
                        cell.style.backgroundColor = jsdata.colors[date - 1];

                    row.appendChild(cell);
                    date++;
                }
            }

            tbl.appendChild(row);
        }

    }

    function daysInMonth(iMonth, iYear) {
        return 32 - new Date(iYear, iMonth, 32).getDate();
    }

    // Modal

    // Get the button that opens the modal
    var btn = document.querySelectorAll("button.modal-button");

    // All page modals
    var modals = document.querySelectorAll('.modal');

    // Get the <span> element that closes the modal
    var spans = document.getElementsByClassName("close");

    // When the user clicks the button, open the modal
    for (var i = 0; i < btn.length; i++) {
        btn[i].onclick = function(e) {
            e.preventDefault();
            modal = document.querySelector(e.target.getAttribute("href"));
            modal.style.display = "block";
        }
    }

    // When the user clicks on <span> (x), close the modal
    for (var i = 0; i < spans.length; i++) {
        spans[i].onclick = function() {
            for (var index in modals) {
                if (typeof modals[index].style !== 'undefined') modals[index].style.display = "none";
            }
        }
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            for (var index in modals) {
                if (typeof modals[index].style !== 'undefined') modals[index].style.display = "none";
            }
        }
    }
</script>