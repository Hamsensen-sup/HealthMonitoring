<?php
include 'conn.php';

// Fetch initial yValues
$initialCounts = array();
for ($i = 1; $i <= 7; $i++) {
    $query = mysqli_query($conn, "SELECT COUNT(*) as count FROM tblrecord WHERE street LIKE 'Purok $i%'");
    $row = mysqli_fetch_assoc($query);
    $initialCounts[] = $row['count'];
}
$yValues = json_encode($initialCounts);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Overall Health Statistics</title>
  <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="styles.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
</head>
<body>
  <?php include 'guest-header.php'; ?>
  <?php include 'guest-sidebar.php'; ?>

  <div class="spacing">
    <div class="container">
      <center>
        <h1>OVERALL BARANGAY HEALTH STATISTICS</h1>
      </center>
    </div>
    <div class="container">
      <center>
        <select class="list-filter" name="diseaselist">
            <option value="all" selected>All Diseases</option>
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
        <p><b>NOTE: </b>The Overall Health Statistics section is as same as the Health Statistics section however, it distinguishes itself by providing a comprehensive overview of disease counts in every purok within Barangay San Pedro over an extended timeframe.<br><br>The Overall Health Statistics section plays a pivotal role in chronicling the health history of Barangay San Pedro. By aggregating and visualizing disease counts across different puroks over time, this feature provides a comprehensive overview of the community's health landscape.</p>
        <script>
        var xValues = ["Purok 1", "Purok 2", "Purok 3", "Purok 4", "Purok 5", "Purok 6", "Purok 7"];
        var yValues = <?php echo $yValues; ?>;
        var maxDataPoint = Math.max(...yValues);
        var yAxisMaxValue = maxDataPoint + 1;

        var myChart = new Chart("myChart", {
          type: "horizontalBar",
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
                xAxes: [{
                    ticks: {
                        beginAtZero: true,
                        max: yAxisMaxValue, // Set the maximum value for the y-axis
                        stepSize: 1,
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

        document.querySelectorAll('.list-filter').forEach(function(select) {
          select.addEventListener('change', function(e) {
            var selectedDisease = document.querySelector('select[name="diseaselist"]').value;
            var selectedYear = document.querySelector('select[name="yearlist"]').value;

            fetch('getPatientCount.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
              },
              body: 'disease=' + selectedDisease + '&year=' + selectedYear
            })
            .then(response => response.json())
            .then(data => {
              // Update the chart data
              myChart.data.datasets[0].data = data;

              // Calculate maxDataPoint dynamically based on new data
              maxDataPoint = Math.max(...data);
              yAxisMaxValue = maxDataPoint + 1;

              // Update the y-axis max value
              myChart.options.scales.xAxes[0].ticks.max = yAxisMaxValue;

              // Update the chart
              myChart.update();
            });
          });
        });


        </script>
      </div>
    </div>
  </div>
  <script src="effect.js"></script>
</body>
</html>
