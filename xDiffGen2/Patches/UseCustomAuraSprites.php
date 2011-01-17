<?php
    function UseCustomAuraSprites($exe){
        if ($exe === true) {
            return new xPatch(45, 'Use Custom Aura Sprites', 'Data');
        }
        $free = 0x380;
        $code =  "\x68" . pack("I", $exe->str("effect\\ring_blue.tga","rva"))
                ."\x8B\xCE"                 // mov     ecx, esi        ; int
                ."\xE8\xAB\xAB\xAB\xAB"
                ."\xE9\xAB\xAB\xAB\xAB"
                ."\x6A\x00"
                ."\x68" . pack("I", $exe->str("effect\\pikapika2.bmp","rva"));
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        $exe->replace($offset, array(1 => pack("I", ($exe->imagebase() + $free)), 20 => pack("I", ($exe->imagebase() + $free + 21))));
        $code =  "effect\aurafloat.tga\x00"
                ."effect\auraring.bmp\x00\x90";
        $exe->insert($code, $free);
        return true;
    }
?>