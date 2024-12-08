<?php

namespace App\WebSocket;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler;

class WsHandler extends WebSocketHandler implements MessageComponentInterface {
    public function onOpen(ConnectionInterface $connection) {
        // Connection is opened
        $connection->send("Welcome");
    }

    public function onClose(ConnectionInterface $connection) {
        // Connection is closed
    }

    public function onMessage(ConnectionInterface $connection, $payload) {
        // New message is received
    }
}