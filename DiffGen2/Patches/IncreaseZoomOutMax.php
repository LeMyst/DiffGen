<?php
    function IncreaseZoomOutMax($exe) {
        if ($exe === true) {
            return "[UI](5)_Increase_Zoom_Out_Max";
        }
        $code = "\x00\x00\x66\x43\x00\x00\xC8\x43\x00\x00\x96\x43";
        $offsets = $exe->matches($code, "\xAB", 0);
        if (count($offsets) == 0) {
            echo "Failed in part 1";
            return false;
        }
        $offset = $offsets[0];
        $exe->replace($offset, array(6 => "\x99\x44"));
        return true;
    }
?>