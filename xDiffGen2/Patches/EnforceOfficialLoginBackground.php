<?php

    function EnforceOfficialLoginBackground($exe) {
        if ($exe === true) {
            return new xPatch(76, 'Enforce Official Login Background', 'UI', 0, 'Enforce Official Login Background for all langtype');
        }
        
		$code ="\x74\xAB\x83\xF8\x04\x74\xAB\x83\xF8\x08\x74\xAB\x83\xF8\x09\x74\xAB\x83\xF8\xAB\x74\xAB\x83\xF8\x03";

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
		$exe->replace($offsets[0], array(0 => "\xEB"));  // XOR AL,AL
		
        return true;
		
    }
?>