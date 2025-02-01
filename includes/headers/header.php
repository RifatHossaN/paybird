<div class="fixed w-screen z-10">
                <header class="w-screen flex justify-between items-center pt-4 sm:py-4 bg-white dark:bg-[#232228] pr-16 sm:pr-36 rounded-lg">
                    <div class="flex items-center gap-4">
                        <ion-icon name="reorder-three-outline" class="text-3xl sm:p-2 rounded-lg transition duration-500 hover:shadow hover:bg-blue-300 sm:hidden" onclick="sidebarToggle()"></ion-icon>
                        <h1 class="hidden sm:block text-4xl font-bold dark:text-white">Dashboard</h1>
                    </div>
                    
                    <div class="flex items-center text-4xl text-gray-800 gap-4">
                        <!-- Search input -->
                        <!-- <div class="relative">
                            <input type="text" placeholder="Search..." class="px-4 py-2 pr-12 border rounded-full text-sm w-64 focus:outline-none focus:ring-1 focus:ring-blue-300" />
                            <button class="absolute right-0 top-0 bottom-0 px-4 bg-blue-500 text-white rounded-full text-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                Search
                            </button>
                        </div> -->
                        <!-- Notification icon -->
                        <div class="flex gap-2">
                            <button class=" pt-2 transition duration-500 dark:text-gray-400 dark:hover:text-white hover:text-black"><ion-icon name="notifications-outline"></ion-icon></button>

                            <button class=" pt-2 transition duration-500 dark:text-gray-400 dark:hover:text-white hover:text-black"><ion-icon name="chatbubbles-outline"></ion-icon></button>
                        </div>
                        

                        <!-- User icon and name -->
                        <div class="flex items-center sm:p-2 bg-gray-200 dark:bg-gray-900 rounded-full gap-4 cursor-pointer">
                            <img src="pfp.jpg" alt="User Icon" class="h-9 w-9 sm:w-8 sm:h-8 rounded-full">
                            <p class="hidden dark:text-white sm:block text-base pr-2">Nukx</p>
                        </div>
                    </div>
                </header>
            </div>