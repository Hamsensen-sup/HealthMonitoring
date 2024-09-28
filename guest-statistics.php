<!DOCTYPE html>
<html>
<head>
  <title>Health Statistics</title>
  <link rel="stylesheet" href="styles.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
</head>
<body>
  <?php include 'guest-header.php'; ?>
  <?php include 'guest-sidebar.php'; ?>
  <div class="spacing">
    <div class="container">
      <center>
        <h1>BARANGAY HEALTH STATISTICS</h1>
      </center>
    </div>

    <div class="container">
      <center>
        <select class="list-filter" name="diseaselist">
            <option value="all">All diseases</option>
            <?php
            include 'conn.php';
            $diseaseQuery = mysqli_query($conn, "SELECT DISTINCT disease FROM tblrecord");
            while ($diseaseRow = mysqli_fetch_array($diseaseQuery)) {
            ?>
                <option value="<?php echo $diseaseRow['disease']; ?>"><?php echo $diseaseRow['disease']; ?></option>
            <?php } ?>
        </select>

        <select class="list-filter" name="yearlist">
          <option value="">Select a year</option>
          <option value="2000">2000</option>
            <option value="2001">2001</option>
            <option value="2002">2002</option>
            <option value="2003">2003</option>
            <option value="2004">2004</option>
            <option value="2005">2005</option>
            <option value="2006">2006</option>
            <option value="2007">2007</option>
            <option value="2008">2008</option>
            <option value="2009">2009</option>
            <option value="2010">2010</option>
            <option value="2011">2011</option>
            <option value="2012">2012</option>
            <option value="2013">2013</option>
            <option value="2014">2014</option>
            <option value="2015">2015</option>
            <option value="2016">2016</option>
            <option value="2017">2017</option>
            <option value="2018">2018</option>
            <option value="2019">2019</option>
            <option value="2020">2020</option>
            <option value="2021">2021</option>
            <option value="2022">2022</option>
            <option value="2023" selected>2023</option>
            <option value="2024">2024</option>
            <option value="2025">2025</option>
            <option value="2026">2026</option>
            <option value="2027">2027</option>
            <option value="2028">2028</option>
            <option value="2029">2029</option>
            <option value="2030">2030</option>
        </select>
      </center>
      <div class="chart">
        <canvas id="myChart" style="width:100%;max-width:800px"></canvas>
        <p><b>NOTE: </b>In the Health Statistics section, this page provides a detailed presentation of the statistical count of diseases reported each month over the course of the year. The objective is to enhance public awareness and establish a monitoring system, contributing to proactive measures for disease prevention and control. <br><br><br>Additionally, this data aids health workers in measuring health patterns, enabling them to anticipate potential risks early for more effective intervention strategies.</p>
        <script>
        var xValues = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var yValues = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; // Default values (zeros)

        var maxDataPoint = Math.max(...yValues); // Use yValues for the initial calculation
        var yAxisMaxValue = maxDataPoint + 5;    // Add 3 units of padding

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: "#EEB04F",
                    data: yValues
                }]
            },
            options: {
                legend: {display: false},
                title: {
                    display: true,
                    text: "Barangay San Pedro Health Monitoring System"
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1,
                            max: yAxisMaxValue,
                            callback: function(value) {
                                if (Math.floor(value) === value) {
                                    return value;
                                }
                            }
                        }
                    }]
                }
            }
        });

        function updateChartData() {
            var disease = document.querySelector('select[name="diseaselist"]').value;
            var year = document.querySelector('select[name="yearlist"]').value;

            if (disease && year) {
                fetch('getMonthlyDiseaseCount.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'disease=' + disease + '&year=' + year
                })
                .then(response => response.json())
                .then(data => {
                    myChart.data.datasets[0].data = data;

                    var maxDataPoint = Math.max(...data); // Recalculate using the fetched data
                    var yAxisMaxValue = maxDataPoint + 1;

                    myChart.options.scales.yAxes[0].ticks.max = yAxisMaxValue;

                    myChart.update();
                });
            }
        }

        document.querySelector('select[name="diseaselist"]').addEventListener('change', updateChartData);
        document.querySelector('select[name="yearlist"]').addEventListener('change', updateChartData);
        updateChartData(); // Call this to populate the chart on page load with the default year 2023

        </script>
      </div>
    </div>
  </div>
  <script src="effect.js"></script>
</body>
</html>
