<?php
    function RestoreLoginWindow($exe) {
        if ($exe === true) {
            return "[Fix]_Restore_Login_Window";
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
                ."\xE9\xEE\x15\x00\x00";        // jmp     loc_6212E3
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $mov = $exe->read($offset + 14, 5);
        $numaccount = pack("I", $exe->str("NUMACCOUNT","rva"));
        $code =  "\xB9\x00\xAB\xAB\x00"         // mov     ecx, offset unk_816600
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
        
        // finally force the client to send old login packet
        $code =  "\x80\x3D\xAB\xAB\xAB\x00\x00" // cmp     g_passwordencrypt, 0
                ."\x0F\x85\xAB\xAB\x00\x00"     // jnz     loc_62072D
                ."\xA1\xAB\xAB\xAB\x00"         // mov     eax, Langtype
                ."\x85\xC0"                     // test    eax, eax
                ."\x0F\x84\xAB\x00\x00\x00"     // jz      loc_620587 <- remove
                ."\x83\xF8\x12"                 // cmp     eax, 12h
                ."\x0F\x84\xAB\x00\x00\x00";    // jz      loc_620587 <- remove
                
        $offset = $exe->code($code, "\xAB");
        $repl = "\x90\x90\x90\x90\x90\x90";
        $exe->replace($offset, array(20 => $repl));
        $exe->replace($offset, array(29 => $repl));
        
    }
?>