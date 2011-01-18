<?php
    function Disable4LetterUserIDLimit($exe) {
        if ($exe === true) {
            return new xPatch(11, 'Disable 4 Letter UserID Limit', 'Fix', 0, 'Will allow people to use account names shorter than 4 characters.');
        }
        $code =  "\xE8\xAB\xAB\xAB\xFF"            // call    <address>
                ."\x83\xAB\x04"                    // cmp     eax, 4
                ."\x0F\xAB\xAB\xAB\x00";           // jl      <location>
        $offset = $exe->matches($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        
        // 1st = CharacterLimit
        // 2nd = Password
        // 3rd = Unknown
        if(count($offset) < 2) {
          echo "Failed in part 2";
          return false;
        }
        
        // The UserID check comes right after password check, so start searching from this position..        
        $offset = $offset[1];
        $offset = $exe->match("\x83\xAB\x04", "\xAB", $offset+strlen($code));
        if($offset == false) {
          echo "Failed in part 3";
          return false;
        }

        $exe->replace($offset, array(2 => "\x00"));
        
        return true;
    }
?>