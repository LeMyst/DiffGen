<?php
        function PacketsKeysChange($exe) {
			return new xPatchGroup(91, 'Packets Keys Change', array(
				'firstkey',
				'secondkey',
				'thirdkey'));
		}
		
		// Search for PACKET_CZ_ENTER it's a little bit at the top position of that string
		
		function firstkey($exe) {
			if ($exe === true) {
				return new xPatch(92, 'First key', '', 91, 'Change the 1st key for packet encryption.');
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
				
				$exe->addInput('$firstkey', XTYPE_STRING, '', 8, 8);
				
				$exe->replace($offset, array(9 => '$firstkey')); // first key
		
				return true;
        }
		
		function secondkey($exe) {
			if ($exe === true) {
				return new xPatch(93, 'Second key', '', 91, 'Change the 1st key for packet encryption.');
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
		
		function thirdkey($exe) {
			if ($exe === true) {
				return new xPatch(94, 'Third key', '', 91, 'Change the 1st key for packet encryption.');
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
				
				$exe->addInput('$thirdkey', XTYPE_STRING, '', 8, 8);
				
				$exe->replace($offset, array(19 => '$thirdkey')); // thirdkey key
		
				return true;
        }
?>