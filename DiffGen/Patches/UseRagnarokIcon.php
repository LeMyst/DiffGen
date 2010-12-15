<?php
    function UseRagnarokIcon($exe) {
        if ($exe === true) {
            return new xPatch(47, 'Use Ragnarok Icon', 'UI');
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