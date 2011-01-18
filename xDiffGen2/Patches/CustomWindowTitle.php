<?php
    function CustomWindowTitle($exe) {
        if ($exe === true) {
            return new xPatch(8, 'Custom Window Title', 'UI', 0, 'Changes window title. Normally, the window title is "Ragnarok".');
        }
        $strOff = 0x310;
        global $clientdate, $clienttype;
        $string = $clientdate . $clienttype . " by Diff Team\x00";
        
        /*if (!$exe->insert($string, $strOff)) {
            echo "Failed in part 1";
            return false;
        }*/
        
        $exe->addInput('$customWindowTitle', XTYPE_STRING, '', 1, 40);
        $exe->replaceString($strOff, array(0 => '$customWindowTitle'));
        
        $strOff += $exe->imagebase();
        $code = pack("I", $exe->str("Ragnarok","rva"));
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2 ";
            return false;
        }
        /*        $exe->addInput('$allowChatFlood', XTYPE_BYTE, '-1', 1);
        $exe->replace($offset, array(13 => '$allowChatFlood'));
        */
        
        $exe->replaceDword($offset, array(0 => $strOff));
        return true;
    }
?>