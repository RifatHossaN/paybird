<?php
include("../../backend/admin-check.php");

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
    <title>Add User</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body onload="onload()" class="overflow-hidden">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="overflow-y-scroll px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/admin-header.php") ?>

            <!-- Add User Form Section -->
            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 h-full">
                <div class="w-full sm:w-2/3 bg-white dark:bg-[#232228] rounded-lg p-8">
                    <h2 class="text-2xl sm:text-3xl font-bold mb-8">Add New User</h2>
                    
                    <form action="../../backend/backend-admin-user-add.php" method="post" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Full Name</label>
                                <input type="text" name="name" required
                                    class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-500"
                                    placeholder="Enter full name">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Username</label>
                                <input type="text" name="username" required
                                    class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-500"
                                    placeholder="Enter username">
                            </div>

                            <div class="relative">
                                <label class="block text-sm font-medium mb-2">Password</label>
                                <input type="password" id="password" name="password" required
                                    class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-500"
                                    placeholder="Enter password">
                                <button type="button" onclick="togglePassword()" 
                                    class="absolute right-3 top-9 text-gray-500 hover:text-gray-700 dark:text-gray-400">
                                    <ion-icon name="eye-outline" class="text-xl"></ion-icon>
                                </button>
                            </div>
                        </div>

                        <?php if($error): ?>
                            <p class="text-red-500 text-sm"><?php echo $error; ?></p>
                        <?php endif; ?>

                        <button type="submit" name="btn-user-add" value="clicked"
                            class="w-32 py-3 bg-blue-400 text-black rounded-lg hover:bg-blue-500 transition duration-300 text-center">
                            Add User
                        </button>
                    </form>
                </div>

                <!-- Right Side Information Panel -->
                <div class="hidden sm:block w-1/3 space-y-6">
                    <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">User Creation Guidelines</h3>
                        <ul class="space-y-2 text-sm">
                            <li>• Username must be unique</li>
                            <li>• Password should be secure</li>
                            <li>• Verify user information</li>
                            <li>• Keep credentials safe</li>
                        </ul>
                    </div>

                    <div class="bg-yellow-100 dark:bg-yellow-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Important Notice</h3>
                        <p class="text-sm mb-4">New users will have default settings. Configure their rates and permissions after creation.</p>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        }
    </script>
    
    <script src="../../assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
