<?php
    function ReadDataFolderFirst($exe) {
        if ($exe === true) {
            return "[Data]_Read_Data_Folder_First_(Recommended)";
        }
        $readfolder = pack("I", $exe->str("readfolder","rva"));
        $loading    = pack("I", $exe->str("loading","rva"));
        $code =  "\x68" . $readfolder           // push    offset aReadfolder ; "readfolder"
                ."\x8B\xAB"                     // mov     ecx, ebp
                ."\xE8\xAB\xAB\xAB\xAB"         // call    XMLElement::FindChild
                ."\x85\xC0"                     // test    eax, eax
                ."\x74\x07"                     // jz      short loc_543B67
                ."\xC6\x05\xAB\xAB\xAB\xAB\x01" // mov     byte_86ABB8, 1
                ."\x68" . $loading;             // push    offset aLoading ; "loading"
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(14 => "\x90\x90"));
        return true;
    }
?>