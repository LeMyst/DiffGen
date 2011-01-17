<?php
    function UseNormalGuildBrackets($exe) {
        if ($exe === true) {
            return new xPatch(46, 'Use Normal Guild Brackets', 'UI');
        }
        $offset = $exe->str("%s"."\xA1\xBA"."%s"."\xA1\xBB","raw");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "%s [%s]\x00"));
        return true;
    }
?>