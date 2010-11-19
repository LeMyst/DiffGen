<?php
	function DisableSwearFilter($exe) {
		if ($exe === true) {
			return "[UI]_Disable_Swear_Filter";
		}
		$code =  "\x52"					// push    ecx
				."\xB9\xAB\xAB\xAB\xAB"	// mov     ecx, offset unk_8136A0
				."\xE8\xAB\xAB\xAB\xAB"	// call    CInsultFilter__IsBadSentence
				."\x84\xC0"				// test    al, al
				."\x74\x4B"				// jz      short loc_5C0D20
				."\x6A\x00"				// push    0
				."\x6A\x00"				// push    0
				."\x6A\x00"				// push    0
				."\x6A\x00"				// push    0
				."\x6A\x03";			// push    3
		$offsets = $exe->code($code, "\xAB", -1);
		if ($offsets === false) {
			echo "Failed in part 1";
			return false;
		}
		foreach ($offsets as $offset) {
			$exe->replace($offset, array(13 => "\xEB"));
		}
		return true;
	}
?>