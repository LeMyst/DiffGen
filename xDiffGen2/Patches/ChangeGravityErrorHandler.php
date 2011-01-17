<?php
    function ChangeGravityErrorHandler($exe) {
        if ($exe === true)
            return new xPatch(7, 'Change Gravity Error Handler', 'Fix'); 
        global $clientdate, $clienttype;
        $code = "";
        $code2 = "";
        $string = "";
        $string2 = "";
        $chars = str_split("Gravity(tm) Error Handler");
        for ($i = 0; $i < count($chars); $i++)
            $code .= $chars[$i] . "\x00";
        $offsets = $exe->matches($code, "\xAB", 0);
        if (count($offsets) == 0) {
            echo "Failed in part 1";
            return false;
        }
        $chars = str_split($clientdate . " " . $clienttype);
        for ($i = 0; $i < count($chars); $i++)
            $string .= $chars[$i] . "\x00";
        if(strlen($code) < strlen($string)) {
            echo "Failed - " . $string . " too long to Fit. ";
            return false;
        }
        $string .= str_repeat("\x20\x00", ((strlen($code) - strlen($string))/2));
        $exe->replace($offsets[1], array(0 => $string));
        $chars = str_split("to Gravity or Game Master.");
        for ($i = 0; $i < count($chars); $i++)
            $code2 .= $chars[$i] . "\x00";
        $offset = $exe->match($code2, "\xAB", 0);
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $chars = str_split("to Diff Team on eA Forums.");
        for ($i = 0; $i < count($chars); $i++)
            $string2 .= $chars[$i] . "\x00";
        if(strlen($code2) < strlen($string2)) {
            echo "Failed " . $string2 . " too long to Fit. ";
            return false;
        }
        $string2 .= str_repeat("\x00\x00", ((strlen($code2) - strlen($string2))/2));
        $exe->replace($offset, array(0 => $string2));
        return true;
    }
?>