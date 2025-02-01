<!--include header-->
<?php include 'header.php';?>
<br>
<div class="back_re">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h2>Rooms</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- blog -->
<div class="rooms">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="titlepage">
                    <p class="margin_0">Rooms Available in Resort</p>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            require_once 'admin/dbconfig.php';

            $dbConfig = new DBConfig();
            $conn = $dbConfig->dbConnect();

            $result = $conn->query("SELECT * FROM rooms");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                        <div class="rooms_box">
                            <div class="rooms_img">
                                <?php if (!empty($row['room_image']) && file_exists('admin/images/' . $row['room_image'])): ?>
                                    <figure><img src="admin/images/<?php echo $row['room_image']; ?>" alt="Room Image"/></figure>
                                <?php else: ?>
                                    <figure><img src="admin/images/default-room-image.jpg" alt="Default Room Image"/></figure>
                                <?php endif; ?>
                            </div>
                            <div class="rooms_room">
                                <h3><?php echo $row['room_type']; ?></h3>
                                <p><?php echo 'Available:' . $row['room_count']; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No rooms found.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</div>
<!-- end blog -->

<!--include footer-->
<?php include 'footer.php';?>
