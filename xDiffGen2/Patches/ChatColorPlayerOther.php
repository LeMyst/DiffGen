<?php
function ChatColorPlayerOther($exe) {
    if ($exe === true) {
        return new xPatch(55, 'OtherPlayer Chat Color', 'Color', 0, 'Changes other players Chat color and sets it to the specified value.');
    }

    $code =  "\x74\x1A"              // jz      short loc_5E179C
            ."\x6A\x00"              // push    0
            ."\x6A\x01"              // push    1
            ."\x68\xFF\xFF\xFF\x00"; // push    0FFFFFFh

    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }

    $exe->addInput('$otherChatColor', XTYPE_COLOR);
    $exe->replaceDword($offset, array(7 => '$otherChatColor'));
    return true;
}
?>