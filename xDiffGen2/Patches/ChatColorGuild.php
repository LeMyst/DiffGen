<?php
function ChatColorGuild($exe) {
    if ($exe === true) {
        return new xPatch(57, 'Guild Chat Color', 'Color', 0, 'Changes the Guild Chat color and sets it to the specified value. Default Value is b4ffb4 (a light green color)');
    }

    $code =  "\x14\x53"                 // 
            ."\x6A\x04"                 // push    4
            ."\x68\xB4\xFF\xB4\x00";    // push    0B4FFB4h
          
    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }

    $exe->addInput('$guildChatColor', XTYPE_COLOR);
    $exe->replaceDword($offset, array(5 => '$guildChatColor'));
    return true;
}
?>