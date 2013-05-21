<?php
// Enable Multiple GRF files
// adds support to load GRF files from a list inside DATA.INI
//
// 26.11.2010 - Organized DiffPatch into a working state for vc9 compiled clients [Yom]
// 12.10.2010 - The complete diff would take 247+9+14 = 264 bytes.
//              Note that if you are using k3dt's diff patcher, you have to use 2.30
//              since 2.31 and all before have a limit of 255 byte changes. [Shinryo]

    function DisableMultipleWindows($exe){
        if ($exe === true) {
            return new xPatch(75, 'Disable Multiple Windows', 'UI', 0, 
"Check if there is another instance of RO open");
        }
        
        // Remove rdata.grf string to stop it loading.
        //$offset = $exe->str("rdata.grf","raw");
        //if ($offset === false) {
        //    echo "Failed in part 1";
        //    return false;
        //}

        //$exe->replace($offset, array(0 => "\x00\x00\x00\x00\x00\x00\x00\x00\x00"));
        $type = 0;
        // Locate call to grf loading function.
        $grf = pack("I", $exe->str("data.grf","rva"));
        //$code =  "\x68" . $grf                      // push    offset aData_grf ; "data.grf"
        //        ."\xB9\xAB\xAB\xAB\x00"             // mov     ecx, offset unk_86ABBC
        //        ."\xE8\xAB\xAB\xAB\xAB"             // call    CFileMgr::AddPak()
        //        ."\x8B\xAB\xAB\xAB\xAB\x00";        // mov     edx, ds:dword_7AA7CC
                
        //$offset = $exe->code($code, "\xAB");
        //if ($offset === false) {
			$type = 1;
			$code =  "\x68" . $grf                      // push    offset aData_grf ; "data.grf"
					."\xB9\xAB\xAB\xAB\x00"             // mov     ecx, offset unk_86ABBC
					."\x88\xAB\xAB\xAB\xAB\x00"			// mov     byte_C08AC2, dl
					."\xE8\xAB\xAB\xAB\xAB";             // call    CFileMgr::AddPak()
					//."\x8B\xAB\xAB\xAB\xAB\x00";        // mov     edx, ds:dword_7AA7CC
			$offset = $exe->code($code, "\xAB");
			if ($offset === false) {
				echo "Failed in part 2";
				return false;
			}
        //}
        
        // Save "this" pointer and address of AddPak.
		if($type = 0){
			$setECX = $exe->read($offset + 5, 5);
			$AddPak = $exe->Raw2Rva($offset+10) + $exe->read($offset + 11, 4, "I") + 5;
		} else {
			$setECX = $exe->read($offset + 5, 5);
			$AddPak = $exe->Raw2Rva($offset+16) + $exe->read($offset + 17, 4, "I") + 5;
		}
        
        $code =  "\xE8"."ST00"    		                                    		// CALL StolenCall
                ."\x56"                                                    			// PUSH ESI
                ."\x33\xF6"                                             			// XOR ESI,ESI
                ."\xE8\x09"."CA00"                                         			// PUSH&JMP
                ."\x4B\x45\x52\x4E\x45\x4C\x33\x32\x00"                             // DB 'KERNEL32',0
                ."\xFF\x15"."CA01"                                         			// CALL <&GetModuleHandleA>
                ."\xE8\x0D"."ST01"                                             		// PUSH&JMP
                ."\x43\x72\x65\x61\x74\x65\x4D\x75\x74\x65\x78\x41\x00"             // DB 'CreateMutexA',0
                ."\x50"                                                    			// PUSH EAX
                ."\xFF\x15"."CA02"                                               	// CALL <&GetProcAddress>
                ."\xE8\x0F"."ST02"                                                  // PUSH&JMP
                ."\x47\x6C\x6F\x62\x61\x6C\x5C\x53\x75\x72\x66\x61\x63\x65\x00" 	// DB 'Global\Surface',0
                ."\x56"                                            					// PUSH ESI
                ."\x56"                                            					// PUSH ESI
                ."\xFF\xD0"                                                			// CALL EAX
                ."\x85\xC0"                                                   		// TEST EAX,EAX
                ."\x74\x0F"                                               			// JE lFailed
                ."\x56"                                                				// PUSH ESI
                ."\x50"                                                				// PUSH EAX
                ."\xFF\x15"."CA03"                                            		// CALL <&WaitForSingleObject>
                ."\x3D\x02\x01\x00\x00"                                             // CMP EAX,258  ; WAIT_TIMEOUT
                ."\x75\x2F"                                							// JNZ lSuccess
                ."\xE8\x09"."ST03"                                                  // lFailed: PUSH&JMP
                ."\x4B\x45\x52\x4E\x45\x4C\x33\x32\x00"                             // DB 'KERNEL32',0
                ."\xFF\x15"."CA01"                                                	// CALL <&GetModuleHandleA>
                ."\xE8\x0C"."ST04"                                            		// PUSH&JMP
                ."\x45\x78\x69\x74\x50\x72\x6F\x63\x65\x73\x73\x00"                 // DB 'ExitProcess',0
                ."\x50"                                            					// PUSH EAX
                ."\xFF\x15"."CA02"                                                  // CALL <&GetProcAddress>
                ."\x56"                                                   			// PUSH ESI
                ."\xFF\xD0"                                             			// CALL EAX
                ."\x5E"                                            					// lSuccess: POP ESI	
                ."\xE9"."ST05";                                         			// JMP AfterStolenCall
                
        $strings =  array(
                      ".\\DATA.INI\x00",
                      "GetPrivateProfileStringA\x00",
                      "WritePrivateProfileStringA\x00",
                      "Data\x00",
                      "KERNEL32\x00"
                    );
                    
        // Calculate free space that the code will need.
        $size = strlen($code);
        foreach($strings as $index => $string)
          $size += strlen($string);
        
        // Find free space to inject our data.ini load function.
        // Note that for the time beeing those will be probably
        // return some space in .rsrc, but that's still okay
        // until our new diff patcher is finished for our own section.
        $free = $exe->zeroed($size+4, false); // Shinryo: Why does the size have to be 4 bytes bigger?
        if ($free === false) {
            echo "Failed in part 3";
            return false;
        }
        
        // Create a call to the free space that was found before.     
        $exe->replace($offset, array(0 => "\x90\x90\x90\x90\x90"		// push    offset aData_grf ; "data.grf"
										 ."\x90\x90\x90\x90\x90",		// mov     ecx, offset unk_86ABBC
									16 =>  "\xE8".pack("I", $exe->Raw2Rva($free) - $exe->Raw2Rva($offset+16) - 5) ));

        // ***********************************************************
        // Create default offsets that will be replaced into the code.
        // ***********************************************************
        
        // GetModuleHandleA
        $CA00 = $exe->func("GetModuleHandleA");
        if ($CA00 === false) {
            echo "Failed in part 4";
            return false;
        }
        
        // GetProcAddress
        $CA01 = $exe->func("GetProcAddress");
        if ($CA01 === false) {
            echo "Failed in part 5";
            return false;
        }
        
        // AddPak
        $CA02 = $AddPak - $exe->Raw2Rva($free + strpos($code, "\xE8"."CA02")) - 5;
 
        // Assign strings.
        $memPosition = $exe->Raw2Rva($free) + strlen($code);
        foreach($strings as $index => $string) {
          $var = "ST".($index > 9 ? "" : "0" ).$index;
          $$var = $memPosition;
          $memPosition += strlen($string);
        }
        
        // Create a table for more control (which replaces are allowed).
        $replaceTable = array("CA00", "CA01", "CA02", "ST00", "ST01","ST02","ST03","ST04");

        // This is a ressource waste but it's more comfortable..
        foreach($replaceTable as $replace) {
          if(!isset($$replace)) {
            echo 'Failed to resolve $'.$replace.'. Check the script for missing declarations.';
            return false;
          }
          
          if(!strpos($code, $replace)) {
            echo 'Failed to replace $'.$replace.' in code. It is not placed inside.';
            return false;
          }
          
          $code = str_replace($replace, pack("V", $$replace), $code);
        }
        
        // Finally, insert everything.
        $exe->insert($code.implode("", $strings), $free);
        
        return true;
    }
?>