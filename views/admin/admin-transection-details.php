<?php 
include("../../backend/admin-check.php") ;

include("../../config/connection.php");

$transID = mysqli_real_escape_string($conn, $_GET['transID']);


$sql = "SELECT * FROM transections WHERE transID = '$transID'";
$result = mysqli_query($conn, $sql) or die("Query Failed.");
$transData = mysqli_fetch_assoc($result);

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
} else {
    $error = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <title>Transaction Details - Admin</title>
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
                <!-- Transaction Details Card -->
                <div class="w-full sm:w-2/3 bg-white dark:bg-[#1b1a20] rounded-xl p-8 shadow-lg">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl sm:text-3xl font-bold">Transaction Details</h2>
                        <span class="px-4 py-2 rounded-lg text-center dark:text-gray-950 <?php 
                            echo match($transData['status']) {
                                'Accepted' => 'bg-green-400',
                                'Pending' => 'bg-yellow-400',
                                'Rejected' => 'bg-red-400',
                                'Canceled' => 'bg-gray-400',
                                default => 'bg-gray-400'
                            };
                        ?>">
                            <?php echo $transData['status']; ?>
                        </span>
                    </div>

                    <!-- Transaction Details Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                        <div class="space-y-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Transaction ID</span>
                                <span class="text-lg font-semibold">#<?php echo $transData['transID']; ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">User</span>
                                <span class="text-lg font-semibold"><?php echo $transData['username']; ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Amount</span>
                                <span class="text-lg font-semibold"><?php echo $transData['amount']; ?> Tk</span>
                                <span class="text-sm text-gray-500"><?php echo $transData['amountYMR']; ?> MYR</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Number</span>
                                <span class="text-lg font-semibold"><?php echo $transData['number']; ?></span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Banking Method</span>
                                <span class="text-lg font-semibold"><?php echo $transData['banking']; ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Transaction Type</span>
                                <span class="text-lg font-semibold"><?php echo $transData['method']; ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Pin/Last Number</span>
                                <span class="text-lg font-semibold"><?php echo $transData['lastnumber']; ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Time</span>
                                <span class="text-lg font-semibold"><?php echo $transData['time']; ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Note</span>
                                <span class="text-lg font-semibold"><?php echo $transData['note']; ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons - Moved outside the grid -->
                    <div class="space-y-4">
                        <?php if($transData['status'] === 'Pending'): ?>
                            <div class="flex gap-4">
                                <a href="admin-accept-transection.php?transID=<?php echo $transData['transID']; ?>"
                                    class="w-32 py-3 bg-green-400 text-black rounded-lg hover:bg-green-500 transition duration-300 text-center">
                                    Accept
                                </a>
                                
                                <a href="admin-reject-transection.php?transID=<?php echo $transData['transID']; ?>"
                                    class="w-32 py-3 bg-red-400 text-black rounded-lg hover:bg-red-500 transition duration-300 text-center">
                                    Reject
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex gap-4">
                            <button onclick="window.history.back();" 
                                class="w-32 py-3 bg-blue-400 text-black rounded-lg hover:bg-blue-500 transition duration-300 text-center">
                                Back
                            </button>
                            
                            <?php if($transData['status'] === 'Pending'): ?>
                                <a href="admin-modify-transection.php?transID=<?php echo $transData['transID']; ?>"
                                    class="w-32 py-3 bg-yellow-400 text-black rounded-lg hover:bg-yellow-500 transition duration-300 text-center">
                                    Modify
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Side Information Panel -->
                <div class="hidden sm:block w-1/3 space-y-6">
                    <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Admin Actions</h3>
                        <ul class="space-y-2 text-sm">
                            <li>• Verify user details before accepting</li>
                            <li>• Check banking information carefully</li>
                            <li>• Modify if amount needs correction</li>
                            <li>• Provide reason when rejecting</li>
                        </ul>
                    </div>

                    <div class="bg-green-100 dark:bg-green-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Transaction Notes</h3>
                        <p class="text-sm mb-4">Review all details thoroughly before taking action.</p>
                        <a href="admin-all-transection-history.php" class="text-blue-500 hover:text-blue-600 text-sm">View All Transactions →</a>
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
