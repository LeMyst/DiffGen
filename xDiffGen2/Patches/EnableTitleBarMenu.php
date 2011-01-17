<?php
    function EnableTitleBarMenu($exe) {
        if ($exe === true) {
            return new xPatch(19, 'Enable Title Bar Menu', 'UI');
        }
        $code = "\x68\x00\x00\xC2\x02"        // push    2C20000h        ; dwStyle
                ."\x51";                      // push    ecx             ; lpWindowName
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(3 => "\xCA"));
        return true;
    }
?>