<?php
    function DisableHShield ($exe) {
        if ($exe === true) {
            return "[Fix]_Disable_HShield_(Recommended)";
        }
        
        $code =  "\xE8\xAB\xAB\xAB\xFF"        // call    LoadAhnLab
                ."\x85\xC0"                    // test    eax, eax
                ."\x74\xAB"                    // jz      short loc_73FEF9
                ."\xE8\xAB\xAB\xAB\xFF"        // call    AudioInit
                ."\x85\xC0"                    // test    eax, eax
                ."\x74\xAB";                   // jz      short loc_73FEF9
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\xEB\x0B\x90\x90\x90"));
        // patch call to LoadAhnLab with jmp to call AudioInit

        $section = $exe->getSection(".rdata");
        $virtual = $section->vOffset - $section->rOffset;
        if($section === false) {
            echo "Failed in part 3";
            return false;
        }
        // remove ahnlab dll from import table
        $aoffset = $exe->str("aossdk.dll","raw");
        if ($aoffset === false) {
            echo "Failed in part 4";
            return false;
        }
        $aoffset += $virtual;
        $code = "\x00\x00\x00\x00\x00\x00\x00\x00\x00".pack("I", $aoffset);
        $offset = $exe->match($code, "\xAB", $section->rOffset, $section->rOffset+$section->rSize);
        if ($offset === false) {
            echo dechex($aoffset) ."  # Failed in part 5";
            return false;
        }
        
        $data2 = $exe->read($offset + 16, 19) . str_repeat("\x00", 20);
        $exe->replace($offset, array(-4 => $data2));

        return true;
    }
?>