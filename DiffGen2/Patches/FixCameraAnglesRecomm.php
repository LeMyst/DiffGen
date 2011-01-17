<?php
function FixCameraAnglesRecomm($exe){
  if ($exe === true) {
    return "[UI](4)_Fix_Camera_Angles_(Recommended)";
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
?>
