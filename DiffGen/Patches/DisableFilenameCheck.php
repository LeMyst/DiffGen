<?php
    function DisableFilenameCheck($exe) {
		if ($exe === true) {
			return "[Fix]_Disable_RagexeRE_Filename_Check_(Recommended)";
		}
		$code =  "\xE8\xAB\xAB\xAB\xFF"        // call    sub_707420
                ."\x39\xAB\xAB\xAB\xAB\x00"    // cmp     Langtype, ebp
                ."\x75\x31"                    // jnz     short loc_73FE94
                ."\xE8\xAB\xAB\xFF\xFF"        // call    sub_73DFB0
                ."\x84\xC0";                   // test    al, al
		$offset = $exe->code($code, "\xAB");
		if ($offset === false) {
			echo "Failed in part 1";
			return false;
		}
		$exe->replace($offset, array(11 => "\xEB"));
		return true;
	}
?>