<?php
    function IgnoreMissingPaletteError($exe) {
        if ($exe === true) {
            return new xPatch(72, 'Ignore Missing Palette Error', 'Fix', 0, '');
        }
        $code =  "\xE8\xAB\xAB\xAB\x00\x84\xC0\x0F\x85\xAC\x00\x00\x00\x56";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }

        $exe->replace($offset, array(5 => "\x90\xE9"));
        return true;
    }
?>