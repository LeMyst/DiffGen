<?php
function ChatColorGM($exe) {
    if ($exe === true) {
        return new xPatch(52, 'GM Chat Color', 'Color', 0, 'Changes the GM Chat color and sets it to the specified value.');
    }
  /*$colors = array(
              "Blue"        => "\x41\x69\xE6",    //Blue
              "LightBlue"   => "\x66\xCC\xFF",    //LightBlue
              "Green"       => "\x96\xF0\x96",    //Green
              "LightGreen"  => "\xCC\xFF\x00",    //LightGreen
              "Orange"      => "\xFA\x8C\x05",    //Orange
              "Pink"        => "\xFA\x14\x96",    //Pink
              "Purple"      => "\x96\x05\xD7",    //Purple
              "Turquoise"   => "\x5A\xA0\xA5",    //Turquoise
              "Red"         => "\xFF\x00\x00",    //Red
              // Not used since the GM Color is yellow by default.
              //"Yellow"      => "\xFF\xFF\x00",  //Yellow
              "White"       => "\xFF\xFF\xFF"     //White
            );*/

  /*$diffs = array();
  foreach($colors as $name => $code)
    $diffs[$name] = "[Color](A)_GM_Chat_Color_(".$name.")";

  if ($exe === true) {
    return $diffs;
  }*/

  $code =  "\x83\xC4\x1C"         // add     esp, 1Ch
          ."\x6A\x00"             // push    0
          ."\x6A\x00"             // push    0
          // push    0FFFFh ; ChatColor #RRGGBBAA
          ."\x68\xFF\xFF\x00\x00"
          ."\x8D\x4C\x24\x14";    // lea     ecx, [esp+118h+Dest]
          
  $offset = $exe->match($code, "\xAB");
  
  // Do not include alpha value.
  //$color = $colors[$key];

  if ($offset === false) {
    echo "Failed in part 1";
    return false;
  }
 
  $exe->addInput('$gmChatColor', XTYPE_COLOR);
  $exe->replaceDword($offset, array(8 => '$gmChatColor'));
  
  return true;
}
?>