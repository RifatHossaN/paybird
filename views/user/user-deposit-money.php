<?php
include("../../backend/user-check.php");
include("../../config/connection.php");

$username = $_SESSION['username'];
$currentDate = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <title>Deposit Money</title>
</head>
<body onload="onload()" class="overflow-hidden dark">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/user-sidebar.php"); ?>

        <div id="main-container" class="overflow-y-scroll px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/user-header.php") ?>
            <section class="flex flex-col sm:flex-row w-full gap-8 pt-16 h-full">
                <div class="w-full sm:w-2/3 bg-white dark:bg-[#232228] rounded-lg p-8">
                    <h2 class="text-2xl font-bold mb-6">Deposit Money</h2>

                    <form action="../../backend/backend-user-deposit-money.php" method="post" enctype="multipart/form-data" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Currency</label>
                                <select name="currency" id="currency" class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500">
                                    <option value="MYR">MYR (RM)</option>
                                    <option value="BDT">BDT (৳)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Payment Method</label>
                                <select name="payment_method" class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500">
                                    <option value="Cash">Cash</option>
                                    <option value="Bank-Transfer">Bank Transfer</option>
                                </select>
                            </div>

                            <div class="relative">
                                <label class="block text-sm font-medium mb-2" id="amount-label">Amount (BDT)</label>
                                <input type="number" step="0.01" name="amount" id="amount" required
                                    class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500"
                                    placeholder="Enter amount">
                                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400" id="conversion-text">
                                    ≈ 0.00 MYR
                                </div>
                                
                                <!-- Hidden inputs for form submission -->
                                <input type="hidden" name="amount_bdt" id="amount_bdt">
                                <input type="hidden" name="amount_myr" id="amount_myr">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Payment Date</label>
                                <input type="date" name="payment_date" value="<?php echo $currentDate; ?>" readonly
                                    class="w-full px-4 py-3 rounded-xl border bg-gray-100 dark:bg-[#161618] dark:border-gray-700 dark:text-gray-400">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Transaction/Reference No. (Optional)</label>
                                <input type="text" name="reference_no" 
                                    class="w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 focus:outline-none focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Receipt Image</label>
                                <div class="relative">
                                    <input type="file" name="receipt_image" id="receipt_image" accept="image/*" class="hidden">
                                    <label for="receipt_image" class="flex items-center gap-2 w-full px-4 py-3 rounded-xl border dark:bg-[#161618] dark:border-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-[#1e1d20] cursor-pointer transition duration-300">
                                        <ion-icon name="cloud-upload-outline" class="text-xl"></ion-icon>
                                        <span id="file-name">Give Recipt Image</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition duration-300">
                            Submit Deposit Request
                        </button>
                    </form>
                </div>

                <!-- Right Side Information Panel -->
                <div class="hidden sm:block w-1/3 space-y-6">
                    <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-xl">
                        <h3 class="text-lg font-bold mb-4">Deposit Instructions</h3>
                        <ul class="space-y-2 text-sm">
                            <li>• Select your preferred currency</li>
                            <li>• Choose payment method</li>
                            <li>• Enter the amount to deposit</li>
                            <li>• Upload receipt for bank transfers</li>
                            <li>• Wait for admin approval</li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        // Get rate from PHP session
        const conversionRate = <?php echo $_SESSION['rate']; ?>;
        
        // Handle currency selection and amount fields
        const currencySelect = document.getElementById('currency');
        const amountInput = document.getElementById('amount');
        const amountLabel = document.getElementById('amount-label');
        const conversionText = document.getElementById('conversion-text');
        const hiddenBDT = document.getElementById('amount_bdt');
        const hiddenMYR = document.getElementById('amount_myr');

        function updateConversion() {
            const amount = parseFloat(amountInput.value) || 0;
            
            if (currencySelect.value === 'BDT') {
                const myrAmount = (amount / conversionRate).toFixed(2);
                conversionText.textContent = `≈ ${myrAmount} MYR`;
                hiddenBDT.value = amount;
                hiddenMYR.value = myrAmount;
            } else {
                const bdtAmount = (amount * conversionRate).toFixed(2);
                conversionText.textContent = `≈ ${bdtAmount} BDT`;
                hiddenBDT.value = bdtAmount;
                hiddenMYR.value = amount;
            }
        }

        currencySelect.addEventListener('change', function() {
            if (this.value === 'BDT') {
                amountLabel.textContent = 'Amount (BDT)';
                amountInput.placeholder = 'Enter BDT amount';
            } else {
                amountLabel.textContent = 'Amount (MYR)';
                amountInput.placeholder = 'Enter MYR amount';
            }
            amountInput.value = ''; // Clear input on currency change
            conversionText.textContent = `≈ 0.00 ${this.value === 'BDT' ? 'MYR' : 'BDT'}`;
            amountInput.focus();
        });

        amountInput.addEventListener('input', updateConversion);

        // Initial setup
        currencySelect.dispatchEvent(new Event('change'));

        // Update file input display
        document.getElementById('receipt_image').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose File';
            document.getElementById('file-name').textContent = fileName;
        });
    </script>

    <script src="../../assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html> 