<?php
function ChatColorGM($exe) {
    if ($exe === true) {
        return new xPatch(52, 'GM Chat Color', 'Color', 0, 'Changes the GM Chat color and sets it to the specified value.');
    }

    $code =  "\x68\xFF\xFF\x00\x00" // push    0FFFFh
            ."\x8D\x54\xAB\xAB"     // lea     edx, [esp+118h+Dst]
            ."\x52"                 // push    edx
            ."\xEB\x48";            // jmp     short loc_5E1790

    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }

    $exe->addInput('$gmChatColor', XTYPE_COLOR);
    $exe->replaceDword($offset, array(8 => '$gmChatColor'));

    return true;
}
?>