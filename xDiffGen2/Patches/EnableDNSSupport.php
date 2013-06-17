<?php

    function EnableDNSSupport($exe){
        if ($exe === true) {
            return new xPatch(90, 'EnableDNSSupport', 'UI', 0, 
"Enable DNS support for clientinfo.xml");
        }
        
        $code =  "\xE8\xAB\xAB\xAB\xFF\x8B\xC8\xE8\xAB\xAB\xAB\xFF\x50\xB9\xAB\xAB\xAB\x00\xE8\xAB\xAB\xAB\xFF\xA1";

        $offset = $exe->code($code, "\xAB");
		if ($offset === false) {
			echo "Failed in part 1";
			return false;
		}
		
        $offsetRVA = $exe->Raw2Rva($offset) + $exe->read($offset + 1, 4, "I") + 5;
        
        $codef =  
			// Call Unknown Function - Pos = 1
			"\xE8"."CA00"								// CALL UnknownCall
			."\x60"										// PUSHAD
			// Pointer of old address - Pos = 8
			."\x8B\x35"."CA01"							// MOV ESI,DWORD PTR DS:[7F8320]            ; ASCII "127.0.0.1"
			."\x56"										// PUSH ESI
			// Call to gethostbyname - Pos = 15
			."\xFF\x15"."CA02"							// CALL DWORD PTR DS:[<&WS2_32.#52>]
			."\x8B\x48\x0C"								// MOV ECX,DWORD PTR DS:[EAX+0C]
			."\x8B\x11"									// MOV EDX,DWORD PTR DS:[ECX]
			."\x89\xD0"									// MOV EAX,EDX
			."\x0F\xB6\x48\x03"							// MOVZX ECX,BYTE PTR DS:[EAX+3]
			."\x51"										// PUSH ECX
			."\x0F\xB6\x48\x02"							// MOVZX ECX,BYTE PTR DS:[EAX+2]
			."\x51"										// PUSH ECX
			."\x0F\xB6\x48\x01"							// MOVZX ECX,BYTE PTR DS:[EAX+1]
			."\x51"										// PUSH ECX
			."\x0F\xB6\x08"								// MOVZX ECX,BYTE PTR DS:[EAX]
			."\x51"										// PUSH ECX
			// IP scheme offset - Pos = 46
			."\x68"."CA03"								// PUSH OFFSET 007B001C                     ; ASCII "%d.%d.%d.%d"
			// Pointer to new address Pos = 51
			."\x68"."CA04"								// PUSH OFFSET 008A077C                     ; ASCII "127.0.0.1"
			// Call to sprintf - Pos = 57
			."\xFF\x15"."CA05"							// CALL DWORD PTR DS:[<&MSVCR90.sprintf>]
			."\x83\xC4\x18"								// ADD ESP,18
			// Replace old ptr with new ptr
			// Old Ptr - Pos = 66
			// New Ptr - Pos = 70
			."\xC7\x05"."CA06"."CA07"					// MOV DWORD PTR DS:[7F8320],OFFSET 008A07C ; ASCII "127.0.0.1"
			."\x61"										// POPAD
			."\xC3";									// RETN
			                    
        // Calculate free space that the code will need.
        $size = strlen($code);
        
        // Find free space to inject our data.ini load function.
        // Note that for the time beeing those will be probably
        // return some space in .rsrc, but that's still okay
        // until our new diff patcher is finished for our own section.
        $free = $exe->zeroed(247 + 4 + 4 + $size + 4 + 16, false); // Free space of enable multiple grf + space for dns support
        if ($free === false) {
            echo "Failed in part 2";
            return false;
        }
		$free += 247 + 4 + 4;
        
        // Create a call to the free space that was found before.     
        $exe->replace($offset, array(0 =>  "\xE8".pack("I", $exe->Raw2Rva($free))));
		$uRvaFreeOffset = $exe->Raw2Rva($free) - $exe->Raw2Rva($offset) - 5 + 2 + 16 ;

        /************************************************************************/
		/* Find old ptr.
		/************************************************************************/
        
		if ($exe->clientdate() <= 20130605)
			$code =  "\xA3\xAB\xAB\xAB\x00\xEB\x0F\x83\xC0\x04\xA3\xAB\xAB\xAB\x00\xEB\x05";
		else
			$code =  "\x8B\x00\xA3\xAB\xAB\xAB\x00\x68\xAB\xAB\xAB\x00\x8B\xCB\xE8\xAB\xAB\xAB\x00\x85\xC0\x74\x1B";

        $offset = $exe->code($code, "\xAB");
		if ($offset === false) {
			echo "Failed in part 3";
			return false;
		}
		if ($exe->clientdate() <= 20130605)
			$uOldptr = $exe->read($offset + 1, 4, "I");
		else
			$uOldptr = $exe->read($offset + 3, 4, "I");
        
        /************************************************************************/
		/* Find gethostbyname().
		/************************************************************************/

		if ($exe->clientdate() <= 20130605)
			$code = "\xFF\x15\xAB\xAB\xAB\x00\x85\xC0\x75\x29\x8B\xAB\xAB\xAB\xAB\x00";
		else
			$code = "\xFF\x15\xAB\xAB\xAB\x00\x85\xC0\x75\x2B\x8B\xAB\xAB\xAB\xAB\x00";

        $offset = $exe->code($code, "\xAB");
		if ($offset === false) {
			$code =  "\xE8\xAB\xAB\xAB\x00\x85\xC0\x75\x35\x8B\xAB\xAB\xAB\xAB\x00";
			$offset = $exe->code($code, "\xAB");
			if ($offset === false) {
				echo "Failed in part 4";
				return false;
			}
			else {
				$offset = $exe->Raw2Rva($offset) + $exe->read($offset + 1, 4, "I") + 5;
				$uGethostbyname = $exe->read($offset, 4, "I") +2;
			}
		}
		else {
			$uGethostbyname = $exe->read($offset + 2, 4, "I");
		}
        
		$uSprintf = $exe->func("sprintf");
        if ($uSprintf === false) {
            echo "Failed in part 5";
            return false;
        }
		
		$uIPScheme = $exe->str("%d.%d.%d.%d","raw");
        if ($uIPScheme === false) {
            echo "Failed in part 6";
            return false;
        }
		
		$offsetRVA = $offsetRVA - $exe->Raw2Rva($free + 2 + 16) - 5;
		$uRVAfreeoffset = $exe->Raw2Rva($free);
		
		$codef = str_replace("CA00", pack("V", $offsetRVA), $codef);
		$codef = str_replace("CA01", pack("V", $uOldptr), $codef);
		$codef = str_replace("CA02", pack("V", $uGethostbyname), $codef);
		$codef = str_replace("CA03", pack("V", $uIPScheme), $codef);
		$codef = str_replace("CA04", pack("V", $uRVAfreeoffset), $codef);
		$codef = str_replace("CA05", pack("V", $uSprintf), $codef);
		$codef = str_replace("CA06", pack("V", $uOldptr), $codef);
		$codef = str_replace("CA07", pack("V", $uRVAfreeoffset), $codef);
		
		$test = bin2hex(pack("V", $offsetRVA));
		echo $test;
		
        // Finally, insert everything.
        $exe->insert($codef, $free);
        
        return true;
    }
?>