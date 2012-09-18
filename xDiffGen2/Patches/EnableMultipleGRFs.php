<?php
// Enable Multiple GRF files
// adds support to load GRF files from a list inside DATA.INI
//
// 26.11.2010 - Organized DiffPatch into a working state for vc9 compiled clients [Yom]
// 12.10.2010 - The complete diff would take 247+9+14 = 264 bytes.
//              Note that if you are using k3dt's diff patcher, you have to use 2.30
//              since 2.31 and all before have a limit of 255 byte changes. [Shinryo]

    function EnableMultipleGRFs($exe){
        if ($exe === true) {
            return new xPatch(49, 'Enable Multiple GRFs', 'UI', 0, 
"If you enable this feature, you will have to put a data.ini in your client folder.
You can only load up to 10 total grf files with this option (0-9).
The read priority is 0 first to 9 last.

--------[ Example of data.ini ]---------
[data]
0=bdata.grf
1=adata.grf
2=sdata.grf
3=data.grf
----------------------------------------

If you only have 3 GRF files, you would only need to use: 0=first.grf, 1=second.grf, 2=last.grf");
        }
        
        // Remove rdata.grf string to stop it loading.
        $offset = $exe->str("rdata.grf","raw");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }

        $exe->replace($offset, array(0 => "\x00\x00\x00\x00\x00\x00\x00\x00\x00"));
        $type = 0;
        // Locate call to grf loading function.
        $grf = pack("I", $exe->str("data.grf","rva"));
        $code =  "\x68" . $grf                      // push    offset aData_grf ; "data.grf"
                ."\xB9\xAB\xAB\xAB\x00"             // mov     ecx, offset unk_86ABBC
                ."\xE8\xAB\xAB\xAB\xAB"             // call    CFileMgr::AddPak()
                ."\x8B\xAB\xAB\xAB\xAB\x00";        // mov     edx, ds:dword_7AA7CC
                
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
			$type = 1;
			$code =  "\x68" . $grf                      // push    offset aData_grf ; "data.grf"
					."\xB9\xAB\xAB\xAB\x00"             // mov     ecx, offset unk_86ABBC
					."\x88\xAB\xAB\xAB\xAB\x00"
					."\xE8\xAB\xAB\xAB\xAB";             // call    CFileMgr::AddPak()
					//."\x8B\xAB\xAB\xAB\xAB\x00";        // mov     edx, ds:dword_7AA7CC
			$offset = $exe->code($code, "\xAB");
			if ($offset === false) {
				echo "Failed in part 2";
				return false;
			}
        }
        
        // Save "this" pointer and address of AddPak.
		if($type = 0){
			$setECX = $exe->read($offset + 5, 5);
			$AddPak = $exe->Raw2Rva($offset+10) + $exe->read($offset + 11, 4, "I") + 5;
		} else {
			$setECX = $exe->read($offset + 5, 5);
			$AddPak = $exe->Raw2Rva($offset+16) + $exe->read($offset + 17, 4, "I") + 5;
		}
        
        $code =  "\xC8\x80\x00\x00"                                        // enter   80h, 0
                ."\x60"                                                    // pusha
                ."\x68"."ST04"                                             // push    offset ModuleName ; "KERNEL32"
                ."\xFF\x15"."CA00"                                         // call    ds:GetModuleHandleA
                ."\x85\xC0"                                                // test    eax, eax
                ."\x74\x23"                                                // jz      short loc_735E01
                ."\x8B\x3D"."CA01"                                         // mov     edi, ds:GetProcAddress
                ."\x68"."ST01"                                             // push    offset aGetprivateprof ; "GetPrivateProfileStringA"
                ."\x89\xC3"                                                // mov     ebx, eax
                ."\x50"                                                    // push    eax             ; hModule
                ."\xFF\xD7"                                                // call    edi ; GetProcAddress()
                ."\x85\xC0"                                                // test    eax, eax
                ."\x74\x0F"                                                // jz      short loc_735E01
                ."\x89\x45\xF6"                                            // mov     [ebp+var_A], eax
                ."\x68"."ST02"                                             // push    offset aWriteprivatepr ; "WritePrivateProfileStringA"
                ."\x89\xD8"                                                // mov     eax, ebx
                ."\x50"                                                    // push    eax             ; hModule
                ."\xFF\xD7"                                                // call    edi ; GetProcAddress()
                ."\x85\xC0"                                                // test    eax, eax
                ."\x74\x6E"                                                // jz      short loc_735E71
                ."\x89\x45\xFA"                                            // mov     [ebp+var_6], eax
                ."\x31\xD2"                                                // xor     edx, edx
                ."\x66\xC7\x45\xFE\x39\x00"                                // mov     [ebp+var_2], 39h ; char 9
                ."\x52"                                                    // push    edx
                ."\x68"."ST00"                                             // push    offset a_Data_ini ; ".\\DATA.INI"
                ."\x6A\x74"                                                // push    74h
                ."\x8D\x5D\x81"                                            // lea     ebx, [ebp+var_7F]
                ."\x53"                                                    // push    ebx
                ."\x8D\x45\xFE"                                            // lea     eax, [ebp+var_2]
                ."\x50"                                                    // push    eax
                ."\x50"                                                    // push    eax
                ."\x68"."ST03"                                             // push    offset aData_2  ; "Data"
                ."\xFF\x55\xF6"                                            // call    [ebp+var_A]
                ."\x8D\x4D\xFE"                                            // lea     ecx, [ebp+var_2]
                ."\x66\x8B\x09"                                            // mov     cx, [ecx]
                ."\x8D\x5D\x81"                                            // lea     ebx, [ebp+var_7F]
                ."\x66\x3B\x0B"                                            // cmp     cx, [ebx]
                ."\x5A"                                                    // pop     edx
                ."\x74\x0E"                                                // jz      short loc_735E44
                ."\x52"                                                    // push    edx
                ."\x53"                                                    // push    ebx
                .$setECX                                                   // mov     ecx, offset unk_810248
                ."\xE8"."CA02"                                             // call    CFileMgr::AddPak()
                ."\x5A"                                                    // pop     edx
                ."\x42"                                                    // inc     edx
                ."\xFE\x4D\xFE"                                            // dec     byte ptr [ebp+var_2]
                ."\x80\x7D\xFE\x30"                                        // cmp     byte ptr [ebp+var_2], 30h ; char 0
                ."\x73\xC1"                                                // jnb     short loc_735E0E
                ."\x85\xD2"                                                // test    edx, edx
                ."\x75\x20"                                                // jnz     short loc_735E71
                ."\x68"."ST00"                                             // push    offset a_Data_ini ; ".\\DATA.INI"
                ."\x68".$grf                                               // push    offset aData_grf ; "data.grf"
                ."\x66\xC7\x45\xFE\x32\x00"                                // mov     [ebp+var_2], 32h
                ."\x8D\x45\xFE"                                            // lea     eax, [ebp+var_2]
                ."\x50"                                                    // push    eax
                ."\x68"."ST03"                                             // push    offset aData_2  ; "Data"
                ."\xFF\x55\xFA"                                            // call    [ebp+var_6]
                ."\x85\xC0"                                                // test    eax, eax
                ."\x75\x97"                                                // jnz     short loc_735E08
                ."\x61"                                                    // popa
                ."\xC9"                                                    // leave
                ."\xC3";                                                   // retn
                
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
        $exe->replace($offset, array(0 => "\xE8".pack("I", $exe->Raw2Rva($free) - $exe->Raw2Rva($offset) - 5)."\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90"));

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