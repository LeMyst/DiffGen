<?php
    function AllowMultipleWindows($exe) {
        if ($exe === true) {
            return "[Fix](13)_Allow_Multiple_Windows";
        }
        $code =  "\x0F\x84\xAB\x00\x00\x00"        // jz      loc_73D976
                ."\x83\xF8\x03"                    // cmp     eax, 3
                ."\x0F\x84\xAB\x00\x00\x00"        // jz      loc_73D976
                ."\x83\xF8\x05";                   // cmp     eax, 5
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 4";
            return false;
        }
        $exe->replace($offset, array(0 => "\x90\xE9"));
        return true;
    }
?>