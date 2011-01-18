<?php
    function LoadLuaBeforeLub($exe) {
        if ($exe === true) {
            return new xPatch(34, 'Load Lua Before Lub', 'Data', 0, 'Makes the client to load .lua files before .lub files');
        }
        $code =  "\x2E\x6C\x75\x61"     // .LUA
                ."\x00\x00\x00\x00"     // padding
                ."\x2E\x6C\x75\x62";    // .LUB
        $offset = $exe->match($code, "\xAB", 0);
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $repl =  "\x2E\x6C\x75\x62"     // .LUB
                ."\x00\x00\x00\x00"     // padding
                ."\x2E\x6C\x75\x61";    // .LUA
        $exe->replace($offset, array(0 => $repl));
        
        return true;
    }
?>