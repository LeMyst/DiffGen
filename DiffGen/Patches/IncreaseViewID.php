<?php
    function IncreaseViewID($exe) {
        if ($exe === true) {
            return "[Add]_Increase_Headgear_ViewID_to_2000";
        }
        
        // In case of break:
        // Search for "ReqAccName" and search somewhere around for the
        // maximum ViewID.
        
        // Search for both cmp's
        $codes = array(
          "\x00\x3D\xE8\x03\x00\x00\x73\x18\x8D",
          "\x40\x3D\xE8\x03\x00\x00\x89\x44\x24\xAB\x7C\x9F",
        );
        $newvalue = "\xD0\x07";
        foreach ($codes as $index => $code) {
            $offset = $exe->code($code, "\xAB");
            if ($offset === false) {
                echo "Failed at index $index";
                return false;
            } else {
                $exe->replace($offset, array(2 => $newvalue));
                // Right after the first compare there has to be a mov to a register with the max ViewID as value
                if($index == 0) {
                  $offset = $exe->match("\xE8\x03\x00\x00", "\xAB", $offset+strlen($codes[$index]));
                  if ($offset === false) {
                      echo "Failed at index $index part 2";
                      return false;
                  }
                  $exe->replace($offset, array(0 => $newvalue));
                }
            }
        }

        return true;
    }
?>