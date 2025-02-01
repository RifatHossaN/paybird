<?php include("../../backend/admin-check.php") ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <title>User Deposit List</title>
</head>
<body onload="onload()" class="overflow-hidden">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <div id="main-container" class="overflow-y-scroll px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <?php include("../../includes/headers/admin-header.php"); ?>

            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 mt-2 h-full">
                <div class="w-full mt-4 bg-white dark:bg-[#232228] rounded-xl">
                    <div class="flex flex-col sm:flex-row items-center justify-between mb-2">
                        <h2 class="text-2xl font-bold mb-4 sm:mb-0">Select User for Deposit</h2>
                        
                        <form method="POST" class="flex flex-row justify-end sm:justify-end space-x-1 sm:space-x-2 overflow-x-auto">
                            <input type="text" id="filter-input" name="filter-val" placeholder="Search..." 
                                class="w-24 sm:w-auto px-1 sm:px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700 focus:outline-none focus:border-blue-500">
                            
                            <select name="filterOption" id="filter-option" 
                                class="w-24 sm:w-auto px-1 sm:px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700 focus:outline-none focus:border-blue-500">
                                <option value="username">by Username</option>
                                <option value="userID">by UserID</option>
                                <option value="name">by Name</option>
                            </select>

                            <button type="submit" name="btn-filter" value="clicked" 
                                class="w-20 sm:w-auto bg-blue-400 text-black px-2 py-1 rounded-lg hover:bg-blue-500 transition duration-300">
                                Filter
                            </button>
                        </form>
                    </div>

                    <!-- Users Table -->
                    <div class="w-full">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b text-sm font-medium text-gray-600 dark:text-gray-400">
                                    <th class="py-3 text-left">User ID</th>
                                    <th class="py-3 text-left">Name</th>
                                    <th class="py-3 text-left">Username</th>
                                    <th class="py-3 text-left">Balance</th>
                                    <th class="py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include("../../config/connection.php");

                                $where = "1=1";
                                if(isset($_POST['btn-filter']) && $_POST['btn-filter'] == "clicked") {
                                    $filterOption = mysqli_real_escape_string($conn, $_POST['filterOption']);
                                    $filterVal = mysqli_real_escape_string($conn, $_POST['filter-val']);
                                    
                                    switch($filterOption) {
                                        case 'username':
                                            $where .= " AND username LIKE '%$filterVal%'";
                                            break;
                                        case 'userID':
                                            $where .= " AND userID LIKE '%$filterVal%'";
                                            break;
                                        case 'name':
                                            $where .= " AND name LIKE '%$filterVal%'";
                                            break;
                                    }
                                }

                                $sql = "SELECT * FROM users WHERE $where ORDER BY userID DESC";
                                $result = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "
                                        <tr class='border-b hover:bg-gray-50 dark:hover:bg-[#1e1d20]'>
                                            <td class='py-3'>" . $row['userID'] . "</td>
                                            <td class='py-3'>" . $row['name'] . "</td>
                                            <td class='py-3'>" . $row['username'] . "</td>
                                            <td class='py-3'>" . number_format($row['balance'], 2) . " Tk</td>
                                            <td class='py-3 flex justify-center gap-2'>
                                                <a href='admin-user-deposit.php?user={$row['username']}' 
                                                    class='bg-blue-400 text-black px-3 py-1 rounded-lg hover:bg-blue-500 transition duration-300'>
                                                    Deposit
                                                </a>
                                                <a href='admin-user-deposit-history.php?user={$row['username']}' 
                                                    class='bg-green-400 text-black px-3 py-1 rounded-lg hover:bg-green-500 transition duration-300'>
                                                    History
                                                </a>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='py-4 text-center text-gray-500'>No users found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
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