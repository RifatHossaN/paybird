<?php 
include("../../backend/admin-check.php");
include("../../config/connection.php");

// Set default values
$groupBy = isset($_POST['groupBy']) ? $_POST['groupBy'] : 'daily';
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Total Transactions</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body onload="onload()" class="overflow-hidden">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/admin-header.php") ?>

            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 h-full">
                <div class="bg-white dark:bg-[#232228] rounded-lg w-full">
                    <!-- Combined title and filter in one row -->
                    <div class="flex flex-col sm:flex-row pt-8 items-center justify-between mb-2 sm:mb-4">
                        <h3 class="text-2xl font-bold mb-4 sm:mb-0">Transaction Totals</h3>
                        
                        <!-- Filter Form -->
                        <form method="POST" class="flex flex-row justify-end sm:justify-end space-x-1 sm:space-x-2 overflow-x-auto">
                            <select name="groupBy" class="w-24 sm:w-auto px-1 sm:px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700">
                                <option value="daily" <?php echo $groupBy == 'daily' ? 'selected' : ''; ?>>Daily</option>
                                <option value="weekly" <?php echo $groupBy == 'weekly' ? 'selected' : ''; ?>>Weekly</option>
                                <option value="monthly" <?php echo $groupBy == 'monthly' ? 'selected' : ''; ?>>Monthly</option>
                            </select>

                            <div class="space-x-1 sm:space-x-2">
                                <input type="date" name="start_date" value="<?php echo $start_date; ?>" 
                                    class="w-24 sm:w-auto px-1 sm:px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700">
                                <input type="date" name="end_date" value="<?php echo $end_date; ?>" 
                                    class="w-24 sm:w-auto px-1 sm:px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700">
                            </div>

                            <button type="submit" name="btn-filter" value="clicked" 
                                class="w-20 sm:w-auto bg-blue-400 text-black px-2 py-1 rounded-lg hover:bg-blue-500 transition duration-300">
                                Filter
                            </button>
                        </form>
                    </div>

                    <div class="overflow-y-scroll h-[82vh] sm:h-[82vh]">
                        <table class="w-full h-1/3 text-left">
                            <thead>
                                <tr class="border-b text-sm font-medium text-gray-600 dark:text-gray-400">
                                    <th class="px-4 py-3 text-left">Date</th>
                                    <th class="px-4 py-3 text-left">Total Accepted (TK)</th>
                                    <th class="px-4 py-3 text-left">Total Accepted (MYR)</th>
                                    <th class="px-4 py-3 text-left">Total Transactions</th>
                                    <th class="px-4 py-3 text-left">Accepted</th>
                                    <th class="px-4 py-3 text-left">Pending</th>
                                    <th class="px-4 py-3 text-left">Rejected</th>
                                    <th class="px-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <?php
                                // Base query with dynamic date format based on grouping
                                $dateFormat = $groupBy === 'monthly' ? '%Y-%m' : ($groupBy === 'weekly' ? '%Y-%u' : '%Y-%m-%d');
                                
                                $sql = "SELECT 
                                    DATE_FORMAT(time, '$dateFormat') as date_group,
                                    SUM(CASE WHEN status = 'Accepted' THEN amount ELSE 0 END) as accepted_amount,
                                    SUM(CASE WHEN status = 'Accepted' THEN amount/rate ELSE 0 END) as accepted_myr,
                                    COUNT(*) as total_transactions,
                                    SUM(CASE WHEN status = 'Accepted' THEN 1 ELSE 0 END) as accepted,
                                    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
                                    SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) as rejected
                                FROM transections 
                                WHERE DATE(time) BETWEEN ? AND ?
                                GROUP BY date_group 
                                ORDER BY date_group DESC";

                                $stmt = mysqli_prepare($conn, $sql);
                                mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $date_display = $groupBy === 'monthly' ? 
                                            date('F Y', strtotime($row['date_group'] . '-01')) : 
                                            ($groupBy === 'weekly' ? 
                                                'Week ' . substr($row['date_group'], -2) . ', ' . substr($row['date_group'], 0, 4) : 
                                                date('d M Y', strtotime($row['date_group'])));
                                        
                                        echo "
                                        <tr class='transition duration-500 hover:bg-gray-50 dark:hover:bg-[#1e1d20]'>
                                            <td class='py-3 px-4 font-medium'>{$date_display}</td>
                                            <td class='py-3 px-4'>" . number_format($row['accepted_amount']) . " TK</td>
                                            <td class='py-3 px-4'>" . number_format($row['accepted_myr'], 2) . " MYR</td>
                                            <td class='py-3 px-4'>" . $row['total_transactions'] . "</td>
                                            <td class='py-3 px-4'>
                                                <span class='bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 px-2 py-1 rounded-full'>
                                                    " . $row['accepted'] . "
                                                </span>
                                            </td>
                                            <td class='py-3 px-4'>
                                                <span class='bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 px-2 py-1 rounded-full'>
                                                    " . $row['pending'] . "
                                                </span>
                                            </td>
                                            <td class='py-3 px-4'>
                                                <span class='bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 px-2 py-1 rounded-full'>
                                                    " . $row['rejected'] . "
                                                </span>
                                            </td>
                                            <td class='py-3 px-4 text-center'>
                                                <a href='admin-user-archived-transections.php?date=" . urlencode($row['date_group']) . "&type=" . urlencode($groupBy) . "'>
                                                    <button class='bg-blue-400 py-1 px-2 rounded-lg transition duration-500 dark:text-gray-950 hover:bg-blue-500'>
                                                        View
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center py-4'>No transactions found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="../../assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html> 