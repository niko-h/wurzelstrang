# Wurzelstrang CMS Installation

[Wurzelstrang CMS](https://github.com/niko-h/wurzelstrang)

## Quick Install

* [Download or clone the latest release](https://github.com/niko-h/wurzelstrang)
* Read the [INSTALL.md](INSTALL.md).
* Upload erverything to your Server.
* Edit `config-example.php` and change it to `config.php`. Open it in a plain text editor (not MS Word).
* Open install.php in your Browser.


## User Requirements

* Persona

    Wurzelstrang does its user authorization using Mozillas Persona. So you are needed to have an account. Register at [Persona](https://login.persona.org)


## System Requirements

* [>=php5.5](http://php.net/)
* [sqlite](https://sqlite.org/) and PDO
* [Apache mod_rewrite](http://httpd.apache.org/docs/2.2/mod/mod_rewrite.html)
        
    * To make sure, .htaccess is working right in Apache: edit `/etc/apache2/sites-available/default`
    * Change `AllowOverride None` to `AllowOverride All`
    * Reload Apache (try `sudo /etc/init.d/apache2 reload`)

### System Recommendations

* [curl](http://curl.haxx.se/)
* [php-curl](http://www.php.net/manual/en/book.curl.php)
* HTTPS/SSL
  
    Wurzelstrang CMS is meant to be used via HTTPS. Please consider it if you haven't already, since otherwise your sites security comes close to none. You dont need to buy a certificate and it is easy to create your own. If you want to but don't feel able to, have a look at [https://letsencrypt.org/](https://letsencrypt.org/). But if you fear no evil, just dont forget to configure and you are free to go. Who am I to tell you not to cross that invisible bridge over the river full of crocodiles.  
    Sincerely, your instructions manual


## Installation

* [Download or clone the latest release](https://github.com/niko-h/wurzelstrang)
* Go to the Wurzelstrang-directory on your server and upload erverything.
* (Only in case of errors!) In the Shell inside your Wurzelstrang-directory, type `sudo chown -R www-data:www-data db` so the database-file can be written.
* (Only in case of errors!) Also type `sudo chown -R www-data:www-data uploads` for the uploads-directory.
* Now edit `config-example.php` and change it to `config.php`. Read its comments for help and edit accordingly.
* Open install.php in your Browser.
* Fill in the form. You can edit all these informations anytime later if you want. Again, it is important for you to enter a valid Persona-account and since it wont be checked at this time, make sure you have no typo.  
* Continue and voila: Your Wurzelstrang should be set up with some Dummy-Content.
* (Only in case of errors!) If something went wrong and you cant go to install.php again, go to the directory /db/ and delete the content.db file. Then you should be able to go to install.php again.
* Now if that went well, you can (and maybe should) delete install.php


## Success!

That should be it! 
Take a breath, grab a beer and continue sometime later.  

...  
  
Now open /login in your Browser, login and happy editing!

...

For Developer-Notes, read DEVELOPMENT.md