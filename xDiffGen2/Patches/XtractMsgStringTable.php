<?php
    function XtractMsgStringTable($exe) {
        global $target;
        
        if ($exe === true) {
            return new xPatch(54, 'eXtract MsgStringTable.txt', 'Yom');
        }
        
        $code = "\x41\x56\x43\x45\x6D\x62\x65\x64\x46\x6F\x6E\x74";
        $offset = $exe->match($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
	$offset += 38;
	echo dechex($offset) . "#";
	$done = false;
	$id = 0;
	$fp = fopen("MsgStringTables\\" . basename($target, ".exe") . "-msgstringtable.txt", 'w');
	while($done == false){
		if($exe->read($offset,4,"L") == $id){
			$str_start_offset = $exe->Rva2Raw($exe->read($offset+4,4,"L"));
			$str_end_offset = $exe->match("\x00","\xAB",$str_start_offset);
			$str = $exe->read($str_start_offset,$str_end_offset-$str_start_offset);
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