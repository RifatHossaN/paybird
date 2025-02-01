<?php
$username = $_SESSION['username'];
$superuser = $_SESSION['superuser'];
?>



<div class="fixed w-screen z-10">
                <header class="w-screen flex justify-between items-center pt-4 sm:py-4 bg-white dark:bg-[#232228] pr-16 sm:pr-36 rounded-lg">
                    <div class="flex items-center gap-4">
                        <ion-icon name="reorder-three-outline" class="text-3xl sm:p-2 rounded-lg transition duration-500 hover:shadow hover:bg-blue-300 sm:hidden" onclick="sidebarToggle()"></ion-icon>
                        <h1 id="page-title" class="hidden sm:block text-4xl font-bold dark:text-white"></h1>
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
                        <div class="flex items-center gap-8">
                        <div class="relative">
                                <a href="admin-notifications.php" class="text-3xl sm:text-4xl hover:text-gray-600 dark:text-gray-300 dark:hover:text-white transition duration-300">
                                    <ion-icon name="notifications-outline"></ion-icon>
                                    <span id="notification-badge" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold hidden"></span>
                                </a>
                            </div>

                            <div class="relative">
                                <a href="admin-chat.php" class="text-3xl sm:text-4xl hover:text-gray-600 dark:text-gray-300 dark:hover:text-white transition duration-300">
                                    <ion-icon name="chatbubbles-outline"></ion-icon>
                                    <span id="chat-badge" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold hidden"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </header>
            </div>


<script type="module">

// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
apiKey: "AIzaSyA4F9QcFvFk9ZvcrNo7x-mtRry3kigttEI",
authDomain: "testapp-db6f5.firebaseapp.com",
projectId: "testapp-db6f5",
storageBucket: "testapp-db6f5.firebasestorage.app",
messagingSenderId: "430580670712",
appId: "1:430580670712:web:c955c4ff2cd46a58af1b0d",
measurementId: "G-202DPQS5LL"
};
// Initialize Firebase
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

navigator.serviceWorker.register("../../assets/js/service_worker.js").then(registration => {
    getToken(messaging, {
        serviceWorkerRegistration: registration,
        vapidKey: 'BEqGWjx-nQFo7y323K1bA3WB8-8qjKIDvJAqChOSzZ7hqfqxkF9C6aOQdKWb95SdLPSax7mJxT5giQJ7aG15muA' }).then((currentToken) => {
        if (currentToken) {
            fetch("../../backend/notificaion-token-register.php", {
                method: "POST",
                body: new URLSearchParams({
                    tokenKey: currentToken,
                    'username' : '<?php echo "admin"; ?>',
                    'superuser' : '<?php echo $superuser; ?>'
                })
            })
            // .then(response => response.text())
            // .then(result => console.log(result));
            // Send the token to your server and update the UI if necessary
            // ...
        } else {
            // Show permission request UI
            console.log('No registration token available. Request permission to generate one.');
            // ...
        }
    }).catch((err) => {
        console.log('An error occurred while retrieving token. ', err);
        // ...
    });
});

Notification.requestPermission().then( function ( permission ) {
   
//    console.log( 'Inside Request Permission' );
  
   if ( permission === "granted" ) {
      
       console.log( 'notificaion Permission Granted' );
    //    const sampleNotification = new Notification( 'Sample Title' );

    //    sampleNotification.onclick = () => {
    //        console.log( 'Notification clicked' );
    //    }

   }else{
    console.log( 'notificaion Permission not Granted' );
   }
} );




</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var title = document.title;
        document.getElementById("page-title").textContent = title;
    });

function checkTotalUnreadMessages() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../../supportChat/php/check-total-unread.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.response);
                const badge = document.getElementById('chat-badge');
                if (data.total > 0) {
                    badge.textContent = data.total;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            } catch (e) {
                console.error("Error parsing unread counts:", e);
            }
        }
    }
    xhr.send();
}

// Check every second
setInterval(checkTotalUnreadMessages, 1000);

// Initial check
checkTotalUnreadMessages();
</script>

<script>
    function checkUserNotifications() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../../backend/check-admin-notification-count.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                try {
                    const data = JSON.parse(xhr.response);
                    const badge = document.getElementById('notification-badge');
                    if (data.unread > 0) {
                        badge.textContent = data.unread;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                } catch (e) {
                    console.error("Error parsing notification count:", e);
                }
            }
        }
        
        xhr.send();
    }

    // Check every second
    setInterval(checkUserNotifications, 1000);

    // Initial check
    checkUserNotifications();

    </script>