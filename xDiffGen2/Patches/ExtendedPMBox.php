<?php
    function ExtendedPMBox($exe){
        if ($exe === true) {
            return new xPatch(22, 'Extended PM Box', 'UI', 0, 'Extend the PM chat box max input chars from 70 to 221.');
        }
        $code = "\xC7\x40\x54\x46";
        $offsets = $exe->code($code, "\xAB", 4);
        if (count($offsets) != 4) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offsets[2], array(3 => "\x58"));  // \xEA
        return true;
    }
?>