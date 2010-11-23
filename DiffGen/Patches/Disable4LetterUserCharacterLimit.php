<?php
    function Disable4LetterUserCharacterLimit($exe) {
        if ($exe === true) {
            return "[Fix]_Disable_4_Letter_UserCharacter_Limit";
        }
        $code =  "\xE8\x1D\xF9\xFD\xFF"            // call    sub_44C7B0
                ."\x83\xF8\x04"                    // cmp     eax, 4
                ."\x0F\x8C\x39\x02\x00\x00";       // jl      loc_46D0D5
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(7 => "\x00"));
        return true;
    }
?>