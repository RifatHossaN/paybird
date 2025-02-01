<?php include("../../backend/admin-check.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <title>Admin Deposit History</title>
</head>
<body onload="onload()" class="overflow-hidden">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="px-8 w-full min-h-full overflow-y-scroll sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/admin-header.php"); ?>

            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 h-full">
                <div class="bg-white sm:mt-6 dark:bg-[#232228] rounded-lg w-full">
                    
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-bold">All Deposits</h3>
                        <a href="admin-user-deposit-list.php" class="hidden sm:block bg-blue-500 text-black px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
                            New Deposit
                        </a>
                    </div>

                    <button id="download" class="bg-green-400 text-black px-3 py-1 text-sm rounded-lg hover:bg-green-500 transition duration-300 flex items-center gap-1"><ion-icon name="download-outline"></ion-icon> Download Excel</button>

                    <!-- Filter Form -->
                    <form id="filter-form" class="flex flex-row justify-end space-x-2">
                        <input type="text" id="filter-input" name="filter-val" placeholder="Search..." 
                            class="w-24 sm:w-auto px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700 focus:outline-none focus:border-blue-500">
                        
                        <select name="filterOption" id="filter-option" 
                            class="w-24 sm:w-auto px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700 focus:outline-none focus:border-blue-500">
                            <option value="all">All</option>
                            <option value="depositID">by Deposit. ID</option>
                            <option value="username">by Username</option>
                            <option value="ref-no">by Ref. No.</option>
                            <option value="status">by Status</option>
                            <option value="date">by Date</option>
                        </select>

                        <select id="status-select" name="status" class="hidden w-24 sm:w-auto px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700">
                            <option value="">Select Status</option>
                            <option value="Approved">Approved</option>
                            <option value="Pending">Pending</option>
                            <option value="Rejected">Rejected</option>
                        </select>

                        <div class="date-inputs hidden space-x-2">
                            <input type="date" name="start-date" id="start-date" 
                                class="w-24 sm:w-auto px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700">
                            <input type="date" name="end-date" id="end-date" 
                                class="w-24 sm:w-auto px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700">
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b text-sm font-medium text-gray-600 dark:text-gray-400">
                                    <th class="py-2 px-4">Deposit ID</th>
                                    <th class="py-2 px-4">Username</th>
                                    <th class="py-2 px-4">Method</th>
                                    <th class="py-2 px-4">Amount</th>
                                    <th class="py-2 px-4">Status</th>
                                    <th class="py-2 px-4">Date</th>
                                    <th class="py-2 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <!-- Data will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <a href="admin-user-deposit-list.php">
                <div class="sm:hidden fixed right-4 bottom-4 px-4 bg-blue-400 text-black h-20 rounded-3xl flex justify-around items-center gap-4 transition duration-500 font-bold hover:bg-blue-500 cursor-pointer">
                    <p>New Deposit</p>
                    <ion-icon name="add-circle-outline" class="text-5xl"></ion-icon>
                </div>
            </a>
        </div>
    </div>

<script>

    function fetchData() {
        const filterOption = document.getElementById('filter-option').value;
        const filterVal = filterOption === 'status' 
            ? document.getElementById('status-select').value 
            : document.getElementById('filter-input').value;
        const startDate = document.getElementById('start-date').value;
        const endDate = document.getElementById('end-date').value;

        const queryString = new URLSearchParams({
            filterOption,
            filterVal,
            startDate,
            endDate
        }).toString();

        fetch(`realtime-fetch/deposit-fetch.php?${queryString}`)
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';

            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4">No transactions found.</td></tr>';
                return;
            }
            

            data.forEach(row => {
                let statusColor = {
                    "Approved": "bg-green-400",
                    "Pending": "bg-yellow-400",
                    "Rejected": "bg-red-400"
                }[row.status];
                
                let tr = `
                        <tr class='border-b hover:bg-gray-50 dark:hover:bg-[#1e1d20]'>
                            <td class='py-3 px-4'>${row.depositID}</td>
                            <td class='py-3 px-4'>${row.username}</td>
                            <td class='py-3 px-4'>${row.payment_method}</td>
                            <td class='py-3 px-4'>
                                ${parseFloat(row.amount).toLocaleString('en-US', {minimumFractionDigits: 2})} BDT<br>
                                <span class='text-sm text-gray-500'>${parseFloat(row.amountMYR).toLocaleString('en-US', {minimumFractionDigits: 2})} MYR</span>
                            </td>
                            <td class='py-3 px-4'>
                                <span class='${statusColor} px-2 py-1 rounded-lg text-black'>
                                    ${row.status}
                                </span>
                            </td>
                            <td class='py-3 px-4'>${row.created_at}</td>
                            <td class='py-3 px-4 text-center'>
                                <a href='admin-deposit-details.php?id=${row.depositID}' 
                                    class='bg-blue-400 text-black px-3 py-1 rounded-lg hover:bg-blue-500'>
                                    View
                                </a>
                            </td>
                        </tr>`;
                    tableBody.innerHTML += tr;
            });
        })
        .catch(error => console.error('Error:', error));
    }

    document.getElementById('filter-option').addEventListener('change', function() {
        const dateInputs = document.querySelector('.date-inputs');
        const filterInput = document.getElementById('filter-input');
        const statusSelect = document.getElementById('status-select');
        
        dateInputs.classList.add('hidden');
        filterInput.classList.add('hidden');
        statusSelect.classList.add('hidden');
        
        if (this.value === 'date') {
            dateInputs.classList.remove('hidden');
        } else if (this.value === 'status') {
            statusSelect.classList.remove('hidden');
        } else {
            filterInput.classList.remove('hidden');
        }
        fetchData();
    });

    document.getElementById('filter-input').addEventListener('input', fetchData);
    document.getElementById('status-select').addEventListener('change', fetchData);
    document.getElementById('start-date').addEventListener('change', fetchData);
    document.getElementById('end-date').addEventListener('change', fetchData);

    setInterval(fetchData, 2000);
    fetchData();

</script>



<!-- csv to excel downloader/converter -->
<script>
    document.getElementById('download').addEventListener('click', async () => {
        try {
            // Fetch CSV Data from PHP
            const response = await fetch('../../backend/excel_downloader/deposits-table-downloader.php');
            const csvData = await response.text();

            // Convert CSV to Sheet
            const rows = csvData.split('\n').map(row => row.split(','));
            const worksheet = XLSX.utils.aoa_to_sheet(rows);

            // Create Workbook and Add Worksheet
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");

            // Format the date and time
            const today = new Date();

            const date = today.toLocaleDateString('en-CA'); // Formats as YYYY-MM-DD
            const time = today.toLocaleTimeString('en-GB').replace(/:/g, '-'); // Formats as HH-MM-SS

            const filename = `Deposits_${date}_${time}.xlsx`;

            // Download as Excel File
            XLSX.writeFile(workbook, filename);
        } catch (error) {
            console.error("Error generating Excel:", error);
        }
    });
</script>




    <script src="../../assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html> 