<?php
    function XtractMsgStringTable($exe) {
        global $target;
        
        if ($exe === true) {
            return new xPatch(54, 'eXtract MsgStringTable.txt', 'Yom');
        }
        
        //$code = "\x41\x56\x43\x45\x6D\x62\x65\x64\x46\x6F\x6E\x74";
		$code = "\x3F\x41\x56\x56\x4E\x49\x49\x6E\x70\x75\x74\x4D\x6F\x64\x65\x40";
        $offset = $exe->match($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
		//$offset += 0x1d3;// - 0x1a8 -8
		if ($exe->clientdate() <= 20130605)
			$offset += 0x23;
		else
			$offset += 0x1D3;
		
		//echo dechex($offset) . "#";
		$done = false;
		$id = 0;
		$fp = fopen("Extracted_data\\MsgStringTables\\" . basename($target, ".exe") . "-msgstringtable.txt", 'w');
		while($done == false){
			if($exe->read($offset,4,"L") == $id){
				$str_start_offset = $exe->Rva2Raw($exe->read($offset+4,4,"L"));
				$str_end_offset = $exe->match("\x00","\xAB",$str_start_offset);
				$str = $exe->read($str_start_offset,$str_end_offset-$str_start_offset);
				$str = preg_replace('/\r\n/', '\\n', $str);
				$str = preg_replace('/\n/', '\\n', $str);
				fwrite($fp,"$str#\n");
				$offset += 0x8;
				$id++;
			} else {
				$done = true;
			}
		}
        fclose($fp);
    }
?>