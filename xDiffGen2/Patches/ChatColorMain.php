<?php
function ChatColorMain($exe) {
    if ($exe === true) {
        return new xPatch(56, 'Main Chat Color', 'Color', 0, 'Changes the Main Chat color and sets it to the specified value.');
    }

    $code =  "\x68\xFF\xFF\x00\x00" // push    0FFFFh
            ."\x8D\x54\xAB\xAB"     // lea     edx, [esp+118h+Dst]
            ."\x52"                 // push    edx
            ."\xEB\x40";            // jmp     
          
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