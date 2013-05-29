<?php	
	function SharedHeadPalettes($exe)
	{
		return new xPatchGroup(81, 'Shared Head Palettes', array(
			'SharedHeadPalettesV1',
			'SharedHeadPalettesV2'
			)
		);
	}
	
	function SharedHeadPalettesV1($exe)
	{
		if($exe === true)
		{
			return new xPatch(82, 'Shared Head Palettes Type1', 'UI', 81, 
			'Makes the client use a single hair palette set (head_%s_%d.pal) for all job classes but seperate for both genders');
		}
		return SharedHeadPaletteBase($exe, 1);
	}
	
	function SharedHeadPalettesV2($exe)
	{
		if($exe === true)
		{
			return new xPatch(83, 'Shared Head Palettes Type2', 'UI', 81, 
			'Makes the client use a single hair palette set (head_%d.pal) for all job classes both genders');
		}
		return SharedHeadPaletteBase($exe, 2);
	}
	
	function SharedHeadPaletteBase($exe, $type)
	{
		//Step 1 - Find Offset of ¸Ó¸®\¸Ó¸®%s%s_%d.pal - Old Format
		//$offset = $exe->str("\xB8\xD3\xB8\xAE\x5C\xB8\xD3\xB8\xAE\x25\x73\x25\x73\x5F\x25\x64\x2E\x70\x61\x6C","raw");
		$offset = $exe->str("¸Ó¸®\¸Ó¸®%s%s_%d.pal","raw");
		
		if(!$offset)				
			$offset = $exe->str("¸Ó¸®\¸Ó¸®%s_%s_%d.pal","raw"); // If not found check ¸Ó¸®\¸Ó¸®%s_%s_%d.pal - New Format			
		//	$offset = $exe->str("\xB8\xD3\xB8\xAE\x5C\xB8\xD3\xB8\xAE\x25\x73\x5F\x25\x73\x5F\x25\x64\x2E\x70\x61\x6C","raw"); 
			
		if(!$offset)
		{
			echo "Failed to Find Matching Pattern";
			return false;
		}
		
		//Step 2 - Replace String with head%.s%.s_%d.pal or head%.s_%s_%d.pal based on type
		if($type == 1)
			$exe->replace($offset,array(0=>"head%.s_%s_%d.pal"));
		else
			$exe->replace($offset,array(0=>"head%.s%.s_%d.pal"));
	}
	
?>