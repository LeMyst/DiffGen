<?php
function Enable64kHairstyle($exe) {
    if ($exe === true) {
        return new xPatch(68, 'Enable 64k Hairstyle', 'UI', 0, 'Enable 64k hairstyle instead 27 by default');
    }
	
    $code =  "\xC0\xCE\xB0\xA3\xC1\xB7\x5C\xB8\xD3\xB8\xAE\xC5\xEB"; // After it must have \\%s\\%s_%s.%s
	
    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }
	
	$exe->replace($offset, array(18 => "\x75")); // %s -> %u
	
	// \\%s\%s_%s.%s
	//$push_var = pack("I", $exe->str("\xC0\xCE\xB0\xA3\xC1\xB7\x5C\xB8\xD3\xB8\xAE\xC5\xEB\x5C\x25\x73\x5C\x25\x73\x5F\x25\x73\x2E\x25\x73","rva"));
	//echo bin2hex($push_var) . " ";

	// Update the parameter PUSHed to be the hair style ID
	// itself rather than the string obtained from hard-coded
	// table. Note, that this will mess up existing hair-style
	// IDs 0..12. Also the 2nd and 3rd patch block ensures, that
	// ID 0 (invalid) is mapped to 2, as the table would do.
	
	if ($exe->clientdate() <= 20130605) {	
		$code =   "\x8B\x4C\x24\xAB"  	// mov     ecx, [esp-50h+arg_84]
				 ."\x73\x04" 		  	// jnb     short loc_67168D
				 ."\x8D\x4C\x24\xAB"	// lea     ecx, [esp-50h+arg_84]
				 ."\x83\xFE\x10";		// cmp     eax, 10h
		$type=0;
	}
	else {
		$code =   "\x83\x7D\xAB\xAB"	// cmp     [ebp+var_18], 10h 
				 ."\x8B\x4D\xD4"  		// mov     ecx, [esp-50h+arg_84]
				 ."\x73\x03" 		  	// jnb     short loc_67168D
				 ."\x8D\x4D\xD4"		// lea     ecx, [esp-50h+arg_84]
				 ."\x83\xF8\x10";		// cmp     eax, 10h
		$type=1;
	}
	
    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 2";
        return false;
    }
	
	if($type==0){
		$exe->replace($offset, array(1 => "\x4D\x00\x90"));			 // -> MOV     ECX,DWORD PTR SS:[EBP]
		$exe->replace($offset, array(4 => "\x85\xC9")); 			 // -> TEST    ECX,ECX
		$exe->replace($offset, array(6 => "\x75\x02\x41\x41")); 	 // -> JNZ     SHORT ADDR v & -> INC     ECX x2
	}
	else
		$exe->replace($offset, array(0 => "\x8B\x4D\x18\x8B\x09\x85\xC9\x75\x02\x41\x41\x90"));			 // -> MOV     ECX,DWORD PTR SS:[EBP]

	// Void table lookup.

	if ($type==0) {
		$code =  "\x8B\x45\x00"  // MOV     EAX,DWORD PTR SS:[EBP]
				."\x8B\x14\x81"; // MOV     EDX,DWORD PTR DS:[ECX+EAX*4]
	}
	else {
		$code =  "\x75\x19"
				."\x8B\x0E"
				."\x8B\x15\xAB\xAB\xAB\x00"  		// MOV     EDX,DWORD PTR SS:[EBP]
				."\x8B\x14\x8A";					// MOV     EDX,DWORD PTR DS:[EDX+ECX*4]
		/*
		$code =  "\x75\x23"
				."\x8B\x06"
				."\x8B\x0D\xAB\xAB\xAB\x00"    // MOV     EDX,DWORD PTR SS:[EBP]
				."\x8B\x14\x81";     // MOV     EDX,DWORD PTR DS:[EDX+ECX*4]
		*/
	}
	
    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 3";
        return false;
    }
	
	if ($type==0)
		$exe->replace($offset, array(4 => "\x11\x90")); // -> MOV     EDX,DWORD PTR DS:[ECX]
	else
		$exe->replace($offset, array(11 => "\x12\x90")); // -> MOV     EDX,DWORD PTR DS:[EDX]
		
		
	if($type==1){
		$code =  "\x75\x23"
					."\x8B\x06"
					."\x8B\x0D\xAB\xAB\xAB\x00"    // MOV     EDX,DWORD PTR SS:[EBP]
					."\x8B\x14\x81";     // MOV     EDX,DWORD PTR DS:[EDX+ECX*4]
					
		$offset = $exe->match($code, "\xAB");

		if ($offset === false) {
			echo "Failed in part 3";
			return false;
		}
		
		$exe->replace($offset, array(11 => "\x12\x90")); // -> MOV     EDX,DWORD PTR DS:[EDX]
	}
		
	// Lift limit that protects table from invalid access. We
	// keep the < 0 check, since lifting it would not give any
	// benefits.

	if ($type==0) {	
		$code =  "\x7C\x05"  						// JL      SHORT ADDR v
				."\x83\xF8\xAB" 					// CMP     EAX,X
				."\x7E\x07"							// JLE     SHORT ADDR v
				."\xC7\x45\x00\x0D\x00\x00\x00";	// MOV     DWORD PTR SS:[EBP],0Dh
	}
	else {
		$code =  "\x7C\x05"  						// JL      SHORT ADDR v
				."\x83\xF8\xAB" 					// CMP     EAX,X
				."\x7E\x06"							// JLE     SHORT ADDR v
				."\xC7\x06\x0D\x00\x00\x00";		// MOV     DWORD PTR SS:[ESI],0Dh	
	}
	
    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 4";
        return false;
    }
	
	$exe->replace($offset, array(5 => "\xEB")); // -> MOV     EDX,DWORD PTR DS:[ECX]
	
    return true;
}
?>