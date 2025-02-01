<?php 
include("../../backend/user-check.php");
include("../../config/connection.php");

$username = $_SESSION['username'];

// Mark both types of notifications as read
if (!isset($_SESSION['notifications_viewed'])) {
    $sql = "UPDATE notifications SET is_read = 1 WHERE username = '$username'";
    mysqli_query($conn, $sql);
    $sql = "UPDATE deposit_notifications SET is_read = 1 WHERE username = '$username'";
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

    // Check for new notifications every 30 seconds
    function checkNewNotifications() {
        fetch('../../backend/check-new-notifications.php')
            .then(response => response.json())
            .then(data => {
                if (data.hasNew) {
                    data.notifications.forEach(notif => {
                        sendNotification(
                            `Transaction ${notif.status}`, 
                            `Transaction #${notif.transID}: ${notif.status === 'Accepted' ? 'Last Numbers: ' : 'Reason: '}${notif.lastnumber}`
                        );
                    });
                }
            });
    }

    // Initialize
    requestNotificationPermission();
    setInterval(checkNewNotifications, 30000);
    </script>

    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/user-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/user-header.php") ?>

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
                                status,
                                number,
                                lastnumber,
                                created_at 
                            FROM notifications 
                            WHERE username = '$username'
                            UNION ALL
                            SELECT 
                                'deposit' as type,
                                depositID as id,
                                username,
                                status,
                                NULL as number,
                                NULL as lastnumber,
                                created_at 
                            FROM deposit_notifications 
                            WHERE username = '$username'
                            ORDER BY created_at DESC";

                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $statusColor = $row['status'] === 'Approved' || $row['status'] === 'Accepted' 
                                    ? 'bg-green-100 dark:bg-green-900' 
                                    : 'bg-red-100 dark:bg-red-900';
                                
                                $statusIcon = $row['status'] === 'Approved' || $row['status'] === 'Accepted'
                                    ? '<ion-icon name="checkmark-circle" class="text-green-600 dark:text-green-400 text-xl"></ion-icon>' 
                                    : '<ion-icon name="close-circle" class="text-red-600 dark:text-red-400 text-xl"></ion-icon>';
                                
                                $statusText = $row['type'] === 'transaction' 
                                    ? "Transaction {$row['status']}" 
                                    : "Deposit {$row['status']}";

                                echo "<div class='p-4 {$statusColor} rounded-xl transition duration-300 hover:shadow-lg'>
                                    <div class='flex items-center gap-3 mb-2'>
                                        {$statusIcon}
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
                                        </p>";
                                
                                // Show number only for transactions
                                if ($row['type'] === 'transaction') {
                                    echo "<p class='text-sm'>
                                            <span class='font-medium'>Number:</span> 
                                            {$row['number']}
                                        </p>
                                        <p class='text-sm'>
                                            <span class='font-medium'>" . 
                                            ($row['status'] === 'Accepted' ? 'Last Numbers:' : 'Reason:') . 
                                            "</span> 
                                            {$row['lastnumber']}
                                        </p>";
                                }

                                echo "</div>
                                    
                                    <div class='ml-7 mt-3'>
                                        <a href='" . 
                                        ($row['type'] === 'transaction' 
                                            ? "user-transection-details.php?transID={$row['id']}"
                                            : "user-deposit-details.php?id={$row['id']}") . 
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