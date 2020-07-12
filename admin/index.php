<?php

include_once "admin_header.php";

$op = "list";

if (isset($HTTP_GET_VARS)) {
    foreach ($HTTP_GET_VARS as $k => $v) {
        $$k = $v;
    }
}

if (isset($HTTP_POST_VARS)) {
    foreach ($HTTP_POST_VARS as $k => $v) {
        $$k = $v;
    }
}

if ( !empty($contents_preview) ) {
    $myts =& MyTextSanitizer::getInstance();
    xoops_cp_header();

    $html = empty($nohtml) ? 1 : 0;
    $smiley = empty($nosmiley) ? 1 : 0;
    $xcode = empty($noxcode) ? 1 : 0;
    $p_title = $myts->makeTboxData4Preview($album);
    $p_contents = $myts->makeTareaData4Preview($comentario, $html, $smiley, $xcode);
    echo"<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td class='bg2'>
    <table width='100%' border='0' cellpadding='4' cellspacing='1'>
    <tr class='bg3' align='center'><td align='left'>$p_title</td></tr><tr class='bg1'><td>$p_contents</td></tr></table></td></tr></table><br />";
    $album = $myts->makeTboxData4PreviewInForm($album);
    $comentario = $myts->makeTareaData4PreviewInForm($comentario);
    include "contentsform.php";

    xoops_cp_footer();
    exit();
}

if ($op == "list") {
    // List quoete in database, and form for add new.
    $myts =& MyTextSanitizer::getInstance();
    xoops_cp_header();

    echo "
    <h4 style='text-align:left;'>"._IS_MOD."</h4>
    <form action='index.php' method='post'>
    <table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td class='bg2'>
    <table width='100%' border='0' cellpadding='4' cellspacing='1'>
    <tr class='bg3' align='center'><td align='left'>"._IS_URL."</td><td>"._IS_KATEB."</td><td>&nbsp;</td></tr>";
    $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("sound"));
    $count = 0;
    while ( list($id, $media, $kateb) = $xoopsDB->fetchRow($result) ) {
        $media=$myts->makeTboxData4Edit($media);
        $kateb=$myts->makeTboxData4Edit($kateb);
        echo "<tr class='bg1'><td align='left'>
            <input type='hidden' value='$id' name='id[]' />
            <input type='hidden' value='$media' name='oldmedia[]' />
            <input dir='ltr' name='newmedia[]' value='$media'  size='70' />
            </td>
        <td align='center'>
            <input type='hidden' value='$kateb' name='oldkateb[]' />
            <input type='text' value='$kateb' name='newkateb[]' maxlength='255' size='20' />
        </td>
        <td nowrap='nowrap' align='left'><a href='index.php?op=del&amp;id=".$id."&amp;ok=0'>"._DELETE."</a></td></tr>";
        $count++;
    }
    if ($count > 0) {
        echo "<tr align='center' class='bg3'><td colspan='4'><input type='submit' value='"._SUBMIT."' /><input type='hidden' name='op' value='edit' /></td></tr>";
    }
    echo "</table></td></tr></table></form>
    <br /><br />
    <h4 style='text-align:left;'>"._IS_NW."</h4>
    <form action='index.php' method='post'>
    <table border='0' cellpadding='0' cellspacing='0' width='100%'>
        <tr>
        <td class='bg2'>
            <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                <tr nowrap='nowrap'>
                <td class='bg3'>"._IS_KATEB." </td>
                <td class='bg1'>
                    <input type='text' name='kateb' size='40' maxlength='255' />
                </td></tr>
                <tr nowrap='nowrap'>
                <td class='bg3'>"._IS_URL." </td>
                <td class='bg1'>
                    <input  type='text' name='media' size='40' maxlength='255' dir='ltr' value='http://' />
                </td></tr>
                <tr>
                <td class='bg3'>&nbsp;</td>
                <td class='bg1'>
                    <input type='hidden' name='op' value='add' />
                    <input type='submit' value='"._SUBMIT."' />
                </td></tr>
            </table>
        </td></tr>
    </table>
    </form>";

    xoops_cp_footer();
    exit();
}

if ($op == "add") {
    // Add quote
    $myts =& MyTextSanitizer::getInstance();
    $artista = $myts->makeTboxData4Save($kateb);
    $media = $myts->makeTboxData4Save($media);
    $newid = $xoopsDB->genId($xoopsDB->prefix("sound")."id");
    $sql = "INSERT INTO ".$xoopsDB->prefix("sound")." (id, kateb, media) VALUES (".$newid.", '".$kateb."', '".$media."')";
    if (!$xoopsDB->query($sql)) {
        xoops_cp_header();
        echo "Could not add category";
        xoops_cp_footer();
    } else {
        redirect_header("index.php?op=list",1,_XD_DB);
    }
    exit();
}

if ($op == "edit") {
    // Edit quotes
    $myts =& MyTextSanitizer::getInstance();
    $count = count($newkateb);
    for ($i = 0; $i < $count; $i++) {
        if ( $newkateb[$i] != $oldkateb[$i] || $newmedia[$i] != $oldmedia[$i]) {
            $kateb = $myts->makeTboxData4Save($newkateb[$i]);
            $media = $myts->makeTboxData4Save($newmedia[$i]);
            $sql = "UPDATE ".$xoopsDB->prefix("sound")." SET kateb='".$kateb."',media='".$media."' WHERE id=".$id[$i]."";
            $xoopsDB->query($sql);
        }
    }
    redirect_header("index.php?op=list",1,_XD_DB);
    exit();
}

if ($op == "del") {
    // Delete quote
    if ($ok == 1) {
        $sql = "DELETE FROM ".$xoopsDB->prefix("sound")." WHERE id = ".$id ;
        if (!$xoopsDB->query($sql)) {
            xoops_cp_header();
            echo "Could not delete category";
            xoops_cp_footer();
        } else {
            redirect_header("index.php?op=list",1,_XD_DB);
        }
        exit();
    } else {
        xoops_cp_header();
        xoops_confirm(array('op' => 'del', 'id' => $id, 'ok' => 1), 'index.php', _IS_DEL);
        xoops_cp_footer();
        exit();
    }
}

?>