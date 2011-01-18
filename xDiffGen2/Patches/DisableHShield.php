<?php
    function DisableHShield ($exe) {
        if ($exe === true) {
            return new xPatch(15, 'Disable HShield', 'Fix', 0, 'Disables HackShield');
        }
        
        $code =  "\x51"                         // push    ecx
                ."\x83\x3D\xAB\xAB\xAB\x00\x00" // cmp     dword_88A210, 0
                ."\x74\x04"                     // jz      short loc_58AD2E
                ."\x33\xC0"                     // xor     eax, eax
                ."\x59"                         // pop     ecx
                ."\xC3";                        // retn
        
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        
        // Just return 1 without initializing AhnLab :)
        $exe->replace($offset, array(1 => "\x31\xC0\x40\x90\x90\x90\x90\x90\x90\x90\x90"));

        // Import table fix for aossdk.dll
        $section = $exe->getSection(".rdata");
        if($section === false) {
            echo "Failed in part 3";
            return false;
        }
        
        // The dll name offset gives the hint where the image descriptor of this
        // dll resides.
        $aOffset = $exe->str("aossdk.dll","raw");
        if ($aOffset === false) {
            echo "Failed in part 4";
            return false;
        }
        $virtual = $section->vOffset - $section->rOffset;
        $aOffset += $virtual;
        
        // The name offset comes after the thunk offset.
        // Thunk offset is guessed through wildcard.
        $code = "\x00\xAB\xAB\xAB\x00\x00\x00\x00\x00\x00\x00\x00\x00".pack("I", $aOffset);
        $offset = $exe->match($code, "\xAB", $section->rOffset, $section->rOffset+$section->rSize);
        if ($offset === false) {
            echo "Failed in part 5";
            return false;
        }
        
        // Shinryo: As far as I see, all clients which were compiled with VC9
        // have always the same import table and therefore I assume that the last entry
        // is always 221 bytes after the aossdk.dll thunk offset.
        // So just read the last import entry, clear it with zeros and
        // place it where aossdk.dll was set before.
        // TO-DO: Create a seperate PE parser for easier access
        // and modification in case this diff should break in the near future.
        $data = $exe->read($offset + 13 + 13*16, 19);
        $exe->replace($offset + 13 + 13*16, array(0 => str_repeat("\x00", 19)));
        $exe->replace($offset, array(1 => $data));

        return true;
    }
?>