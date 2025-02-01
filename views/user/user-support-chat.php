<?php 
include("../../backend/user-check.php");
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Support Chat</title>
</head>
<body onload="onload()" class="overflow-hidden">
    <div class="bg-black h-screen dark:text-white w-full flex">
        <!-- Sidebar -->
        <?php include("../../includes/sidebars/user-sidebar.php"); ?>

        <!-- Main Content -->
        <div id="main-container" class="overflow-y-scroll px-8 w-full min-h-full sm:ml-16 sm:w-full bg-white dark:bg-[#232228] rounded-3xl rounded-bl-3xl transition-all duration-200">
            <!-- Header -->
            <?php include("../../includes/headers/user-header.php") ?>

            <!-- Chat Section -->
            <div class="max-w-4xl mx-auto mt-16 mb-28">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden flex flex-col h-[calc(100vh-6rem)]">
                    <!-- Chat Header -->
                    <div class="p-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                <ion-icon name="headset-outline" class="text-xl"></ion-icon>
                            </div>
                            <div>
                                <h2 class="font-bold">Support Chat</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">We typically reply within a few minutes</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chat Messages -->
                    <div class="chat-box flex-1 overflow-y-auto p-4" id="chat-box">
                        <!-- Messages loaded via AJAX -->
                    </div>

                    <!-- Chat Input -->
                    <div class="p-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <form id="chat-form" class="typing-area flex gap-3">
                            <input type="hidden" name="username" value="<?php echo $username; ?>">
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

    <script>
    const form = document.getElementById('chat-form');
    const chatBox = document.getElementById('chat-box');
    const messageInput = form.querySelector('input[name="message"]');
    const sendButton = form.querySelector('button');

    // Prevent form submission
    form.onsubmit = (e) => {
        e.preventDefault();
    }

    // Send message
    sendButton.onclick = () => {
        const messageText = messageInput.value.trim();
        if (!messageText) return;

        let formData = new FormData(form);
        
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../../supportChat/php/insert-chat.php", true);
        xhr.onload = () => {
            if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                messageInput.value = "";
                fetchMessages();
            }
        }
        xhr.send(formData);
    }

    // Fetch messages
    function fetchMessages(isInitialLoad = false) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../../supportChat/php/get-chat.php", true);
        xhr.onload = () => {
            if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                const messages = JSON.parse(xhr.response);
                let html = '';
                
                if(messages.length > 0) {
                    messages.forEach(msg => {
                        // Admin messages (received) on left
                        if(msg.is_admin == 1) {
                            html += `<div class="flex justify-start mb-4">
                                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg py-2 px-4 max-w-[70%]">
                                            <p class="text-gray-800 dark:text-white">${msg.message}</p>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">${msg.time}</span>
                                        </div>
                                    </div>`;
                        } 
                        // User messages (sent) on right
                        else {
                            html += `<div class="flex justify-end mb-4">
                                        <div class="bg-blue-500 rounded-lg py-2 px-4 max-w-[70%]">
                                            <p class="text-white">${msg.message}</p>
                                            <span class="text-xs text-white/70">${msg.time}</span>
                                        </div>
                                    </div>`;
                        }
                    });
                } else {
                    html = '<div class="text-center text-gray-500 dark:text-gray-400">No messages yet. Start the conversation!</div>';
                }
                
                const wasAtBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox.scrollTop + 1;
                
                chatBox.innerHTML = html;

                // Only scroll to bottom on initial load or if user was already at bottom
                if (isInitialLoad || wasAtBottom) {
                    scrollToBottom();
                }
            }
        }
        let formData = new FormData();
        formData.append("username", "<?php echo $username; ?>");
        xhr.send(formData);
    }

    function scrollToBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Add mouseenter/mouseleave events to handle scrolling
    chatBox.onmouseenter = () => chatBox.classList.add("active");
    chatBox.onmouseleave = () => chatBox.classList.remove("active");

    // Periodic refresh without forcing scroll
    setInterval(() => fetchMessages(false), 500);

    // Initial load with scroll to bottom
    fetchMessages(true);

    function markMessagesAsRead() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../../supportChat/php/mark-user-read.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Messages marked as read
                const badge = document.getElementById('chat-badge');
                badge.classList.add('hidden');
            }
        }
        let formData = new FormData();
        formData.append("username", "<?php echo $username; ?>");
        xhr.send(formData);
    }

    // Call this function when user opens the chat
    document.addEventListener('DOMContentLoaded', function() {
        markMessagesAsRead();
    });

    // Also mark messages as read when user returns to the chat tab
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            markMessagesAsRead();
        }
    });
    </script>

    <script src="../../assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html> 