<?php
// $Id: xhp_block_ranking.php 11919 2013-08-14 14:07:10Z beckmi $
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

// Variables
// $options[0]: sorting order <DESC or ''> //
// $options[1]: num to list //
// $options[2]: avg threshold //
// $options[3]: module dirname //

/**
 * @param $options
 * @return array
 */
function b_XHP_ranking_show($options)
{
    global $xoopsDB, $xoopsUser, $xoopsConfig;
    $myts      = MyTextSanitizer::getInstance();
    $block     = array();
    $mydirname = $options[3];
    $mymodpath = "modules/$mydirname";
    include "$mymodpath/module_prefix.php";
    $rsl    = $xoopsDB->prefix($module_prefix . '_results');
    $usr    = $xoopsDB->prefix('users');
    $sql    =
        "SELECT round(avg(score),2) AS average, $rsl.uid, $usr.uname FROM $rsl INNER JOIN $usr ON $rsl.uid=$usr.uid GROUP BY $rsl.uid HAVING average >= $options[2] ORDER BY average $options[0] LIMIT $options[1]";
    $result = $xoopsDB->query($sql);
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $items            = array();
        $items['average'] = $myrow['average'];
        $items['uname']   = $myrow['uname'];
        $items['uid']     = $myrow['uid'];
        $block['items'][] = $items;
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_XHP_ranking_edit($options)
{
    $form = _MB_XHP_ITEMS_ORDER . "&nbsp;<select name='options[]'>";
    $form .= "<option value='DESC'";
    if ($options[0] === 'DESC') {
        $form .= " selected='selected'";
    }
    $form .= '>' . _MB_XHP_ITEMS_DESC . "</option>\n";
    $form .= "<option value=''";
    if ($options[0] === '') {
        $form .= " selected='selected'";
    }
    $form .= '>' . _MB_XHP_ITEMS_ASCEND . "</option>\n";
    $form .= "</select>\n";
    $form .= '&nbsp;' . _MB_XHP_ITEMS_DISP . "&nbsp;<input type='text' size=5 name='options[]' value='" . $options[1] . "' />&nbsp;" . _MB_XHP_ITEMS_ARTCLS . "<br>\n";
    $form .= '&nbsp;' . _MB_XHP_MINIMUM . "&nbsp;<input type='text' size=5 name='options[]' value='" . $options[2] . "' />&nbsp; %";
    $form .= "<input type='hidden' name='options[]' value='" . $options[3] . "'>";

    return $form;
}
