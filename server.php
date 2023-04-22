<?php declare(strict_types=1);

require 'vendor/autoload.php';
require 'plugins.php';

use React\Socket\TcpServer as Server;
use React\Socket\ConnectionInterface as Connection;

$server_name = "PHPCraft";
$server_motd = "Hello from PHP";
$server_port = 25565;
$server_slots = 100;

$server_status = array(
    "version" => array(
        "name" => "1.19.4",
        "protocol" => 762,
    ),
    "players" => array(
        "max" => $server_slots,
        "online" => 0,
    ),
    "description" => array(
        "text" => $server_motd
    ),
    "favicon" => "data:image/png;base64,<data>",
    "enforcesSecureChat" => true
);
$server_status_json = json_encode($server_status);

$server = new Server('192.168.0.46:'.$server_port);
echo "Started server on: ".$server->getAddress()."\n";

$server->on('connection', function(Connection $conn) use($server_status_json) {
    echo "New request from: ".$conn->getRemoteAddress()."\n";

    /**
     * Handshake
     */
    $handshake_packets = 
        pack('C', 0x00) .
        pack('c', varint_encode(762)) . // VarInt
        pack('a', '192.168.0.46') . // String (255)
        pack('n', 25565) . // Unsigned Short
        pack('c', varint_encode(1)); // VarInt Enum
    $conn->write(pack('C', $handshake_packets));

    $conn->on('data', function($data) use($conn, $server_status_json) {
        
        // if(strpos($data, "\x00") !== false) {
        //     echo $data;
        // }

        $server_ping_packets =
            pack('C', 0x01) .
            pack('l', 1);
        $conn->write(pack('C', $server_ping_packets));

        $server_status_packets = 
            pack('C', 0x00) .
            pack('a', $server_status_json);
        $conn->write(pack('C', $server_status_packets));
    });

});