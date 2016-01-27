<?php
//TOTP seed (String representation)
$secret_seed = "3132333435363738393031323334353637383930";

//number of seconds of otp period
$time_window = 30;

//time formating to epoch
$exact_time = microtime(true);
$rounded_time = floor($exact_time/$time_window);

//binary represetation of time without padding
$packed_time = pack("N", $rounded_time);

//binary representation of time with padding
$padded_packed_time = str_pad($packed_time,8, chr(0), STR_PAD_LEFT);

//binary representation of seed
$packed_secret_seed = pack("H*", $secret_seed);

//HMAC SHA1 hash (time + seed)
$hash = hash_hmac ('sha1', $padded_packed_time, $packed_secret_seed, true);

$offset = ord($hash[19]) & 0xf;
$otp = (
		((ord($hash[$offset+0]) & 0x7f) << 24 ) |
		((ord($hash[$offset+1]) & 0xff) << 16 ) |
		((ord($hash[$offset+2]) & 0xff) << 8 ) |
		(ord($hash[$offset+3]) & 0xff)
) % pow(10, 6); 

//adding pad to otp, in order to assure a "6" digits
$otp = str_pad($otp, 6, "0", STR_PAD_LEFT);

echo $otp;

?>
