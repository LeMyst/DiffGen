<?php
    function UnlimitedChatFlood($exe) {
        if ($exe === true) {
            return "[UI](1)_Unlimited_Chat_Flood";
        }
        $code =  "\x83\xC4\x08"             // add     esp, 8
                ."\x84\xC0"                 // test    al, al
                ."\x74\x08"                 // jz      short loc_5DA6A6
                ."\xFF\xAB\xAB\xAB\x00\x00" // inc     dword ptr [ebp+498h]
                ."\xEB\x0A";                // jmp     short loc_5DA6B0
        $offsets = $exe->code($code, "\xAB", 4);
        if ($offsets === false) {
            echo "Failed in part 1";
            return false;
        }
        foreach ($offsets as $offset) {
            $exe->replace($offset, array(5 => "\xEB"));
        }
        return true;
    }
?>