let currentUser = null;
const chatBox = document.querySelector(".chat-box");
const form = document.querySelector(".typing-area");
const inputField = form.querySelector("input[name='message']");
const chatUserInput = document.getElementById("chat_user");

function loadChat(username) {
    currentUser = username;
    chatUserInput.value = username;
    fetchMessages();
}

form.onsubmit = (e) => {
    e.preventDefault();
}

form.querySelector("button").onclick = () => {
    if(!currentUser) return;
    
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../supportChat/php/insert-chat.php", true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            inputField.value = "";
            scrollToBottom();
        }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}

function fetchMessages() {
    if(!currentUser) return;
    
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../supportChat/php/get-chat.php", true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            chatBox.innerHTML = xhr.response;
            scrollToBottom();
        }
    }
    let formData = new FormData();
    formData.append("chat_user", currentUser);
    xhr.send(formData);
}

setInterval(fetchMessages, 500);

function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
} 