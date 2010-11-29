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
        $exe->replace($offset, array(0 => "\x90\x90\x90\x90\x90\x90\x90\x90\x90"));
        // patch call to LoadAhnLab with jmp to call AudioInit

        $section = $exe->getSection(".rdata");
        if($section === false) {
            echo "Failed in part 3";
            return false;
        }
        // remove ahnlab dll from import table
        $aOffset = $exe->str("aossdk.dll","raw");
        if ($aOffset === false) {
            echo "Failed in part 4";
            return false;
        }
        $virtual = $section->vOffset - $section->rOffset;
        $aOffset += $virtual;
        $code = "\x00\xAB\xAB\xAB\x00\x00\x00\x00\x00\x00\x00\x00\x00".pack("I", $aOffset);
        $offset = $exe->match($code, "\xAB", $section->rOffset, $section->rOffset+$section->rSize);
        if ($offset === false) {
            echo dechex($aOffset) ."  # Failed in part 5";
            return false;
        }
        
        $data2 = str_repeat("\x00", 20);
        //$exe->replace($offset, array(1 => $data2));

        return true;
    }
?>