<?php 
include("../../backend/admin-check.php");
include("../../config/connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Pending Transactions</title>
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

            <section class="flex flex-col sm:flex-row w-full gap-8 mt-4 pt-16 h-full">
                <!-- Transactions Table Card -->
                <div class="w-full">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                        <h2 class="text-2xl sm:text-3xl font-bold">All Pending Transactions</h2>
                        
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

                    <!-- Transactions Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Banking</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Method</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Username</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Number</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Amount</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="table-body" class="divide-y divide-gray-100 dark:divide-gray-700">
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
    

<script>
function fetchData() {
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
        endDate,
        page: 'pending'
    }).toString();

    // Fetch with filters
    fetch(`realtime-fetch/pending-fetch.php?${queryString}`)
    .then(response => response.json())
    .then(data => {
        let tableBody = document.getElementById('table-body');
        tableBody.innerHTML = '';

        if (data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4">No pending transactions found.</td></tr>';
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
                        <a href='admin-accept-transection.php?transID=${row.transID}' class='bg-green-400 py-2 px-2 rounded-lg transition duration-500 dark:text-gray-950 hover:bg-green-500'>Accept</a>
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
    fetchData();
});

document.getElementById('filter-input').addEventListener('input', fetchData);
document.getElementById('start-date').addEventListener('change', fetchData);
document.getElementById('end-date').addEventListener('change', fetchData);

setInterval(fetchData, 2000);
fetchData();
</script>

    <script src="../../assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html> 