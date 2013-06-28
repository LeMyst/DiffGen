<?php
// Patches 23-26
function FixCameraAngles($exe) {
	return new xPatchGroup(23, 'Fix Camera Angles', array(
			'FixCameraAnglesRecomm',
			'FixCameraAnglesLess',
			'FixCameraAnglesFull'));
}

function FixCameraAnglesRecomm($exe){
	if ($exe === true) {
		return new xPatch(24, 'Fix Camera Angles', 'UI', 23, 'Unlocks the possible camera angles to give more freedom of placement. Gives a medium range of around 60 degress');
	}
	return _fixangle($exe, "\x00\x00\x28\x42"); //little endian hex of 42.00
}
  
function FixCameraAnglesLess($exe){
	if ($exe === true) {
		return new xPatch(25, 'Fix Camera Angles (LESS)', 'UI', 23, 'Unlocks the possible camera angles to give more freedom of placement. This enables an 30deg angle ');
	}	
	return _fixangle($exe, "\x00\x00\xEC\x41"); //little endian hex of 29.50
}

function FixCameraAnglesFull($exe){
	if ($exe === true) {
		return new xPatch(26, 'Fix Camera Angles (FULL)', 'UI', 23, 'Unlocks the possible camera angles to give more freedom of placement. This enables an almost ground-level camera.');
	}
	return _fixangle($exe, "\x00\x00\x82\x42"); //little endian hex of 65.00
}
	
function _fixangle($exe, $newvalue){
  
	// Shinryo:
	// VC9 compiler finally recognized to store
	// float values which are used more than once
	// at an offset and use FLD/FSTP to place
	// those in registers.

	if ($exe->clientdate() <= 20130605)
		$code =  "\x74\xAB\xD9\x05\xAB\xAB\xAB\x00\xD9\x5C\x24\x08";
	else
		$code =  "\x74\xAB\xD9\x05\xAB\xAB\xAB\x00\xD9\x5D\xFC\x8B";

	$offset = $exe->match($code, "\xAB");
	if ($offset === false) {
		echo "Failed in part 1";
		return false;
	}	  
	
	$free = $exe->zeroed(4);
	$exe->insert($newvalue, 4, $free);
	
	$exe->replace($offset, array(4 => $exe->Raw2Rva($free)));
  
  return true;
}
?>
