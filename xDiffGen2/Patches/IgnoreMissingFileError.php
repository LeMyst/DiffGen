<?php
    function IgnoreMissingFileError($exe) {
        if ($exe === true) {
            return new xPatch(71, 'Ignore Missing File Error', 'Fix', 0, '');
        }
        $code =  "\xE8\xAB\xAB\xAB\xFF\x8B\x44\x24\x04\x8B\x0D\xAB\xAB\xAB\xAB\x6A\x00";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }

        $exe->replace($offset, array(5 => "\x31\xC0\xC3\x90"));
        return true;
    }
?>