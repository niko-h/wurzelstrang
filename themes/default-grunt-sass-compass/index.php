<!--
/***************************
*
* Website
*
**************************/
-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?php echo getSiteinfo('site_title') ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="themes/<?php echo getSiteinfo('site_theme') ?>/static/css/style.css"/>

</head>

<body>
    <div id="mother">
        <div class="languages"><?php echo getLanguages() ?></div>
        
        <div id="head">
            <a href="<?php echo AUDIENCE; ?>"><h1><?php echo getSiteinfo('site_headline') ?></h1></a>
        </div>

        <div id="menu">
            <ul id="menu_list">
                <?php echo getMenu() ?>
            </ul>
        </div>

        <div id="content">
            <?php
            //Call first site in case no site is defined
            if(!isset($_GET['page']) || $_GET['page'] == "") {
                echo getContent(getFirstItemID());
            } else {
                echo getContent();
            }
            ?>
        </div>
        <div id="footer">
            Powered by <a href="http://niko-h.github.com/wurzelstrang" target="_blank">Wurzelstrang</a>
        </div>
    </div>

    <!--*************
        * JavaScript
        *************-->

    <!-- Load jQuery -->
    <script src="login/lib/jquery.min.js"></script>
    <script src="login/lib/jquery.cookie.js"></script>
    <script type="text/javascript" src="themes/<?php echo getSiteinfo('site_theme') ?>/static/js/master.js"></script>

</body>
