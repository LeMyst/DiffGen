<?php
    function SkipLicenseScreen($exe) {
        if ($exe === true) {
           return new xPatch(50, 'Skip License Screen', 'UI', 0, 'Skip the warning screen and goes directly to the main window with the Service Select.');
        }
        
        // Find jump table
        //$ptr = $exe->code("\x18\xAB\x00\x00\x00\x83\xF8\xAB\x0F\x87\xAB\xAB\x00\x00\xFF\x24\x85\xAB\xAB\xAB\x00", "\xAB", 1);
        //if( $ptr === false ) {
        //    echo "Failed in part 1";
        //    return false;
        //}
		// Search NUMACCOUNT , then xref that function to locate the jump table
        
        //print "Ptr: ".dechex($ptr)."\n";
        
        // JMP DWORD PTR DS:[EAX*4+<address>]
		$code =  "\xFF\x24\x85\xAB\xAB\xAB\x00"
				."\x8D\xB3\xAB\xAB\x00\x00"
				."\x8B\xAB";
        $ptr = $exe->code($code, "\xAB", 3);
        if( $ptr === false ) {
            echo "Failed in part 2";
            return false;
        }
		$ptr = $ptr[2];
        //print "Ptr: ".dechex($ptr)."\n";
        // Read the value where the first entry of the jump table resides.
        // Note: raw and virtual offset aren't the same in VC9 clients!
        // Therefore the difference has to be calculated also.
        $ptr = $exe->read($ptr + 3, 4, 'V') - $exe->imagebase() - $exe->getSection(".text")->vrDiff;
        
        // Read third entry from the jump table
        $bin = $exe->read($ptr + 8, 4);
        
        // Replace first and second entry with the third entry
        $exe->replace($ptr, array(0 => $bin, 4 => $bin));
                
        return true;
    }
?>