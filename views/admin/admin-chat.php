<?php 
include("../../backend/admin-check.php");
include("../../config/connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <title>Support Chat Management</title>
</head>
<body onload="onload()" class="overflow-hidden">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="overflow-y-scroll px-4 sm:px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/admin-header.php") ?>

            <!-- Chat Interface -->
            <div class="mt-20">
                <!-- Desktop Layout -->
                <div class="hidden md:flex gap-6 h-[calc(100vh-180px)]">
                    <!-- Users List (Left Side) -->
                    <div class="w-1/3 bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
                        <!-- Users Header -->
                        <div class="p-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            <h3 class="text-lg font-bold">Active Chats</h3>
                            <!-- Search Box -->
                            <div class="mt-4 relative">
                                <input type="text" 
                                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:border-blue-500" 
                                    placeholder="Search users...">
                                <ion-icon name="search-outline" class="absolute left-3 top-3 text-gray-400"></ion-icon>
                            </div>
                        </div>

                        <!-- Users List -->
                        <div class="users-list divide-y dark:divide-gray-700 overflow-y-auto h-[calc(100vh-300px)]">
                            <?php
                            $sql = "SELECT u.username, u.name,
                                    (SELECT COUNT(*) FROM chat_messages 
                                     WHERE username = u.username 
                                     AND is_read = 0 
                                     AND is_admin = 0) as unread
                                    FROM users u
                                    WHERE u.superuser = 'false'
                                    ORDER BY unread DESC, u.regdate DESC";
                            
                            $result = mysqli_query($conn, $sql);
                            
                            while($row = mysqli_fetch_assoc($result)) {
                                // Debug output
                                echo "<!-- Debug: {$row['username']} has {$row['unread']} unread messages -->";
                                
                                $unreadBadge = ($row['unread'] > 0) ? 
                                    "<div class='absolute top-4 right-4 w-2 h-2 bg-red-500 rounded-full'></div>" : "";
                                
                                echo "<div class='user-chat relative flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors' 
                                          data-username='{$row['username']}'
                                          onclick='loadChat(\"{$row['username']}\", \"{$row['name']}\")'>
                                        <div class='flex items-center space-x-3'>
                                            <div class='w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center'>
                                                <ion-icon name='person-outline' class='text-xl'></ion-icon>
                                            </div>
                                            <div>
                                                <h4 class='font-medium'>{$row['name']}</h4>
                                                <p class='text-sm text-gray-500 dark:text-gray-400'>@{$row['username']}</p>
                                            </div>
                                        </div>
                                        <div class='unread-indicator'></div>
                                    </div>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Chat Area (Right Side) -->
                    <div class="w-2/3 bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden flex flex-col">
                        <!-- Chat Header -->
                        <div class="p-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <ion-icon name="person-outline" class="text-xl"></ion-icon>
                                </div>
                                <div>
                                    <h2 class="font-bold" id="desktop-chat-user-name">Select a user</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400" id="desktop-chat-username">to start chatting</p>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Messages -->
                        <div class="flex-1 overflow-y-auto p-4" id="chat-messages">
                            <!-- Messages will be loaded here -->
                        </div>

                        <!-- Chat Input -->
                        <div class="p-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            <form id="chat-form" class="flex gap-3">
                                <input type="hidden" name="chat_user" id="chat_user">
                                <input type="text" 
                                    class="flex-1 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:border-blue-500" 
                                    name="message" 
                                    placeholder="Type your message..." 
                                    autocomplete="off">
                                <button type="button" 
                                    class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center gap-2">
                                    <span>Send</span>
                                    <ion-icon name="send-outline"></ion-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile Layout -->
                <div class="md:hidden">
                    <!-- Users List View -->
                    <div id="mobile-users-list" class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
                        <!-- Same users list as desktop but with mobile-specific click handlers -->
                        <?php
                        $result = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_assoc($result)) {
                            $unreadBadge = $row['unread'] > 0 ? 
                                "<span class='bg-red-500 text-white text-xs px-2 py-1 rounded-full'>{$row['unread']}</span>" : "";
                            
                            echo "<div class='user-chat flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors'
                                      onclick='openMobileChat(\"{$row['username']}\", \"{$row['name']}\")'>
                                    <div class='flex items-center space-x-3'>
                                        <div class='w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center'>
                                            <ion-icon name='person-outline' class='text-xl'></ion-icon>
                                        </div>
                                        <div>
                                            <h4 class='font-medium'>{$row['name']}</h4>
                                            <p class='text-sm text-gray-500 dark:text-gray-400'>@{$row['username']}</p>
                                        </div>
                                    </div>
                                    {$unreadBadge}
                                </div>";
                        }
                        ?>
                    </div>

                    <!-- Mobile Chat View (Initially Hidden) -->
                    <div id="mobile-chat" class="hidden bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden h-[calc(100vh-6rem)] flex flex-col mb-28">
                        <!-- Mobile Chat Header -->
                        <div class="p-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            <div class="flex items-center space-x-3">
                                <button onclick="closeMobileChat()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                    <ion-icon name="arrow-back-outline" class="text-xl"></ion-icon>
                                </button>
                                <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <ion-icon name="person-outline" class="text-xl"></ion-icon>
                                </div>
                                <div>
                                    <h2 class="font-bold" id="mobile-chat-user-name"></h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400" id="mobile-chat-username"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Chat Messages -->
                        <div class="flex-1 overflow-y-auto p-4" id="mobile-chat-messages">
                            <!-- Messages will be loaded here -->
                        </div>

                        <!-- Mobile Chat Input -->
                        <div class="p-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            <form id="mobile-chat-form" class="flex gap-3">
                                <input type="hidden" name="chat_user" id="mobile_chat_user">
                                <input type="text" 
                                    class="flex-1 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:border-blue-500 w-3/4" 
                                    name="message" 
                                    placeholder="Type your message..." 
                                    autocomplete="off">
                                <button type="button" 
                                    class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center gap-2 w-1/4">
                                    <span>Send</span>
                                    <ion-icon name="send-outline"></ion-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    let currentUser = '';

    // Desktop chat functions
    function loadChat(username, name) {
        currentUser = username;
        document.getElementById('chat_user').value = username;
        document.getElementById('desktop-chat-username').textContent = '@' + username;
        document.getElementById('desktop-chat-user-name').textContent = name;
        
        // Mark messages as read
        markMessagesAsRead(username);
        
        fetchMessages('chat-messages', true);
    }

    // Mobile chat functions
    function openMobileChat(username, name) {
        currentUser = username;
        document.getElementById('mobile_chat_user').value = username;
        document.getElementById('mobile-chat-username').textContent = '@' + username;
        document.getElementById('mobile-chat-user-name').textContent = name;
        document.getElementById('mobile-users-list').classList.add('hidden');
        document.getElementById('mobile-chat').classList.remove('hidden');
        
        // Mark messages as read
        markMessagesAsRead(username);
        
        fetchMessages('mobile-chat-messages', true);
    }

    function closeMobileChat() {
        document.getElementById('mobile-chat').classList.add('hidden');
        document.getElementById('mobile-users-list').classList.remove('hidden');
    }

    // Chat functionality
    function fetchMessages(containerId, isInitialLoad = false) {
        if (!currentUser) return;

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../../supportChat/php/get-chat.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                const messages = JSON.parse(xhr.response);
                let html = '';
                
                const container = document.getElementById(containerId);
                const wasAtBottom = container.scrollHeight - container.clientHeight <= container.scrollTop + 1;
                
                if(messages.length > 0) {
                    messages.forEach(msg => {
                        // Admin messages (sent) on right
                        if(msg.is_admin == 1) {
                            html += `<div class="flex justify-end mb-4">
                                        <div class="bg-blue-500 rounded-lg py-2 px-4 max-w-[70%]">
                                            <p class="text-white">${msg.message}</p>
                                            <span class="text-xs text-white/70">${msg.time}</span>
                                        </div>
                                    </div>`;
                        } 
                        // User messages (received) on left
                        else {
                            html += `<div class="flex justify-start mb-4">
                                        <div class="bg-gray-200 dark:bg-gray-700 rounded-lg py-2 px-4 max-w-[70%]">
                                            <p class="text-gray-800 dark:text-white">${msg.message}</p>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">${msg.time}</span>
                                        </div>
                                    </div>`;
                        }
                    });
                } else {
                    html = '<div class="text-center text-gray-500 dark:text-gray-400">No messages yet. Start the conversation!</div>';
                }
                
                container.innerHTML = html;

                // Only scroll if:
                // 1. It's initial load (when selecting a user)
                // 2. User was already at bottom and not actively viewing messages
                if (isInitialLoad || (!container.classList.contains("active") && wasAtBottom)) {
                    scrollToBottom(containerId);
                }
            }
        }
        let formData = new FormData();
        formData.append("chat_user", currentUser);
        xhr.send(formData);
    }

    function scrollToBottom(containerId) {
        const container = document.getElementById(containerId);
        container.scrollTop = container.scrollHeight;
    }

    // Handle message sending for both desktop and mobile
    ['chat-form', 'mobile-chat-form'].forEach(formId => {
        const form = document.getElementById(formId);
        if (!form) return;
        
        const messageInput = form.querySelector('input[name="message"]');
        const sendButton = form.querySelector('button');

        form.onsubmit = (e) => {
            e.preventDefault();
        }

        sendButton.onclick = () => {
            if (messageInput.value.trim() === "") return;

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../../supportChat/php/insert-chat.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    messageInput.value = "";
                    const containerId = formId === 'chat-form' ? 'chat-messages' : 'mobile-chat-messages';
                    fetchMessages(containerId, true); // Force scroll on new message
                }
            }
            let formData = new FormData(form);
            formData.append("chat_user", currentUser);
            xhr.send(formData);
        };
    });

    // Add active class when user is viewing older messages
    document.querySelectorAll('#chat-messages, #mobile-chat-messages').forEach(box => {
        box.onmouseenter = () => box.classList.add("active");
        box.onmouseleave = () => box.classList.remove("active");
    });

    // Periodic refresh
    setInterval(() => {
        if (currentUser) {
            if (window.innerWidth >= 768) {
                fetchMessages('chat-messages');
            } else if (!document.getElementById('mobile-chat').classList.contains('hidden')) {
                fetchMessages('mobile-chat-messages');
            }
        }
    }, 500);

    // Add this new function
    function markMessagesAsRead(username) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../../supportChat/php/mark-as-read.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Remove red dot immediately after marking as read
                document.querySelectorAll(`.user-chat[data-username="${username}"] .unread-indicator`)
                    .forEach(indicator => indicator.innerHTML = '');
            }
        }
        let formData = new FormData();
        formData.append("chat_user", username);
        xhr.send(formData);
    }

    // Add this after your existing JavaScript code
    function checkUnreadMessages() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../../supportChat/php/check-unread.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                try {
                    const data = JSON.parse(xhr.response);
                    const usersList = document.querySelector('.users-list');
                    const users = Array.from(usersList.children);

                    // Sort users based on latest message time
                    users.sort((a, b) => {
                        const timeA = data[a.getAttribute('data-username')]?.lastMessageTime || 0;
                        const timeB = data[b.getAttribute('data-username')]?.lastMessageTime || 0;
                        return timeB - timeA;
                    });

                    // Reorder the DOM elements
                    users.forEach(user => {
                        const username = user.getAttribute('data-username');
                        const unreadIndicator = user.querySelector('.unread-indicator');
                        
                        // Update unread indicator with mobile-style badge
                        if (data[username]?.unread > 0) {
                            unreadIndicator.innerHTML = `
                                <span class='bg-red-500 text-white text-xs px-2 py-1 rounded-full'>${data[username].unread}</span>
                            `;
                        } else {
                            unreadIndicator.innerHTML = '';
                        }
                        
                        // Move user to new position
                        usersList.appendChild(user);
                    });
                } catch (e) {
                    console.error("Error parsing data:", e);
                }
            }
        }
        xhr.send();
    }

    // Check for unread messages every second
    setInterval(checkUnreadMessages, 1000);

    // Also check immediately when page loads
    checkUnreadMessages();
    </script>

    <script src="../../assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html> 