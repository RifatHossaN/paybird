<?php 
include("../../backend/user-check.php");
include("../../config/connection.php");

$depositID = mysqli_real_escape_string($conn, $_GET['id']);
$username = $_SESSION['username'];

// Fetch deposit data with username check for security
$sql = "SELECT * FROM deposits WHERE depositID = '$depositID' AND username = '$username'";
$result = mysqli_query($conn, $sql) or die("Query Failed.");

if(mysqli_num_rows($result) == 0) {
    header("Location: user-deposit-history.php");
    exit();
}

$depositData = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <title>Deposit Details</title>
</head>
<body onload="onload()" class="overflow-hidden dark">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/user-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="overflow-y-scroll px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/user-header.php") ?>

            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 h-full mt-2">
                <!-- Deposit Details Card -->
                <div class="w-full sm:w-2/3 rounded-xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl sm:text-3xl font-bold">Deposit Details</h2>
                        <span class="px-4 py-2 rounded-lg text-center dark:text-gray-950 <?php 
                            echo match($depositData['status']) {
                                'Approved' => 'bg-green-400',
                                'Pending' => 'bg-yellow-400',
                                'Rejected' => 'bg-red-400',
                                default => 'bg-gray-400'
                            };
                        ?>">
                            <?php echo $depositData['status']; ?>
                        </span>
                    </div>

                    <!-- Deposit Details Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                        <div class="space-y-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Deposit ID</span>
                                <span class="text-lg font-semibold">#<?php echo $depositData['depositID']; ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Amount</span>
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
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Payment Method</span>
                                <span class="text-lg font-semibold"><?php echo $depositData['payment_method']; ?></span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Reference Number</span>
                                <span class="text-lg font-semibold"><?php echo $depositData['reference_no'] ?? 'N/A'; ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Created At</span>
                                <span class="text-lg font-semibold"><?php echo date('Y-m-d H:i', strtotime($depositData['created_at'])); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Payment Currency</span>
                                <span class="text-lg font-semibold"><?php echo $depositData['currency']; ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Receipt Image -->
                    <?php if($depositData['receipt_image']): ?>
                    <div class="mt-6">
                        <h3 class="text-xl font-semibold mb-4">Receipt Image</h3>
                        <div class="space-y-4">
                            <img src="<?php echo htmlspecialchars($depositData['receipt_image']); ?>" 
                                 alt="Receipt" 
                                 class="w-[80vw] sm:max-w-md rounded-lg shadow-lg">
                            
                            <!-- Download Button -->
                            <div>
                                <a href="<?php echo htmlspecialchars($depositData['receipt_image']); ?>" 
                                   download="receipt_<?php echo "username-".$depositData['username']."_deposidID-".$depositData['depositID']; ?>.png"
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                                    <ion-icon name="download-outline" class="mr-2"></ion-icon>
                                    Download Receipt
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Back Button -->
                    <div class="mt-8">
                        <button onclick="window.history.back();" 
                            class="px-6 py-2 bg-gray-400 text-black rounded-lg hover:bg-gray-500 transition duration-300">
                            Back
                        </button>
                    </div>
                </div>

                <!-- Right Side Information Panel -->
                <div class="hidden sm:block w-1/3 space-y-6">
                    <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Deposit Status Guide</h3>
                        <ul class="space-y-2 text-sm">
                            <li>• <span class="text-green-600 dark:text-green-400">Approved</span>: Deposit confirmed</li>
                            <li>• <span class="text-yellow-600 dark:text-yellow-400">Pending</span>: Under review</li>
                            <li>• <span class="text-red-600 dark:text-red-400">Rejected</span>: Not approved</li>
                            <li>• Contact support for assistance</li>
                        </ul>
                    </div>

                    <?php if($depositData['status'] === 'Rejected'): ?>
                    <div class="bg-red-100 dark:bg-red-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Rejected Notice</h3>
                        <p class="text-sm">If your deposit was rejected, please create a new deposit request with correct information or contact support for assistance.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <script src="../../assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html> 