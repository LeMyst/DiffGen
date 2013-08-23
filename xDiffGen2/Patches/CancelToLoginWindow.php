<?php
    function CancelToLoginWindow($exe) 
	{
        if ($exe === true) {
            return new xPatch(97, 'Cancel to Login Window', 'Fix', 0, 'Makes clicking the Cancel button in Character selection window return to login window instead of Quitting');
        }
		
		//Step 1 - Find the offset of Message (Since Client is not translated we will use the original korean B8 DE BD C3 C1 F6
		$msg = $exe->str("\xB8\xDE\xBD\xC3\xC1\xF6", "rva");
		if ($msg == false)
		{
			echo "Failed in Step 1";
			return false;
		}
		
		//Step 2 - Find the location where the message box gets displayed and client quits
		$prefix =  "\x6A\x78"				//PUSH 78
		          ."\x68\x18\x01\x00\x00"	//PUSH 118
				  ;
				  
		$code =  "\x68".pack("I",$msg)	//PUSH <title name (Message in translated version)>
				."\xAB"					//PUSH reg32 (contains 0)
				."\xAB"					//PUSH reg32 (contains 0)
				."\x6A\x01"				//PUSH 1
				."\x6A\x02"				//PUSH 2
				."\x6A\x11"				//PUSH 11
				;
		$overwriter = $exe->code($prefix.$code, "\xAB");
		if ($overwriter == false)
		{
			$prefix = "";
			$overwriter = $exe->code($code, "\xAB");
		}		
		if ($overwriter == false)
		{			
			echo "Failed in Step 2";
			return false;
		}
		$winoffset = strlen($prefix.$code) + 5 + 3 + 1 + 5 + 5 + 5 + 6; //CALL + ADD ESP + PUSH reg + MOV reg, offset + CALL + CMP eax, value + JNE long
		
		
		//Step 3 - Find CConnection::Disconnect & CRagConnection::instanceR
		//3.1 - Find the signature 
		$code =  "\x68\xAB\xAB\xAB\x00"	//PUSH OFFSET "5,01,2600,1832"
				."\x51"					//PUSH ECX
				."\xFF\xD0"				//CALL EAX
				."\x83\xC4\x08"			//ADD ESP, 8
				."\xE8"					//CALL CRag
				;
				
		$offset = $exe->code($code, "\xAB");
		if ($offset == false)
		{
			echo "Failed in Step 3.1";
			return false;
		}
		
		//3.2 - Read function addresses.
		$crag = $offset + 16 + $exe->read($offset + 12, 4, "i");
		$ccon = $offset + 23 + $exe->read($offset + 19, 4, "i");//NO RVA conversion needed since we are traversing same section.
		
		//Step 4 - Prep the replace code
		//4.1 - Disconnect from Char server
		$code =  "\xE8".pack("i", $crag - ($overwriter + 5))	//CALL CRagConnection::instanceR
				."\x8B\xC8"										//MOV ECX, EAX
				."\xE8".pack("i", $ccon - ($overwriter + 12))	//CALL CConnection::disconnect
				;
				
		//4.2 - Append Window Caller - read from existing code (till window code)
		$code .= $exe->read($overwriter + $winoffset, 15);
		
		//4.3 - Provide Login Window's code and call the Window caller.
		$code .=  "\x68\x23\x27\x00\x00"	//PUSH 2723
			    . "\xFF\xD0"				//CALL EAX
			    . "\xEB".pack("c", ($winoffset + 15 + 4) - (12 + 15 + 9)) //JMP to PUSH ESI below - skipping rest.
			  ;
		
		//Step 5 - Replace with the prepped code
		$exe->replace($overwriter, array(0=>$code));		
		return true;
	}
?>