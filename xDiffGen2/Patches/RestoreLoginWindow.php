<?php
    function RestoreLoginWindow($exe) {
        if ($exe === true) {
            return new xPatch(40, 'Restore Login Window', 'Fix');
        }
        
        $code =  "\x50"                         // push    eax
                ."\xE8\xAB\xAB\xAB\xFF"         // call    sub_54AF30
                ."\x8B\xC8"                     // mov     ecx, eax
                ."\xE8\xAB\xAB\xAB\xFF"         // call    sub_54B3D0
                ."\x50"                         // push    eax
                ."\xB9\xAB\xAB\xAB\x00"         // mov     ecx, offset unk_7D9DF0
                ."\xE8\xAB\xAB\xAB\xFF"         // call    sub_508EB0
                // replace with CreateWindow call
                ."\x80\x3D\xAB\xAB\xAB\x00\x00" // cmp     T_param, 0
                ."\x74\xAB"                     // jz      short loc_61FCF5
                ."\xC6\xAB\xAB\xAB\xAB\x00\x00" // mov     T_param, 0
                ."\xC7\xAB\xAB\x04\x00\x00\x00" // mov     dword ptr [ebx+0Ch], 4
                // end of patch
                ."\xE9\xAB\xAB\x00\x00";        // jmp     loc_6212E3
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        
        $mov = $exe->read($offset + 14, 5);
        $numaccount = pack("I", $exe->str("NUMACCOUNT","rva"));
        $code =  "\xB9\xAB\xAB\xAB\x00"         // mov     ecx, offset unk_816600
                ."\xE8\xAB\xAB\xAB\xFF"         // call    CreateWindow
                ."\x6A\x00"                     // push    0
                ."\x6A\x00"                     // push    0
                ."\x68" . $numaccount           // push    offset aNumaccount ; "NUMACCOUNT"
                ."\x8B\xF8"                     // mov     edi, eax
                ."\x8B\x17"                     // mov     edx, [edi]
                ."\x8B\x82\x90\x00\x00\x00"     // mov     eax, [edx+90h]
                ."\x68\x23\x27\x00\x00";        // push    2723h
        $offseta = $exe->code($code, "\xAB");
        if ($offseta === false) {
            echo "Failed in part 2";
            return false;
        }
        $call = $exe->read($offseta + 6, 4, "i");
        $call2 = pack("i", (($offseta + $call) - ($offset + 26)));    // shit brix o_O

        $code =  "\x6A\x03"                     // push    3
                .$mov                           // mov     ecx, offset unk_7D9DF0
                ."\xE8" . $call2                // call    CreateWindow
                ."\x90\x90\x90\x90\x90"         // 11 nops
                ."\x90\x90\x90\x90\x90"
                ."\x90";
        $exe->replace($offset, array(24 => $code));
        
        // Force the client to send old login packet.
        $code =  "\x80\x3D\xAB\xAB\xAB\x00\x00" // cmp     g_passwordencrypt, 0
                ."\x0F\xAB\xAB\xAB\x00\x00"     // jnz     loc_62072D
                ."\xA1\xAB\xAB\xAB\x00"         // mov     eax, Langtype
                // Some clients (this far only 2010-10-05a and 2010-10-07a)
                // use cmp eax,ebp instead of test eax,eax
                ."\xAB\xAB"                     // test    eax, eax
                ."\x0F\xAB\xAB\x00\x00\x00"     // jz      loc_620587 <- remove
                ."\x83\xF8\x12"                 // cmp     eax, 12h
                ."\x0F\x84\xAB\x00\x00\x00";    // jz      loc_620587 <- remove
                
        $offset = $exe->code($code, "\xAB");
 
        $repl = "\x90\x90\x90\x90\x90\x90";
        if ($offset === false) {
            echo "Failed in part 3";
            return false;
        }
        $exe->replace($offset, array(20 => $repl));
        $exe->replace($offset, array(29 => $repl));
        
        // Shinryo:
        // The client doesn't return to the old login interface when an error
        // occurs. E.g. wrong password, failed to connect, etc.
        // This shall fix this behaviour by aborting the quit operation,
        // set the return mode to 3 (login) and pass 10013 as idle value.
        // It was handy that "this" pointer was passed before. :)
        $code =  "\x8B\xF1"                     // MOV ESI,ECX
                ."\x8B\x46\x04"                 // MOV EAX,DWORD PTR DS:[ESI+4]
                ."\xC7\x40\x14\x00\x00\x00\x00" // MOV DWORD PTR DS:[EAX+14],0
                ."\x83\x3D\xAB\xAB\xAB\x00\x0B" // CMP DWORD PTR DS:[<address>],0B
                ."\x75\xAB"                     // JNE SHORT 0054A7D3
                ."\x8B\x0D\xAB\xAB\xAB\x00"     // MOV ECX,DWORD PTR DS:[<address>]
                ."\x6A\x01"                     // PUSH 1
                ."\x6A\x00"                     // PUSH 0
                ."\x6A\x00"                     // PUSH 0
                ."\x68\xAB\xAB\xAB\x00"         // PUSH <offset>  ; ASCII "http://www.ragnarok.co.in/index.php"
                ."\x68\xAB\xAB\xAB\x00"         // PUSH <offset>  ; ASCII "open"
                ."\x51"                         // PUSH ECX
                ."\xFF\x15\xAB\xAB\xAB\x00"     // CALL DWORD PTR DS:[<address>]  ; ShellExecuteA
                
                // Shinryo:
                // The easierst way would be propably to set this value to a random value instead of 0,
                // but the client would dimmer down/flicker and appear again at login interface.
                // I prefer the old way that the client used.
                ."\xC7\x06\x00\x00\x00\x00";      // MOV DWORD PTR DS:[ESI],0 <----- Return to which mode
        
        $replace =  "\x8B\x4C\xE4\x10"              // MOV ECX,DWORD PTR SS:[ESP+10]
                    // Save the used registers this time..
                    ."\x52"                         // PUSH EDX
                    ."\x50"                         // PUSH EAX
                    ."\x8B\x11"                     // MOV EDX,DWORD PTR DS:[ECX]
                    ."\x8B\x42\x18"                 // MOV EAX,DWORD PTR DS:[EDX+18]
                    ."\x6A\x00"                     // PUSH 0
                    ."\x6A\x00"                     // PUSH 0
                    ."\x6A\x00"                     // PUSH 0
                    ."\x68\x1D\x27\x00\x00"         // PUSH 271D
                    ."\xC7\x41\x0C\x03\x00\x00\x00" // MOV DWORD PTR DS:[ECX+0C],3
                    ."\xFF\xD0"                     // CALL EAX
                    // ..and restore them again.
                    ."\x58"                         // POP EAX
                    ."\x5A"                         // POP EDX
                    .str_repeat("\x90", 23);        // NOPS                        
                    
        $offset = $exe->match($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 4";
            return false;
        }

        $exe->replace($offset, array(0 => $replace));
       
        return true;
    }
?>