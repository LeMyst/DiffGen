<?php
	function RemoveGMSprite($exe)
	{
		if($exe === true)
			return new xPatch(96, 'Remove GM Sprites', 'UI', 0,
			'Remove the GM sprites and keeping all the functionality like yellow name and admin right click.');

		$offsets = array();
		//Step 1 : Find RVA of GM spr file
		$code = "\xC0\xCE\xB0\xA3\xC1\xB7\x5C\xBF\xEE\xBF\xB5\xC0\xDA\x5C\xBF\xEE\xBF\xB5\xC0\xDA\x32\x5F\xB3\xB2\x5F\xB0\xCB" . ".Spr";
				//ÀÎ°£Á·\¿î¿µÀÚ\¿î¿µÀÚ2_³²_°Ë.Spr
				
		$offsets[] = pack("I", $exe->str($code,"rva"));
		
		//Step 2 : Find RVA of GM act file
		$code = "\xC0\xCE\xB0\xA3\xC1\xB7\x5C\xBF\xEE\xBF\xB5\xC0\xDA\x5C\xBF\xEE\xBF\xB5\xC0\xDA\x32\x5F\xB3\xB2\x5F\xB0\xCB" . ".Act";
				//ÀÎ°£Á·\¿î¿µÀÚ\¿î¿µÀÚ2_³²_°Ë.Act
		
		$offsets[] = pack("I", $exe->str($code,"rva"));
	
		foreach($offsets as $offset)
		{
			//Step 3 : Find Pushed location
			$code = "\x68" . $offset;
			$finish = $exe->code($code, "");
			
			//Step 4 : Find Pattern within boundary from finish (lets say within 0x200 bytes)
			$code = "\x83\xC4\x04"				//add esp, 4
					."\x84\xC0"					//test al,al
					."\x0F\x84\xAB\xAB\x00\x00";	//jz <location skipping GM sprite override>
					
			$location = $exe->match($code, "\xAB", $finish - 0x200, $finish);
			
			//Step 5 : Prep pattern for replace
			$diff = $exe->read($location + 7, 4, "I");
			$code = "\xE9" . pack("I", $diff + 1) . "\x90";
			
			//Step 6 : Replace pattern
			$exe->replace($location, array(5 => $code));
		}		
	}
?>