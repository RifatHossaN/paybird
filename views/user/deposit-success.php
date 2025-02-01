<?php
include("../../backend/user-check.php");

if (!isset($_SESSION['temp_depositID'])) {
    header("Location: user-dashboard.php");
    exit();
}

include("../../config/connection.php");

$depositID = $_SESSION['temp_depositID'];
$sql = "SELECT * FROM deposits WHERE depositID = '$depositID'";
$result = mysqli_query($conn, $sql);
$depositData = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <title>Deposit Submitted</title>
</head>
<body onload="onload()" class="overflow-hidden">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/user-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="overflow-y-scroll px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/user-header.php") ?>

            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 h-full">
                <div class="w-full sm:w-2/3 bg-white dark:bg-[#1b1a20] rounded-xl p-8 shadow-lg">
                    <!-- Success Message -->
                    <div class="text-center mb-8">
                        <div class="flex justify-center mb-6">
                            <div class="w-20 h-20 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                <ion-icon name="checkmark-circle" class="text-6xl text-green-500"></ion-icon>
                            </div>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold mb-4">Deposit Request Submitted!</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">Your deposit request has been received</p>
                    </div>

                    <!-- Deposit Details -->
                    <div class="bg-gray-50 dark:bg-[#161618] rounded-xl p-6 mb-8">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Deposit ID</p>
                                <p class="font-semibold">#<?php echo $depositData['depositID']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Amount</p>
                                <p class="font-semibold">
                                    <?php
                                    if ($depositData['currency'] == 'BDT'){
                                        echo $depositData['amount']." BDT";
                                        echo "<span class='block text-sm text-gray-500'>". $depositData['amountMYR'] ." MYR</span>";
                                    }else{
                                        echo $depositData['amountMYR']." MYR";
                                        echo "<span class='block text-xs text-gray-500'>". $depositData['amount'] ." BDT</span>";
                                    }
                                    ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Payment Method</p>
                                <p class="font-semibold"><?php echo $depositData['payment_method']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Reference Number</p>
                                <p class="font-semibold"><?php echo $depositData['reference_no'] ?? 'N/A'; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-center gap-4">
                        <a href="user-deposit-details.php?id=<?php echo $depositData['depositID']; ?>" 
                            class="px-8 py-3 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition duration-300 dark:text-gray-950">
                            View Details
                        </a>
                        <a href="user-deposit-history.php" 
                            class="px-8 py-3 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition duration-300 dark:text-gray-950">
                            View History
                        </a>
                    </div>
                </div>

                <!-- Right Side Information Panel -->
                <div class="hidden sm:block w-1/3 space-y-6">
                    <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Important Notice</h3>
                        <ul class="space-y-2 text-sm">
                            <li>• Save your deposit ID</li>
                            <li>• Processing time: 5-10 minutes</li>
                            <li>• Admin will verify your deposit</li>
                            <li>• Check status in deposit history</li>
                        </ul>
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