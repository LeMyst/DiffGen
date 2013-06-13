<?php
function ChatColorGM($exe) {
    if ($exe === true) {
        return new xPatch(52, 'GM Chat Color', 'Color', 0, 'Changes the GM Chat color and sets it to the specified value. Default value is ffff00 (a yellow color)');
    }
	
	if ($exe->clientdate() <= 20130605) {
		$code =  "\x68\xFF\xFF\x00\x00" // push    0FFFFh
				."\xEB\x43\x8B\x56\x04"; 
	}
	else {
		$code =  "\x68\xFF\xFF\x00\x00" // push    0FFFFh
				."\xEB\x40\x8B\x47\x04"; 
	}
	
	$offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }

    $exe->addInput('$gmChatColor', XTYPE_COLOR);
    $exe->replaceDword($offset, array(1 => '$gmChatColor'));

    return true;
}
?>