<?php
    function SkipResurrectionButtons($exe) {
        if ($exe === true) {
            return "[UI]_Skip_Resurrection_Buttons";
        }
        // Simply change the 'Token of Siegfried' ID to 0xFFFF - way easier.
        $ptr = $exe->code("\x68\xC5\x1D\x00\x00", "\xAB", 1);
        if( !$ptr ) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($ptr, array(1 => "\xFF\xFF"));
        return true;
    }
?>