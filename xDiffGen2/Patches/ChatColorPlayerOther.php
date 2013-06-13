<?php
function ChatColorPlayerOther($exe) {
    if ($exe === true) {
        return new xPatch(55, 'OtherPlayer Chat Color', 'Color', 0, 'Changes other players Chat color and sets it to the specified value. Default value is ffffff (a white color)');
    }
	if ($exe->clientdate() <= 20130605) {
		$code =  "\x74\x1A"              // jz      short loc_5E179C
				."\x6A\x00"              // push    0
				."\x6A\x01"              // push    1
				."\x68\xFF\xFF\xFF\x00"; // push    0FFFFFFh
		$type=0;	
	}
	else {
		$code =  "\x74\x15"              // jz      short loc_5E179C
				."\x53"              	 // push    ebx
				."\x6A\x01"              // push    1
				."\x68\xFF\xFF\xFF\x00"; // push    0FFFFFFh
		$type=1;	
	}
	
	$offset = $exe->match($code, "\xAB");

	if ($offset === false) {
		echo "Failed in part 1";
		return false;
	}

	$exe->addInput('$otherChatColor', XTYPE_COLOR);
	
	if($type==0)
		$exe->replaceDword($offset, array(7 => '$otherChatColor'));	
	else
		$exe->replaceDword($offset, array(6 => '$otherChatColor'));	

    return true;
}
?>