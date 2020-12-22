# wbsh_gen.php
> Generate an encrypted webshell to mantain access to a server.


wbsh_gen.php can be used to create a
<a href="https://en.wikipedia.org/wiki/Web_shell">web shell</a> with an encrypted payload, as a way of keeping it undetected.
The payload is encrypted using the OpenSSL extension avaiable for PHP > 5.3.0. Older PHP versions could require other cryptographic extensions or even a personalized encryption algorithm.
The default cipher is AES-256 in CTR mode, but any cipher supported by the OpenSSL extension can be used through the -c option.

## File structure
	
	wbsh/
	  |----- LICENSE (GNU GPL license text)
	  |----- README.md (This README file)
	  |
	  |----- src/
		      |----- payload.php (an example of payload)
		      |----- wbsh_gen.php (the main script of this project)


## Usage

```
wbsh_gen.php [option(s)] -p <payload> -k <password> -o <output>

Options:

   -h              Display this help message and exit
   -s              Save the password to a session cookie
   -f              Add a login form to access the web shell
   -p <payload>    The file containing the payload
   -n <field>      The name of the password field
   -k <password>   The password to encrypt the payload
   -i <passfile>   Read the password from file
   -c <cipher>     The cipher algorithm and mode of operation as
                   identified by openssl (example: AES-256-CTR)
   -m POST|GET     The method used to get the password
   -r              Fill the end of the code with random noise
   -o <output>     Save the web shell as <output_file>

```

## Examples


```
	user@host:wbsh$ ./wbsh_gen.php -p payload.php -k secret123 -o ws.php
```


```
	user@host:wbsh$ ./wbsh_gen.php -f -p payload.php -i key.txt -o form.php
```


```
	user@host:wbsh$ ./wbsh_gen.php -f -s -p payload.php -k secret123 -o session.php
```

## Testing and deploying

First, we need a payload to be executed upon entering the correct password. The file payload.php contains a very simple and limited shell-like web interface that allows the execution of arbitrary commands using the native PHP function shell_exec().

For this example, we will create a web shell with a form to enter the password (what can be a really bad idea in a real life scenario). After entering it, the password will be saved into a session cookie until the end of the current session (which can as well be a bad idea, but this is just a demonstration).

```
user@host:wbsh/src$ ./wbsh_gen.php -r -f -s -p payload.php -k mypassword -o evil.php
```

The -r option will fill the end of the PHP code with some random noise, further hiding the legible part of the code.

Now, we can test it using the built-in PHP server by running (assuming your current directory contains the generated file):

```
user@host:wbsh/src$ php -S localhost:8000
```

Then, point your browser to http://localhost:8000/evil.php and have fun!

## Notes and hints

- Do not (and I can't emphasize this enough) do not use this to perform any illegal activity. If you do so, it will be your sole responsability and you must be prepared for the consequences if you get caught.

- The default method used by the generated script to retrieve the password is POST. You can change it by using -m POST|GET. But I would advise you against using GET as it could potentially generate a log entry on the server containing your password.

- Using the -s option to save the password in a session cookie can be very pratical, as you would need to type it only once for each session. The problem is that it could be used by someone to recover your password or even help identifying you.

- Never leave a webshell on a server for too long. A suspicious file containing seemingly encrypted random noise is sure to raise a lot of suspicion. After gaining access to a shell on the server, use it to get high (root) privileges and with that set up a better method of maintaining access (such as a backdoor or even a rootkit).

- If you need to leave the webshell on the server for a long time, always make sure that the file you are accessing is the same one you left there. A smart administrator could tamper with the webshell so that it saves the credentials used to access it, to log all your actions or even warn it at the time of access. To avoid this, always keep a hash image of the webshell with you and once logged in, make sure it was not tampered with by comparing the hash of the file on the server to the one generated at the time of file creation. If they do not match, you may have been compromised. Burn your PC and run for your life... (but first, take a copy of the tampered file so that you can find out exactly what the administrator did and what he may have accomplished with it)

## Meta

Lucas V. Araujo – lucas.vieira.ar@disroot.org

Distributed under the GNU GPL license. See ``LICENSE`` for more information.

[https://github.com/LvMalware/wbsh](https://github.com/LvMalware/)

## Contributing

1. Fork it (<https://github.com/LvMalware/wbsh/fork>)
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request

## IMPORTANT:
#### This project was created as a case of study and only for research purposes. Any use of it for illegal purposes is your sole responsibility.