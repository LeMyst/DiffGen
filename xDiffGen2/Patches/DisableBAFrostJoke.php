<?php
	function DisableBAFrostJoke($exe) {
        if ($exe === true) {
			return new xPatch(99, 'Disable ba_frostjoke.txt', 'UI', 0, 'Disable chat on file ba_frostjoke');
		}
	
		$offset = $exe->str("english\BA_frostjoke.txt","raw");
		if ($offset === false)
		{
			echo "Failed in Part 1";
			return false;
		}
		$exe->replace($offset, array(0=>"\x00"));
		
		$offset = $exe->str("BA_frostjoke.txt","raw");
		if ($offset === false)
		{
			echo "Failed in Part 2";
			return false;
		}
		$exe->replace($offset, array(0=>"\x00"));
		return true;
	}
?>