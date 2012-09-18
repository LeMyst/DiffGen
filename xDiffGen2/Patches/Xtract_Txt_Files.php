<?php
    function Xtract_Txt_Files($exe) {
        global $target;
        
        if ($exe === true) {
            return new xPatch(55, 'eXtract txt file strings', 'Yom');
        }
		$code = ".txt\x00";
		$section = $exe->getSection(".rdata");
        $offsets = $exe->matches($code, "\xAB", $section->rOffset, $section->rOffset + $section->rSize);
        if ($offsets === false) {
            echo "Failed in part 1";
            return false;
        }
		$fp = fopen("Extracted_data\\Loaded_Txt_Files\\" . basename($target, ".exe") . ".txt", 'w');
        fwrite($fp,"Extracted With DiffGen2\n\n");
		
		foreach($offsets AS $offset){
			// read backwards to find the \x00 byte before the string
			$end = $offset + 3;
			while($exe->read($offset,1) != "\x00" && $exe->read($offset,1) != "\x40"){
				$offset--;
			}
			$string = $exe->read($offset+1, $end - $offset);
			fwrite($fp,"$string\n");
			//echo "\n" .$string;
		}
        fclose($fp);
    }
?>