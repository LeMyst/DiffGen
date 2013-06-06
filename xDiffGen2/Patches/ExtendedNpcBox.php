<?php
function ExtendedNpcBox($exe) {
    if ($exe === true) {
        return new xPatch(69, 'ExtendNpcBox', 'UI', 0, '');
    }

    $code =  "\x81\xEC\x08\x08\x00\x00\xA1\xAB\xAB\xAB\x00\x33\xC4\x89\x84\x24\x04\x08\x00\x00\x56\x8B\xC1\x57\x8B\xBC\x24\x14\x08\x00\x00";
          
    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }

    $exe->replace($offset, array(2 => "\x04\x10"));
    $exe->replace($offset, array(16 => "\x00\x10"));
    $exe->replace($offset, array(27 => "\x10\x10"));

    $code =  "\xFF\xD2\x8B\x8C\x24\x0C\x08\x00\x00\x5F\x5E\x33\xCC\xE8\xAB\xAB\x0C\x00\x81\xC4\x08\x08\x00\x00";
          
    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 2";
        return false;
    }

    $exe->replace($offset, array(5 => "\x08\x10"));
    $exe->replace($offset, array(20 => "\x04\x10"));		
	
    return true;
	
}
?>