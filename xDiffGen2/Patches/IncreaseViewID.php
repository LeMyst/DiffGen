<?php
    function IncreaseViewID($exe) {
        if ($exe === true) {
            return new xPatch(28, 'Increase Headgear ViewID to 5000', 'Add', 0, 'Increases the limit for the headgear ViewIDs from 2000 to 5000');
        }
        
        // In case of break:
        // Search for "ReqAccName" and search somewhere around for the
        // maximum ViewID.
        
        // Search for both cmp's
        $oldValue = pack("V", 2000);
		if ($exe->clientdate() <= 20130605)
			$code = "\x00\x68".$oldValue."\x8D";
		else
			$code = "\x52\xBA".$oldValue."\x2B";
        $newvalue = pack("V", 5000);
		
		//echo bin2hex($code) . "#";
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