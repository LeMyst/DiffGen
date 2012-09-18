<?php
	function UseRagnarokIcon($exe) {
		if ($exe === true) {
			return new xPatch(47, 'Use Ragnarok Icon', 'UI', 0, 'Makes the hexed client use the RO program icon instead of the generic Win32 app icon.');
		}
		$code = "\x72\x00\x00\x00\xAB\x01\x00\x80";
		$section = $exe->getSection(".data");
		$offset = $exe->match($code, "\xAB", $section->rOffset);
		if ($offset === false) {
			echo "Failed in part 1";
			return false;
		}
		$old_value = $exe->read($offset + 4, 2, "S") + 24;
		$new_value = pack("S", $old_value);
		$exe->replace($offset, array(4 => $new_value));
		return true;
	}
?>