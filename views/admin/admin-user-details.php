<?php 
include("../../backend/admin-check.php");
include("../../config/connection.php");

$username = mysqli_real_escape_string($conn, $_GET['username']);
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql) or die("Query Failed.");
$userData = mysqli_fetch_assoc($result);
$_SESSION['deleteusername'] = $userData['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>User Details</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
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
                <!-- User Details Card -->
                <div class="w-full sm:w-2/3 bg-white dark:bg-[#1b1a20] rounded-xl p-8 shadow-lg">
                    <h2 class="text-2xl sm:text-3xl font-bold mb-8">User Details</h2>

                    <!-- User Details Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                        <div class="space-y-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">User ID</span>
                                <span class="text-lg font-semibold">#<?php echo $userData['userID']; ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Full Name</span>
                                <span class="text-lg font-semibold"><?php echo $userData['name']; ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Username</span>
                                <span class="text-lg font-semibold"><?php echo $userData['username']; ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Rate</span>
                                <span class="text-lg font-semibold"><?php echo $userData['rate']; ?> Tk</span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Registration Date</span>
                                <span class="text-lg font-semibold"><?php echo $userData['regdate']; ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Balance</span>
                                <span class="text-lg font-semibold"><?php echo $userData['balance'] ?> TK</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4">
                        <a href="admin-user-update.php?name=<?php echo $userData['name'].'&user='.$userData['username']?>"
                            class="w-32 py-3 bg-blue-400 text-black rounded-lg hover:bg-blue-500 transition duration-300 text-center">
                            Update
                        </a>
                        <a href="admin-user-transection-history.php?user=<?php echo $userData['username']; ?>"
                            class="w-32 py-3 bg-green-400 text-black rounded-lg hover:bg-green-500 transition duration-300 text-center">
                            Transac. His.
                        </a>
                        <!-- <a href="../../backend/backend-admin-user-delete.php"
                            class="w-32 py-3 bg-red-400 text-black rounded-lg hover:bg-red-500 transition duration-300 text-center">
                            Delete
                        </a> -->
                        <button onclick="window.history.back();" 
                            class="w-32 py-3 bg-gray-400 text-black rounded-lg hover:bg-gray-500 transition duration-300 text-center">
                            Back
                        </button>
                    </div>
                </div>

                <!-- Right Side Information Panel -->
                <div class="hidden sm:block w-1/3 space-y-6">
                    <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">User Management</h3>
                        <ul class="space-y-2 text-sm">
                            <li>• Review user information carefully</li>
                            <li>• Check transaction history</li>
                            <li>• Verify account status</li>
                            <li>• Monitor user activity</li>
                        </ul>
                    </div>

                    <div class="bg-yellow-100 dark:bg-yellow-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Important Notice</h3>
                        <p class="text-sm mb-4">User deletion cannot be undone. Make sure to backup any important data.</p>
                        <a href="admin-all-users.php" class="text-blue-500 hover:text-blue-600 text-sm">View All Users →</a>
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
