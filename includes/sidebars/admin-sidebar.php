<div id="sidebar-options" class="sm:fixed sm:flex hidden z-40 w-16 h-screen text-gray-400 flex-col items-center space-y-8 text-4xl justify-around">
    <div class="flex flex-col gap-4 w-full">
        <p class="absolute top-8 left-2 text-5xl text-gray-400 transition duration-500 hover:text-white"><ion-icon name="settings-outline"></ion-icon></p>
        
        <div class="w-full mt-16">
            <hr class="border-gray-400 w-full">
        </div>
    </div>
    
    
    <div class="flex flex-col gap-4">
        <a href="../../views/admin/admin-panel.php" class="transition duration-500 hover:text-white p-2">
            <div class="group relative flex items-center justify-center">
                <ion-icon name="home-outline"></ion-icon>
                <p class="absolute z-10 left-8 w-36 h-14 text-lg bg-black px-2 py-1 rounded-tr-3xl rounded-br-3xl flex justify-center items-center opacity-0 pointer-events-none group-hover:opacity-100 text-white group-hover:translate-x-2 transition duration-200 hover:z-10">
                    Home
                </p>
            </div>               
        </a>
        <a href="../../views/admin/admin-payment-config.php" class="p-2 transition duration-500 hover:text-white">
            <div class="group relative flex items-center justify-center">
                <ion-icon name="cash-outline"></ion-icon>
                <p class="absolute z-10 left-8 w-36 h-14 text-lg bg-black px-2 py-1 rounded-tr-3xl rounded-br-3xl flex justify-center items-center opacity-0 pointer-events-none group-hover:opacity-100 text-white group-hover:translate-x-2 transition duration-200">
                    Exchange Rate
                </p>
            </div>
        </a>
        <a href="../../views/admin/admin-user-list.php" class="p-2 transition duration-500 hover:text-white">
            <div class="group relative flex items-center justify-center">
                <ion-icon name="people-outline"></ion-icon>
                <p class="absolute z-10 left-8 w-36 h-14 text-lg bg-black px-2 py-1 rounded-tr-3xl rounded-br-3xl flex justify-center items-center opacity-0 pointer-events-none group-hover:opacity-100 text-white group-hover:translate-x-2 transition duration-200">
                    Users
                </p>
            </div>
        </a>
        <a href="admin-all-user-deposit-history.php" class="transition duration-500 hover:text-white p-2">
            <div class="group relative flex items-center justify-center">
            <i class="fa-solid fa-building-columns" class="text-xs"></i>
                <p class="absolute z-10 left-8 w-36 h-14 text-lg bg-black px-2 py-1 rounded-tr-3xl rounded-br-3xl flex justify-center items-center opacity-0 pointer-events-none group-hover:opacity-100 text-white group-hover:translate-x-2 transition duration-200">
                    Deposits
                </p>
            </div>               
        </a>
        <a href="admin-all-transection-history.php" class="p-2 transition duration-500 ease-in-out hover:text-white">
            <div class="group relative flex items-center justify-center">
                <ion-icon name="time-outline"></ion-icon>
                <p class="absolute z-10 left-8 w-36 h-14 text-lg bg-black px-2 py-1 rounded-tr-3xl rounded-br-3xl flex justify-center items-center opacity-0 pointer-events-none group-hover:opacity-100 text-white group-hover:translate-x-2 transition duration-200">
                Trans. History
                </p>
            </div>
        </a>
    </div>

    <div class="flex flex-col gap-4">
        <a href="../../backend/backend-logout.php" class="p-2 transition duration-500 hover:text-white">
            <div class="group relative flex items-center justify-center">
                <ion-icon name="log-out-outline"></ion-icon>
                <p class="absolute z-10 left-8 w-36 h-14 text-lg bg-black px-2 py-1 rounded-tr-3xl rounded-br-3xl flex justify-center items-center opacity-0 pointer-events-none group-hover:opacity-100 text-white group-hover:translate-x-2 transition duration-200">
                    Logout
                </p>
            </div>
        </a>
        
        <div class="text-2xl pl-2.5 m-1 h-12 w-12 pt-2 transition duration-500 border-2 rounded-full cursor-pointer hover:text-white" onclick="darkModeToggle()"><ion-icon id="darkModeToggleBtn" name="moon"></ion-icon></div>
    </div>
</div>
