<?php
    function ReadMsgstringtabledottxt($exe) {
        if ($exe === true) {
            return new xPatch(36, 'Read msgstringtable.txt', 'Data');
        }
        
        $code =  "\x83\x3D\xAB\xAB\xAB\x00\x00" // cmp     dword_869FF0, 0
                ."\x56"                         // push    esi
                ."\x75\x24"                     // jnz     short loc_582B4B <---- Jmp to ReadMsgStringTable()
                ."\x33\xC9"                     // xor     ecx, ecx
                ."\x33\xC0"                     // xor     eax, eax
                ."\x8B\xFF"                     // mov     edi, edi
                ."\x8B\x90\xAB\xAB\xAB\x00";    // mov     edx, off_7EF7FC[eax]
                
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        
        // Force a jump to ReadMsgStringTable(): JNZ -> JMP
        $exe->replace($offset, array(8 => "\xEB"));
        
        return true;
    }
?>