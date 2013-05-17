<?php
        function CustomWindowTitle($exe) {
                if ($exe === true) {
                        return new xPatch(8, 'Custom Window Title', 'UI', 0, 'Changes window title. Normally, the window title is "Ragnarok".');
                }
               
                $replace = "http://ro.hangame.com/login/loginstep.asp?prevURL=/NHNCommon/NHN/Memberjoin.asp";
               
                $strOff = $exe->str($replace, "raw");
                if(!$strOff){
                        echo "Failed in part 1 ";
                        return false;
                }
                //echo dechex($strOff) . " - ";
                $exe->addInput('$customWindowTitle', XTYPE_STRING, '', 1, 60);
                $exe->replaceString($strOff, array(0 => '$customWindowTitle'));
               
                $strOff = $exe->str($replace, "rva");
                $code = pack("I", $exe->str("Ragnarok","rva"));
                $offset = $exe->code($code, "\xAB");
                if ($offset === false) {
                        echo "Failed in part 2 ";
                        return false;
                }
 
                $exe->replaceDword($offset, array(0 => $strOff));
                return true;
        }
?>