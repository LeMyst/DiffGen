<?php
    function ExtendedChatBox($exe){
        if ($exe === true) {
            return new xPatch(20, 'Extended Chat Box', 'UI');
        }
        $code = "\xC7\x40\x54\x46";
        $offsets = $exe->code($code, "\xAB", 4);
        if (count($offsets) != 4) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offsets[3], array(3 => "\x58"));  // \xEA
        return true;
    }
?>