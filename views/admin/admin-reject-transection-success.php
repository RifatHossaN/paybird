<?php 
include("../../backend/admin-check.php");
include("../../config/connection.php");

$transID = mysqli_real_escape_string($conn, $_GET['transID']);

// Get transaction data
$sql = "SELECT * FROM transections WHERE transID = '$transID'";
$result = mysqli_query($conn, $sql) or die("Query Failed.");
$transData = mysqli_fetch_assoc($result);

// Redirect if no transaction found
if (!$transData) {
    header("Location: admin-dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <title>Transaction Rejected</title>
</head>
<body onload="onload()" class="overflow-hidden dark">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="overflow-y-scroll px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/admin-header.php") ?>

            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 h-full">
                <!-- Success Message Card -->
                <div class="w-full sm:w-2/3 bg-white dark:bg-[#1b1a20] rounded-xl p-8 shadow-lg">
                    <div class="text-center mb-8">
                        <div class="flex justify-center mb-6">
                            <div class="w-20 h-20 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                <ion-icon name="close-circle" class="text-6xl text-red-500"></ion-icon>
                            </div>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold mb-4">Transaction Rejected</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">The transaction has been rejected</p>
                    </div>

                    <!-- Transaction Details -->
                    <div class="bg-gray-50 dark:bg-[#161618] rounded-xl p-6 mb-8">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Transaction ID</p>
                                <p class="font-semibold">#<?php echo $transData['transID']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">User</p>
                                <p class="font-semibold"><?php echo $transData['username']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Amount</p>
                                <p class="font-semibold"><?php echo $transData['amount']; ?> Tk</p>
                                <p class="text-sm text-gray-500"><?php echo $transData['amountYMR']; ?> MYR</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Banking</p>
                                <p class="font-semibold"><?php echo $transData['banking']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Number</p>
                                <p class="font-semibold"><?php echo $transData['number']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Method</p>
                                <p class="font-semibold"><?php echo $transData['method']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                <p class="w-24 bg-red-400 p-0.5 rounded-lg text-center dark:text-gray-950"><?php echo $transData['status']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Rejection Reason</p>
                                <p class="font-semibold text-red-500"><?php echo $transData['lastnumber']; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 mt-6">
                        <a href="admin-panel.php" 
                            class="w-32 py-3 bg-blue-400 text-black rounded-lg hover:bg-blue-500 transition duration-300 text-center">
                            Home
                        </a>
                        
                        <a href="admin-pending-transection.php" 
                            class="w-32 py-3 bg-gray-400 text-black rounded-lg hover:bg-gray-500 transition duration-300 text-center">
                            Pending Trans.
                        </a>
                    </div>
                </div>

                <!-- Right Side Information Panel -->
                <div class="hidden sm:block w-1/3 space-y-6">
                    <div class="bg-red-100 dark:bg-red-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Transaction Rejected</h3>
                        <ul class="space-y-2 text-sm">
                            <li>• Transaction has been rejected</li>
                            <li>• User will be notified</li>
                            <li>• Rejection reason recorded</li>
                            <li>• Status updated to Rejected</li>
                        </ul>
                    </div>

                    <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Next Steps</h3>
                        <p class="text-sm mb-4">You can view the transaction details or return to all transactions.</p>
                        <a href="admin-dashboard.php" class="text-blue-500 hover:text-blue-600 text-sm">Go to Dashboard →</a>
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