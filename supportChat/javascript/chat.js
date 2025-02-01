const form = document.querySelector(".typing-area"),
inputField = form.querySelector("input[name='message']"),
sendBtn = form.querySelector("button"),
chatBox = document.querySelector(".chat-box");

form.onsubmit = (e) => {
    e.preventDefault();
}

sendBtn.onclick = () => {
    if(inputField.value.trim() === "") return;
    
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
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../supportChat/php/get-chat.php", true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            chatBox.innerHTML = xhr.response;
            if(!chatBox.classList.contains("active")) {
                scrollToBottom();
            }
        }
    }
    xhr.send();
}

// Initial load and periodic refresh
fetchMessages();
setInterval(fetchMessages, 500);

function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
}

// Optional: Add active class when user is viewing older messages
chatBox.onmouseenter = () => chatBox.classList.add("active");
chatBox.onmouseleave = () => chatBox.classList.remove("active");
  