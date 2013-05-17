<?php
    function UseNormalGuildBrackets($exe) {
        if ($exe === true) {
            return new xPatch(46, 'Use Normal Guild Brackets', 'UI', 0, 'On langtype 0, instead of square-brackets, japanese style brackets are used, this option reverts that behaviour to the normal square brackets ("[" and"]").');
        }
        $offset = $exe->str("%s"."\xA1\xBA"."%s"."\xA1\xBB","raw");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "%s (%s)\x00"));
        return true;
    }
?>