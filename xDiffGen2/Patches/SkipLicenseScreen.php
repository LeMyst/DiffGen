<?php
    function SkipLicenseScreen($exe) {
        if ($exe === true) {
           return new xPatch(50, 'Skip License Screen', 'UI', 0, 'Skip the warning screen and goes directly to the main window with the Service Select.');
        }
		
		// Find offset of btn_disagree
		$btnoff = $exe->str("btn_disagree", "rva");
		
		// Find the location where it is pushed
		$finish = $exe->code("\x68". pack("I",$btnoff), "");
		
		$start = $finish - 0x1A0;//will increase this number if necessary
		
		// Now find the jump table jumper inside that address set.
		$offset = $exe->match("\xFF\x24\x85\xAB\xAB\xAB\x00", "\xAB", $start, $finish);
		
		// Now retrieve the jumptable address from the instruction
		$jmpoffset = $exe->read($offset + 3, 4, "I");
		$jmpoffset = $exe->Rva2Raw($jmpoffset);// we need raw address
		
		// Pick up the third entry in jumptable
		$third = $exe->read($jmpoffset + 8, 4);
		
		// Now replace first and second with third.
		$exe->replace($jmpoffset, array(0 => $third, 4 => $third));
		
        return true;
    }
?>