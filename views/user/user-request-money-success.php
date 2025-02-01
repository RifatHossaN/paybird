<?php 
include("../../backend/user-check.php");
include("../../config/connection.php");

$username = $_SESSION['username'];

// Get the latest transaction for this user
$sql = "SELECT *, TIMESTAMPDIFF(SECOND, time, NOW()) as seconds_elapsed 
        FROM transections 
        WHERE username = '$username' 
        ORDER BY time DESC 
        LIMIT 1";

$result = mysqli_query($conn, $sql);
$transData = mysqli_fetch_assoc($result);

// Redirect if no transaction found
if (!$transData) {
    header("Location: user-dashboard.php");
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
    <title>Request Money Successful</title>
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
                <!-- Success Message Card -->
                <div class="w-full sm:w-2/3 bg-white dark:bg-[#1b1a20] rounded-xl p-8 shadow-lg">
                    <!-- Success Message -->
                    <div class="text-center mb-8">
                        <div class="flex justify-center mb-6">
                            <div class="w-20 h-20 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                <ion-icon name="checkmark-circle" class="text-6xl text-green-500"></ion-icon>
                            </div>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold mb-4">Request Submitted Successfully!</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">Your transaction request has been received</p>
                        <?php if($transData['seconds_elapsed'] <= 30): ?>
                            <p class="text-red-500 dark:text-red-400 mb-2">You can only cancel it within 30 seconds.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Transaction Details -->
                    <div class="bg-gray-50 dark:bg-[#161618] rounded-xl p-6 mb-8">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Transaction ID</p>
                                <p class="font-semibold">#<?php echo $transData['transID']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Amount</p>
                                <p class="font-semibold"><?php echo $transData['amount']; ?> Tk</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Banking</p>
                                <p class="font-semibold"><?php echo $transData['banking']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Method</p>
                                <p class="font-semibold"><?php echo $transData['method']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Number</p>
                                <p class="font-semibold"><?php echo $transData['number']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                <p class="font-semibold"><?php echo $transData['status']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Time</p>
                                <p class="font-semibold"><?php echo $transData['time']; ?></p>
                            </div>
                        </div>
                    </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4">
                            <?php if($transData['status'] === 'Pending' && $transData['seconds_elapsed'] <= 30): ?>
                                <button onclick="cancelTransaction('<?php echo $transData['transID']; ?>')" 
                                    class="w-32 py-3 bg-red-400 text-black rounded-lg hover:bg-red-500 transition duration-300 text-center">
                                    Cancel
                                </button>
                            <?php endif; ?>
                            
                            <a href="user-transection-details.php?transID=<?php echo $transData['transID']; ?>" 
                                class="w-32 py-3 bg-blue-400 text-black rounded-lg hover:bg-blue-500 transition duration-300 text-center">
                                Details
                            </a>
                            
                            <a href="user-dashboard.php" 
                                class="w-32 py-3 bg-gray-400 text-black rounded-lg hover:bg-gray-500 transition duration-300 text-center">
                                Dashboard
                            </a>
                        </div>
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