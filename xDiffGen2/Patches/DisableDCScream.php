<?php
	function DisableDCScream($exe) {
        if ($exe === true) {
			return new xPatch(98, 'Disable dc_scream.txt', 'UI', 0, 'Disable chat on file dc_scream');
		}
		
		$offset = $exe->str("english\DC_scream.txt","raw");
		if ($offset === false)
		{
			echo "Failed in Part 1";
			return false;
		}
		$exe->replace($offset, array(0=>"\x00"));
		
		$offset = $exe->str("DC_scream.txt","raw");
		if ($offset === false)
		{
			echo "Failed in Part 2";
			return false;
		}
		$exe->replace($offset, array(0=>"\x00"));
		return true;
	}
?>