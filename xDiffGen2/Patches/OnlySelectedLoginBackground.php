<?php
	function OnlySelectedLoginBackground($exe)
	{
		return new xPatchGroup(85, 'Only Selected Login Background', array(
			'OnlyFirstLoginBackground',
			'OnlySecondLoginBackground'
			)
		);
	}
	
	function OnlyFirstLoginBackground($exe)
	{
		if ($exe === true)
			return new xPatch(86, 'Only First Login Background', 'UI', 85, 'Displays always the first login background.');
	
		return OnlyLoginBase($exe,1);
	}
	
	function OnlySecondLoginBackground($exe)
	{
		if ($exe === true)
			return new xPatch(87, 'Only Second Login Background', 'UI', 85, 'Displays always the second login background.');
	
		return OnlyLoginBase($exe,2);
	}
	
	function OnlyLoginBase($exe, $type)
	{
		$prefix = "\xC0\xAF\xC0\xFA\xC0\xCE\xC5\xCD\xC6\xE4\xC0\xCC\xBD\xBA\x5C";
		$first = 'T_' . "\xB9\xE8\xB0\xE6" . '%d-%d.bmp' ."\x00\x00";
		$second = 'T2_' . "\xB9\xE8\xB0\xE6" . '%d-%d.bmp' . "\x00";
		
		//Step 1 - Find one of the strings based on type
		if ($type == 2)
			$offset = $exe->match($prefix.$first,"");
		else
			$offset = $exe->match($prefix.$second,"");
		
		if(!$offset)
		{
			echo "Failed to find matching data : Part 1";
			return false;
		}
		
		//Step 2 - Replace with the other 
		if ($type == 2)
			$exe->replace($offset, array(15=>$second));
		else
			$exe->replace($offset, array(15=>$first));		
	}
?>