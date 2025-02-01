<?php include 'header.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add your head content here -->
    <!-- Make sure to include Bootstrap CSS and JavaScript files -->
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Include Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<!-- Include Bootstrap JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</head>
<body>

    <!-- Your page content goes here -->

    <!-- Add this script at the end of the body -->
    <script>
        // This script will show the modal with the ID 'autoModal' when the page loads
        $(document).ready(function(){
            $('#autoModal').modal('show');
        });
    </script>

<div class="modal fade" id="autoModal" tabindex="-1" role="dialog" aria-labelledby="autoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="autoModalLabel">Terms and Condition</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>FULL PAYMENT MUST BE SETTLED 30 MINUTES AFTER THE ARRIVAL</p>
                    <p>Reservation fee of 50% will be required to ensure the specified schedule of the client. The client will be given a 2 hours allowance based on the agreement time of arrival, after which, the management has the right to cancel the reservation and forfeit the reservation.</p>
                    <p>Reservation changes may still be considered five days prior to the scheduled time. Rescheduling a reservation is still possible according to the private pool’s availability and with the right guidance. THERE IS NO REFUND FOR THE RESERVATION FEE.</p>
                    <p>Day session is from 8:00 am to 5:00 pm, Night session is from 7:00 pm to 6:00 am, No extension is allowed unless permitted by the management.</p>
                    <p>The maximum number of persons shall be 20, in excess of which, 200 additional fees (including 4 years old above) per person will be charged.</p>
                    <p>It is understood that the management is not responsible for an accident, injury, or losses to any clients belonging during the stay.</p>
                    <p>Clients must properly observe house rules, No horse playing, eating & drinking beside the pool & bedrooms. Childrens should always be supervised by an adult within the pool premises at all times. Firearms and illegal drugs are strictly prohibited.</p>
                    <p>It is the standard operating procedure of the management to check items and equipment thirty minutes upon the check out of the premises, Therefore, items and equipment found missing or damaged will be charged to the customer’s account.</p>
                    <p>Pets are not allowed inside the rooms and in the pool area (500 penalty)</p>
                    <p>STRICTLY NO EARLY CHECK-IN</p>
                </div>

                </div>
            </div>
        </div>
    </div>


</body>
</html>
