<?php
    function ReadQuestid2displaydottxt($exe) {
        if ($exe === true) {
            return "[Data]_Read_questid2display.txt_(Recommended)";
        }
        
        $code =  "\x83\x3D\xAB\xAB\xAB\x00\x00" // cmp     <langtype>, 0
                ."\x0F\x85\xCB\x00\x00\x00"     // jnz     short <address> <---- Skip "ReadQuestid2display()"
                ."\x6A\x00"                     // push    0
                ."\x68\xAB\xAB\xAB\x00"         // push    offset <offset> ; "questID2display.txt"
                ."\x8D\x44\x24\x30";            // lea     eax, [esp+7Ch+var_4C]
                
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        
        // Skip JNZ and force reading of questid2display.txt
        $exe->replace($offset, array(7 => str_repeat("\x90", 6)));
        
        return true;
    }
?>