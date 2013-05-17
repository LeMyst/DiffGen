<?php
function ChatColorMain($exe) {
    if ($exe === true) {
        return new xPatch(56, 'Main Chat Color', 'Color', 0, 'Changes the Main Chat color and sets it to the specified value.');
    }

	// To find ZC_Notify_Chat : "68 FF 8D 1D 00";  // PUSH 1D8DFFh (orange)
	
    $code =  "\x68\xFF\xFF\x00\x00" // push    0FFFFh
            ."\x8B\x4C\x24\x20\x51"; 
          
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