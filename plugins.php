<?php declare(strict_types=1);

/**
 * Dealing with VarInt in PHP
 */
function varint_encode(int $val): string {
    $buf = '';

    while($val >= 0x80) {
        $buf .= chr($val | 0x80);
        $val >>= 7;
    }
    $buf .= chr($val);

    return $buf;
}

// echo varint_encode(762);
// echo bin2hex(varint_encode(762));

function read($data) {
    $clean = '';

    for($i = 0; $i < $data; $i++) {
        echo hex2bin($data);
    }
}