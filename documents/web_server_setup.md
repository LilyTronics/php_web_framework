# How to setup a web server

There are many flavors of web servers. We cannot cover them all.

## XAMPP (Windows)

Download XAMPP from: https://sourceforge.net/projects/xampp/files/. We like to use the portable version.
We extract it to `C:\xampp`. If you use another folder just simply replace this with yours.

Before starting apache, set the port to 8080. Open the file: `C:\xampp\apache\conf\httpd.conf` in a text editor.
Set the port to 8080: `Listen 8080`. Save the file and exit the editor.

* Enable Apache as service:
  * Open a command prompt in administrator mode
  * Go to: C:\xampp\apache\bin
  * Install service: `httpd -k install`
  * Remove service: `httpd -k unistall`
* Enable MySQL as service:
  * Open a command prompt in administrator mode
  * Go to: C:\xampp\mysql\bin
  * Install service: `mysqld --install`
  * Remove service: `mysqld --remove`

Check if the services are started (Windows Services).

Now the web page should be available at: http://localhost:8080.

The MySQL admin page should be available at: http://localhost:8080/phpmyadmin.

The web pages are served from the folder: `C:\xampp\htdocs`. Emty this folder

Clone this repo to the folder `C:\xampp\htdocs`.

The framework webpage should be available at: http://localhost:8080/php_web_framework.
