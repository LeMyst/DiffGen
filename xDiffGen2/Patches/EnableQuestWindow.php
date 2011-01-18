<?php
    function EnableQuestWindow($exe) {
        if ($exe === true) {
            return new xPatch(18, 'Enable Quest Window', 'UI', 0, 'Enables the use of the Quest Window with CTRL + U in Langtypes 1+');
        }
        $code =  "\x0F\x85\xCB\x00\x00\x00"    // jnz     loc_555684
                ."\x6A\x00"                    // push    0               ; int
                ."\x68\xAB\xAB\xAB\x00";       // push    offset aQuestid2displa ; "questID2display.txt"
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\x90\x90\x90\x90\x90\x90"));
        return true;
    }
?>