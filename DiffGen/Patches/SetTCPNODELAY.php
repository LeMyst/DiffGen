<?php
    function SetTCPNODELAY($exe){
        if( $exe === true ) {
            return "[Add]_Disable_Nagle_Algorithm";
        }
       
        $code =  "\x55"                           // PUSH EBP       
                ."\x8B\xEC"                       // MOV EBP,ESP
                ."\x83\xEC\x0C"                   // SUB ESP,0C
                ."\xC7\x45\xF8\x01\x00\x00\x00"   // MOV DWORD PTR SS:[EBP-8],1
                ."\x8B\x45\x10"                   // MOV EAX,DWORD PTR SS:[EBP+10]
                ."\x50"                           // PUSH EAX
                ."\x8B\x4D\x0C"                   // MOV ECX,DWORD PTR SS:[EBP+0C]
                ."\x51"                           // PUSH ECX
                ."\x8B\x55\x08"                   // MOV EDX,DWORD PTR SS:[EBP+8]
                ."\x52"                           // PUSH EDX
                ."\xA1"."CA00"                    // MOV EAX,DWORD PTR DS:[<&WS2_32.#23>]                  ; socket()
                ."\xFF\xD0"                       // CALL EAX
                ."\x89\x45\xFC"                   // MOV DWORD PTR SS:[EBP-4],EAX
                ."\x83\x7D\xFC\xFF"               // CMP DWORD PTR SS:[EBP-4],-1
                ."\x74\x35"                       // JE SHORT 00734F4C
                ."\x68"."ST00"                    // PUSH 00734ED7                                         ; ASCII "setsockopt"
                ."\x68"."ST01"                    // PUSH 00734EE3                                         ; ASCII "WS2_32.DLL"
                ."\x8B\x0D"."CA01"                // MOV ECX,DWORD PTR DS:[<&KERNEL32.GetModuleHandleA>]
                ."\xFF\xD1"                       // CALL ECX
                ."\x50"                           // PUSH EAX
                ."\x8B\x15"."CA02"                // MOV EDX,DWORD PTR DS:[<&KERNEL32.GetProcAddress>]
                ."\xFF\xD2"                       // CALL EDX
                ."\x89\x45\xF4"                   // MOV DWORD PTR SS:[EBP-0C],EAX
                ."\x83\x7D\xF4\x00"               // CMP DWORD PTR SS:[EBP-0C],0
                ."\x74\x11"                       // JE SHORT 00734F4C
                ."\x6A\x04"                       // PUSH 4
                ."\x8D\x45\xF8"                   // LEA EAX,[EBP-8]
                ."\x50"                           // PUSH EAX
                ."\x6A\x01"                       // PUSH 1
                ."\x6A\x06"                       // PUSH 6
                ."\x8B\x4D\xFC"                   // MOV ECX,DWORD PTR SS:[EBP-4]
                ."\x51"                           // PUSH ECX
                ."\xFF\x55\xF4"                   // CALL DWORD PTR SS:[EBP-0C]
                ."\x8B\x45\xFC"                   // MOV EAX,DWORD PTR SS:[EBP-4]
                ."\x8B\xE5"                       // MOV ESP,EBP
                ."\x5D"                           // POP EBP
                ."\xC2\x0C\x00";                  // RETN 0C
                
        $strings =  array(
                      "setsockopt\x00",
                      "WS2_32.DLL\x00",
                    );                
                    
        // Calculate free space that the code will need.
        $size = strlen($code);
        foreach($strings as $index => $string)
          $size += strlen($string);

        $free = $exe->zeroed($size, ".rsrc");
        if ($free === false) {
            echo "Failed in part 1";
            return false;
        }
        
        // ***********************************************************
        // Create default offsets that will be replaced into the code.
        // ***********************************************************
        
        // socket
        // Shinryo:
        // This one is a bit tricky..
        // First try to search for a call ds:socket, if not found
        // then search for call socket. If it was a call by distance
        // then calculate the offset at which socket() resides.
        $CA00_partA = "\xE8\xAB\xAB\x00\x00\x6A\x00\x6A\x01\x6A\x02";
        
        // Call offset or distance.
        $CA00_part1 = "\xFF\x15\xAB\xAB\xAB\x00";
        $CA00_part2 = "\xE8\xAB\xAB\xAB\x00";
        
        $CA00_offsetPos = 2;
        $socketDistanceCall = false;
        
        // Try to match both cases.
        $CA00_offset = $exe->match($CA00_partA.$CA00_part1, "\xAB");
        if($CA00_offset === false) {
          $CA00_offset = $exe->match($CA00_partA.$CA00_part2, "\xAB");
          if($CA00_offset === false) {
            echo "Failed in part 2";
            return false;
          }         
          $CA00_offsetPos -= 1;
          $socketDistanceCall == true;
        }
        
        // If called by offset..
        $CA00 = $exe->read($CA00_offset + strlen($CA00_partA) + $CA00_offsetPos, 4, "I");
        
        // If called by distance..
        if($socketDistanceCall == true)
          $CA00 = $exe->Raw2Rva($CA00_offset + strlen($CA00_partA)) + $CA00 + 5;
        
        if ($CA00 === false) {
            echo "Failed in part 3";
            return false;
        }
        
        // GetModuleHandleA
        $CA01 = $exe->func("GetModuleHandleA");
        if ($CA01 === false) {
            echo "Failed in part 4";
            return false;
        }
        
        // GetProcAddress
        $CA02 = $exe->func("GetProcAddress");
        if ($CA02 === false) {
            echo "Failed in part 5";
            return false;
        }
        
        // Assign strings.
        $memPosition = $exe->Raw2Rva($free) + strlen($code);
        foreach($strings as $index => $string) {
          $var = "ST".($index > 9 ? "" : "0" ).$index;
          $$var = $memPosition;
          $memPosition += strlen($string);
        }
        
        // Create a table for more control (which replaces are allowed).
        $replaceTable = array("CA00", "CA01", "CA02", "ST00", "ST01");

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
        
        // Replace all occurances where a call to socket() is made.
        $freeRva = $exe->Raw2Rva($free);
        
        // A JMP to socket() is found in each client.
        $offset = $exe->code("\xFF\x25".pack('V', $CA00), '', 1);
        if($offset === false) {
          echo "Failed in part 6";
          return false;
        }
        
        $freeRva = $exe->Raw2Rva($free);
        $exe->insert(pack("I", $freeRva), $offset + 2);
        
        if($socketDistanceCall == false) {
          // Offset call to socket() is only available in VC9 clients.
          $offsets = $exe->matches("\xFF\x15".pack('V', $CA00));
          if($offsets === false || count($offsets) == 0) {
              echo "Failed in part 7";
              return false;
          }

          // Replace all calls by offset with a call by distance.
          foreach($offsets as $offset)
            $exe->insert("\xE8".pack("I", $exe->Raw2Rva($free) - $exe->Raw2Rva($offset) - 5)."\x90", $offset);
        }
                
        // Finally, insert everything.
        $exe->insert($code.implode("", $strings), $free);
        
        return true;
    }
?>