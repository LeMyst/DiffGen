<?php
    function AllowChatFlood25Lines($exe) {
        if ($exe === true) {
            return "[UI](1)_Allow_Chat_Flood_(25_lines)";
        }
        $code =  "\x83\x3D\xAB\xAB\xAB\xAB\x0A"    // cmp     Langtype, 10
                ."\x74\xAB"                        // jz      short loc_5CE560
                ."\x83\x7C\x24\x04\x02"            // cmp     [esp+arg_0], 2    ; <-- Patch
                ."\x7C\x47"                        // jl      short loc_5CE560
                ."\x6A\x00";                       // push    ebx
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(13 => "\x18"));
        return true;
    }
?>