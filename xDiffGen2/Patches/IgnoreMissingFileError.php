<?php
    function IgnoreMissingFileError($exe) {
        if ($exe === true) {
            return new xPatch(71, 'Ignore Missing File Error', 'Fix', 0, '');
        }
		
		if ($exe->clientdate() <= 20130605)
			$code =  "\xE8\xAB\xAB\xAB\xFF\x8B\x44\x24\x04\x8B\x0D\xAB\xAB\xAB\xAB\x6A\x00";
		else
			$code =  "\xE8\xAB\xAB\xAB\xFF\x8B\x45\x08\x8B\x0D\xAB\xAB\xAB\xAB\x6A\x00";
			
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }

		if ($exe->clientdate() <= 20130605)
			$exe->replace($offset, array(5 => "\x31\xC0\xC3\x90"));
		else
			$exe->replace($offset, array(5 => "\x31\xC0\x5D\xC3"));
		
        return true;
    }
?>