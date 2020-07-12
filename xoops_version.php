<?php
$modversion['name'] = _IS_NAME;
$modversion['version'] = 1.0;
$modversion['description'] = _IS_DESC;
$modversion['credits'] = "Fuga meme";
$modversion['author'] = "Dndon http://dndon.com";
$modversion['help'] = "not yet";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "sound";
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "sound";
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";
$modversion['blocks'][1]['file'] = "random_sound.php";
$modversion['blocks'][1]['name'] = _IS_BNAME;
$modversion['blocks'][1]['description'] = _IS_BDESC;
$modversion['blocks'][1]['show_func'] = "random_sound";
$modversion['blocks'][1]['template'] = 'sound.html';

?>