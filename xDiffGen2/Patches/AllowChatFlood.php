<?php
    function AllowChatFlood($exe) {
        if ($exe === true) {
            return new xPatch(2, 'Allow Chat Flood (? lines)', 'UI', 0, 'Disable the clientside repeat limit of 3, and sets it to the specified value.');
        }
		if ($exe->clientdate() <= 20130605) {
			$code =  "\x83\x3D\xAB\xAB\xAB\xAB\x0A"    // cmp     Langtype, 10
					."\x74\xAB"                        // jz      short loc_5CE560
					."\x83\x7C\x24\x04\x02"            // cmp     [esp+arg_0], 2    ; <-- Patch
					."\x7C\x47"                        // jl      short loc_5CE560
					."\x6A\x00";                       // push    ebx
			$type = 0;
		}
		else {
			$code =  "\x83\x3D\xAB\xAB\xAB\xAB\x0A"    // cmp     Langtype, 10
					."\x74\xAB"                        // jz      short loc_xxxxx
					."\x83\x7D\x08\x02"            	   // cmp     [ebp+arg_0], 2    ; <-- Patch
					."\x7C\x49"                        // jl      short loc_xxxxx
					."\x6A\x00";                       // push    ebx
			$type = 1;
		}
		
		$offset = $exe->code($code, "\xAB");
		if ($offset === false) {
			echo "Failed in part 1";
			return false;
		}
		
		$exe->addInput('$allowChatFlood', XTYPE_BYTE, '-1', 1);
		
		if($type==0)
			$exe->replace($offset, array(13 => '$allowChatFlood'));
		else
			$exe->replace($offset, array(12 => '$allowChatFlood'));
        
        return true;
    }    
?>