<?php
function ChatColorPartyOther($exe) {
    if ($exe === true) {
        return new xPatch(58, 'Other Party Chat Color', 'Color', 0, 'Changes the Other Party members Chat color and sets it to the specified value. Default value is ffc8c8 (a pinkish color)');
    }

    $code =  "\x6A\x00"                 // push    0
            ."\x6A\x0F"                 // push    15
            ."\x68\xFF\xC8\xC8\x00";    // push    0C8C8FFh (a pinkish color)

    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }

    $exe->addInput('$otherpartyChatColor', XTYPE_COLOR);
    $exe->replaceDword($offset, array(5 => '$otherpartyChatColor'));
    return true;
}
?>