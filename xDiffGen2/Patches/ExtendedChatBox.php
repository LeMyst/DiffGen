<?php
    function ExtendedChatBox($exe){
        if ($exe === true) {
            return new xPatch(20, 'Extended Chat Box', 'UI', 0, 'Extend the Main/Battle chat box max input chars from 70 to 234.');
        }
		
        $offsets = $exe->code("\xC7\x40\x64\x46", "", -1);
        if (count($offsets) != 4) 
		{
			$offsets = $exe->code("\xC7\x40\x68\x46", "", 4);
		}
		
		if (count($offsets) != 4) 
		{
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offsets[3], array(3 => "\xEA"));  // \xEA
        return true;
    }
?>