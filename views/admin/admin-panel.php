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
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <title>Admin Panel</title>
</head>
<body onload="onload()" class="overflow-hidden">
    <div class="bg-black h-screen dark:text-white w-full flex">
        
        <!-- Sidebar -->
        <?php 
        include("../../includes/sidebars/admin-sidebar.php");
        include("../../config/connection.php");

        $username = $_SESSION['username'];
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql) or die("Query Failed.");
        $userData = mysqli_fetch_assoc($result);
        $rate = $userData['rate'];
        $_SESSION['blask']= $userData['bkash'];
        $_SESSION['nagad']= $userData['nagad'];
        ?>

        <!-- Main Content -->
        <div id="main-container" class="px-8 w-full min-h-full overflow-y-scroll sm:overflow-hidden sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200 flex flex-col">
            
            <!-- Header -->
            <?php include("../../includes/headers/admin-header.php") ?>

            <!-- Rate and Stats Section -->
            <section class="flex flex-col sm:flex-row mt-16 sm:mt-24 w-full sm:h-1/3 gap-12 sm:gap-8 sm:pb-6">
                <!-- Rate Card -->
                <div class="w-full sm:w-1/2 h-48 sm:h-full">
                    <h2 class="pb-2 sm:pb-6 text-2xl sm:text-3xl font-bold">Exchange Rate</h2>
                    <div class="p-6 bg-blue-200 dark:bg-blue-900 w-full h-full flex flex-col justify-between rounded-2xl group transition duration-500 hover:shadow-xl hover:bg-blue-300">
                        <div class="flex gap-16 sm:justify-between w-full">
                            <div>
                                <h2 class="text-lg font-semibold">Current Rate</h2>
                                <div id="rate" class="text-3xl flex font-bold"></div>
                                <p class="text-gray-500 text-base font-normal dark:text-gray-300">= 1MYR</p>
                            </div>
                            
                            <!-- Added Calculator Section -->
                            <div class="rounded-2xl pt-1 flex flex-col h-32 sm:flex-row-reverse">
                                <div class="flex-1">
                                    <label for="myr" class="block text-sm font-medium text-gray-700 dark:text-gray-300">MYR</label>
                                    <input id="myr" type="number" placeholder="MYR"
                                    class="mt-1 block w-full dark:bg-blue-950 dark:text-white px-4 py-2 border rounded-2xl shadow-sm focus:outline-none text-gray-700" />
                                </div>
                                
                                <div class="hidden sm:block text-gray-700 dark:text-gray-300 text-4xl pt-6">
                                    <ion-icon name="swap-horizontal-outline"></ion-icon>
                                </div>
                                
                                <div class="flex-1">
                                    <label for="bdt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">TK</label>
                                    <input id="bdt" type="number" placeholder="TK"
                                    class="mt-1 dark:bg-blue-950 dark:text-white block w-full px-4 py-2 border rounded-2xl shadow-sm focus:outline-none text-gray-700" />
                                </div>
                            </div>
                        </div>
                        <script>
                            const conversionRate = parseFloat(<?php echo $rate ;?>);
                        
                            // Convert MYR to BDT
                            const myrInput = document.getElementById('myr');
                            const bdtInput = document.getElementById('bdt');
                        
                            myrInput.addEventListener('input', function () {
                            const myrValue = parseFloat(myrInput.value) || 0;
                            bdtInput.value = (myrValue * conversionRate).toFixed(2);
                            });
                        
                            // Convert BDT to MYR
                            bdtInput.addEventListener('input', function () {
                            const bdtValue = parseFloat(bdtInput.value) || 0;
                            myrInput.value = (bdtValue / conversionRate).toFixed(2);
                            });
                        </script>


                        <div class="flex justify-between items-center">
                            <ion-icon name="trending-up" class="hidden sm:block text-5xl z-0 text-blue-500 p-2 bg-white dark:text-blue-900 rounded-2xl transition duration-500 dark:group-hover:text-blue-900 group-hover:text-blue-600 group-hover:shadow-xl"></ion-icon>
                            <a href="admin-payment-config.php" class="hidden sm:block bg-white dark:bg-blue-950 px-4 py-2 rounded-xl text-sm font-medium hover:bg-blue-100 dark:hover:bg-blue-900 transition duration-300">
                                Configure Rate
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="sm:w-1/2 h-full">
                    <h2 class="pb-2 sm:pb-6 text-2xl sm:text-3xl font-bold">Statistics</h2>
                    <div class="w-full h-40 sm:h-full flex gap-2 sm:gap-6">
                        <a href="admin-user-list.php" class="w-1/3 h-full">
                            <div class="p-4 sm:p-6 bg-purple-200 dark:bg-purple-900 w-full h-full flex flex-col justify-between rounded-2xl hover:bg-purple-300 group transition duration-500 hover:shadow-xl">
                                <div>
                                    <h3 class="text-sm font-semibold">Total Users</h3>
                                    <div id="total-user" class="text-xl sm:text-2xl font-bold"></div>
                                    <div class="text-gray-500 dark:text-gray-300 text-xs sm:text-base sm:mb-4">Active Users</div>
                                </div>
                                <ion-icon name="people" class="z-0 text-4xl sm:text-6xl text-violet-500 dark:text-purple-900 p-1 bg-white rounded-2xl transition duration-500 group-hover:text-violet-900 dark:group-hover:text-purple-900 group-hover:shadow-xl"></ion-icon>
                            </div>
                        </a>

                        <a href="admin-total-sent-transactions.php" class="w-1/3 h-full">
                            <div class="p-4 sm:p-6 bg-green-200 dark:bg-green-800 w-full h-full flex flex-col justify-between rounded-2xl hover:bg-green-300 group transition duration-500 hover:shadow-xl">
                                <div>
                                    <h3 class="text-sm font-semibold">Todays Sent</h3>
                                    <div id = "todays-sent-bdt" class="text-xl sm:text-2xl font-bold"></div>
                                    <div id = "todays-sent-myr" class="text-gray-500 dark:text-gray-300 text-xs sm:text-base sm:mb-4"></div>
                                </div>
                                <ion-icon name="arrow-up" class="z-0 text-4xl sm:text-6xl text-green-700 dark:text-green-800 p-1 bg-white rounded-2xl transition duration-500 dark:group-hover:text-green-800 group-hover:text-green-950 group-hover:shadow-xl"></ion-icon>
                            </div>
                        </a>

                        <a href="admin-pending-transactions.php" class="w-1/3 h-full">
                            <div class="p-4 sm:p-6 bg-yellow-200 dark:bg-yellow-600 w-full h-full flex flex-col justify-between rounded-2xl hover:bg-yellow-300 group transition duration-500 hover:shadow-xl">
                                <div>
                                    <h3 class="text-sm font-semibold">Pending</h3>
                                    <div id="pending-count" class="text-xl sm:text-2xl font-bold"></div>
                                    <div class="text-gray-500 dark:text-gray-200 text-xs sm:text-base sm:mb-4">Transactions</div>
                                </div>
                                <ion-icon name="time" class="z-0 text-4xl sm:text-6xl text-yellow-500 dark:text-yellow-600 p-1 bg-white rounded-2xl transition duration-500 group-hover:text-yellow-600 group-hover:shadow-xl"></ion-icon>
                            </div>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Pending Requests Section -->
            <section class="flex flex-col sm:flex-row w-full gap-4 pt-4 sm:pt-16 h-2/3 sm:h-1/2">
                <div class="bg-white dark:bg-[#232228] rounded-lg w-full sm:w-3/4">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <h3 class="text-2xl font-bold">Pending Requests</h3>
                        
                        <!-- Added Filter Form -->
                        <form id="filter-form" class="flex flex-row justify-end space-x-2">
                            <input type="text" id="filter-input" name="filter-val" placeholder="Search..." 
                                class="w-24 sm:w-auto px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700 focus:outline-none focus:border-blue-500">
                            
                            <select name="filterOption" id="filter-option" 
                                class="w-24 sm:w-auto px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700 focus:outline-none focus:border-blue-500">
                                <option value="all">All</option>
                                <option value="transID">by Trans. ID</option>
                                <option value="username">by Username</option>
                                <option value="name">by Name</option>
                                <option value="number">by Number</option>
                                <option value="banking">by Banking</option>
                                <option value="date">by Date</option>
                            </select>

                            <div class="date-inputs hidden space-x-2">
                                <input type="date" name="start-date" id="start-date" 
                                    class="w-24 sm:w-auto px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700">
                                <input type="date" name="end-date" id="end-date" 
                                    class="w-24 sm:w-auto px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700">
                            </div>
                        </form>
                    </div>

                    <div class="overflow-y-scroll h-[98vh]  sm:h-[40vh]">
                        <table class="w-full h-1/3 text-left">
                            <thead>
                                <tr class="border-b text-sm font-medium text-gray-600 dark:text-gray-400">
                                    <th class="py-2 text-left">Banking</th>
                                    <th class="py-2 px-4">Method</th>
                                    <th class="py-2 px-4">Sender</th>
                                    <th class="py-2 px-4">Number</th>
                                    <th class="py-2 px-4">Amount</th>
                                    <th class="py-2 px-4">Status</th>
                                    <th class="py-2 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="hidden sm:flex p-6 bg-gray-950 w-1/4 text-white font-bold text-2xl rounded-2xl shadow flex-col gap-4">
                    <h1 class="font-bold text-3xl p-2">Quick Actions</h1>
                    <a href="admin-user-send-money-list.php" class="h-1/3">
                        <div class="bg-green-200 dark:bg-green-700 text-black h-full rounded-3xl items-center gap-4 transition duration-500 hover:bg-green-400 cursor-pointer flex justify-between px-8">
                            <p>Send Money</p>
                            <ion-icon name="arrow-forward-circle-outline" class="text-5xl"></ion-icon>
                        </div>
                    </a>
                    <a href="admin-user-deposit-list.php" class="h-1/3">
                        <div class="bg-blue-200 dark:bg-blue-800 text-black h-full rounded-3xl items-center gap-4 transition duration-500 hover:bg-blue-400 cursor-pointer flex justify-between px-8">
                            <p>Deposit</p>
                            <ion-icon name="add-circle-outline" class="text-5xl"></ion-icon>
                        </div>
                    </a>
                </div>
                <a href="admin-user-send-money-list.php"><div class="sm:hidden fixed right-4 bottom-4 px-2 bg-blue-400 text-black h-20 rounded-3xl flex justify-around items-center transition duration-500 font-bold hover:bg-blue-400 cursor-pointer">
                    <p>Send Money</p>
                    <ion-icon name="arrow-redo-circle-outline" class="text-5xl"></ion-icon>
                </div></a>
            </section>
        </div>
    </div>

    

   

