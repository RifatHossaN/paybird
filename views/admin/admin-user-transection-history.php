<?php 
include("../../backend/admin-check.php");
include("../../config/connection.php");

if (isset($_GET['user'])) {
    $user = $_GET['user'];
    $user = filter_var($user, FILTER_SANITIZE_STRING);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Transaction History</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body onload="onload()" class="overflow-hidden">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="px-8 w-full overflow-y-scroll min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/admin-header.php") ?>

            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 h-full">
                <div class="w-full bg-white dark:bg-[#232228] rounded-xl">
                    <div class="flex items-center justify-between mb-2 pt-2">
                        <h2 class="text-2xl font-bold">Transaction History - <?php echo $user; ?></h2>
                        
                    </div>

                    <div class="">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b text-sm font-medium text-gray-600 dark:text-gray-400">
                                    <th class="py-3 px-4">Trans. ID</th>
                                    <th class="py-3 px-4">Method</th>
                                    <th class="py-3 px-4">Number</th>
                                    <th class="py-3 px-4">Amount</th>
                                    <th class="py-3 px-4">Time</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM transections 
                                        WHERE username = '$user' 
                                        AND TIMESTAMPDIFF(SECOND, time, NOW()) > 30 
                                        ORDER BY time DESC";
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
                                            <td class='py-3 px-4 font-medium'>#".$row["transID"]."</td>
                                            <td class='py-3 px-4'>".$row["method"]."</td>
                                            <td class='py-3 px-4'>".$row["number"]."</td>
                                            <td class='py-3 px-4'>
                                                <h4 class='text-base font-bold'>".$row["amount"]." TK</h4>
                                                <p class='text-sm text-gray-700 dark:text-gray-400'>".$row["amountYMR"]." MYR</p>
                                            </td>
                                            <td class='py-3 px-4'>
                                                <p class='text-sm text-gray-700 dark:text-gray-400'>".$row["time"]."</p>
                                            </td>
                                            <td class='py-3 px-4'>
                                                <p class='".$statusColor." p-0.5 rounded-lg text-center dark:text-gray-950'>".$row["status"]."</p>
                                            </td>
                                            <td class='py-3 px-4 text-center'>
                                                <a href='admin-transection-details.php?transID=".$row["transID"]."'>
                                                    <button class='bg-blue-400 py-1 px-2 rounded-lg transition duration-500 dark:text-gray-950 hover:bg-blue-500'>View</button>
                                                </a>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center py-4'>No transactions found.</td></tr>";
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
