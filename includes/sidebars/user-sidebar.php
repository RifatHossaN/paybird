
        <div id="sidebar-options" class="sm:fixed sm:flex hidden z-40 w-16 h-screen text-gray-400 flex-col items-center space-y-8 text-4xl justify-around">
            <p class="absolute top-8 left-2 text-5xl text-gray-400 transition duration-500 hover:text-white"><ion-icon name="wallet"></ion-icon></p>
            
            <div class="w-full">
                <hr class=" border-gray-400 w-full">
            </div>
            

            <div class="flex flex-col gap-4">

                <a href="user-dashboard.php" class=" transition duration-500 hover:text-white p-2">
                    <div class="group relative flex items-center justify-center">
                        <ion-icon name="home-outline"></ion-icon>
                        <p class="absolute z-10 left-8 w-36 h-14 text-lg bg-black px-2 py-1 rounded-tr-3xl rounded-br-3xl flex justify-center items-center opacity-0 pointer-events-none group-hover:opacity-100 text-white group-hover:translate-x-2 transition duration-200 hover:z-10">
                            Dashboard
                        </p>
                    </div>               
                </a>

                <a href="user-transection-history.php" class="p-2 transition duration-500 ease-in-out hover:text-white">
                    <div class="group relative flex items-center justify-center">
                        <ion-icon name="time-outline"></ion-icon>
                        <p class="absolute z-10 left-8 w-36 h-14 text-lg bg-black px-2 py-1 rounded-tr-3xl rounded-br-3xl flex justify-center items-center opacity-0 pointer-events-none group-hover:opacity-100 text-white group-hover:translate-x-2 transition duration-200">
                        Trans. History
                        </p>
                    </div>
                </a>
                
                <a href="user-deposit-history.php" class="transition duration-500 hover:text-white p-2">
                    <div class="group relative flex items-center justify-center">
                    <i class="fa-solid fa-building-columns" class="text-xs"></i>
                        <p class="absolute z-10 left-8 w-36 h-14 text-lg bg-black px-2 py-1 rounded-tr-3xl rounded-br-3xl flex justify-center items-center opacity-0 pointer-events-none group-hover:opacity-100 text-white group-hover:translate-x-2 transition duration-200">
                            Deposits
                        </p>
                    </div>               
                </a>
                
                <!-- Add more icons as needed -->
            </div>


            <div class="flex flex-col gap-4">
                <a href="../../backend/backend-logout.php" class="p-2 transition duration-500 hover:text-white">
                    <div class="group relative flex items-center justify-center">
                        <ion-icon name="log-out-outline"></ion-icon>
                        <p class="absolute z-10 left-8 w-36 h-14 text-lg bg-black px-2 py-1 rounded-tr-3xl rounded-br-3xl flex justify-center items-center opacity-0 pointer-events-none group-hover:opacity-100 text-white group-hover:translate-x-2 transition duration-200">
                            LogOut
                        </p>
                    </div>
                </a>
                
                <div class="text-2xl pl-2.5 m-1 h-12 w-12 pt-2 transition duration-500 border-2 rounded-full cursor-pointer hover:text-white" onclick="darkModeToggle()"><ion-icon id="darkModeToggleBtn" name="moon"></ion-icon></div>
            </div>
            
        </div>