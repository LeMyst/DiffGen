<?php
// 10.12.2010 - Changed behaviour of this diff to always (in any case) use Arial on all language types. [Shinryo]

    function UseChineseFontOnAllLangtypes($exe) {
        if ($exe === true) {
            return new xPatch(89, 'Use Chinese on all Langtype', 'UI', 0, 'Makes MingLiu the default font on all Langtypes');
        }
        
		$code ="\x75\x5B\x8D\x57\xFF\x83\xFA\x0A\x77\x53";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\x75\x5B\x8D\x57\xFF\x83\xFA\x0A\xEB\x0C"));
		
		$code ="\x41\x72\x69\x61\x6C\x00\x00\x00";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(0 => "\x4D\x69\x6E\x67\x4C\x69\x55\x00"));
		
        return true;
    }
?>