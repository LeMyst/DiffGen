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
  
  // Shinro:
  // VC9 compiler finally recognized to store
  // float values which are used more than once
  // at an offset and use FLD/FSTP to place
  // those in registers.
  $code =  "\xA0\x41\x91\x00\xD9\x5C\x24\x08\x8B" // <----- Value for camera angle (A0 41 91)
		  ."\x96\xD4\x00\x00\x00\xD9\x42\x44\xDA";
          
  $offset = $exe->match($code, "\xAB");
  if ($offset === false) {
    echo "Failed in part 1";
    return false;
  }
  
  $exe->replace($offset, array(0 => "\xAE\xE0\xDD"));
  
  return true;
}

function FixCameraAnglesLess($exe){
  if ($exe === true) {
    return new xPatch(25, 'Fix Camera Angles (LESS)', 'UI', 23, 'Unlocks the possible camera angles to give more freedom of placement. This enables an 30deg angle ');
  }
  
  // Shinro:
  // VC9 compiler finally recognized to store
  // float values which are used more than once
  // at an offset and use FLD/FSTP to place
  // those in registers.
  $code =  "\x74\xAB\xD9\x05\xAB\xAB\xAB\x00\xD9\x5C\x24\x08";
         
  $offset = $exe->match($code, "\xAB");
  
  if ($offset === false) {
    echo "Failed in part 1";
    return false;
  }
  
  $offset+=4;
  
  $free = $exe->zeroed(4, false);
  $exe->replace($free, array(0 => "29.50"));
  
  $free_offset = $exe->Raw2Rva($free);
  $exe->replace($offset, array(0 => $free_offset));
  
  return true;
}

function FixCameraAnglesFull($exe){
  if ($exe === true) {
    return new xPatch(26, 'Fix Camera Angles (FULL)', 'UI', 23, 'Unlocks the possible camera angles to give more freedom of placement. This enables an almost ground-level camera.');
  }
  
  // Shinro:
  // VC9 compiler finally recognized to store
  // float values which are used more than once
  // at an offset and use FLD/FSTP to place
  // those in registers.
  $code =  "\x74\xAB\xD9\x05\xAB\xAB\xAB\x00\xD9\x5C\x24\x08";
         
  $offset = $exe->match($code, "\xAB");
  
  if ($offset === false) {
    echo "Failed in part 1";
    return false;
  }
  
  $offset+=4;
  
  $free = $exe->zeroed(4, false);
  $exe->replace($free, array(0 => "65.00"));
  
  $free_offset = $exe->Raw2Rva($free);
  $exe->replace($offset, array(0 => $free_offset));
  
  return true;
}
?>
