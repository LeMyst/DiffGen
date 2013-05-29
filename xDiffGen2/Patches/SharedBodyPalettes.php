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
		$oldStyle  = true;
		//$offset = $exe->str("\xB8\xF6\x5C\x25\x73\x25\x73\x5F\x25\x64\x2E\x70\x61\x6C","raw");
		$offset = $exe->str("个\%s%s_%d.pal", "raw");
		
		if(!$offset)
		{// Otherwise look for new format 个\%s_%s_%d.pal
			$oldStyle = false;
			//$offset = $exe->str("\xB8\xF6\x5C\x25\x73\x5F\x25\x73\x5F\x25\x64\x2E\x70\x61\x6C","raw");		
			$offset = $exe->str("个\%s_%s_%d.pal","raw");
		}
		if(!$offset)
		{
			echo "Failed in Part 1";
			return false;
		}
		
		//Step 2 - Replace the string with our format string
		if($type == 1)
			$exe->replace($offset,array(0=>"body_%s_%d.pal\x00"));
		else
			$exe->replace($offset,array(0=>"body_%d.pal\x00"));
		
		//Step 3 - Find reference to this string (PUSH <OFFSET>)
		$offsetRVA = $exe->Raw2Rva($offset);
		$code = $exe->code("\x68".pack("I",$offsetRVA), "");
		if(!$code)
		{
			echo "Failed in Part 2";
			return false;
		}	
		
		//Step 4 - the offset is pushed to use as arguent to a function but since we now have less arguments we need to NOP the others
		$isvc9 = ( $exe->func("_encode_pointer") ) ? true : false;
		if ($isvc9)
		{
			if($oldStyle)
			{
				switch($type)
				{
					case 2: if(!Nullify($exe, $code-1, "Part 4-1 (Old Format)")) return false;
					case 1: if(!Nullify($exe, $code-16, "Part 4-2 (Old Format)")) return false;
				}
			}
			else
			{
				switch($type)
				{
					case 2: if(!Nullify($exe, $code-5, "Part 4-1 (New Format)")) return false;
					case 1: if(!Nullify($exe, $code-9, "Part 4-2 (New Format)")) return false;
				}
			}
		}
		else
		{
			switch($type)
			{
				case 2: if(!Nullify($exe, $code-1, "Part 4-1 (VC6 Image)")) return false;
				case 1: if(!Nullify($exe, $code-19, "Part 4-2 (VC6 Image)")) return false;
			}			
		}
		
		//Step 5 - 	Clean up the Stack Return (we are not pushing two data so we need to pop two less data)
		$reloffset = ($isvc9 && $oldStyle)? 14 : 13 ;
		$exe->replace($code, array($reloffset=>14 - $type)); //Change ADD ESP, 14h to ADD ESP, 12h (13h for type 1)
		
		//Step 6 - Adjust Stack Reference
		if ($isvc9 && !$oldStyle)
		{
			$value = $exe->read($code-1,1) - (4 * $type);
			$exe->replace($code, array(-1=>$value));
		}
		
		return true;
	}
	
	function Nullify($exe, $offset, $stage)
	{
		$byte = $exe->read($offset,1, "c");
		if ($byte >= 0x50 && $byte <= 0x57){
			$exe->replace($offset, array(0=>"\x90"));
			return true;
		}
		else
		{
			echo "Failed at Part ".$stage;
			return false;
		}
	}
?>