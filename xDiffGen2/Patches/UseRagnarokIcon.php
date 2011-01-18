<?php
    function UseRagnarokIcon($exe) {
        if ($exe === true) {
            return new xPatch(47, 'Use Ragnarok Icon', 'UI', 0, 'Makes the hexed client use the RO program icon instead of the generic Win32 app icon.');
        }
        $code = "\x38\x01\x00\x80\x77";
        $section = $exe->getSection(".data");
        $offset = $exe->match($code, "\xAB", $section->rOffset);
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\x50"));
        return true;
    }
?>