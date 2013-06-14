<?php
// 10.12.2010 - Changed behaviour of this diff to always (in any case) use Arial on all language types. [Shinryo]

    function UseArialOnAllLangtypes($exe) {
        if ($exe === true) {
            return new xPatch(51, 'Ascii & Arial on All Langtypes', 'UI', 0, 'Makes Arial the default font on all Langtypes (it s enable ascii by default');
        }
				
		if ($exe->clientdate() <= 20130605)		
			$code ="\x75\x5B\x8D\x57\xFF\x83\xFA\x0A\x77\x53";
		else
			$code ="\x75\x5A\x8D\x57\xFF\x83\xFA\x0A\x77\x52";
		
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(8 => "\xEB\x0C"));
		
        return true;
    }
?>