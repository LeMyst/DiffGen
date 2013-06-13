<?php
function ChatColorGuild($exe) {
    if ($exe === true) {
        return new xPatch(57, 'Guild Chat Color', 'Color', 0, 'Changes the Guild Chat color and sets it to the specified value. Default Value is b4ffb4 (a light green color)');
    }
	
	if ($exe->clientdate() <= 20130605) {
		$code =  "\x14\x53"                 // push    ebx
				."\x6A\x04"                 // push    4
				."\x68\xB4\xFF\xB4\x00";    // push    0B4FFB4h
		$type=0;
	}
	else {
		$code =  "\x53"                 	// push    ebx
				."\x6A\x04"                 // push    4
				."\x68\xB4\xFF\xB4\x00";    // push    0B4FFB4h	
		$type=1;
	}
          
    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }

    $exe->addInput('$guildChatColor', XTYPE_COLOR);
	if($type==0)
		$exe->replaceDword($offset, array(5 => '$guildChatColor'));
	else
		$exe->replaceDword($offset, array(4 => '$guildChatColor'));
		
    return true;
	
}
?>