<?php
	function Disable1rag1N1sak1($exe) {
		if ($exe === true) {
			return "[Fix]_Disable_1rag1_&_1sak1_(Recommended)";
		}
		$code =  "\x68\xAB\xAB\xAB\x00"		// push    offset byte_898286 ; Str
				."\xFF\xD6"					// call    esi ; strstr
				."\x83\xC4\x08"				// add     esp, 8
				."\x85\xC0"					// test    eax, eax
				."\x75\xAB"					// jnz     short loc_73D643 (patch JNZ to JMP)
				."\x68\xAB\xAB\xAB\x00"		// push    offset aEvent   ; "Event"
				."\x68\xAB\xAB\xAB\x00"		// push    offset byte_898286 ; Str
				."\xFF\xD6"					// call    esi ; strstr
				."\x83\xC4\x08";			// add     esp, 8
		$offset = $exe->code($code, "\xAB");
		if ($offset === false) {
			echo "Failed in part 1";
			return false;
		}
		$exe->replace($offset, array(12 => "\xEB"));
		return true;
	}
?>