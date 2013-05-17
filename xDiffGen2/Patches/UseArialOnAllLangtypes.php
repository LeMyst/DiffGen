<?php
// 10.12.2010 - Changed behaviour of this diff to always (in any case) use Arial on all language types. [Shinryo]

    function UseArialOnAllLangtypes($exe) {
        if ($exe === true) {
            return new xPatch(51, 'Arial on All Langtypes', 'UI', 0, 'Makes Arial the default font on all Langtypes (it s enable ascii by default');
        }
        
        /* $code =  "\x75\x22"       // JNE SHORT <current+22>
                ."\x83\xF8\x14"   // CMP EAX,14
                ."\x7C\x1D"       // JL SHORT <current+1D>
                ."\x89\x41\x0C";  // MOV DWORD PTR DS:[ECX+0C],EAX
        // Overwrite both conditions and set variables so that it will use Arial
        $replace = "\x31\xD2"     // XOR EDX,EDX
                  ."\x83\xC2\x0F" // ADD EDX,0F
                  ."\x31\xC0"     // XOR EAX,EAX
                  ."\x40"         // INC EAX
                  ."\xEB\x1E";    // JMP SHORT <current+1E>
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => $replace)); */
        
        /*$code =  "\x83\xFA\x08"                 // cmp     edx, 0Ah
                ."\x0F\x87\xE6\x00\x00\x00"     // ja      loc_40899B -> Here 0F 87 to EB 10 ?
                ."\xFF\x24\x95\xAB\xAB\xAB\xAB" // jmp     ds:off_4089E0[edx*4] ; switch jump
                ."\x8A\xAB\xAB\xAB\xAB"         // mov     eax, Langtype
                ."\xFA\x44\x74";*/                // 
		$code ="\x75\x5B\x8D\x57\xFF\x83\xFA\x0A\x77\x53";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\x75\x5B\x8D\x57\xFF\x83\xFA\x0A\xEB\x0C"));
        return true;
    }
?>