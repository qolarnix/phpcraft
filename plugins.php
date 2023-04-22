<?php declare(strict_types=1);

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