# PHP WebSocket Server

This project provides a simple WebSocket server implemented in PHP, along with a basic JavaScript client. 
It demonstrates real-time bidirectional communication using PHP sockets and the WebSocket API in JavaScript.

## Features
- WebSocket server in PHP
- WebSocket client in JavaScript
- Supports multiple client connections
- Implements WebSocket handshake
- Encodes and decodes WebSocket frames
- Broadcasts messages to all connected clients

## Requirements
- PHP 7.4+ (with socket extension enabled)
- A web browser with WebSocket support (e.g., Chrome, Firefox, Edge)

## Installation
1. Clone the repository or download the ZIP file:
   ```sh
   git clone https://github.com/drupalerSV/websocket-with-php-sockets.git php-websocket
   cd php-websocket
   ```
2. Start the WebSocket server:
   ```sh
   php socket2.php
   ```
3. Open `client.php` in a browser to test the WebSocket connection.

## Usage
### Running the WebSocket Server
To start the WebSocket server, run the following command:
```sh
php socket2.php
```
The server will start listening on `ws://127.0.0.1:8000`.

### Using the WebSocket Client
1. Open `client.php` in a browser.
2. It will establish a WebSocket connection to the server.
3. You can send messages using the input field.
4. Messages from the server will appear in the chat window.

### JavaScript Client Example
If you want to integrate the WebSocket client into another project, you can use the following JavaScript snippet:
```javascript
const socket = new WebSocket("ws://127.0.0.1:8000");

socket.onopen = () => {
    console.log("Connected to WebSocket server");
    socket.send("Hello Server!");
};

socket.onmessage = (event) => {
    console.log("Message from server:", event.data);
};

socket.onclose = () => {
    console.log("Connection closed");
};
```

## File Structure
```
php-websocket/
│── client.php          # WebSocket client UI
│── socket2.php         # WebSocket server in PHP
│── index.php           # Entry point (optional)
│── README.md           # Documentation
│── images/             # Screenshots and illustrations
```

## Troubleshooting
- If the server does not start, ensure the socket extension is enabled in `php.ini`:
  ```ini
  extension=sockets
  ```
- If the connection fails, check if port 8000 is open and not used by another application.
- Use browser developer tools (`F12 > Console`) to debug WebSocket messages.

## License
This project is open-source and available under the MIT License.

## Author
Developed by **[Chance Nyasulu](https://github.com/CHANCENY)**. Contributions are welcome!

## Contribution
If you'd like to improve this project, feel free to fork and submit a pull request.

