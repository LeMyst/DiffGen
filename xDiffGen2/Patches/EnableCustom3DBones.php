<?php
	function EnableCustom3DBones ($exe) {
		if ($exe === true) {
            return new xPatch(77, 'Enable Custom 3D Bones', 'Data', 0, 'Enables the use of custom 3D monsters (Granny) by lifting hardcoded ID limit.');
        }
		
		//Step 1 - Find location of the sprintf control string for 3d mob bones
		$mob_bone = $exe->str("model\\3dmob_bone\\%d_%s.gr2","rva");		
		if (!$mob_bone)
		{
			echo "Failed in Part 1";
			return false;
		}		
		//Step 2 - Find C3dGrannyBoneRes::GetAnimation by bone 
		// MOV <R32>, [ARRAY] <= Find offset of this instruction
		// PUSH <R32>
		// PUSH <R32>
		// PUSH <$mob_bone>
		$finish = $exe->code("\x68".pack("I",$mob_bone), "") - 9;
		if (!$finish)
		{
			echo "Failed in Part 2";
			return false;
		}
		
		//Step 3 - Find Limiting CMP
		// Find offset of instruction after CMP ESI, 9h within this function before $finish
		// We use $finish - 0x70 as an approximate location where the function starts
		$offset = $exe->match("\x83\xFE\x09", "", $finish - 0x70, $finish) + 3;
		if (!$offset)
		{	// For VC9 images the valus is 09h but for earlier VC6 images the value is 10h
			$offset = $exe->match("\x83\xFE\x0A", "", $finish - 0x70, $finish) + 3;
		}
		if (!$offset)
		{
			echo "Failed in Part 3";
			return false;
		}		
		
		//Step 4 - Make it always use 3dmob_bone
		// Modify JGE/JA to always address bones. Do not care about which CMP we hit, the important thing is the conditional
        // JGE/JA after it, be it SHORT or LONG. Also let's trust the client here, that it never calls the function with nAniIdx outside of [0;4]
		$byte = $exe->read($offset,1);
		switch($byte)
		{
			case "\x77":
			case "\x7D":
			{// Short Jump
				$exe->replace($offset, array(1 => pack("c",$finish - $offset - 2)));
				break;
			}
			case "\x0F":
			{// Long Jump
				$exe->replace($offset, array(2 => pack("i",$finish - $offset - 6)));
				break;
			}
			default:
			{
				echo "Failed in Part 4";
				return false;
			}
		}
		return true;			
	}