<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        /* Loader Styles */
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
            display: block;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<h2>Students List</h2>

<!-- Loader -->
<div id="loader" class="loader"></div>

<!-- Table Container -->
<div id="studentsTable"></div>

<script>
    // Simulate data loading delay (replace this with an API call if needed)
    // setTimeout(renderTable, 1000);
    renderTable();

    function renderTable() {
        // Convert PHP array to JSON
        let students = <?= json_encode($students, JSON_HEX_TAG) ?>;
        let tableContainer = document.getElementById("studentsTable");
        let loader = document.getElementById("loader");

        // Hide loader once data is loaded
        loader.classList.add("hidden");

        if (students.length > 0) {
            // Create table
            let table = document.createElement("table");
            table.border = "1";
            table.cellPadding = "10";
            table.cellSpacing = "0";

            // Create table header
            let thead = document.createElement("thead");
            let headerRow = document.createElement("tr");
            let headers = ["ID", "Name", "Age", "Email", "Created At"];

            headers.forEach(headerText => {
                let th = document.createElement("th");
                th.textContent = headerText;
                headerRow.appendChild(th);
            });

            thead.appendChild(headerRow);
            table.appendChild(thead);

            // Create table body
            let tbody = document.createElement("tbody");

            students.forEach(student => {
                let row = document.createElement("tr");

                row.innerHTML = `
                    <td>${student.id}</td>
                    <td>${student.name}</td>
                    <td>${student.age}</td>
                    <td>${student.email}</td>
                    <td>${student.created_at}</td>
                `;

                tbody.appendChild(row);
            });

            table.appendChild(tbody);
            tableContainer.appendChild(table);
        } else {
            tableContainer.innerHTML = "<p>No students found.</p>";
        }
    }


</script>

</body>
</html>
