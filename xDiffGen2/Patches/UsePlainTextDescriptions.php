<?php
    function UsePlainTextDescriptions($exe) {
        if ($exe === true) {
            return new xPatch(48, 'Use Plain Text Descriptions', 'Data');
        }
        
        $code =  "\x75\x54"             // jnz     short loc_58CADD
                ."\x56"                 // push    esi
                ."\x57"                 // push    edi
                ."\x8B\x7C\x24\x0C";    // mov     edi, [esp+8+arg_0]
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        
        $exe->replace($offset, array(0 => "\xEB"));
        return true;
    }
?>