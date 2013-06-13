<?php
function ChatColorMain($exe) {
    if ($exe === true) {
        return new xPatch(56, 'Main Chat Color', 'Color', 0, 'Changes the Main Chat color and sets it to the specified value.');
    }

	// To find ZC_Notify_Chat : "68 FF 8D 1D 00";  // PUSH 1D8DFFh (orange)
	
	if ($exe->clientdate() <= 20130605) {
		$code =  "\x68\xFF\xFF\xFF\x00" // push    0FFFFh
				."\x8B\x4C\xAB\xAB"     // lea     edx, [esp+118h+Dst]
				."\x51"                 // push    edx
				."\x6A\x01";            // jmp     short loc_5E1790
	}
	else {
		$code =  "\x68\xFF\xFF\xFF\x00" 	// push    0FFFFh
				."\xB9\x98\xAB\xAB\x00"		// lea     edx, [ebp+var_104]
				."\x56"                	 	// push    edx
				."\x6A\x01";            	// jmp     short loc_5E1790	
	}

    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }

    $exe->addInput('$mainChatColor', XTYPE_COLOR);
    $exe->replaceDword($offset, array(1 => '$mainChatColor'));
    return true;
}
?>