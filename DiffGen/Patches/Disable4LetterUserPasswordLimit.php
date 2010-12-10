<?php
    function Disable4LetterUserPasswordLimit($exe) {
        if ($exe === true) {
            return "[Fix]_Disable_4_Letter_UserPassword_Limit";
        }
        $code =  "\xE8\xAB\xAB\xAB\xFF"            // call    <address>
                ."\x83\xAB\x04"                    // cmp     eax, 4
                ."\x0F\xAB\xAB\xAB\x00";       		 // jl      <location>
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
        
        $offset = $offset[1];

        $exe->replace($offset, array(7 => "\x00"));
        
        return true;
    }
?>