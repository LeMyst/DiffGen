<?php
		// Patches 1-5

    function AllowChatFlood($exe) {
	    return new xPatchGroup(1, 'Allow Chat Flood', array(
	    	'AllowChatFlood25Lines',
	    	'AllowChatFlood50Lines',
	    	'AllowChatFlood100Lines',
	    	'AllowChatFloodUnlimited'));
    }
    
    function AllowChatFlood25Lines($exe) {
        if ($exe === true) {
            return new xPatch(2, 'Allow Chat Flood (25 lines)', 'UI', 1);
        }
        $code =  "\x83\x3D\xAB\xAB\xAB\xAB\x0A"    // cmp     Langtype, 10
                ."\x74\xAB"                        // jz      short loc_5CE560
                ."\x83\x7C\x24\x04\x02"            // cmp     [esp+arg_0], 2    ; <-- Patch
                ."\x7C\x47"                        // jl      short loc_5CE560
                ."\x6A\x00";                       // push    ebx
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(13 => "\x18"));
        return true;
    }
    
    function AllowChatFlood50Lines($exe) {
        if ($exe === true) {
            return new xPatch(3, 'Allow Chat Flood (50 lines)', 'UI', 1);
        }
        $code =  "\x83\x3D\xAB\xAB\xAB\xAB\x0A"    // cmp     Langtype, 10
                ."\x74\xAB"                        // jz      short loc_5CE560
                ."\x83\x7C\x24\x04\x02"            // cmp     [esp+arg_0], 2    ; <-- Patch
                ."\x7C\x47"                        // jl      short loc_5CE560
                ."\x6A\x00";                       // push    ebx
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(13 => "\x31"));
        return true;
    }
    
    function AllowChatFlood100Lines($exe) {
        if ($exe === true) {
            return new xPatch(4, 'Allow Chat Flood (100 lines)', 'UI', 1);
        }
        $code =  "\x83\x3D\xAB\xAB\xAB\xAB\x0A"    // cmp     Langtype, 10
                ."\x74\xAB"                        // jz      short loc_5CE560
                ."\x83\x7C\x24\x04\x02"            // cmp     [esp+arg_0], 2    ; <-- Patch
                ."\x7C\x47"                        // jl      short loc_5CE560
                ."\x6A\x00";                       // push    ebx
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(13 => "\x63"));
        return true;
    }
    
    function AllowChatFloodUnlimited($exe) {
        if ($exe === true) {
            return new xPatch(5, 'Allow Chat Flood (unlimited lines)', 'UI', 1);
        }
        $code =  "\x83\xC4\x08"             // add     esp, 8
                ."\x84\xC0"                 // test    al, al
                ."\x74\x08"                 // jz      short loc_5DA6A6
                ."\xFF\xAB\xAB\xAB\x00\x00" // inc     dword ptr [ebp+498h]
                ."\xEB\x0A";                // jmp     short loc_5DA6B0
        $offsets = $exe->code($code, "\xAB", 4);
        if ($offsets === false) {
            echo "Failed in part 1";
            return false;
        }
        foreach ($offsets as $offset) {
            $exe->replace($offset, array(5 => "\xEB"));
        }
        return true;
    }
?>