<?php
function ChatColorGM($exe) {
    if ($exe === true) {
        return new xPatch(52, 'GM Chat Color', 'Color', 0, 'Changes the GM Chat color and sets it to the specified value.');
    }

    $code =  "\x83\xC4\x1C"         // add     esp, 1Ch
            ."\x6A\x00"             // push    0
            ."\x6A\x00"             // push    0
            ."\x68\xFF\xFF\x00\x00" // push    0FFFFh ; ChatColor #RRGGBBAA
            ."\x8D\x4C\x24\x14";    // lea     ecx, [esp+118h+Dest]

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