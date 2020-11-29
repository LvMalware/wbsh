# wbsh_gen.php
> Generate an encrypted webshell to mantain access to a server.


wbsh_gen.php is a PHP script that can be used to generate a
<a href="https://en.wikipedia.org/wiki/Web_shell">web shell</a> with an encrypted payload, as a way of keeping it undetected.

## File structure
	
	wbsh/
	  |----- LICENSE (GNU GPL license text)
	  |----- README.md (This README file)
	  |
	  |----- src/
		      |----- payload.php (an example of payload)
		      |----- wbsh_gen.php (the main script of this project)


## Usage examples

```
	user@host:wbsh$ php wbsh_gen.php <payload_file> <password> <output_file>
```


```
	user@host:wbsh$ php wbsh_gen.php payload.php secret123 unsuspecting_file.php
```

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