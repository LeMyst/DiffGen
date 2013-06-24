<?php
	function RemoveGMSprite($exe)
	{
		if($exe === true)
			return new xPatch(96, 'Remove GM Sprites', 'UI', 0,
			'Remove the GM sprites and keeping all the functionality like yellow name and admin right click.');

		// 2 matches	                            
		if ($exe->clientdate() <= 20130605){
			$code =  "\x83\xC4\x04"					// add     esp, 4
					."\x84\xC0"						// test    al, al
					."\x0F\x84\xE3\x00\x00\x00";	// jz      loc_80F81E
		}
		else {
			$code =  "\x83\xC4\x04"					// add     esp, 4
					."\x84\xC0"						// test    al, al
					."\x0F\x84\x5B\x01\x00\x00";	// jz      loc_80F81E	
		}

		$offsets = $exe->matches($code, "\xAB", 0);
	
        if (count($offsets) != 2) {
            echo "Failed in part 1";
            return false;
        }
		
		if ($exe->clientdate() <= 20130605){
			$exe->replace($offsets[0], array(5 => "\xE9\xE4\x00\x00\x00\x90")); // jmp     loc_80F81E & nop
			$exe->replace($offsets[1], array(5 => "\xE9\xE4\x00\x00\x00\x90")); //
		}
		else {
			$exe->replace($offsets[0], array(5 => "\xE9\x5C\x01\x00\x00\x90")); // jmp     loc_80F81E & nop
			$exe->replace($offsets[1], array(5 => "\xE9\x5C\x01\x00\x00\x90")); //		
		}
		
		//It have to jump here : just count the offset number between both -5
		//8B 4C 24 14                                   mov     ecx, [esp+arg_4]
		// 51                                            push    ecx

		
	}
?>