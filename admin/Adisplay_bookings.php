<!DOCTYPE html>
<html>
<head>
    <title>Resort Reservation Management System</title>
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
    <style>
    /* Add your CSS styles here */
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    
    th {
        background-color: #f2f2f2;
    }
    
    button.sort-btn {
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
    }
    
    /* Display the sorting arrows without the right-facing arrow */
    button.sort-btn::after {
        content: " ▲";
        color: black;
    }
    
    /* Display the up arrow when the table is sorted in ascending order */
    button.sort-btn.sorted-asc::after {
        content: " ▼";
    }
    
    /* Display the down arrow when the table is sorted in descending order */
    button.sort-btn.sorted-desc::after {
        content: " ▲";
    }
    </style>
</head>
<body>
    <?php
    require_once 'dbconfig.php';
    $dbConfig = new DBConfig();
    $conn = $dbConfig->dbConnect();
    $result = $conn->query("SELECT r.ID, u.email, r.check_in, r.check_out, ro.room_type, r.time, r.recorded_at, r.status, r.payment_receipt
                        FROM reservations r
                        INNER JOIN user_form u ON r.user_id = u.user_id
                        INNER JOIN rooms ro ON r.room_id = ro.room_id");
    if ($result->num_rows > 0) {
      
        // Fetch all rows into an array for sorting
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        
        // Define a custom comparison function for merge sort based on "recorded_at" column
        function compareByRecordedAt($a, $b) {
            return strcmp($a['recorded_at'], $b['recorded_at']);
        }

        // Merge Sort implementation for the rows array
        function mergeSort($arr, $compareFn) {
            if (count($arr) <= 1) {
                return $arr;
            }

            $middle = floor(count($arr) / 2);
            $left = array_slice($arr, 0, $middle);
            $right = array_slice($arr, $middle);

            return merge(mergeSort($left, $compareFn), mergeSort($right, $compareFn), $compareFn);
        }

        function merge($left, $right, $compareFn) {
            $result = [];
            $leftIndex = 0;
            $rightIndex = 0;

            while ($leftIndex < count($left) && $rightIndex < count($right)) {
                if ($compareFn($left[$leftIndex], $right[$rightIndex]) <= 0) {
                    $result[] = $left[$leftIndex];
                    $leftIndex++;
                } else {
                    $result[] = $right[$rightIndex];
                    $rightIndex++;
                }
            }

            return array_merge($result, array_slice($left, $leftIndex), array_slice($right, $rightIndex));
        }

        // Sort the rows based on "recorded_at" in ascending order
        $sortedRows = mergeSort($rows, 'compareByRecordedAt');
    ?>
    <a href="Arecord.php" class="btn btn-warning">Reset Sort</a>


    <table class="table table-striped table-hover">
        <thead>
            <br>
            <tr>
                <th><button class="sort-btn" data-column="ID">ID</button></th>
                <th><button class="sort-btn" data-column="email">Email</button></th>
                <th><button class="sort-btn" data-column="check_in">Check in</button></th>
                <th><button class="sort-btn" data-column="check_out">Check out</button></th>
                <th><button class="sort-btn" data-column="room_type">Room</button></th>
                <th><button class="sort-btn" data-column="time">Time</button></th>
                <th><button class="sort-btn" data-column="payment_receipt">Payment</button></th>
                <th><button class="sort-btn" data-column="recorded_at">Recorded At</button></th>
                <th><button class="sort-btn" data-column="status">Status</button></th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Get current date in the format of check_in column
            $currentDate = date('Y-m-d');
            // Get current timestamp
            $currentTimestamp = time();

            foreach ($sortedRows as $row) :
                // Convert check-in date to a timestamp for comparison
                $checkInTimestamp = strtotime($row['check_in']);

                // Check if the check-in date is today or in the future
                if ($row['check_in'] >= $currentDate) :
            ?>
                <tr>
                    <td><?php echo $row['ID']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['check_in']; ?></td>
                    <td><?php echo $row['check_out']; ?></td>
                    <td><?php echo $row['room_type']; ?></td>
                    <td><?php echo $row['time']; ?></td>
                    <td>
                        <?php if (isset($row['payment_receipt']) && !empty($row['payment_receipt'])) : ?>
                            <?php if (is_file('images/' . $row['payment_receipt'])) : ?>
                                <img src="images/<?php echo $row['payment_receipt']; ?>" alt="Payment Image" width="100">
                            <?php else : ?>
                                <?php echo $row['payment_receipt']; ?>
                            <?php endif; ?>
                        <?php else : ?>
                            No payment receipt
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['recorded_at']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <?php if ($row['status'] === 'pending') : ?>
                            <a href="update_status.php?data=<?php echo $row['ID']; ?>" class="btn btn-success confirm-btn" onclick="return confirm('Are you sure you want to Confirm this record?')">Confirm</a>
                        <?php else : ?>
                            <a href="AeditRecord.php?data=<?php echo $row['ID']; ?>" class="btn btn-warning">Edit</a>
                        <?php endif; ?>
                        <a href="AdeleteRecord.php?data=<?php echo $row['ID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                        <a href="view.php?data=<?php echo $row['ID']; ?>" class="btn btn-primary">View</a>
                    </td>
                </tr>
            <?php endif; endforeach; ?>
        </tbody>
    </table>
    <?php
    } else {
        echo "No Reservation.";
    }

    $conn->close();
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            $(".sort-btn").click(function () {
                var columnIndex = $(this).parent().index();
                var sortOrder = "asc"; // Default sort order is ascending

                // Remove the sorting class from all buttons except the clicked one
                $(".sort-btn").not(this).removeClass("sorted-asc sorted-desc");

                if ($(this).hasClass("sorted-asc")) {
                    sortOrder = "desc";
                    $(this).removeClass("sorted-asc");
                    $(this).addClass("sorted-desc");
                } else {
                    $(this).removeClass("sorted-desc");
                    $(this).addClass("sorted-asc");
                }

                sortTable(columnIndex, sortOrder);
            });

            function sortTable(columnIndex, sortOrder) {
                var $table = $("table.table");
                var $tbody = $table.find("tbody");
                var $rows = $tbody.find("tr").get();

                $rows.sort(function (a, b) {
                    var keyA = $(a).children("td").eq(columnIndex).text().toUpperCase();
                    var keyB = $(b).children("td").eq(columnIndex).text().toUpperCase();

                    if (sortOrder === "asc") {
                        return (keyA > keyB) ? 1 : -1;
                    } else {
                        return (keyA < keyB) ? 1 : -1;
                    }
                });

                $.each($rows, function (index, row) {
                    $tbody.append(row);
                });
            }
        });
    </script>
</body>
</html>
