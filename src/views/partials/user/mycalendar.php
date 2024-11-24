<div class="widget-card p-3 shadow-sm rounded border" id="myCalendar">
    <div class="d-flex justify-content-between align-items-center">
        <button id="prevMonth" class="btn btn-sm">&lt;</button>
        <p class="fs-6 fw-semibold text-success m-0" id="calendarMonth"></p>
        <button id="nextMonth" class="btn btn-sm">&gt;</button>
    </div>
    <hr class="opacity-90 mx-0 my-1">
    <div class="p-0">
        <table class="w-100">
            <thead>
                <tr>
                    <th>S</th>
                    <th>M</th>
                    <th>T</th>
                    <th>W</th>
                    <th>T</th>
                    <th>F</th>
                    <th>S</th>
                </tr>
            </thead>
            <tbody id="calendarBody"></tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        const today = new Date();
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();

        function generateCalendar(month, year) {
            const calendarBody = $("#calendarBody");
            calendarBody.empty(); // Clear previous calendar

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            let date = 1;
            for (let i = 0; i < 6; i++) {
                const row = $("<tr></tr>");

                for (let j = 0; j < 7; j++) {
                    const cell = $("<td></td>");

                    if (i === 0 && j < firstDay) {
                        cell.addClass("opacity-50"); // Empty cells before the first day
                    } else if (date > daysInMonth) {
                        cell.addClass("opacity-50"); // Empty cells after the last day
                    } else {
                        cell.text(date);

                        // Highlight today
                        if (
                            date === today.getDate() &&
                            month === today.getMonth() &&
                            year === today.getFullYear()
                        ) {
                            cell.addClass("active-day");
                        }

                        date++;
                    }

                    row.append(cell);
                }

                calendarBody.append(row);

                if (date > daysInMonth) {
                    break;
                }
            }

            $("#calendarMonth").text(
                `${new Intl.DateTimeFormat("en-US", { month: "short" }).format(
                new Date(year, month)
            )} ${year}`
            );
        }

        function changeMonth(step) {
            currentMonth += step;

            // Handle year change
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            } else if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }

            generateCalendar(currentMonth, currentYear);
        }

        // Initial render
        generateCalendar(currentMonth, currentYear);

        // Event listeners for navigation
        $("#prevMonth").on("click", function() {
            changeMonth(-1);
        });

        $("#nextMonth").on("click", function() {
            changeMonth(1);
        });
    });
</script>