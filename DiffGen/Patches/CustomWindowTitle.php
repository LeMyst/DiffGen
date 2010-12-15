<?php
    function CustomWindowTitle($exe) {
        if ($exe === true) {
            return new xPatch(8, 'Custom Window Title', 'UI');
        }
        $strOff = 0x310;
        global $clientdate, $clienttype;
        $string = $clientdate . $clienttype . " by Diff Team\x00";
        if (!$exe->insert($string, $strOff)) {
            echo "Failed in part 1";
            return false;
        }
        $strOff += $exe->imagebase();
        $code = pack("I", $exe->str("Ragnarok","rva"));
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2 ";
            return false;
        }
        $exe->replace($offset, array(0 => pack("I", $strOff)));
        return true;
    }
?>