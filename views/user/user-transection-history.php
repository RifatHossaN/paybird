<?php 
include("../../backend/user-check.php") ;
$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Document</title>
</head>
<body onload="onload()" class="overflow-hidden">
    <div class=" bg-black h-screen dark:text-white w-full flex">
        
        
        <!-- Sidebar -->
        <?php 
        include("../../includes/sidebars/user-sidebar.php") ;

        include("../../config/connection.php");

        

        $result = null;

        $sql = "SELECT rate FROM users WHERE username = '$username'";


        // Execute query and check if successful
        $result = mysqli_query($conn, $sql) or die("Query Failed.");



        
        ?>

        <!-- Main Content -->
        <div id="main-container" class="px-8 w-full min-h-full overflow-y-scroll sm:ml-16 sm:w-full  bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            
            <!-- Header -->
            <?php include("../../includes/headers/user-header.php") ?>
            

           
            <section class=" flex flex-col sm:flex-row w-full gap-8  pt-16 h-full sm:h-full">
                
                <div class="bg-white sm:mt-6 dark:dark:bg-[#232228] rounded-lg w-full sm:full">
                    
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <h3 class="text-2xl font-bold">All Transections</h3>
                        
                    </div>
                  
                    <!-- Filter Form -->
                    <form method="GET" class="flex flex-row justify-end sm:justify-end space-x-1 sm:space-x-2 overflow-x-auto">
                        <input type="text" id="filter-input" name="filter-val" placeholder="Search..." 
                            class="w-24 sm:w-auto px-1 sm:px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700 focus:outline-none focus:border-blue-500">
                        
                        <select name="filterOption" id="filter-option" 
                            class="w-24 sm:w-auto px-1 sm:px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700 focus:outline-none focus:border-blue-500">
                            <option value="All">All</option>
                            <option value="transID">by Trans. ID</option>
                            <option value="number">by Number</option>
                            <option value="banking">by Banking</option>
                            <option value="date">by Date</option>
                        </select>

                        <div class="date-inputs hidden space-x-1 sm:space-x-2">
                            <input type="date" name="start-date" class="w-24 sm:w-auto px-1 sm:px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700">
                            <input type="date" name="end-date" class="w-24 sm:w-auto px-1 sm:px-2 py-1 rounded-lg border dark:bg-[#161618] dark:border-gray-700">
                        </div>

                        <button type="submit" name="btn-filter" value="clicked" 
                            class="w-20 sm:w-auto bg-blue-400 text-black px-2 py-1 rounded-lg hover:bg-blue-500 transition duration-300">
                            Filter
                        </button>
                    </form>
                    
                    
                    <div class="">
                        <table class="w-full full text-left">
                            <thead>
                                <tr class="border-b text-sm font-medium text-gray-600 dark:text-gray-400">
                                    <th class="py-2 text-left">Banking</th>
                                    <th class="py-2 px-4">Method</th>
                                    <th class="py-2 px-4">Number</th>
                                    <th class="py-2 px-4">Amount</th>
                                    <th class="py-2 px-4">Status</th>
                                    <th class="py-2 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            
<?php





include("../../config/connection.php");

if(!empty($_GET["btn-filter"]) && $_GET["btn-filter"]=="clicked") {
    $filterOption = mysqli_real_escape_string($conn, $_GET['filterOption']);
    $filterVal = mysqli_real_escape_string($conn, $_GET['filter-val']);

    switch($filterOption) {
        case 'all':
            $sql = "SELECT * FROM transections WHERE username = '$username' ORDER BY time DESC";
            break;
        case 'transID':
            $sql = "SELECT * FROM transections WHERE username = '$username' AND transID LIKE '%$filterVal%' ORDER BY time DESC";
            break;
        case 'number':
            $sql = "SELECT * FROM transections WHERE username = '$username' AND number LIKE '%$filterVal%' ORDER BY time DESC";
            break;
        case 'banking':
            $sql = "SELECT * FROM transections WHERE username = '$username' AND banking LIKE '%$filterVal%' ORDER BY time DESC";
            break;
        case 'date':
            $startDate = mysqli_real_escape_string($conn, $_GET['start-date']);
            $endDate = mysqli_real_escape_string($conn, $_GET['end-date']);
            if(!empty($startDate) && !empty($endDate)) {
                $sql = "SELECT * FROM transections WHERE username = '$username' AND DATE(time) BETWEEN '$startDate' AND '$endDate' ORDER BY time DESC";
            } else {
                $sql = "SELECT * FROM transections username = '$username' ORDER BY time DESC";
            }
            break;
        default:
            $sql = "SELECT * FROM transections WHERE username = '$username' ORDER BY time DESC";
    }
} else {
    $sql = "SELECT * FROM transections 
            WHERE username = '$username' AND TIMESTAMPDIFF(SECOND, time, NOW()) > 30 
            ORDER BY time DESC";
}


$result = mysqli_query($conn, $sql) or die("Query Failed.");

// Check if there are any rows returned
if (mysqli_num_rows($result) > 0) {
    // Loop through each row in the result
    while ($row = mysqli_fetch_assoc($result)) {

        $statusColor = [
            "Accepted" => "bg-green-400",
            "Pending" => "bg-yellow-400",
            "Rejected" => "bg-red-400",
            "Canceled" => "bg-gray-400"
        ][$row["status"]];


        echo"

                                <tr class='transition duration-500 hover:bg-gray-50 dark:hover:bg-[#1e1d20]'>
                                    <td class='py-2 px-1'>
                                        <img class='h-12 w-12' src='../../assets/imgs/".$row["banking"].".png' alt='bkash-logo'>
                                    </td>
                                    <td class='py-2 px-4 '>
                                        <h4 class='text-base font-bold'>".$row["method"]."</h4>
                                        <p class='hidden sm:block text-sm text-gray-700 dark:text-gray-400'>".$row["time"]."</p>
                                    </td>
                                    <td class='py-2 px-4 text-base font-bold'>".$row["number"]."</td>
                                    <td class='py-2 px-4 '>
                                        <h4 class='text-base font-bold'>".$row["amount"]." TK</h4>
                                        <p class='hidden sm:block text-sm text-gray-700 dark:text-gray-400'>".$row["amountYMR"]." MYR</p>
                                    </td>
                                    <td class='py-2 px-4 '>
                                        <p class='".$statusColor." p-0.5 rounded-lg text-center dark:text-gray-950'>".$row["status"]."</p>
                                    </td>
                                    <td class='py-2 px-4 h-full flex justify-center items-center'>
                                        <a href='user-transection-details.php?transID=$row[transID]'><button class='bg-blue-400 py-1 px-2 rounded-lg transition duration-500 dark:text-gray-950 hover:bg-blue-500'>View</button></a>
                                    </td>
                                </tr>
        ";

    }
} else {
    echo "No results found.";
}

?>




                            </tbody>
                        </table>
                    </div>
                </div>
                  
            </section>

        </div>
    </div>


    <script>
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
        });
    </script>

    

<script src="../../assets/js/script.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>