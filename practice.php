while ($row = $result->fetch_assoc()) {
    $checkInDate = $row['check_in'];
    $checkOutDate = $row['check_out'];
    $status = $row['status'];

    // Check if the date is in the past
    $isPast = strtotime($checkOutDate) < strtotime(date('Y-m-d'));

    // Only add the event if check_in and check_out are equal
    if ($checkInDate == $checkOutDate) {
        $eventColor = ($status == 'confirm') ? '' : '';

        // Set title based on status and whether the date is in the past
        $title = ($status == 'confirm' && !$isPast) ? 'Reserved' : 'Stay Completed';

        // Set completed color for past dates
        if ($isPast) {
            $status = 'completed';
            $eventColor = '#FFD700'; // You can change the color as needed
        }

        $event = array(
            'title' => $title,
            'start' => $checkInDate,
            'color' => $eventColor,
        );
        $events[] = $event;
    }
}