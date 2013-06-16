<?php
function SsoLogin($exe) {
    if ($exe === true) {
        return new xPatch(95, 'Use SSO Login Packet', '', 0, 'Enable using SSO packet on all langtype (to use login and pass with a launcher)');
    }
	
	$code =  "\xA1\xAB\xAB\xAB\xAB" // push    0FFFFh
			."\x85\xC0"
			."\x0F\x84\xAB\xAB\xAB\xAB"
			."\x83\xF8\x12"
			."\x0F\x84\xAB\xAB\xAB\xAB"
			."\x83\xF8\x0C"
			."\x0F\x84\xAB\xAB\xAB\xAB";
	
	$offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }

    $exe->replace($offset, array(7 => "\x90\xE9"));

    return true;
}
?>