<?php

function random_sound() {
    global $xoopsDB;
    $block = array();
    $result = $xoopsDB->query("SELECT media, kateb FROM ".$xoopsDB->prefix("sound")." ORDER BY RAND() LIMIT 1");
    list($media, $kateb)= $xoopsDB->fetchRow($result);
    $block['media']=$media;
    $block['kateb']=$kateb;
    return $block;
}
?>