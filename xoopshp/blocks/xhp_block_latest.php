<?php
// $Id: xhp_block_latest.php 11919 2013-08-14 14:07:10Z beckmi $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

// $options[0]: sorting order <DESC or ''> //
// $options[1]: num to list //
// $options[2]: module dirname //

function b_XHP_latest_show($options)
{
    global $xoopsDB, $xoopsUser, $xoopsConfig;
    $myts      =& MyTextSanitizer::getInstance();
    $block     = array();
    $mydirname = $options[2];
    $mymodpath = "modules/$mydirname";
    include "$mymodpath/module_prefix.php";
    if (file_exists("$mymodpath/language/" . $xoopsConfig['language'] . "/main.php")) {
        include "$mymodpath/language/" . $xoopsConfig['language'] . "/main.php";
    } else {
        include "$mymodpath/language/english/main.php";
    }
    $mytablename = $xoopsDB->prefix($module_prefix . "_quiz");
    if ($xoopsUser) {
        $alert = "";
    } else {
        $alert = " onClick='alert(\"" . _MD_ALERTGUEST . "\")'";
    }
    $sql
            = "SELECT artid, secid, title, posted, counter, display, expire FROM $mytablename WHERE display=1  ORDER BY posted $options[0] LIMIT $options[1]";
    $result = $xoopsDB->query($sql);
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $items            = array();
        $items['ref']     = $mymodpath . "/index.php?op=viewarticle&amp;artid=" . $myrow['artid'];
        $items['title']   = $myts->makeTboxData4Show($myrow["title"]);
        $items['posted']  = $myrow['posted'];
        $items['counter'] = $myrow['counter'];
        $items['alert']   = $alert;
        $block['items'][] = $items;
    }
    return $block;
}

function b_XHP_latest_edit($options)
{
    $form = _MB_XHP_ITEMS_ORDER . "&nbsp;<select name='options[]'>";
    $form .= "<option value='DESC'";
    if ($options[0] == "DESC") {
        $form .= " selected='selected'";
    }
    $form .= ">" . _MB_XHP_ITEMS_DESC . "</option>\n";
    $form .= "<option value=''";
    if ($options[0] == "") {
        $form .= " selected='selected'";
    }
    $form .= ">" . _MB_XHP_ITEMS_ASCEND . "</option>\n";
    $form .= "</select>\n";
    $form .= "&nbsp;" . _MB_XHP_ITEMS_DISP . "&nbsp;<input type='text'  size=5 name='options[]' value='" . $options[1]
        . "' />&nbsp;" . _MB_XHP_ITEMS_ARTCLS . "";
    $form .= "<input type='hidden' name='options[]' value='" . $options[2] . "'>";


    return $form;
}

?>