<?php 
include("../../backend/user-check.php") ;

include("../../config/connection.php");

$transID = mysqli_real_escape_string($conn, $_GET['transID']);


$sql = "SELECT *, TIMESTAMPDIFF(SECOND, time, NOW()) as seconds_elapsed FROM transections WHERE transID = '$transID'";


// Execute query and check if successful
$result = mysqli_query($conn, $sql) or die("Query Failed.");



$transData = mysqli_fetch_assoc($result);

$_SESSION['canceltrans']= $transData['transID'];

$username = $_SESSION['username'];

if ($transData['username']!=$username){
    header("Location: ../../views/user/user-dashboard.php", true, 303);
    exit();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Transaction Details</title>
</head>
<body onload="onload()" class="overflow-hidden dark">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/user-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="overflow-y-scroll px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/user-header.php") ?>

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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                        <div class="space-y-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Transaction ID</span>
                                <span class="text-lg font-semibold">#<?php echo $transData['transID']; ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Pin/Last Number</span>
                                <span class="text-lg font-semibold"><?php echo $transData['lastnumber']; ?></span>
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
                                <span class="text-sm text-gray-500 dark:text-gray-400">Time</span>
                                <span class="text-lg font-semibold"><?php echo $transData['time']; ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Note</span>
                                <span class="text-lg font-semibold"><?php echo $transData['note']; ?></span>
                            </div>

                            <div class="flex flex-col gap-4 mt-6">
                                <div class="flex gap-4">
                                    <button onclick="window.history.back();" 
                                        class="w-32 py-3 bg-blue-400 text-black rounded-lg hover:bg-blue-500 transition duration-300 text-center">
                                        Back
                                    </button>
                                    
                                    <?php 
                                    // Show cancel button if status is Pending and transaction is less than 30 seconds old
                                    if($transData['status'] === 'Pending' && $transData['seconds_elapsed'] <= 30): 
                                    ?>
                                        <button onclick="cancelTransaction('<?php echo $transData['transID']; ?>')" 
                                            class="w-32 py-3 bg-red-400 text-black rounded-lg hover:bg-red-500 transition duration-300 text-center">
                                            Cancel
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side Information Panel -->
                <div class="hidden sm:block w-1/3 space-y-6">
                    <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Transaction Information</h3>
                        <ul class="space-y-2 text-sm">
                            <li>• Keep your transaction ID for future reference</li>
                            <li>• Processing time: 5-10 minutes</li>
                            <li>• Check status in transaction history</li>
                            <li>• Contact support for any issues</li>
                        </ul>
                    </div>

                </div>
            </section>
        </div>
    </div>

    <script src="../../assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
        function cancelTransaction(transID) {
            if (confirm('Are you sure you want to cancel this transaction?')) {
                window.location.href = '../../backend/backend-user-request-money-cancel.php';
            }
        }
    </script>
</body>
</html>