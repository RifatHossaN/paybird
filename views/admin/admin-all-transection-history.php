<?php include("../../backend/admin-check.php") ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <title>Transaction History</title>
</head>
<body onload="onload()" class="overflow-hidden">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <div id="main-container" class="px-8 w-full min-h-full overflow-y-scroll sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/admin-header.php"); ?>

            <!-- Transaction History Section -->
            <section class="flex flex-col sm:flex-row w-full  gap-8 pt-16 h-full">
                <div class="bg-white dark:bg-[#232228]  rounded-lg w-full">
                    <!-- Combined title and filter in one row -->
                    <div class="flex flex-col sm:flex-row pt-8 items-center justify-between mb-2 sm:mb-4">
                        <div class="flex items-center gap-2">
                            <h3 class="text-2xl font-bold mb-4 sm:mb-0">All Transactions</h3>
                            <button id="download" 
                                class="bg-green-400 text-black px-3 py-1 text-sm rounded-lg hover:bg-green-500 transition duration-300 flex items-center gap-1">
                                <ion-icon name="download-outline"></ion-icon>
                                Download Excel
                            </button>
                            
                        </div>
                        
                        <!-- Filter Form -->
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
                                <option value="status">by Status</option>
                                <option value="date">by Date</option>
                            </select>

                            <select id="status-select" name="status" class="hidden w-24 sm:w-auto px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700">
                                <option value="">Select Status</option>
                                <option value="Accepted">Accepted</option>
                                <option value="Pending">Pending</option>
                                <option value="Rejected">Rejected</option>
                                <option value="Canceled">Canceled</option>
                            </select>

                            <div class="date-inputs hidden space-x-2">
                                <input type="date" name="start-date" id="start-date" 
                                    class="w-24 sm:w-auto px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700">
                                <input type="date" name="end-date" id="end-date" 
                                    class="w-24 sm:w-auto px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700">
                            </div>
                        </form>
                    </div>

                    <div class="">
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
            </section>
        </div>
    </div>

    <script src="../../assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
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

            fetch(`realtime-fetch/all-fetch.php?${queryString}`)
            .then(response => response.json())
            .then(data => {
                let tableBody = document.getElementById('table-body');
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4">No transactions found.</td></tr>';
                    return;
                }

                data.forEach(row => {
                    const statusColor = {
                        "Accepted": "bg-green-400",
                        "Pending": "bg-yellow-400",
                        "Rejected": "bg-red-400",
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
                            <td class='py-2 px-4 text-lg font-bold'>${row.username}</td>
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
            const response = await fetch('../../backend/excel_downloader/transections-table-downloader.php');
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

            const filename = `transactions_${date}_${time}.xlsx`;

            // Download as Excel File
            XLSX.writeFile(workbook, filename);

        } catch (error) {
            console.error("Error generating Excel:", error);
        }
    });
</script>


</body>
</html>
