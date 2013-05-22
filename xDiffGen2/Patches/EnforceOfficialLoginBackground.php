<?php

    function EnforceOfficialLoginBackground($exe) {
        if ($exe === true) {
            return new xPatch(76, 'Enforce Official Login Background', 'UI', 0, 'Enforce Official Login Background for all langtype');
        }
        
		$code ="\x75\x5B\x8D\x57\xFF\x83\xFA\x0A\x77\x53";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\x75\x5B\x8D\x57\xFF\x83\xFA\x0A\xEB\x0C"));
        return true;
		
    }
?>