<?php 


include("backend/both-loggedin-check.php"); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayBird - Welcome</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body onload="onload()" class="overflow-hidden" >
    <div class="bg-white dark:bg-[#1a1a1f] h-screen w-full">
        <div class="flex min-h-screen w-full">
            <!-- Left Section - Welcome Content -->
            <div class="hidden lg:flex lg:w-1/2 bg-blue-600 items-center justify-center p-12 relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0 bg-repeat" style="background-image: url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.5)\"%3E%3C/path%3E%3C/svg%3E')"></div>
                </div>
                <div class="max-w-lg relative">
                    <h1 class="text-5xl font-bold text-white mb-8">PayBird</h1>
                    <p class="text-blue-100 text-xl mb-8">Fast, secure, and reliable money transactions at your fingertips.</p>
                    <div class="flex gap-6 text-blue-100">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Secure</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span>Fast</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Reliable</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Section - Welcome Actions -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
                <div class="w-full max-w-md space-y-8 bg-white dark:bg-[#232228] p-8 rounded-3xl shadow-lg">
                    <!-- Logo and Avatar for mobile -->
                    <div class="text-center">
                        <div class="lg:hidden">
                            <h1 class="text-4xl font-bold text-blue-600 dark:text-blue-400">PayBird</h1>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">Welcome Back!</p>
                        </div>
                        <!-- Avatar -->
                        <div class="flex justify-center my-8">
                            <div class="w-24 h-24 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center relative">
                                <svg class="w-16 h-16 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <div class="absolute -bottom-2 w-full flex justify-center">
                                    <div class="bg-green-500 w-4 h-4 rounded-full border-2 border-white dark:border-[#232228]"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="text-center space-y-2">
                            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Welcome to PayBird</h2>
                            <p class="text-gray-600 dark:text-gray-400">Your trusted financial partner</p>
                        </div>
                        <a href="public/login.php" 
                           class="block w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-2xl transition duration-200 text-center">
                            Login to Continue
                        </a>
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Dark mode toggle button -->
        <button onclick="darkModeToggle()" 
                
                name="moon"
                class="fixed bottom-4 right-4 px-2 pb-1 pt-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200">
            <ion-icon name="moon" id="darkModeToggleBtn" class="w-6 h-6"></ion-icon>
        </button>
    </div>

    <script src="assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>