<?php

include("../../config/connection.php");

// Fetch Data from the Database
$sql = "SELECT `depositID`, `username`, `amount`, `amountMYR`, `currency`, `payment_method`, `reference_no`, `status`, `admin_note`, `created_at`, `payment_date` FROM `deposits` ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="table_data.csv"');

    $output = fopen('php://output', 'w');

    // Add Headers
    $columns = array_keys($result->fetch_assoc());
    fputcsv($output, $columns);
    $result->data_seek(0); // Reset result pointer

    // Add Data Rows
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
} else {
    echo "No data available.";
}

?>
