<?php 
include("../../backend/admin-check.php");
include("../../config/connection.php");

$transID = mysqli_real_escape_string($conn, $_GET['transID']);

// Get transaction data
$sql = "SELECT * FROM transections WHERE transID = '$transID'";
$result = mysqli_query($conn, $sql) or die("Query Failed.");
$transData = mysqli_fetch_assoc($result);

$username = $transData['username'];
$sql = "SELECT rate FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql) or die("Query Failed.");
$userData = mysqli_fetch_assoc($result);
$rate = $userData['rate'];

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
    <title>Modify Transaction</title>
</head>
<body onload="onload()" class="overflow-hidden dark">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="overflow-y-scroll px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/admin-header.php") ?>

            <!-- Modify Transaction Form Section -->
            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 h-full">
                <div class="w-full sm:w-2/3 bg-white dark:bg-[#232228] rounded-lg p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl sm:text-3xl font-bold">Modify Transaction</h2>
                        <span class="text-lg font-semibold">#<?php echo $transData['transID']; ?></span>
                    </div>
                    
                    <form action="../../backend/backend-admin-modify-transection.php" method="post" class="space-y-6">
                        <input type="hidden" name="transID" value="<?php echo $transData['transID']; ?>">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Banking Method</label>
                                <select name="banking" class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500">
                                    <option value="bkash" <?php echo ($transData['banking'] === 'bkash') ? 'selected' : ''; ?>>Bkash</option>
                                    <option value="nagad" <?php echo ($transData['banking'] === 'nagad') ? 'selected' : ''; ?>>Nagad</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Transaction Type</label>
                                <select name="method" class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500">
                                    <option value="send-money" <?php echo ($transData['method'] === 'send-money') ? 'selected' : ''; ?>>Send Money</option>
                                    <option value="cash-out" <?php echo ($transData['method'] === 'cash-out') ? 'selected' : ''; ?>>Cash Out</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Phone Number</label>
                                <input type="tel" name="number" pattern="^(01[3-9][0-9]{8})$" 
                                    id="phoneNumber"
                                    value="<?php echo $transData['number']; ?>"
                                    class="w-full px-4 py-3 rounded-xl border bg-white dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500 focus:bg-white dark:focus:bg-[#161618] dark:focus:border-blue-500 transition-colors duration-200"
                                    placeholder="01XXXXXXXXX" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Amount (BDT)</label>
                                <input type="number" name="amount" id="bdt"
                                    value="<?php echo $transData['amount']; ?>"
                                    class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500"
                                    placeholder="Enter amount"  max="150000" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Amount (MYR)</label>
                                <input type="number" name="amountYMR" id="myr" step="0.01"
                                    value="<?php echo $transData['amountYMR']; ?>"
                                    class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500"
                                    placeholder="Enter MYR amount" required>
                            </div>

                            <input type="hidden" id="rate" value="<?php echo $rate; ?>">

                            <div>
                                <label class="block text-sm font-medium mb-2">Username</label>
                                <input type="text" name="username"
                                    value="<?php echo $transData['username']; ?>"
                                    class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500"
                                    readonly>
                            </div>
                        </div>

                        <!-- Remove Admin Note Section -->
                        <!-- <div>
                            <label class="block text-sm font-medium mb-2">Admin Note</label>
                            <textarea name="admin_note" rows="3" 
                                class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500"
                                placeholder="Add admin note..."><?php echo $transData['admin_note']; ?></textarea>
                        </div> -->

                        <?php if($error): ?>
                            <p class="text-red-500 text-sm"><?php echo $error; ?></p>
                        <?php endif; ?>

                        <div class="flex gap-4">
                            <button type="submit" name="btn-modify-trans" value="clicked"
                                class="w-32 py-3 bg-blue-400 text-black rounded-lg hover:bg-blue-500 transition duration-300 text-center">
                                Save
                            </button>
                            
                            <a href="admin-transection-details.php?transID=<?php echo $transID; ?>" 
                                class="w-32 py-3 bg-gray-400 text-black rounded-lg hover:bg-gray-500 transition duration-300 text-center">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Right Side Information Panel -->
                <div class="hidden sm:block w-1/3 space-y-6">
                    <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Modification Guidelines</h3>
                        <ul class="space-y-2 text-sm">
                            <li>• Verify all changes carefully</li>
                            <li>• Double-check phone numbers</li>
                            <li>• Ensure correct currency conversion</li>
                            <li>• Add clear admin notes for changes</li>
                        </ul>
                    </div>

                    <div class="bg-yellow-100 dark:bg-yellow-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Original Transaction Info</h3>
                        <ul class="space-y-2 text-sm">
                            <li>• Created: <?php echo $transData['time']; ?></li>
                            <li>• Status: <?php echo $transData['status']; ?></li>
                            <li>• User Note: <?php echo $transData['note'] ?: 'None'; ?></li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="../../assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
        const conversionRate = parseFloat(document.getElementById('rate').value);
        const myrInput = document.getElementById('myr');
        const bdtInput = document.getElementById('bdt');
        let darkModeToggleBtn = document.querySelector('#darkModeToggleBtn');
        let body = document.body;

        let red = '#fef2f2'; // light red
        let blue = '#ffffff'; // light blue

        document.body.classList.toggle('dark', localStorage.getItem('darkmode') === 'true');

        if (localStorage.getItem('darkmode') === 'true'){
            // console.log("dark mode");
            red = '#450a0a';  // dark red
            blue = '#161618'; // dark blue
        }else{
            red = '#fef2f2'; // light red
            blue = '#ffffff'; // light blue
            // console.log("light mode");
        }

        darkModeToggleBtn.addEventListener('click', function () {
            if(body.classList.contains("dark")){
                red = '#fef2f2'; // light red
                blue = '#ffffff'; // light blue
                // console.log("light mode");

            }else{
                // console.log("dark mode");
                red = '#450a0a';  // dark red
                blue = '#161618'; // dark blue
            }
        });

        // Convert MYR to BDT
        myrInput.addEventListener('input', function() {
            const myrValue = parseFloat(myrInput.value) || 0;
            bdtInput.value = (myrValue * conversionRate).toFixed(2);
        });

        // Convert BDT to MYR
        bdtInput.addEventListener('input', function() {
            const bdtValue = parseFloat(bdtInput.value) || 0;
            myrInput.value = (bdtValue / conversionRate).toFixed(2);
        });

        // Phone number validation
        const phoneInput = document.getElementById('phoneNumber');
        
        // Check validation on blur (leaving the field)
        phoneInput.addEventListener('blur', function() {
            // Removing anything other then digits
            this.value = this.value.replace(/\D/g, '');
            // Remove leading "+88", "88", or "8"
            this.value = this.value.replace(/^(\+88|88|8)/, '');

            const isValid = this.checkValidity();
            if (!isValid) {
                this.style.backgroundColor = red;
                this.style.borderColor = '#ef4444';     // red border
            }
        });

        // Remove red styling when input becomes valid
        phoneInput.addEventListener('input', function() {
            const isValid = this.checkValidity();
            if (isValid) {
                this.style.backgroundColor = blue;
                this.style.borderColor = '';           // reset to default border
            }
        });


        // Check validation on blur (leaving the field)
        bdtInput.addEventListener('blur', function() {

        const isValid = this.checkValidity();
        if (!isValid) {
            this.style.backgroundColor = red;
            this.style.borderColor = '#ef4444';     // red border
        }
        });

        // Remove red styling when input becomes valid
        bdtInput.addEventListener('input', function() {
        const isValid = this.checkValidity();
        if (isValid) {
            this.style.backgroundColor = blue;
            this.style.borderColor = '';           // reset to default border
        }
        });


        // Check validation on blur (leaving the field)
        myrInput.addEventListener('blur', function() {

        const isValid = bdtInput.checkValidity();
        if (!isValid) {
        bdtInput.style.backgroundColor = red;
        bdtInput.style.borderColor = '#ef4444';     // red border
        }
        });

        // Remove red styling when input becomes valid
        myrInput.addEventListener('input', function() {
        const isValid = bdtInput.checkValidity();
        if (isValid) {
        bdtInput.style.backgroundColor = blue;
        bdtInput.style.borderColor = '';           // reset to default border
        }
        });
    </script>
</body>
</html> 