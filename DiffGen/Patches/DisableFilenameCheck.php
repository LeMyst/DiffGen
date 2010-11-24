<?php
    function DisableFilenameCheck($exe) {
		if ($exe === true) {
			return "[Fix]_Disable_RagexeRE_Filename_Check_(Recommended)";
		}
		$code =  "\x33\xFF"                    // xor     edi, edi
                ."\x39\x3D\xAB\xAB\xAB\x00"    // cmp     Langtype, edi
                ."\x75\x31"                    // jnz     short loc_73FE94
                ."\xE8\xAB\xAB\xFF\xFF"        // call    sub_73DFB0
                ."\x84\xC0";                   // test    al, al
		$offset = $exe->code($code, "\xAB");
		if ($offset === false) {
			echo "Failed in part 1";
			return false;
		}
		$exe->replace($offset, array(8 => "\xEB"));
		return true;
	}
?>