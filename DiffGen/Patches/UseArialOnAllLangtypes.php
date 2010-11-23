<?php
    function UseArialOnAllLangtypes($exe) {
		if ($exe === true) {
			return "[UI](9)_Use_Arial_on_All_Langtypes";
		}
		$codes = array(
		"\x83\xFA\x0A\x0F\x87\xAD\x00\x00\x00\xFF\x24\x95\xAB\xAB\xAB\xAB\xA1\xAB\xAB\xAB\xAB\x83\xF8\x06",
		"\x83\xFA\x06\x0F\x87\xA6\x00\x00\x00\xFF\x24\x95\xAB\xAB\xAB\xAB\xA1\xAB\xAB\xAB\xAB\x83\xF8\x06",
		"\x83\xFA\xAB\x0F\x87\xAB\x00\x00\x00\xFF\x24\x95\xAB\xAB\xAB\xAB\xA1\xAB\xAB\xAB\xAB\x83\xF8\x06",
		);
		$codeoffsets = array(3,3,3);
		$changes = array("\xEB\x10","\xEB\x10","\xEB\x10");
		foreach ($codes as $index => $code) {
			$offset = $exe->code($code, "\xAB");
			if ($offset !== false) {
				break;
			}
		}
		if ($offset === false) {
			echo "Failed in part 1";
			return false;
		}
		$exe->replace($offset, array($codeoffsets[$index] => $changes[$index]));
		return true;
	}
?>