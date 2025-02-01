<!-- Include the header -->
<?php include 'Aheader.php'; ?>
   <br>
      <div class="back_re">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="title">
                    <h2>Reservation Record</h2>
                  </div>
               </div>
            </div>
         </div>
      </div>

    <div class="container">
        <h2>Search</h2>
        <form method="GET" action="AsearchRecord.php">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Enter search keyword...">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">Search</button>
                </span>
            </div>
        </form>
    </div>
    <br>
    <?php
    require 'Adisplay_bookings.php'; 
    ?>
     
     <!-- Include the footer -->
   <?php include 'Afooter.php'; ?>
   </body>
</html>







