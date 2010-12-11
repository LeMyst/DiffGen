<?php
function RemoveGravityLogo($exe){
  if ($exe === true) {
    return "[UI]_Remove_Gravity_Logo";
  }
  
  // T_R%d.tga
  $code = "\x54\x5F\x52\x25\x64\x2E\x74\x67\x61";
  $offset = $exe->matches($code, "\xAB", 0);
  if(count($offset) != 1) {
    echo "Failed in part 1";
    return false;
  }

  $exe->replace($offset[0], array(0 => "\x00\x00\x00\x00\x00\x00\x00\x00\x00"));
  
  return true;
}
?>