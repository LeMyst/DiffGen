<?php
function ChatColorPlayerYou($exe) {
    if ($exe === true) {
        return new xPatch(60, 'Your Player Chat Color', 'Color', 0, 'Changes your players Chat color and sets it to the specified value. Default value is 00ff00 (a green color)');
    }
	
	if ($exe->clientdate() <= 20130605) {
		$code =  "\x1B\xC0"              // jz      short loc_5E179C
				."\x23\xC1"               // push    1
				."\x68\x00\xFF\x00\x00"; // push    0FF00h
	}
	else {
		$code =  "\x6A\x01"              // jz      short loc_5E179C
				."\x1B\xC0"              // push    1
				."\x68\x00\xFF\x00\x00"; // push    0FF00h	
	}
          
    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }

    $exe->addInput('$yourChatColor', XTYPE_COLOR);
    $exe->replaceDword($offset, array(5 => '$yourChatColor'));
    return true;
}
?>