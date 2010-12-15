<?php

function FixCameraAngles($exe) {
	return new xPatchGroup(23, 'Fix Camera Angles', array(
			'FixCameraAnglesRecomm',
			'FixCameraAnglesLess',
			'FixCameraAnglesFull'));
}

function FixCameraAnglesRecomm($exe){
  if ($exe === true) {
    return new xPatch(24, 'Fix Camera Angles', 'UI', 23);
  }
  
  // Shinro:
  // VC9 compiler finally recognized to store
  // float values which are used more than once
  // at an offset and use FLD/FSTP to place
  // those in registers.
  $code =  "\x00\x00\x00\x80\xB5\xF8\xD4\x3E"
          ."\x00\x00\xA0\x41\x00\x00\x00\x00"; // <----- Value for camera angle
          
  $offset = $exe->match($code, "\xAB");
  if ($offset === false) {
    echo "Failed in part 1";
    return false;
  }
  
  $exe->replace($offset, array(10 => "\x28\x42"));
  
  return true;
}

function FixCameraAnglesLess($exe){
  if ($exe === true) {
    return new xPatch(25, 'Fix Camera Angles (less)', 'UI', 23);
  }
  
  // Shinro:
  // VC9 compiler finally recognized to store
  // float values which are used more than once
  // at an offset and use FLD/FSTP to place
  // those in registers.
  $code =  "\x00\x00\x00\x80\xB5\xF8\xD4\x3E"
          ."\x00\x00\xA0\x41\x00\x00\x00\x00"; // <----- Value for camera angle
          
  $offset = $exe->match($code, "\xAB");
  if ($offset === false) {
    echo "Failed in part 1";
    return false;
  }
  
  $exe->replace($offset, array(10 => "\xEC"));
  
  return true;
}

function FixCameraAnglesFull($exe){
  if ($exe === true) {
    return new xPatch(26, 'Fix Camera Angles (FULL)', 'UI', 23);
  }
  
  // Shinro:
  // VC9 compiler finally recognized to store
  // float values which are used more than once
  // at an offset and use FLD/FSTP to place
  // those in registers.
  $code =  "\x00\x00\x00\x80\xB5\xF8\xD4\x3E"
          ."\x00\x00\xA0\x41\x00\x00\x00\x00"; // <----- Value for camera angle
          
  $offset = $exe->match($code, "\xAB");
  if ($offset === false) {
    echo "Failed in part 1";
    return false;
  }
  
  $exe->replace($offset, array(10 => "\x82\x42"));
  
  return true;
}
?>
