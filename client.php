<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket Chat</title>
</head>
<body>
<h2>WebSocket Chat</h2>
<input type="text" id="messageInput" placeholder="Type a message...">
<button onclick="sendMessage()">Send</button>
<div id="messages"></div>

<script>
    const socket = new WebSocket("ws://127.0.0.1:8000");

    socket.onopen = function () {
        console.log("Connected to server");
    };

    socket.onmessage = function (event) {
        console.log("Server says:", event.data);
        document.getElementById("messages").innerHTML += `<p><strong>Server:</strong> ${event.data}</p>`;
    };

    function sendMessage() {
        const input = document.getElementById("messageInput");
        const message = input.value.trim();
        if (message) {
            socket.send(message);
            document.getElementById("messages").innerHTML += `<p><strong>You:</strong> ${message}</p>`;
            input.value = "";
        }
    }
</script>
</body>
</html>
