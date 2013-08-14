<?php
	function SharedBodyPalettes($exe)
	{
		return new xPatchGroup(78, 'Shared Body Palettes', array(
			'SharedBodyPalettesV1',
			'SharedBodyPalettesV2'
			)
		);
	}
	
	function SharedBodyPalettesV1($exe)
	{
		if($exe === true)
		{
			return new xPatch(80, 'Shared Body Palettes Type1', 'UI', 78, 
			'Makes the client use a single cloth palette set (body_%s_%d.pal) for all job classes but seperate for both genders');
		}
		return SharedBodyPaletteBase($exe, 1);
	}
	
	function SharedBodyPalettesV2($exe)
	{
		if($exe === true)
		{
			return new xPatch(79, 'Shared Body Palettes Type2', 'UI', 78, 
			'Makes the client use a single cloth palette set (body_%d.pal) for all job classes both genders');
		}
		return SharedBodyPaletteBase($exe, 2);
	}
	
	function SharedBodyPaletteBase($exe, $type)
	{
		//Step 1 - Find offset of String 个\%s%s_%d.pal - Old Format
		//$offset = $exe->str("\xB8\xF6\x5C\x25\x73\x25\x73\x5F\x25\x64\x2E\x70\x61\x6C","raw");
		$offset = $exe->str("个\%s%s_%d.pal", "rva");
		
		if(!$offset)
		{// Otherwise look for new format 个\%s_%s_%d.pal
			//$offset = $exe->str("\xB8\xF6\x5C\x25\x73\x5F\x25\x73\x5F\x25\x64\x2E\x70\x61\x6C","raw");		
			$offset = $exe->str("个\%s_%s_%d.pal","rva");
		}
		if($offset == false)
		{
			echo "Failed in Part 1";
			return false;
		}
		
		//Step 2 - Originally we used to adjust stack and lot of hurdles were there. so I said screw it.
		//		   Since we cant place our own string in that area (not enough space) 
		//		   we will insert it in a new place DUH!		
		
		if($type == 1)
		{
			$code = "body%.s_%s_%d.pal\x00";
		}
		else
		{
			$code = "body%.s%.s_%d.pal\x00"; //%.s is required
		}
		$offset2 = $exe->zeroed(sizeof($code));
		if ($offset2 == false)
		{
			echo "Failed in Step 2. Not enough space";
			return false;
		}		
		$exe->insert($code, sizeof($code), $offset2);
		
		//Step 3 - Replace the pushed string with ours.
		$offset = $exe->code("\x68".pack("I",$offset), "");
		if ($offset == false)
		{
			echo "Failed in Step 3";
			return false;
		}
		$exe->replace($offset, array(1=>pack("I",$exe->Raw2Rva($offset2))));
		return true;
	}
?>