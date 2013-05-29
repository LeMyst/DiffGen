<?php
	function RemoveSerialDisplay($exe)
	{
		if($exe === true)
			return new xPatch(84, 'Remove Serial Display', 'UI', 0,
			'Removes the display of the client serial number in the login window (bottom right corner).');
		
		//Step 1 - Check if the client date is valid for this diff
		if ($exe->clientdate() <= 20101116)
		{
			echo "Client Date <= 16-11-2010 , Diff not valid";
			return false;
		}
		
		//Step 2 - Find offset of pattern
		$offset = $exe->match("\x83\xC0\xAB\x3B\x41\xAB\x0F\x8C\xAB\x00\x00\x00\x56\x57\x6A\x00", "\xAB");
		if(!$offset)
		{
			echo "Failed in Part 2";
			return false;
		}
		
		//Step 3 - Replace pattern at the offset
		$exe->replace($offset, array(0=>"\x31\xC0\x83\xF8\x01\x90"));
	}
?>