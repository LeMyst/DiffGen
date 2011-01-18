<?php
    function ExtendedChatRoomBox($exe){
        if ($exe === true) {
            return new xPatch(21, 'Extended Chat Room Box', 'UI', 0, 'Extend the chat room box max input chars from 70 to 234.');
        }
        $code = "\xC7\x40\x54\x46";
        $offsets = $exe->code($code, "\xAB", 4);
        if (count($offsets) != 4) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offsets[0], array(3 => "\x58"));  // \xEA
        return true;
    }
?>