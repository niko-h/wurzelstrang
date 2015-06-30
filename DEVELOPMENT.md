DEVELOPMENT INFORMATION
=========================

###Contents:
* grunt
* Write your own form-template
* Write your own theme

-------------------

###grunt

For developing the Wurzelstrang-Frontend in `/login`, you need to install grunt as follows

-------------------

> You may need to prepend sudo to these commands

* In case you don't have the grunt-cli yet:  
    `npm install -g grunt-cli`     

* In /login do:  
    `npm install -S grunt`

    `npm install grunt-contrib-jshint --save-dev`  
    `npm install grunt-contrib-watch --save-dev`  
    `npm install grunt-contrib-concat --save-dev`  
    `npm install grunt-contrib-csslint --save-dev`  
    `npm install grunt-contrib-watch --save-dev`  
    `npm install grunt-contrib-uglify --save-dev`  

* run `grunt`
    It will watch the login/js/ and login/css/ folders, lint, concat and minify them into login/static/

-------------------
###Write your own form-template

To write your own Form-Template, follow these Steps:

* copy one of the existing template-folders and name it according to how your template should be named.
* edit the index.php to include the func.js from the correct directory
* edit the func.js: rename the first function to be named exactly like your template-folder is named.
* the index.php must contain a text-area with class "contentarea". That is a must have! It contains the data that will be pushed to the database, wether it will be plain text or blob in json.
* from here you have to get through on your own.

-------------------
###Write your own theme

To write your own theme:

* create a new theme-folder in themes/ and name it how you want your theme to be named.
* your theme has to start with a "main.php", which can include all the following stuff.
* have a look at other themes or do it your way. 
* You can use JS to access the database, or you can use php. 
* For the latter, you have a function "CallAPI($method, $url, $data)" 
  * It takes "GET, POST,..." for the first argument.
  * It takes `AUDIENCE."/api/..."` for the second argument to access your local API.
  * $data is optional