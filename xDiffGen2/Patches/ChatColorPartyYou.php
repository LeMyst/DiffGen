<?php
function ChatColorPartyYou($exe) {
    if ($exe === true) {
        return new xPatch(59, 'Your Party Chat Color', 'Color', 0, 'Changes Your Party Chat color and sets it to the specified value. Default value is ffc800 (An orange color)');
    }

    $code =  "\x75\x37"                 // jnz     short loc_5E826C
            ."\x6A\x03"                 // push    3
            ."\x68\xFF\xC8\x00\x00";    // push    0C8FFh (

    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }

    $exe->addInput('$yourpartyChatColor', XTYPE_COLOR);
    $exe->replaceDword($offset, array(5 => '$yourpartyChatColor'));
    return true;
}
?>