<script>
function fetchTableData() {
    // Get filter values
    const filterOption = document.getElementById('filter-option').value;
    const filterVal = document.getElementById('filter-input').value;
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    // Build query string
    const queryString = new URLSearchParams({
        filterOption,
        filterVal,
        startDate,
        endDate
    }).toString();

    // Fetch with filters
    fetch(`realtime-fetch/pending-fetch.php?${queryString}`)
    .then(response => response.json())
    .then(data => {
        let tableBody = document.getElementById('table-body');
        tableBody.innerHTML = '';

        if (data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4">No pending requests found.</td></tr>';
            return;
        }

        data.forEach(row => {
            let statusColor = {
                "Accepted":"bg-green-400",
                "Rejected":"bg-red-400", 
                "Pending":"bg-yellow-400",
                "Canceled": "bg-gray-400"

            }[row.status];
            
            let tr = `
                <tr class='transition duration-500 hover:bg-gray-50 dark:hover:bg-[#1e1d20]'>
                    <td class='py-2 px-1'>
                        <img class='h-12 w-12' src='../../assets/imgs/${row.banking}.png' alt='${row.banking}-logo'>
                    </td>
                    <td class='py-2 px-4'>
                        <h4 class='text-base font-bold'>${row.method}</h4>
                        <p class='hidden sm:block text-sm text-gray-700 dark:text-gray-400'>${row.time}</p>
                    </td>
                    <td class='py-2 px-4 text-lg font-bold'>
                        ${row.username}
                    </td>
                    <td class='py-2 px-4 text-base font-bold'>
                        ${row.number}
                        <p class='sm:hidden text-sm text-gray-700 dark:text-gray-400'>${row.time}</p>
                        <p class='hidden sm:block text-sm text-gray-700 dark:text-gray-400'>Transec. ID: #${row.transID}</p>
                    </td>
                    <td class='py-2 px-4'>
                        <h4 class='text-base font-bold'>${row.amount} TK</h4>
                        <p class='hidden sm:block text-sm text-gray-700 dark:text-gray-400'>${row.amountYMR} MYR</p>
                    </td>
                    <td class='py-2 px-4'>
                        <p class='${statusColor} p-0.5 rounded-lg text-center dark:text-gray-950'>${row.status}</p>
                    </td>
                    <td class='py-2 px-4 flex justify-center items-center h-full gap-2'>
                        <a href='admin-transection-details.php?transID=${row.transID}'>
                            <button class='bg-blue-400 py-1 px-2 rounded-lg transition duration-500 dark:text-gray-950 hover:bg-blue-500'>View</button>
                        </a>
                        <a href='admin-accept-transection.php?transID=${row.transID}' class='bg-green-400 py-1 px-2 rounded-lg transition duration-500 dark:text-gray-950 hover:bg-green-500'>Accept</a>
                    </td>
                </tr>`;
            tableBody.innerHTML += tr;
        });
    })
    .catch(error => console.error('Error fetching data:', error));
}

