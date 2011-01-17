<?php
    function IncreaseViewID($exe) {
        if ($exe === true) {
            return "[Add]_Increase_Headgear_ViewID_to_2000";
        }
        
        // In case of break:
        // Search for "ReqAccName" and search somewhere around for the
        // maximum ViewID.
        
        // Search for both cmp's
        $oldValue = pack("V", 1000);
        $code = "\x00\x3D".$oldValue."\x73\xAB\x8D";
        $newvalue = pack("V", 2000);
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
        	echo "Failed at part 1";
        	return false;
				}
				
				$exe->replace($offset, array(2 => $newvalue));
				$offset += strlen($code);

				// Right after the first compare there have to 2 more checks with $oldValue one after another.
				for($i = 0; $i < 2; $i++) {
					$offset = $exe->match($oldValue, "\xAB", $offset);
					if ($offset === false) {
						echo "Failed at part 2 index $index";
						return false;
					}
					$exe->replace($offset, array(0 => $newvalue));
					$offset += 4;
				}

        return true;
    }
?>