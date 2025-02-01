<?php 
include("../../backend/admin-check.php");
include("../../config/connection.php");

$date = isset($_GET['date']) ? $_GET['date'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';

// Format title based on type
$title = "";
if ($type === 'monthly') {
    $title = date('F Y', strtotime($date . '-01'));
    $date_condition = "DATE_FORMAT(time, '%Y-%m') = '$date'";
} elseif ($type === 'weekly') {
    $title = 'Week ' . substr($date, -2) . ', ' . substr($date, 0, 4);
    $date_condition = "DATE_FORMAT(time, '%Y-%u') = '$date'";
} else {
    $title = date('d F Y', strtotime($date));
    $date_condition = "DATE(time) = '$date'";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Archived Transactions</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body onload="onload()" class="overflow-hidden">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <div id="main-container" class="px-8 w-full min-h-full overflow-y-scroll sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/admin-header.php") ?>

            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 h-full">
                <div class="bg-white dark:bg-[#232228] rounded-lg w-full">
                    <div class="flex flex-col sm:flex-row pt-8 items-center justify-between mb-2 sm:mb-4">
                        <h3 class="text-2xl font-bold mb-4 sm:mb-0">Transactions for <?php echo $title; ?></h3>
                        
                        <!-- Filter Form -->
                        <form method="POST" class="flex flex-row justify-end sm:justify-end space-x-1 sm:space-x-2 overflow-x-auto">
                            <input type="text" id="filter-input" name="filter-val" placeholder="Search..." 
                                class="w-24 sm:w-auto px-1 sm:px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700 focus:outline-none focus:border-blue-500">
                            
                            <select name="filterOption" id="filter-option" 
                                class="w-24 sm:w-auto px-1 sm:px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700 focus:outline-none focus:border-blue-500">
                                <option value="transID">by Trans. ID</option>
                                <option value="username">by Username</option>
                                <option value="name">by Name</option>
                                <option value="number">by Number</option>
                                <option value="banking">by Banking</option>
                            </select>

                            <button type="submit" name="btn-filter" value="clicked" 
                                class="w-20 sm:w-auto bg-blue-400 text-black px-2 py-1 rounded-lg hover:bg-blue-500 transition duration-300">
                                Filter
                            </button>
                        </form>
                    </div>

                    <div class="">
                        <table class="w-full h-1/3 text-left">
                            <thead>
                                <tr class="border-b text-sm font-medium text-gray-600 dark:text-gray-400">
                                    <th class="py-2 text-left w-24">Banking</th>
                                    <th class="py-2 px-4 w-40">Method</th>
                                    <th class="py-2 px-4 w-40">Sender</th>
                                    <th class="py-2 px-4 w-48">Number</th>
                                    <th class="py-2 px-4 w-40">Amount</th>
                                    <th class="py-2 px-4 w-32">Status</th>
                                    <th class="py-2 px-4 text-center w-24">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(!empty($_POST["btn-filter"]) && $_POST["btn-filter"]=="clicked") {
                                    $filterOption = mysqli_real_escape_string($conn, $_POST['filterOption']);
                                    $filterVal = mysqli_real_escape_string($conn, $_POST['filter-val']);

                                    switch($filterOption) {
                                        case 'transID':
                                            $sql = "SELECT * FROM transections WHERE $date_condition AND transID LIKE '%$filterVal%' ORDER BY time DESC";
                                            break;
                                        case 'username':
                                            $sql = "SELECT * FROM transections WHERE $date_condition AND username LIKE '%$filterVal%' ORDER BY time DESC";
                                            break;
                                        case 'name':
                                            $sql = "SELECT t.* FROM transections t 
                                                    JOIN users u ON t.username = u.username 
                                                    WHERE $date_condition AND u.name LIKE '%$filterVal%' 
                                                    ORDER BY t.time DESC";
                                            break;
                                        case 'number':
                                            $sql = "SELECT * FROM transections WHERE $date_condition AND number LIKE '%$filterVal%' ORDER BY time DESC";
                                            break;
                                        case 'banking':
                                            $sql = "SELECT * FROM transections WHERE $date_condition AND banking LIKE '%$filterVal%' ORDER BY time DESC";
                                            break;
                                        default:
                                            $sql = "SELECT * FROM transections WHERE $date_condition ORDER BY time DESC";
                                    }
                                } else {
                                    $sql = "SELECT * FROM transections WHERE $date_condition ORDER BY time DESC";
                                }

                                $result = mysqli_query($conn, $sql) or die("Query Failed.");

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $statusColor = [
                                            "Accepted" => "bg-green-400",
                                            "Pending" => "bg-yellow-400",
                                            "Rejected" => "bg-red-400",
                                            "Canceled" => "bg-gray-400"
                                        ][$row["status"]];

                                        echo "
                                        <tr class='transition duration-500 hover:bg-gray-50 dark:hover:bg-[#1e1d20]'>
                                            <td class='py-2 px-1 w-24'>
                                                <img class='h-12 w-12' src='../../assets/imgs/".$row["banking"].".png' alt='".$row["banking"]."-logo'>
                                            </td>
                                            <td class='py-2 px-4 w-40'>
                                                <h4 class='text-base font-bold truncate'>".$row["method"]."</h4>
                                                <p class='hidden sm:block text-sm text-gray-700 dark:text-gray-400 truncate'>".$row["time"]."</p>
                                            </td>
                                            <td class='py-2 px-4 w-40'>
                                                <p class='text-lg font-bold truncate'>".$row["username"]."</p>
                                            </td>
                                            <td class='py-2 px-4 w-48'>
                                                <p class='text-base font-bold truncate'>".$row["number"]."</p>
                                                <p class='sm:hidden text-sm text-gray-700 dark:text-gray-400 truncate'>".$row["time"]."</p>
                                                <p class='hidden sm:block text-sm text-gray-700 dark:text-gray-400'>Transec. ID: #".$row["transID"]."</p>
                                            </td>
                                            <td class='py-2 px-4 w-40'>
                                                <h4 class='text-base font-bold'>".$row["amount"]." TK</h4>
                                                <p class='hidden sm:block text-sm text-gray-700 dark:text-gray-400'>".number_format($row["amount"]/$row["rate"], 2)." MYR</p>
                                            </td>
                                            <td class='py-2 px-4 w-32'>
                                                <p class='".$statusColor." p-0.5 rounded-lg text-center dark:text-gray-950'>".$row["status"]."</p>
                                            </td>
                                            <td class='py-2 px-4 w-24 text-center'>
                                                <a href='admin-transection-details.php?transID=".$row["transID"]."'>
                                                    <button class='bg-blue-400 py-1 px-2 rounded-lg transition duration-500 dark:text-gray-950 hover:bg-blue-500'>View</button>
                                                </a>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center py-4'>No transactions found for this period.</td></tr>";
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