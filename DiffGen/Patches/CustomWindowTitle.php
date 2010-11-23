<?php
	function CustomWindowTitle($exe) {
		if ($exe === true) {
			return "[UI]_Custom_Window_Title_(Recommended)";
		}
		$strOff = 0x310;
		global $clientdate, $clienttype;
		$string = $clientdate . $clienttype . " by Diff Team\x00";
		if (!$exe->insert($string, $strOff)) {
			echo "Failed in part 1";
			return false;
		}
		$strOff += $exe->imagebase();
		$code = pack("I", $exe->str("Ragnarok","rva"));
		$offsets = $exe->code($code, "\xAB", -1);
		if ($offsets === false) {
			echo "Failed in part 2 ";
			return false;
		}
		foreach ($offsets as $offset) {
			$exe->replace($offset, array(0 => pack("I", $strOff)));
		}
		return true;
	}
?>