<?php
    function UseNormalGuildBrackets($exe) {
        if ($exe === true) {
            return "[UI]_Use_Normal_Guild_Brackets";
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