<?php

$secret_seed = "3132333435363738393031323334353637383930";

$time_window = 30;

$exact_time = microtime(true);

$rounded_time = floor($exact_time/$time_window);

$packed_time = pack("N", $rounded_time);

$padded_packed_time = str_pad($packed_time,8, chr(0), STR_PAD_LEFT);

$packed_secret_seed = pack("H*", $secret_seed);

$hash = hash_hmac ('sha1', $padded_packed_time, $packed_secret_seed, true);

$offset = ord($hash[19]) & 0xf;
$otp = (
		((ord($hash[$offset+0]) & 0x7f) << 24 ) |
		((ord($hash[$offset+1]) & 0xff) << 16 ) |
		((ord($hash[$offset+2]) & 0xff) << 8 ) |
		(ord($hash[$offset+3]) & 0xff)
) % pow(10, 6);

$otp = str_pad($otp, 6, "0", STR_PAD_LEFT);

echo $otp;
