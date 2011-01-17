<?php
    function Disable1rag1N1sak1($exe) {
        if ($exe === true) {
            return "[Fix]_Disable_1rag1_&_1sak1_(Recommended)";
        }
        $rag1 = pack("I", $exe->str("1rag1","rva"));
        $code =  "\x68" . $rag1     // push    offset a1rag1   ; "1rag1"
                ."\x55"             // push    ebp             ; Str
                ."\xFF\xAB"         // call    esi ; strstr
                ."\x83\xAB\xAB"     // add     esp, 8
                ."\x85\xAB"         // test    eax, eax
                ."\x75\xAB";        // jnz     short loc_723E28
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(13 => "\xEB"));
        return true;
    }
?>