<?php
    function EnableAcii($exe) {
        if ($exe === true) {
            return new xPatch(70, 'Enable Ascii', 'Fix', 0, '');
        }
        $code =  "\xF6\x04\xAB\x80\x75\xAB\xAB\x3B\xAB\x7C\xF5";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }

        $exe->replace($offset, array(4 => "\x90\x90"));
        return true;
    }
?>