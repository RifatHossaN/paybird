<?php 

error_log(0);
include("../../backend/admin-check.php");

if (isset($_GET['user'])) {
    $user = $_GET['user'];
    $user = filter_var($user, FILTER_SANITIZE_STRING);
}else{
    header("Location: admin-panel.php", true, 303);
    exit();
}


include("../../config/connection.php");

$sql = "SELECT * FROM users WHERE username = '$user'";
$result = mysqli_query($conn, $sql) or die("Query Failed.");
$userData = mysqli_fetch_assoc($result);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Single User Configuration</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body onload="onload()" class="overflow-hidden dark">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <div id="main-container" class="overflow-y-scroll px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <?php include("../../includes/headers/admin-header.php"); ?>

            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 h-full">
                <!-- Main Configuration Card -->
                <div class="w-full sm:w-2/3 pt-4">

                    <!-- Payment Methods Form -->
                    <form action="../../backend/backend-admin-single-user-payment-config.php" method="post" class="mb-8">
                    <input type="hidden" name="user" value="<?php echo $user; ?>">
                        <div class="bg-gray-50 dark:bg-[#161618] rounded-xl p-6 mb-6">
                            <h3 class="text-lg font-bold mb-4">Payment Methods</h3>
                            <div class="flex flex-wrap gap-4">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="payment-method[]" value="bkash" 
                                        class="form-checkbox h-5 w-5 text-blue-500 rounded border-gray-300 dark:border-gray-700" <?php if($userData['bkash']=='true'){echo "checked";} ?>>
                                    <span class="text-gray-700 dark:text-gray-300">Bkash</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="payment-method[]" value="nagad" 
                                        class="form-checkbox h-5 w-5 text-blue-500 rounded border-gray-300 dark:border-gray-700" <?php if($userData['nagad']=='true'){echo "checked";} ?>>
                                    <span class="text-gray-700 dark:text-gray-300">Nagad</span>
                                </label>
                            </div>
                        </div>
                        <button type="submit" name="btn-config" value="clicked" 
                            class="w-full sm:w-auto px-6 py-2.5 bg-blue-400 text-black rounded-lg hover:bg-blue-500 transition duration-300">
                            Update Payment Methods
                        </button>
                    </form>
                    

                    <!-- User Rate Configuration Form -->
                    <form action="../../backend/backend-admin-single-user-rate-config.php" method="post">
                        <input type="hidden" name="user" value="<?php echo $user; ?>">
                        <div class="bg-gray-50 dark:bg-[#161618] rounded-xl p-6 mb-6">
                            <h3 class="text-lg font-bold mb-4">Exchange Rate Configuration</h3>
                            <div class="flex items-center gap-4">
                                <span class="text-gray-700 dark:text-gray-300">1 MYR =</span>
                                <input type="number" step="0.01" id="rate" name="rate" value="<?php echo $userData['rate']; ?>" required
                                    class="w-24 px-4 py-2 rounded-lg border dark:bg-[#1b1a20] dark:border-gray-700 focus:outline-none focus:border-blue-500"
                                    placeholder="Rate">
                                <span class="text-gray-700 dark:text-gray-300">BDT</span>
                            </div>
                        </div>
                        <button type="submit" name="btn-config" value="clicked" 
                            class="w-full sm:w-auto px-6 py-2.5 bg-blue-400 text-black rounded-lg hover:bg-blue-500 transition duration-300">
                            Update Rate
                        </button>
                    </form>

                    <!-- Add Balance Form -->
                    <form action="../../backend/backend-admin-add-balance.php" method="post" class="mt-8">
                        <input type="hidden" name="user" value="<?php echo $user; ?>">
                        <div class="bg-gray-50 dark:bg-[#161618] rounded-xl p-6 mb-6">
                            <h3 class="text-lg font-bold mb-4 inline">Add Balance</h3>
                            <span class="text-gray-700 dark:text-gray-300 inline">(Current Balance: <?php echo $userData['balance']; ?> TK)</span>
                            <div class="flex items-center gap-4">
                                <span class="text-gray-700 dark:text-gray-300">Amount:</span>
                                <input type="number" step="0.01" id="balance" name="balance" required
                                    class="w-24 px-4 py-2 rounded-lg border dark:bg-[#1b1a20] dark:border-gray-700 focus:outline-none focus:border-blue-500"
                                    placeholder="0.00">
                                <span class="text-gray-700 dark:text-gray-300">TK</span>
                            </div>
                        </div>
                        <button type="submit" name="btn-add-balance" value="clicked" 
                            class="w-full sm:w-auto px-6 py-2.5 bg-green-400 text-black rounded-lg hover:bg-green-500 transition duration-300">
                            Add Balance
                        </button>
                    </form>
                </div>

                <!-- Right Side Information Panel -->
                <div class="hidden sm:block w-1/3 space-y-6">
                    <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Configuration Guide</h3>
                        <ul class="space-y-2 text-sm">
                            <li>• Set exchange rate carefully</li>
                            <li>• Changes apply to the selected user</li>
                            <li>• Rate updates are immediate</li>
                        </ul>
                    </div>

                    <div class="bg-yellow-100 dark:bg-yellow-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Important Notice</h3>
                        <p class="text-sm mb-4">Changes to the exchange rate will affect all ongoing and future transactions for this user.</p>
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





