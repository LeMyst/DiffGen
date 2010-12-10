<?php
// 10.12.2010 - As far as I see those three codes that were searched before aren't necessary in VC9 [Shinryo]

    function IncreaseViewID($exe) {
        if ($exe === true) {
            return "[Add]_Increase_Headgear_ViewID_to_2000";
        }
        
        // In case of break:
        // Search for "ReqAccName" and search somewhere below for the
        // maximum ViewID.
        // inc     eax
				// cmp     eax, <maxViewID>
				// mov     [esp+4Ch+Src], eax
				// jl      short <location>                
				$code	=	"\x40\x3D\xE8\x03\x00\x00\x89\x44\x24\xAB\x7C\x9F";
				$offset	=	$exe->code($code,	"\xAB");
				if ($offset	===	false) {
					echo "Failed in	part 1";
					return false;
				}

				$exe->replace($offset, array(2 =>	"\xD0\x07"));
				
        return true;
    }
?>