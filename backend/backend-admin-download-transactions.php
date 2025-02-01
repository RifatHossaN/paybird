<?php
include("admin-check.php");
include("../config/connection.php");

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="transactions_' . date('Y-m-d') . '.csv"');

// Create output stream
$output = fopen('php://output', 'w');

// Add UTF-8 BOM for proper Excel encoding
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Add CSV headers
fputcsv($output, array('Transaction ID', 'Username', 'Banking', 'Method', 'Number', 'Amount (TK)', 'Amount (MYR)', 'Status', 'Time'));

// Get all transactions
$sql = "SELECT * FROM transections ORDER BY time DESC";
$result = mysqli_query($conn, $sql);

// Write data rows
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, array(
        $row['transID'],
        $row['username'],
        $row['banking'],
        $row['method'],
        $row['number'],
        $row['amount'],
        $row['amountYMR'],
        $row['status'],
        $row['time']
    ));
}

// Close the output stream
fclose($output);
exit();