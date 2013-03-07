# Wurzelstrang CMS Installation

[Wurzelstrang CMS v0](https://bitbucket.org/LordNiko/1pagecms)

## User Requirements

* Persona

  > Wurzelstrang does its user authorization using Mozillas Persona. So you are needed to have an account. Register at [Persona](https://login.persona.org)


## System Requirements

* [php5](http://php.net/)
* [sqlite](https://sqlite.org/)
* [Apache mod_rewrite](http://httpd.apache.org/docs/2.2/mod/mod_rewrite.html)
        * To make sure, .htaccess is working right. In Apache:
                `/etc/apache2/sites-available/default`
        * Change `AllowOverride None` to `AllowOverride All`
        * Reload Apache by typing `sudo /etc/init.d/apache2 reload`>

** System Recommendations **

* [curl](http://curl.haxx.se/)
* HTTPS/SSL
  
  > Wurzelstrang CMS is preconfigured to be used via HTTPS. Please consider it if you havent already, since otherwise your sites security comes close to none. You dont need to buy a certificate and it is easy to create your own. But if you fear no evil, just dont forget to configure and you are free to go. Who am I to tell you not to cross that invisible bridge over the river full of crocodiles.  
  > Sincerely, your instructions manual


## Installation

* [Download or clone the latest release](https://bitbucket.org/LordNiko/wurzelstrang/)
* Go to the Wurzelstrang-directory and upload erverything.
* In the shell, type `sudo chown -R www-data:www-data db` so the database-file can be written.
* Now edit `config.php`. Open it in a plain text editor (not MS Word), read its comments for help and edit accordingly.
* Open install.php in your Browser.
* Fill in the form. You can edit all these informations anytime later if you want. Again, it is important for you to enter a valid Persona-account and since it wont be checked at that time, make sure you have no typo.  
* Continue and voila: Your Wurzelstrang should be set up with some Dummy-Content.
* If something went wrong and you cant go to install.php again, go to the directory/db/ and delete the content.db file. Then you should be able to go to install.php again.


## Success!

That should be it! 
Take a breath, grab a beer and continue sometime later.  

...  
  
Now open /login in your Browser, login and happy editing!
