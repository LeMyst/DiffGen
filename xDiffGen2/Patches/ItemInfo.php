<?php
function ItemInfo($exe) {
    if ($exe === true) {
        return new xPatch(65, 'Load ItemInfo.lua before lub', 'UI', 0, 'If the client has been update, your ItemInfo.lub with your translated items will be lost, check the option to keep your items into the lua file');
    }

    $code =  "\x2F\x69\x74\x65\x6D\x69\x6E\x66\x6F\x2E\x6C\x75\x62"; // ItemInfo.lub
          
    $offset = $exe->match($code, "\xAB");

    if ($offset === false) {
        echo "Failed in part 1";
        return false;
    }

    $exe->replace($offset, array(12 => "\x61"));
    return true;
}
?>