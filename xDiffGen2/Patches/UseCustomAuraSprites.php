<?php
    function UseCustomAuraSprites($exe){
        if ($exe === true) {
            return new xPatch(45, 'Use Custom Aura Sprites', 'Data', 0, 
"This option will make it so your warp portals will not be affected by your aura sprites.
If you enable this feature, you will have to make aurafloat.tga and auraring.bmp and
place them in your '.\\data\\texture\\effect' folder.

Enable this to used aurafloat.tga, auraring.bmp and freezing_circle.bmp as aura sprites.
The default aura files are pikapika2.bmp, blue_ring.tga and freezing_circle.bmp.");
        }
        $free = 0x380;
		
		if ($exe->clientdate() <= 20130605) {
			$code00 =  "\x68" . pack("I", $exe->str("effect\\ring_blue.tga","rva"))	// PUSH    "effect\ring_blue.tga"
					."\xFF\x15\xAB\xAB\xAB\xAB"                 					// CALL    NEAR DWORD PTR DS:[&MSVCP90.std::basic_string<char>::basic_string<char>]
					."\x89\xAB\xAB\xAB"												// MOV     [esp+44h], ebp
					."\xC7\x44\xAB\xAB\xAB\xAB\xAB\xAB"								// MOV     dword ptr [esp+44h], 0FFFFFFFFh
					."\x8B\xCE"														// MOV     ECX,ESI
					."\xE8\xAB\xAB\xAB\xAB"											// CALL    ADDR
					."\x8B\x57\xAB"													// MOV     EAX,DWORD PTR DS:[EDI+CONST]
					."\x56"															// PUSH    esi
					."\x8B\xCF"														// MOV     ecx, edi
					."\x89\xAB\xAB"													// mov     [esi+4], edx
					."\x89\xAB\xAB"													// mov     [esi+0Ch], ebx
					."\x89\xAB\xAB"													// mov     [esi+10h], ebp
					."\xC7\x46\xAB\xAB\xAB\xAB\xAB"									// mov     dword ptr [esi+8], 1
					."\xE8\xAB\xAB\xAB\xAB";										// call    sub_62A440
					
			$code01 = "\x68" . pack("I", $exe->str("effect\\pikapika2.bmp","rva"))	// PUSH    "effect\pikapika2.bmp"
					 ."\xFF\x15";													// CALL    NEAR DWORD PTR DS:[&MSVCP90.std::basic_string<char>::basic_string<char>]

		}
		else {
			$code00 =  "\x68" . pack("I", $exe->str("effect\\ring_blue.tga","rva"))	// PUSH    "effect\ring_blue.tga"
					."\xC6\x01\x00"                									// CALL    NEAR DWORD PTR DS:[&MSVCP90.std::basic_string<char>::basic_string<char>]
					."\xE8\xAB\xAB\xAB\xAB"											// MOV     [esp+44h], ebp
					."\xC7\x45\xAB\xAB\xAB\xAB\xAB"									// mov     [ebp+var_4], 0
					."\xC7\x45\xAB\xAB\xAB\xAB\xAB"									// mov     [ebp+var_4], 0FFFFFFFFh
					."\x8B\xCE"														// mov     ecx, esi
					."\xE8\xAB\xAB\xAB\xAB"											// call    sub_64CB00
					."\x8B\x57\x04"													// mov     edx, [edi+4]
					."\x56"															// PUSH    esi
					."\x8B\xCF"														// MOV     ecx, edi
					."\x89\xAB\xAB"													// mov     [esi+4], edx
					."\x89\xAB\xAB"													// mov     [esi+0Ch], ebx
					."\xC7\x46\xAB\xAB\xAB\xAB\xAB"									// mov     dword ptr [esi+10h], 0
					."\xC7\x46\xAB\xAB\xAB\xAB\xAB"									// mov     dword ptr [esi+8], 1
					."\xE8\xAB\xAB\xAB\xAB";										// call    sub_42AAE0	

			$code01 = "\x68" . pack("I", $exe->str("effect\\pikapika2.bmp","rva"))	// PUSH    "effect\pikapika2.bmp"
					 ."\xC6\xAB\xAB"												// mov     byte ptr [ecx], 0
					 ."\xE8\xAB\xAB\xAB\xAB";										// CALL    ADR
					
		}

        $offset00 = $exe->code($code00, "\xAB");
        if ($offset00 === false) {
            echo "Failed in part 1";
            return false;
        }
		
        $offset01 = $exe->code($code01, "\xAB");
        if ($offset01 === false) {
            echo "Failed in part 2";
            return false;
        }
		//$offset01b = $offset01 + 1 - $offset00;
		
        $exe->replace($offset00, array(1 => pack("I", ($exe->imagebase() + $free))));
		$exe->replace($offset01, array(1 => pack("I", ($exe->imagebase() + $free + 21))));
		
        $code =  "effect\aurafloat.tga\x00"
                ."effect\auraring.bmp\x00\x90";
        $exe->insert($code, $free);
		
        return true;
    }
?>