<?php
function FixChatAt($exe) {
    if ($exe === true) {
        return new xPatch(64, '@ Bug Fix (Recommended)', 'UI', 0, 'Correct the bug to write @ in chat');
    }

	if ($exe->clientdate() <= 20130605)
		$code =  "\x46\x29\x00\x5F\x5E\x5D\xB0\x01"; // push    0FF00h
	else
		$code =  "\x46\x2D\x00\x5F\x5E\xB0\x01\x5B"; // push    0FF00h
          
    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }

    $exe->replace($offset, array(2 => "\x01"));
	
    return true;
}
?>