<?php
    function UseArialOnAllLangtypes($exe) {
        if ($exe === true) {
            return "[UI](9)_Use_Arial_on_All_Langtypes";
        }
        $code =  "\x83\xFA\x0A"                 // cmp     edx, 0Ah
                ."\x0F\x87\xAB\x00\x00\x00"     // ja      loc_40899B
                ."\xFF\x24\x95\xAB\xAB\xAB\xAB" // jmp     ds:off_4089E0[edx*4] ; switch jump
                ."\xA1\xAB\xAB\xAB\xAB"         // mov     eax, Langtype
                ."\x83\xF8\x06";                // cmp     eax, 6
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(3 => "\xEB\x10"));
        return true;
    }
?>