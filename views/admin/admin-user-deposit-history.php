<?php 
include("../../backend/admin-check.php");

if (!isset($_GET['user'])) {
    header("Location: admin-user-deposit-list.php");
    exit();
}

$depositUser = $_GET['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <title>User Deposit History</title>
</head>
<body onload="onload()" class="overflow-hidden">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <div id="main-container" class="px-8 w-full min-h-full overflow-y-scroll sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/admin-header.php") ?>

            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 h-full">
                <div class="bg-white sm:mt-6 dark:bg-[#232228] rounded-lg w-full">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold">Deposit History for <?php echo htmlspecialchars($depositUser ); ?></h3>
                        </div>
                    </div>

                    <!-- Deposits Table -->
                    <div class="">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b text-sm font-medium text-gray-600 dark:text-gray-400">
                                    <th class="py-2 text-left">Deposit ID</th>
                                    <th class="py-2">Date</th>
                                    <th class="py-2">Method</th>
                                    <th class="py-2">Amount</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include("../../config/connection.php");

                                $sql = "SELECT * FROM deposits WHERE username = '$depositUser ' ORDER BY created_at DESC";
                                $result = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $statusColor = [
                                            "Approved" => "bg-green-400",
                                            "Pending" => "bg-yellow-400",
                                            "Rejected" => "bg-red-400"
                                        ][$row["status"]];

                                        echo "
                                        <tr class='border-b hover:bg-gray-50 dark:hover:bg-[#1e1d20]'>
                                            <td class='py-3'>#" . $row['depositID'] . "</td>
                                            <td class='py-3'>" . $row['created_at'] . "</td>
                                            <td class='py-3'>" . ucfirst($row['payment_method']) . "</td>
                                            <td class='py-3'>
                                                " . number_format($row['amount'], 2) . " " . $row['currency'] . "
                                            </td>
                                            <td class='py-3'>
                                                <span class='$statusColor px-2 py-1 rounded-lg text-black'>
                                                    " . $row['status'] . "
                                                </span>
                                            </td>
                                            <td class='py-3'>
                                                <a href='admin-deposit-details.php?id=" . $row['depositID'] . "' 
                                                    class='bg-blue-400 text-black px-3 py-1 rounded-lg hover:bg-blue-500'>
                                                    View
                                                </a>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='py-4 text-center'>No deposits found for this user</td></tr>";
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