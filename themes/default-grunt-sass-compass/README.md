DEVELOPMENT INFORMATION
=========================

###Contents:
* grunt
* Write your own form-template
* Write your own theme

-------------------

###grunt

You need to install grunt as follows

-------------------

> You may need to prepend sudo to these commands

* In case you don't have the grunt-cli yet:  
    `npm install -g grunt-cli`     

* In this theme-folder do:  
    `npm install -S grunt`

    `npm install grunt-contrib-watch --save-dev`  
    `npm install grunt-contrib-concat --save-dev`  
    `npm install grunt-contrib-compass --save-dev`  
    `npm install grunt-contrib-uglify --save-dev`  

* run `grunt`
    It will watch the login/js/ and login/css/ folders, lint, concat and minify them into login/static/

-------------------
