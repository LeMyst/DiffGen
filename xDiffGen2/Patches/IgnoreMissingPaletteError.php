<?php
    function IgnoreMissingPaletteError($exe) {
        if ($exe === true) {
            return new xPatch(72, 'Ignore Missing Palette Error', 'Fix', 0, '');
        }
		
		if ($exe->clientdate() <= 20130605)	
			$code =  "\xE8\xAB\xAB\xAB\x00\x84\xC0\x0F\x85\xAC\x00\x00\x00\x56";
		else 
			$code =  "\xE8\xAB\xAB\xAB\x00\x84\xC0\x0F\x85\x30\x01\x00\x00\xBF";
			
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }

        $exe->replace($offset, array(7 => "\x90\xE9"));
		
        return true;
    }
?>