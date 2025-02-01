<?php 
include("../../backend/admin-check.php");
include("../../config/connection.php");

$username = $_SESSION['username'];

// Mark both types of notifications as read
if (!isset($_SESSION['notifications_viewed'])) {
    $sql = "UPDATE admin_deposit_notifications SET is_read = 1";
    mysqli_query($conn, $sql);
    $sql = "UPDATE admin_transection_notifications SET is_read = 1";
    mysqli_query($conn, $sql);
    $_SESSION['notifications_viewed'] = true;
}

// Clear the session variable when leaving the page
if (isset($_SESSION['notifications_viewed'])) {
    unset($_SESSION['notifications_viewed']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body onload="onload()" class="overflow-hidden">
    <script>
    // Request notification permission when page loads
    function requestNotificationPermission() {
        if (!("Notification" in window)) {
            console.log("This browser does not support notifications");
            return;
        }

        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }
    }

    // Function to send notification
    function sendNotification(title, body) {
        if (Notification.permission === "granted") {
            const notification = new Notification(title, {
                body: body,
                icon: "../../assets/imgs/logo.png", // Add your logo path here
                badge: "../../assets/imgs/logo.png"
            });

            // Click event for notification
            notification.onclick = function() {
                window.focus();
                this.close();
            };
        }
    }


    // Initialize
    requestNotificationPermission();
    </script>

    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/admin-header.php") ?>

            <section class="flex flex-col w-full gap-8 pt-16 h-full overflow-scroll mt-4">
                <div class="w-full bg-white dark:bg-[#232228] rounded-xl">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold">Notifications</h2>
                    </div>

                    <div class="space-y-4">
                        <?php
                        // Combine both transaction and deposit notifications with UNION
                        $sql = "SELECT 
                                'transaction' as type,
                                transID as id,
                                username,
                                amount,
                                number,
                                banking,
                                created_at 
                            FROM admin_transection_notifications
                            UNION ALL
                            SELECT 
                                'deposit' as type,
                                depositID as id,
                                username,
                                amount,
                                reference_no as number,
                                payment_method as banking,
                                created_at 
                            FROM admin_deposit_notifications
                            ORDER BY created_at DESC";

                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $statusColor = $row['type'] === 'transaction' 
                                    ? 'bg-blue-200 dark:bg-blue-900' 
                                    : 'bg-yellow-100 dark:bg-yellow-900';
                                
                                
                                $statusText = $row['type'] === 'transaction' 
                                    ? "Transaction " 
                                    : "Deposit ";

                                echo "<div class='p-4 {$statusColor} rounded-xl transition duration-300 hover:shadow-lg'>
                                    <div class='flex items-center gap-3 mb-2'>
                                        <ion-icon name='time-outline' class='text-yellow-600 dark:text-yellow-400 text-xl'></ion-icon>
                                        <h3 class='font-semibold'>{$statusText}</h3>
                                        <span class='text-sm text-gray-500 dark:text-gray-400 ml-auto'>
                                            " . date('M d, Y H:i', strtotime($row['created_at'])) . "
                                        </span>
                                    </div>
                                    
                                    <div class='ml-7 space-y-1'>
                                        <p class='text-sm'>
                                            <span class='font-medium'>" . 
                                            ($row['type'] === 'transaction' ? 'Transaction' : 'Deposit') . 
                                            " ID:</span> 
                                            #{$row['id']}
                                        </p>
                                        <p class='text-sm'>
                                            <span class='font-medium'>Username:</span> 
                                            {$row['username']}
                                        </p>
                                        <p class='text-sm'>
                                            <span class='font-medium'>Amount:</span> 
                                            {$row['amount']}
                                        </p>";
                                
                                // Show number only for transactions
                                if ($row['type'] === 'transaction') {
                                    echo "
                                        <p class='text-sm'>
                                            <span class='font-medium'>Banking:</span> 
                                            {$row['banking']}
                                        </p>
                                        <p class='text-sm'>
                                            <span class='font-medium'>Number:</span> 
                                            {$row['number']}
                                        </p>";
                                } else {
                                    echo "
                                        <p class='text-sm'>
                                            <span class='font-medium'>Payment Method:</span> 
                                            {$row['banking']}
                                        </p>
                                        <p class='text-sm'>
                                            <span class='font-medium'>Reference Number:</span> 
                                            {$row['number']}
                                        </p>";
                                }

                                echo "</div>
                                    
                                    <div class='ml-7 mt-3'>
                                        <a href='" . 
                                        ($row['type'] === 'transaction' 
                                            ? "admin-transection-details.php?transID={$row['id']}"
                                            : "admin-deposit-details.php?id={$row['id']}") . 
                                        "' class='text-sm text-blue-500 hover:text-blue-600 transition duration-300'>
                                            View " . ($row['type'] === 'transaction' ? 'Transaction' : 'Deposit') . " Details →
                                        </a>
                                    </div>
                                </div>";
                            }
                        } else {
                            echo "<div class='text-center py-8 text-gray-500 dark:text-gray-400'>
                                <ion-icon name='notifications-off' class='text-4xl mb-2'></ion-icon>
                                <p>No notifications found</p>
                            </div>";
                        }
                        ?>
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