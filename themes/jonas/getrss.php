<?php
header ("Content-Type:text/xml");
 
echo file_get_contents('https://alpha-api.app.net/feed/rss/users/156123/posts');

?>