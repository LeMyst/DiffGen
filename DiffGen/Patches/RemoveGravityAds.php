<?php
function RemoveGravityAds($exe){
  if ($exe === true) {
    return new xPatch(38, 'Remove Gravity Ads', 'UI');
  }

  // T_중력성인.tga
  $code = "\x54\x5F\xC1\xDF\xB7\xC2\xBC\xBA\xC0\xCE\x2E\x74\x67\x61";
  $offset = $exe->matches($code, "\xAB", 0);
  if (count($offset) != 1) {
    echo "Failed in part 1";
    return false;
  }
  
  $exe->replace($offset[0], array(0 => "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00"));

  // T_GameGrade.tga
  $code = "\x54\x5F\x47\x61\x6D\x65\x47\x72\x61\x64\x65\x2E\x74\x67\x61";
  $offset = $exe->matches($code, "\xAB", 0);
  if (count($offset) != 1) {
    echo "Failed in part 3";
    return false;
  }
  $exe->replace($offset[0], array(0 => "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00"));

  // T_테입%d.tga
  $code = "\x54\x5F\xC5\xD7\xC0\xD4\x25\x64\x2E\x74\x67\x61";
  $offset = $exe->matches($code, "\xAB", 0);
  if (count($offset) != 1) {
    echo "Failed in part 4";
    return false;
  }
  $exe->replace($offset[0], array(0 => "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00"));

  return true;
}
?>