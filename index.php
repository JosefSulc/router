<?php

require('router.php');
header('Content-Type: text/html; charset=utf-8');
echo 'Router class navrátí tohle:<br>';
print_r(router::route('hue/kai/ser'));
echo '<br> po použití příkazu "router::route("hue/kai/ser");';
echo '<br> z těhle masek v .txt: <br>';
echo '<pre>';
echo file_get_contents('routes.txt');
echo '</pre>';