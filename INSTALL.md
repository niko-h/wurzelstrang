# Wurzelstrang CMS Installation

[Wurzelstrang CMS v0](https://bitbucket.org/LordNiko/1pagecms)


## Getting started

* [Download or clone the latest release](https://bitbucket.org/LordNiko/wurzelstrang/)
* Read this file ;)


## Server-requirements

Make sure you have the following installed.

* php5
* sqlite
* curl is recommended, but not a must have


## HTTPS/SSL
Wurzelstrang CMS is preconfigured to be used via HTTPS. Please consider it if you havent already, since otherwise your sites security comes close to none. You dont need to buy a certificate and it is easy to create your own. But if you fear no evil, just dont forget to configure and you are free to go. Who am I to tell you not to cross that invisible bridge over the river full of crocodiles.  
Sincerely, your instructions manual


## Configuration file

After you downloaded Wurzelstrang you find the config.php in it. Open it in a plain text editor (not MS Word), read its comments for help and edit accordingly.


## Persona

Wurzelstrang does its user authorization using Mozillas Persona. Before you continue, make sure you have a Persona-account. Follow the link to register.
* [Persona](https://login.persona.org)


## install.php

After you edited the config.php and registered your Persona-Account, go to your Browser, go to where your Wurzelstrang-content lies and enter install.php. This might look something like this:  
* localhost/wurzelstrang/install.php  
* www.mycoolsite.com/install.php  
Fill in the form. You can edit all these informations anytime later if you want. Again, it is important you enter a valid Persona-account and since it wont be checked, make sure you have no typo.  
Then continue and voila: Your Wurzelstrang should be set up!  
Some Dummy-Content will be created. You can of course delete that.  
(Help: If something went wrong and you cant enter install.php again, go to the Wurzelstrang-directory, enter the db/ folder and delete the content.db-file. Then you should be able to run install.php again.)


## Success!

That should be it! 
Take a breath, grab a beer and continue sometime later.


...


Now go to /login in your Browser, login and happy editing!