// Add event listeners for filter changes
document.getElementById('filter-option').addEventListener('change', function() {
    const dateInputs = document.querySelector('.date-inputs');
    const filterInput = document.getElementById('filter-input');
    
    if (this.value === 'date') {
        dateInputs.classList.remove('hidden');
        filterInput.classList.add('hidden');
    } else {
        dateInputs.classList.add('hidden');
        filterInput.classList.remove('hidden');
    }
    fetchTableData();
});

document.getElementById('filter-input').addEventListener('input', fetchTableData);
document.getElementById('start-date').addEventListener('change', fetchTableData);
document.getElementById('end-date').addEventListener('change', fetchTableData);

setInterval(fetchTableData, 2000);
fetchTableData();

</script>

<script>
    

function fetchStatus() {
    let xhr = new XMLHttpRequest();

    let data_to_send = new URLSearchParams({
        username: "<?php echo $username; ?>" // Replace with dynamic PHP value
    });


    xhr.open("POST", "realtime-fetch/admin-data-fetch.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.response);
                const show_rate = document.getElementById('rate') ;
                const show_total_user = document.getElementById('total-user');
                const show_todays_sent_bdt = document.getElementById('todays-sent-bdt');
                const show_todays_sent_myr = document.getElementById('todays-sent-myr');
                const show_pending_count = document.getElementById('pending-count');
                

                show_rate.textContent = (data.rate ? data.rate:0) + " TK";
                show_total_user.textContent = (data.total_user ? data.total_user:0);
                show_todays_sent_bdt.textContent = (data.todays_sent_bdt ? data.todays_sent_bdt:0) + " TK";
                show_todays_sent_myr.textContent = (data.todays_sent_myr?data.todays_sent_myr:0) + " MYR";
                show_pending_count.textContent = (data.pending_count?data.pending_count:0);


            } catch (e) {
                console.error("Error parsing JSON:", e);
            }
        }
    };
    xhr.send(data_to_send.toString());
}
// Check every second
setInterval(fetchStatus, 1000);

// Initial check
fetchStatus();

</script>

    <script src="../../assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html> 