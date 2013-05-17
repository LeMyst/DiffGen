<?php
    function IncreaseScreenshotQuality($exe) {
        if ($exe === true) {
            return new xPatch(74, 'Increase Screenshot Quality', 'UI', 0, '');
        }
		
		/*
		  "C74424 70 03000000"   // MOV     DWORD PTR SS:[ESP+70h],3    ; DIBChannels
          "C74424 74 02000000"   // MOV     DWORD PTR SS:[ESP+74h],2    ; DIBColor
        */
		
        $code =  "\xC7\x44\x24\x70\x03\x00\x00\x00\xC7\x44\x24\x74\x02\x00\x00\x00";
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
		$exe->addInput('$uQuality', XTYPE_STRING, '', 2, 2);
		
        $exe->replace($offset, array(1 => "\x84")); // MOV DST operand 8 bit -> 32 bit
		$exe->replace($offset, array(3 => "\xAC\x00")); // [ESP+70h] -> [ESP+0ACh]
		$exe->replace($offset, array(7 => '$uQuality')); // uQuality
		$exe->replace($offset, array(8 => "\x00\x00\x00\x90\x90\x90\x90\x90")); // Filling
		
        return true;
    }
?>