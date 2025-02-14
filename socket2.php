<?php


/**
 * Start tcp server for websocket via http request and terminal.
 */

$host = '127.0.0.1';
$port = 8000;

// Create a TCP Stream socket
$serverSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($serverSocket, SOL_SOCKET, SO_REUSEADDR, 1);

if (!socket_bind($serverSocket, $host, $port)) {
    die("Failed to bind socket\n");
}

socket_listen($serverSocket);
echo "WebSocket Server listening on ws://$host:$port...\n";

$clients = [];

while (true) {
    $readSockets = $clients;
    $readSockets[] = $serverSocket;
    socket_select($readSockets, $write, $except, 0);

    // Check for new client connections
    if (in_array($serverSocket, $readSockets)) {
        $clientSocket = socket_accept($serverSocket);
        $clients[] = $clientSocket;
        performWebSocketHandshake($clientSocket);
        echo "New client connected\n";
        unset($readSockets[array_search($serverSocket, $readSockets)]);
    }

    // Handle client messages
    foreach ($readSockets as $socket) {
        $message = socket_read($socket, 1024);
        if ($message === false) {
            unset($clients[array_search($socket, $clients)]);
            socket_close($socket);
            echo "Client disconnected\n";
            continue;
        }

        $decodedMessage = unmask($message);
        echo "Client says: $decodedMessage\n";

        // Respond back
        $responseMessage = "Server received: $decodedMessage";
        socket_write($socket, mask($responseMessage));
    }
}

// Perform WebSocket handshake
function performWebSocketHandshake($clientSocket)
{
    $request = socket_read($clientSocket, 1024);
    if (preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $request, $matches)) {
        $key = trim($matches[1]);
        $acceptKey = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        $headers = "HTTP/1.1 101 Switching Protocols\r\n" .
            "Upgrade: websocket\r\n" .
            "Connection: Upgrade\r\n" .
            "Sec-WebSocket-Accept: $acceptKey\r\n\r\n";
        socket_write($clientSocket, $headers);
    }
}

// WebSocket message encoding
function mask($text)
{
    $b1 = 0x81; // FIN, text frame
    $length = strlen($text);
    $header = chr($b1);

    if ($length <= 125) {
        $header .= chr($length);
    } elseif ($length >= 126 && $length <= 65535) {
        $header .= chr(126) . pack("n", $length);
    } else {
        $header .= chr(127) . pack("xxxxN", $length);
    }

    return $header . $text;
}

// WebSocket message decoding
function unmask($text)
{
    $length = ord($text[1]) & 127;

    if ($length == 126) {
        $masks = substr($text, 4, 4);
        $data = substr($text, 8);
    } elseif ($length == 127) {
        $masks = substr($text, 10, 4);
        $data = substr($text, 14);
    } else {
        $masks = substr($text, 2, 4);
        $data = substr($text, 6);
    }

    $decodedText = "";
    for ($i = 0; $i < strlen($data); ++$i) {
        $decodedText .= $data[$i] ^ $masks[$i % 4];
    }

    return $decodedText;
}
