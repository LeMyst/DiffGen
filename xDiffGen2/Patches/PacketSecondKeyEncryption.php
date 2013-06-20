<?php
		// Search for PACKET_CZ_ENTER it's a little bit at the top position of that string
		
		function PacketSecondKeyEncryption($exe) {
			if ($exe === true) {
				return new xPatch(93, 'Packet Second Key Encryption', '', 0, 'Change the 2nd key for packet encryption. It needs you don\'t check the patch Disable Packet Header Encryption. Don\'t use it if you don\'t know what you are doing. (Not avaible yet on rathena)
															You have to translate the string entered into hex code to add it in your conf file. You can do it here : http://goo.gl/9Pmxf');
			}
               		
				$code =  "\xFF\xFF"
						."\x8B\x0D\xAB\xAB\xAB\x00"    	   // cmp     Langtype, 10
						."\x68\xAB\xAB\xAB\xAB"                        // jz      short loc_5CE560
						."\x68\xAB\xAB\xAB\xAB"            // cmp     [esp+arg_0], 2    ; <-- Patch
						."\x68\xAB\xAB\xAB\xAB"                        // jl      short loc_5CE560
						."\xE8\xAB\xAB\xAB\xAB";                       // push    ebx
				
				$offset = $exe->code($code, "\xAB");
				if ($offset === false) {
					echo "Failed in part 1";
					return false;
				}
				
				$exe->addInput('$secondkey', XTYPE_STRING, '', 8, 8);
				
				$exe->replace($offset, array(14 => '$secondkey')); // secondkey key
		
				return true;

		}		
?>