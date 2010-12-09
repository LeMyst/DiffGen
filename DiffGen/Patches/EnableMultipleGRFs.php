<?php
// Enable Multiple GRF files
// adds support to load GRF files from a list inside DATA.INI
//
// 26.11.2010 - Organized DiffPatch into a working state for vc9 compiled clients [Yom]
//

    function EnableMultipleGRFs($exe){
        if ($exe === true) {
            return "[Data](7)_Enable_Multiple_GRFs";
        }
        // remove rdata.grf string to stop it loading
        $offset = $exe->str("rdata.grf","raw");
        if ($offset === false) {
            echo "Failed in part 1";
            return false;
        }
        $exe->replace($offset, array(0 => "\x00\x00\x00\x00\x00\x00\x00\x00\x00"));
        
        // Locate call to grf loading function
        $grf = pack("I", $exe->str("data.grf","rva"));
        $code =  "\x68" . $grf                      // push    offset aData_grf ; "data.grf"
                ."\xB9\xAB\xAB\xAB\x00"             // mov     ecx, offset unk_86ABBC
                ."\xE8\xAB\xAB\xAB\xAB"             // call    CFileMgr::AddPak()
                ."\x8B\xAB\xAB\xAB\xAB\x00";        // mov     edx, ds:dword_7AA7CC
        $offset = $exe->code($code, "\xAB");
        if ($offset === false) {
            echo "Failed in part 2";
            return false;
        }
        // find the codecave to inject our data.ini load function
        $free = $exe->zeroed(251); // find 251 bytes
        // $zeroed = str_repeat("\x00", 251);
        // $free = $exe->match($zeroed);
        if ($free === false) {
            echo "Failed in part 3";
            return false;
        }
        $rdata = $exe->getSection(".rdata");
        $vrdata = $rdata->vOffset - $rdata->rOffset;
        $text = $exe->getSection(".text");
        $vtext = $text->vOffset - $text->rOffset;
        // read mov and call from WinMain();
        $mov = $exe->read($offset + 5, 5);
        $call = $exe->read($offset + 11, 4, "i");
        // patch
        $exe->replace($offset, array(0 => "\xE8" . pack("I", ($free + $vrdata) - ($offset + 5 + $vtext)) . "\x90\x90\x90\x90\x90\x90\x90\x90\x90\x90"));

        // GetModuleHandleA
        $getmodulehandlea = $exe->func("GetModuleHandleA");
        if ($getmodulehandlea === false) {
            echo "Failed in part 5";
            return false;
        }
        // GetProcAddress
        $getprocaddress = $exe->func("GetProcAddress");
        if ($getprocaddress === false) {
            echo "Failed in part 6";
            return false;
        }

        // string locations after injected code
        $dataini        = pack("I", ($exe->imagebase() + $vrdata + $free + 170)); // DATA.INI
        $getprofile     = pack("I", ($exe->imagebase() + $vrdata + $free + 181)); // GetPrivateProfileStringA
        $writeprofile   = pack("I", ($exe->imagebase() + $vrdata + $free + 206)); // WritePrivateProfileStringA
        $data           = pack("I", ($exe->imagebase() + $vrdata + $free + 233)); // Data
        $kernel32       = pack("I", ($exe->imagebase() + $vrdata + $free + 238)); // KERNEL32
        $call           = pack("i", (($call + $offset + 15 + $vtext) - ($free + 120 + $vrdata)));
        
        $code =  "\xC8\x80\x00\x00"                                        // enter   80h, 0
                ."\x60"                                                    // pusha
                ."\x68" . $kernel32                                        // push    offset ModuleName ; "KERNEL32"
                ."\xFF\x15" . pack("I", $getmodulehandlea)                 // call    ds:GetModuleHandleA
                ."\x85\xC0"                                                // test    eax, eax
                ."\x74\x23"                                                // jz      short loc_735E01
                ."\x8B\x3D" . pack("I", $getprocaddress)                   // mov     edi, ds:GetProcAddress
                ."\x68" . $getprofile                                      // push    offset aGetprivateprof ; "GetPrivateProfileStringA"
                ."\x89\xC3"                                                // mov     ebx, eax
                ."\x50"                                                    // push    eax             ; hModule
                ."\xFF\xD7"                                                // call    edi ; GetProcAddress()
                ."\x85\xC0"                                                // test    eax, eax
                ."\x74\x0F"                                                // jz      short loc_735E01
                ."\x89\x45\xF6"                                            // mov     [ebp+var_A], eax
                ."\x68" . $writeprofile                                    // push    offset aWriteprivatepr ; "WritePrivateProfileStringA"
                ."\x89\xD8"                                                // mov     eax, ebx
                ."\x50"                                                    // push    eax             ; hModule
                ."\xFF\xD7"                                                // call    edi ; GetProcAddress()
                ."\x85\xC0"                                                // test    eax, eax
                ."\x74\x6E"                                                // jz      short loc_735E71
                ."\x89\x45\xFA"                                            // mov     [ebp+var_6], eax
                ."\x31\xD2"                                                // xor     edx, edx
                ."\x66\xC7\x45\xFE\x39\x00"                                // mov     [ebp+var_2], 39h ; char 9
                ."\x52"                                                    // push    edx
                ."\x68" . $dataini                                         // push    offset a_Data_ini ; ".\\DATA.INI"
                ."\x6A\x74"                                                // push    74h
                ."\x8D\x5D\x81"                                            // lea     ebx, [ebp+var_7F]
                ."\x53"                                                    // push    ebx
                ."\x8D\x45\xFE"                                            // lea     eax, [ebp+var_2]
                ."\x50"                                                    // push    eax
                ."\x50"                                                    // push    eax
                ."\x68" . $data                                            // push    offset aData_2  ; "Data"
                ."\xFF\x55\xF6"                                            // call    [ebp+var_A]
                ."\x8D\x4D\xFE"                                            // lea     ecx, [ebp+var_2]
                ."\x66\x8B\x09"                                            // mov     cx, [ecx]
                ."\x8D\x5D\x81"                                            // lea     ebx, [ebp+var_7F]
                ."\x66\x3B\x0B"                                            // cmp     cx, [ebx]
                ."\x5A"                                                    // pop     edx
                ."\x74\x0E"                                                // jz      short loc_735E44
                ."\x52"                                                    // push    edx
                ."\x53"                                                    // push    ebx
                . $mov                                                     // mov     ecx, offset unk_810248
                ."\xE8" . $call                                            // call    CFileMgr::AddPak()
                ."\x5A"                                                    // pop     edx
                ."\x42"                                                    // inc     edx
                ."\xFE\x4D\xFE"                                            // dec     byte ptr [ebp+var_2]
                ."\x80\x7D\xFE\x30"                                        // cmp     byte ptr [ebp+var_2], 30h ; char 0
                ."\x73\xC1"                                                // jnb     short loc_735E0E
                ."\x85\xD2"                                                // test    edx, edx
                ."\x75\x20"                                                // jnz     short loc_735E71
                ."\x68" . $dataini                                         // push    offset a_Data_ini ; ".\\DATA.INI"
                ."\x68" . $grf                                             // push    offset aData_grf ; "data.grf"
                ."\x66\xC7\x45\xFE\x32\x00"                                // mov     [ebp+var_2], 32h
                ."\x8D\x45\xFE"                                            // lea     eax, [ebp+var_2]
                ."\x50"                                                    // push    eax
                ."\x68" . $data                                            // push    offset aData_2  ; "Data"
                ."\xFF\x55\xFA"                                            // call    [ebp+var_6]
                ."\x85\xC0"                                                // test    eax, eax
                ."\x75\x97"                                                // jnz     short loc_735E08
                ."\x61"                                                    // popa
                ."\xC9"                                                    // leave
                ."\xC3"                                                    // retn
                
                // strings
                .".\\DATA.INI\x00"
                ."GetPrivateProfileStringA\x00"
                ."WritePrivateProfileStringA\x00"
                ."Data\x00"
                ."KERNEL32\x00";
        
        $exe->insert($code, $free);
        $free = $exe->zeroed(251); // find 251 bytes
        return true;
    }
?>