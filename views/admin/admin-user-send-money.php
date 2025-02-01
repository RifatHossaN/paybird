<?php 

include("../../backend/admin-check.php"); 
include("../../config/connection.php");

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
} else {
    $error = null;
}
if (isset($_GET['user'])) {
    $user = $_GET['user'];
    $user = filter_var($user, FILTER_SANITIZE_STRING);
    
    // Get user's rate
    $sql = "SELECT * FROM users WHERE username = '$user'";
    $result = mysqli_query($conn, $sql) or die("Query Failed.");
    $userData = mysqli_fetch_assoc($result);
    $rate = $userData['rate'];
    $bkash = $userData['bkash'];
    $nagad= $userData['nagad'];
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <title>Send Money</title>
</head>
<body onload="onload()" class="overflow-hidden">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="overflow-y-scroll px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/admin-header.php") ?>

            <!-- Send Money Form Section -->
            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 h-full">
                <div class="w-full sm:w-2/3 bg-white dark:bg-[#232228] rounded-lg p-8">
                    <h2 class="text-2xl sm:text-3xl font-bold mb-8">Send Money</h2>
                    
                    <form action="../../backend/backend-admin-user-send-money.php" method="post" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Username</label>
                                <input type="text" name="username" value="<?php echo $user; ?>" readonly
                                    class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Select Banking</label>
                                <select name="banking" class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-500">
                                    <?php if($bkash=="true"){
                                        echo "<option value='bkash'>Bkash</option>";
                                    }else{
                                        echo "<option value='bkash' disabled>Bkash<p class='text-red-900'>(Not Available)</p></option>";
                                    }
                                    
                                    if($nagad=="true"){
                                        echo "<option value='nagad'>Nagad</option>";
                                    }else{
                                        echo "<option value='nagad' disabled>Nagad <p class='text-red-900'>(Not Available)</p></option>";
                                    }?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Transaction Type</label>
                                <select name="method" class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-500">
                                    <option value="send-money">Send Money</option>
                                    <option value="cash-out">Cash Out</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Phone Number</label>
                                <input type="tel" name="number" pattern="^(01[3-9][0-9]{8})$" 
                                    id="phoneNumber"
                                    class="w-full px-4 py-3 rounded-xl border bg-white dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500 focus:bg-white dark:focus:bg-[#161618] dark:focus:border-blue-500 transition-colors duration-200"
                                    placeholder="01XXXXXXXXX" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Amount BDT</label>
                                <input type="number" name="amount" id="bdt"
                                    class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-500"
                                    placeholder="Enter amount in BDT" max="150000" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Amount MYR</label>
                                <input type="number" name="amountYMR" id="myr" step="0.01"
                                    class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-500"
                                    placeholder="Enter amount in MYR" required>
                            </div>

                            <input type="hidden" id="rate" value="<?php echo $rate; ?>">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Note (Optional)</label>
                            <textarea name="note" rows="3" 
                                class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-500"
                                placeholder="Add a note..."></textarea>
                        </div>

                        <?php if($error): ?>
                            <p class="text-red-500 text-sm"><?php echo $error; ?></p>
                        <?php endif; ?>

                        <button type="submit" name="btn-send-money" value="clicked"
                            class="w-32 py-3 bg-blue-400 text-black rounded-lg hover:bg-blue-500 transition duration-300 text-center">
                            Send Money
                        </button>
                    </form>
                </div>

                <!-- Right Side Information Panel -->
                <div class="hidden sm:block w-1/3 space-y-6">
                    <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Important Notice</h3>
                        <ul class="space-y-2 text-sm">
                            <li>• Maximum transaction limit: 1,50,000 TK</li>
                            <li>• Verify user details before sending</li>
                            <li>• Double-check the phone number</li>
                        </ul>
                    </div>

                    <div class="bg-yellow-100 dark:bg-yellow-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Quick Tips</h3>
                        <ul class="space-y-2 text-sm">
                            <li>• Verify user identity</li>
                            <li>• Check transaction limits</li>
                            <li>• Confirm banking details</li>
                            <li>• Review before sending</li>
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
