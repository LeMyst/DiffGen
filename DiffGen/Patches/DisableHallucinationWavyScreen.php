<?php
    function DisableHallucinationWavyScreen($exe) {
        if ($exe === true) {
            return "[Fix]_Disable_Hallucination_Wavy_Screen";
        }
        $code =  "\x83\xC6\xAB"
                ."\x89\x3D\xAB\xAB\xAB\xAB";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $dword = $exe->read($offset + 5, 4);
        // echo bin2hex($dword) . "#";
        $code =  "\x8B\xCD"
                ."\xE8\xAB\xAB\xAB\xAB"
                ."\x83\x3D" . $dword . "\x00"
                ."\x0F\x84\xAB\xAB\xAB\xAB";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(14 => "\x90\xE9"));
        return true;
    }
?>