<!-- Include the header -->
<?php include 'Aheader.php'; ?>
<br>
<div class="back_re">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="title">
               <h2>Reservation Available</h2>
            </div>
         </div>
      </div>
   </div>
</div>

<!doctype html>
<html lang="en">
  <head>
    <title>Calendar #9</title>
  </head>
  <body>
  <div class="content">
    <div id='calendar'></div>
  </div>
  <?php
require_once 'dbconfig.php';

$dbConfig = new DBConfig();
$conn = $dbConfig->dbConnect();

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$events = array();

$query = "SELECT check_in, check_out, status FROM reservations";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $checkInDate = $row['check_in'];
    $checkOutDate = $row['check_out'];
    $status = $row['status'];

    // Check if the date is in the past
    $isPast = strtotime($checkInDate) < strtotime(date('Y-m-d'));

    // Only add the event if status is not pending and check_in and check_out are equal
    if ($status != 'pending' && $checkInDate == $checkOutDate) {
        $eventColor = ($status == 'confirm') ? '' : '';

        // Set completed color for past dates
        if ($isPast) {
            $status = 'completed';
            $eventColor = '#FFD700'; // You can change the color as needed
        } else {
            $status = 'confirm'; // Set status to 'Reserved' for future dates
            $eventColor = '#ff1a1a';
        }

        $event = array(
            'title' => ($status == 'confirm') ? 'Day Reserved' : 'Stay Completed',
            'start' => $checkInDate,
            'color' => $eventColor,
        );
        $events[] = $event;
    } elseif ($status != 'pending' && $checkInDate < $checkOutDate) {
        $eventColor = ($status == 'confirm') ? '' : '';

        // Set completed color for past dates
        if ($isPast) {
            $status = 'completed';
            $eventColor = '#FFD700'; // You can change the color as needed
        } else {
            $status = 'confirm'; // Set status to 'Reserved' for future dates
            $eventColor = '#0000ff';
        }

        // Dates are not equal, set title to 'Reserved' and color to green
        $event = array(
            'title' => ($status == 'confirm') ? 'Night Reserved' : 'Stay Completed',
            'start' => $checkInDate,
            'color' => $eventColor, // Green color
        );
        $events[] = $event;
    }
}

// Add "today" event for the current date
$todayEvent = array(
    'title' => 'Today',
    'start' => date('Y-m-d'), // Current date in the format expected by FullCalendar
    'color' => '#33cc33', // You can change the color as needed
);

$events[] = $todayEvent;

$conn->close();

?>



    <script src="design/js/jquery-3.3.1.min.js"></script>
    <script src="design/js/popper.min.js"></script>
    <script src="design/js/bootstrap.min.js"></script>
    <script src='design/fullcalendar/packages/core/main.js'></script>
    <script src='design/fullcalendar/packages/interaction/main.js'></script>
    <script src='design/fullcalendar/packages/daygrid/main.js'></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
          var calendarEl = document.getElementById('calendar');

          var calendar = new FullCalendar.Calendar(calendarEl, {
              plugins: ['interaction', 'dayGrid'],
              defaultDate: new Date(), // Set default date to the current month
              editable: true,
              eventLimit: true,
              events: <?php echo json_encode($events); ?>
          });

            calendar.render();
        });
    </script>
    <script src="design/js/main.js"></script>
  </body>
</html>

<?php include 'Afooter.php'; ?>
