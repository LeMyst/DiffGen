<?php
    function SkipLicenseScreen($exe) {
        if ($exe === true) {
            return "[UI](6)_Skip_License_Screen_(Recommended)";
        }
        
        $ptr = $exe->code("\x83\xF8\xAB\xC7\xAB\x18\x01\x00\x00\x00\x0F\x87", "\xAB", 1);
        if( $ptr === false ) {
            echo "Failed in part 1";
            return false;
        }
        $ptr = $exe->match("\xFF\x24\x85", "\xAB", $ptr);
        if( $ptr === false ) {
            echo "Failed in part 2";
            return false;
        }
        $ptr = $exe->read($ptr + 3, 4, 'V') - $exe->imagebase();
        
        $bin = $exe->read($ptr + 8, 4);
        $exe->replace($ptr, array(0 => $bin, 4 => $bin));
        
        return true;
    }
?>