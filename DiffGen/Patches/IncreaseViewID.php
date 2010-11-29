<?php
    function IncreaseViewID($exe) {
        if ($exe === true) {
            return "[Add]_Increase_Headgear_ViewID_to_2000";
        }
        $codes = array(
        "\xFF\xB9\xE8\x03\x00\x00\x2B\xC8\x51",
        "\xFF\x3D\xE8\x03\x00\x00\x76\x15\x8B",
        "\x81\xFE\xE8\x03\x00\x00\x7C\xB8\x8B",
        );
        $codeoffsets = array(2,2,2);
        $changes = array("\xD0\x07","\xD0\x07","\xD0\x07");
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset === false) {
                echo "Failed in part $index";
                return false;
            } else {
                $exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
            }
        }
        return true;
    }
?>