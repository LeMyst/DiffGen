<?php
    function DisableSwearFilter($exe) {
        if ($exe === true) {
            return new xPatch(16, 'Disable Swear Filter', 'UI');
        }
        
        // Shinryo: It's better to use a generic approach
        // as some calls to IsBadSentence can not be found.
        // Else it would be a huge mess to ensure that every location
        // is correctly found.
        $code =  "\x8B\x44\x24\x04"     // MOV EAX,DWORD PTR SS:[ESP+4]
                ."\x50"                 // PUSH EAX
                ."\xE8\xAB\xAB\xFF\xFF" // CALL <address>
                ."\x33\xC9"             // XOR ECX,ECX
                ."\x84\xC0"             // TEST AL,AL
                ."\x0F\x94\xC1"         // SETE CL
                ."\x8A\xC1"             // MOV AL,CL
                ."\xC2\x04\x00";        // RETN 4

        $offsets = $exe->matches($code, "\xAB");
        if ($offsets === false) {
            echo "Failed in part 1";
            return false;
        }
        
        if(count($offsets) != 2) {
            echo "Failed in part 2";
            return false;
        }

        // The first one is the correct one.
        $exe->replace($offsets[0], array(17 => "\x30\xC0"));  // XOR AL,AL

        return true;
    }
?>