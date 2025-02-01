<?php 
include("../../backend/admin-check.php");
include("../../config/connection.php");

$depositID = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "SELECT * FROM deposits WHERE depositID = '$depositID'";
$result = mysqli_query($conn, $sql) or die("Query Failed.");
$depositData = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <title>Deposit Details - Admin</title>
</head>
<body onload="onload()" class="overflow-hidden dark">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="overflow-y-scroll px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/admin-header.php") ?>

            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 h-full mt-2">
                <!-- Deposit Details Card -->
                <div class="w-full sm:w-2/3 rounded-xl">
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
                                <span class="text-sm text-gray-500 dark:text-gray-400">Username</span>
                                <span class="text-lg font-semibold"><?php echo $depositData['username']; ?></span>
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
                                <span class="text-sm text-gray-500 dark:text-gray-400">payment Date</span>
                                <span class="text-lg font-semibold"><?php echo date('Y-m-d', strtotime($depositData['payment_date'])); ?></span>
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
                    <br>

                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        <?php if($depositData['status'] === 'Pending'): ?>
                            <div class="flex gap-4">
                                <form action="../../backend/backend-admin-deposit-accept.php" method="POST">
                                    <input type="hidden" name="depositID" value="<?php echo $depositData['depositID']; ?>">
                                    <button type="submit" name="btn-deposit-accept" value="clicked"
                                        class="w-32 py-3 bg-green-400 text-black rounded-lg hover:bg-green-500 transition duration-300 text-center">
                                        Accept
                                    </button>
                                </form>
                                
                                <form action="../../backend/backend-admin-deposit-reject.php" method="POST">
                                    <input type="hidden" name="depositID" value="<?php echo $depositData['depositID']; ?>">
                                    <button type="submit" name="btn-deposit-reject" value="clicked"
                                        class="w-32 py-3 bg-red-400 text-black rounded-lg hover:bg-red-500 transition duration-300 text-center">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex gap-4">
                            <button onclick="window.history.back();" 
                                class="w-32 py-3 bg-blue-400 text-black rounded-lg hover:bg-blue-500 transition duration-300 text-center">
                                Back
                            </button>
                        </div>
                    </div>

                    <!-- Error Message Display -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="mt-4 p-4 bg-red-100 dark:bg-red-900 rounded-lg">
                            <p class="text-red-600 dark:text-red-400"><?php echo $_SESSION['error']; ?></p>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                </div>

                <!-- Right Side Information Panel -->
                <div class="hidden sm:block w-1/3 space-y-6">
                    <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Admin Actions</h3>
                        <ul class="space-y-2 text-sm">
                            <li>• Verify deposit receipt image</li>
                            <li>• Check payment method details</li>
                            <li>• Confirm amount matches receipt</li>
                            <li>• Process deposits promptly</li>
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