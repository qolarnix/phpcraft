<?php declare(strict_types=1);

require 'vendor/autoload.php';
require 'plugins.php';

use React\Socket\TcpServer as Server;
use React\Socket\ConnectionInterface as Connection;

$server_name = "PHPCraft";
$server_port = 25565;
$server_slots = 100;

$server = new Server('192.168.1.39:'.$server_port);
echo "Started server on: ".$server->getAddress()."\n";

$server->on('connection', function(Connection $conn) {
    echo "New request from: ".$conn->getRemoteAddress()."\n";

    $handshake_packets = 
        pack('C', 0x00) .
        pack('c', varint_encode(762)) . // VarInt
        pack('a', '192.168.1.39') . // String (255)
        pack('n', 25565) . // Unsigned Short
        pack('c', varint_encode(1)); // VarInt Enum

    $conn->write(pack('C', $handshake_packets));

    $conn->on('data', function($data) {
        echo trim($data)."\n";
    });

});