<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Record Management</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include 'admin-header.php'; ?>
    <?php include 'admin-sidebar.php'; ?>
    <div class="spacing">
        <div class="cases-content">
            <h1>DISEASE/ILLNESS CASES</h1>
            <table id="casesTable" style="text-transform: capitalize;">
              <tr>
                  <th onclick="sortTable(0)">Surname<span class="sortable-icon"></span></th>
                  <th onclick="sortTable(1)">First Name<span class="sortable-icon"></span></th>
                  <th onclick="sortTable(2)">Middle Name<span class="sortable-icon"></span></th>
                  <th onclick="sortTable(3)">Age<span class="sortable-icon"></span></th>
                  <th onclick="sortTable(4)">Sex<span class="sortable-icon"></span></th>
                  <th onclick="sortTable(5)">Disease<span class="sortable-icon"></span></th>
                  <th onclick="sortTable(6)">Month of Occurrence<span class="sortable-icon"></span></th>
                  <th onclick="sortTable(7)">Year of Occurrence<span class="sortable-icon"></span></th>
              </tr>
                <?php
                include 'conn.php';

                session_start(); // Start the session

                if (isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
                    // Now you can use $username to display the logged-in user's name or perform other actions.
                } else {
                    // If the session variable is not set, the user is not logged in; you can redirect them to the login page or take appropriate action.
                    header('Location: login.php');
                    exit();
                }

                $sql = "SELECT * FROM tblrecord WHERE disease IS NOT NULL AND month IS NOT NULL AND year IS NOT NULL ORDER BY id DESC";
                $result = mysqli_query($conn, $sql);

                // Check if any records exist
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['disease'] !== '' && $row['month'] !== '' && $row['year'] !== '') {
                            echo "<tr>";
                            echo "<td>{$row['lastname']}</td>";
                            echo "<td>{$row['firstname']}</td>";
                            echo "<td>{$row['middlename']}</td>";
                            echo "<td>{$row['age']}</td>";
                            echo "<td>{$row['sex']}</td>";
                            echo "<td>{$row['disease']}</td>";
                            echo "<td>{$row['month']}</td>";
                            echo "<td>{$row['year']}</td>";
                            echo "</tr>";
                        }
                    }
                } else {
                    echo "<tr><td colspan='8'>No records found.</td></tr>";
                }

                mysqli_close($conn);
                ?>
            </table>
        </div>
    </div>
    <script src="effect.js"></script>
    <script>
        function sortTable(columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("casesTable");
            switching = true;

            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < rows.length - 1; i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("TD")[columnIndex];

                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }
        let currentSortColumn = -1;
        let currentSortOrder = 1; // 1 for ascending, -1 for descending

        function sortTable(columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.querySelector("table");
            switching = true;

            // Toggle the sort order if the column is clicked again
            if (currentSortColumn === columnIndex) {
                currentSortOrder = -currentSortOrder;
            } else {
                currentSortOrder = 1;
            }

            // Record the current column index
            currentSortColumn = columnIndex;

            while (switching) {
                switching = false;
                rows = table.rows;

                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("TD")[columnIndex];

                    if (currentSortOrder == 1) {  // Ascending
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (currentSortOrder == -1) {  // Descending
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }

                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }

                // Update the header icon
                let headers = table.querySelectorAll("th");
                headers.forEach(header => {
                    let icon = header.querySelector(".sortable-icon");
                    if (icon) {
                        icon.classList.remove("desc");
                    }
                });

                if (currentSortOrder == -1) {
                    headers[columnIndex].querySelector(".sortable-icon").classList.add("desc");
                }
            }
        }

    </script>
</body>

</html>
