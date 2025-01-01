<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Excel</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>

<body>
    <button id="exportBtn">Export to Excel</button>
    <script>
        // Example data
        const gradebookData = {
            students: [{
                    student_name: 'Rizal, Protacio Jose',
                    score: 85
                },
                {
                    student_name: 'Bonifacio, Andres',
                    score: 92
                },
                {
                    student_name: 'Mabini, Apolinario',
                    score: 88
                }
            ],
            subject_section: {
                id: 1,
                subject_name: 'Mathematics'
            }
        };

        // Function to export the data to an Excel file
        function exportGradebook() {
            // Create a new workbook
            const workbook = XLSX.utils.book_new();

            // Prepare data for the sheet
            const sheetData = [
                ['STUDENTS', 'SCORES']
            ]; // Header row
            gradebookData.students.forEach(student => {
                sheetData.push([student.student_name, student.score]);
            });

            // Convert data to worksheet
            const worksheet = XLSX.utils.aoa_to_sheet(sheetData);

            // Append worksheet to workbook
            XLSX.utils.book_append_sheet(workbook, worksheet, gradebookData.subject_section.subject_name);

            // Generate Excel file and trigger download
            XLSX.writeFile(workbook, 'gradebook.xlsx');
        }

        // Add event listener to button
        document.getElementById('exportBtn').addEventListener('click', exportGradebook);
    </script>
</body>

</html>