<?php
	function AllowSpaceInGuildName($exe)
	{
		if($exe === true)
			return new xPatch(88, 'Allow space in guild name', 'UI', 0,
			'Allow player to create a guild with space in the name (/guild "Space Name").');
		
		$offset = $exe->match("\x6A\x20\x53\xFF\xD6\x83\xC4\x08","\xAB");
		if(!$offset)
		{
			echo "Failed in Part 1";
			return false;
		}
		
		$exe->replace($offset, array(1=>"\x21"));
	}
?>