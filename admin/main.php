<?php
//  ------------------------------------------------------------------------ //
//             --  XoopsHP Module --       Xoops e-Learning System           //
//                     Copyright (c) 2005 SUDOW-SOKEN                        //
//                      <http://www.mailpark.co.jp/>                         //
//  ------------------------------------------------------------------------ //
//               Based on XoopsHP1.01 by Yoshi, aka HowardGee.               //
//  ------------------------------------------------------------------------ //
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
include '../../../include/cp_header.php';

if (file_exists("../language/" . $xoopsConfig['language'] . "/main.php")) {
    include "../language/" . $xoopsConfig['language'] . "/main.php";
} else {
    include "../language/english/main.php";
}

/*********************************************************/
/* Sections Manager Functions                            */
/*********************************************************/
function sections($secid2show = 0)
{
    global $xoopsConfig, $xoopsDB, $xoopsModule, $xoopsModuleConfig;
    xoops_cp_header();
    // JS for checkbox manipulation
    ?>
    <SCRIPT TYPE="text/javascript">
        <!--
        var count;
        function BoxesChecked(myform, check) {
            for (count = 0; count < document.forms(myform).selected.length; count++) {
                document.forms(myform).selected[count].checked = check;
            }
        }
        -->
    </SCRIPT>
    <?php
    echo "<h4>" . _AM_SECCONF . "</h4>";
    include '../module_prefix.php';
    $result = $xoopsDB->query(
        "SELECT secid, secname, secdesc, display, expire FROM " . $xoopsDB->prefix($module_prefix . "_sections")
        . " ORDER BY secname"
    );
    if ($xoopsDB->getRowsNum($result) > 0) {
        $myts =& MyTextSanitizer::getInstance();
        echo "<hr /><h4>" . _MD_CURACTIVESEC . _MD_CLICK2EDIT . "</h4>";
        echo "<form enctype='multipart/form-data' action='main.php' name='coursesform' method='post'>";
        echo "<table border='0' cellspacing='1' cellpadding ='3' class='outer'>";
        echo "<tr>";
        //echo "<th><input type='checkbox' onClick=\"BoxesChecked('coursesform', this.checked);\"></th>";
        echo "<th>" . _MD_SECNAMEC . "</th>";
        echo "<th>" . _MD_SECDESC . "</th>";
        echo "<th>" . _MD_LT_DISPLAY . "</th>";
        echo "<th size=19>" . _MD_LT_EXPIRE . "</th>";
        echo "<th>" . _MD_LT_ACTION . "</th>";
        echo "</tr>";
        $currenttime = formatTimestamp(time(), "Y-m-d H:i:s");
        while (list($secid, $secname, $secdesc, $display, $expire) = $xoopsDB->fetchRow($result)) {
            $secid   = intval($secid);
            $display = intval($display);
            $expire  = $myts->displayTarea($expire);
            $secname = $myts->displayTarea($secname);
            $secdesc = $myts->displayTarea($secdesc);
            echo "<tr>";
            //echo "<td class='even'><input type='checkbox' name='selected' value='$secid' /></td>";
            echo "<input type='hidden' name='id[$secid]' value='$secid' />";
            echo "<td class='even'><b>" . $secname . "</b></td>";
            echo "<td class='even'>" . $secdesc . "</td>";
            $checked = ($display) ? "checked" : "";
            echo "<td class='even'><input type='checkbox' name='display[$secid]' " . $checked . " /></td>";
            if ($expire != '0000-00-00 00:00:00') {
                if ($expire > $currenttime) {
                    echo "<td class='even'>" . $expire . "</td>";
                } else {
                    echo "<td class='even'>" . $expire . "<span style='color:#ff0000;'>(" . _MD_LT_EXPIRED
                        . ")</span></td>";
                }
            } else {
                echo "<td class='even'>" . '-------------------' . "</td>";
            }
            echo
                "<td class='even'><a href='main.php?op=sectionedit&amp;secid=" . $secid . "'>" . _MD_EDIT . "</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br>";
        echo "<input type='hidden' name='op' value='sectiondispchange'>";
        echo "<input type='submit' value=" . _MD_SAVECHANGES . ">";
        echo "</form>";
        echo "<br>";

        echo "<hr><h4>" . _MD_ADDARTICLE . "</h4>";
        echo "<form enctype='multipart/form-data' action='main.php' method='post'>";
        echo "<b>" . _MD_TITLEC . "</b>";
        echo "<input class=textbox type='text' name='title' size=40 value=''><br><br>";
        include '../module_prefix.php';
        $result = $xoopsDB->query(
            "SELECT secid, secname, display, expire FROM " . $xoopsDB->prefix($module_prefix . "_sections")
            . "  ORDER BY secname"
        );
        echo "<b>" . _MD_SECNAMEC . "</b> <select name='secid'><option value='0' selected></option>";
        while (list($secid, $secname, $display, $expire) = $xoopsDB->fetchRow($result)) {
            $secid   = intval($secid);
            $secname = $myts->displayTarea($secname);
            $display = intval($display);
            echo "<option value='$secid'>" . $secname;
            if (!$display) {
                echo " (" . _MD_LT_HIDDEN . ")";
            }
            echo "</option>";
        }
        echo "</select><br><br>";
        echo "<b>" . _MD_CONTENTC . "</b>";
        echo "<input type='file' name='quizfile'>";
        echo "<i>" . _MD_FILE_MAX . intval($xoopsModuleConfig['max_file_size']) . "</i><br><br>";
        echo "<input type='hidden' name='MAX_FILE_SIZE' value='" . $xoopsModuleConfig['max_file_size'] . "'>";
        echo "<b>" . _MD_LT_DISPLAY . "</b>";
        echo "<input type='checkbox' name='display[$secid]' checked /><br><br>";
        $currenttime = formatTimestamp(time(), "Y-m-d H:i:s");
        $expire      = formatTimestamp(time() + $xoopsModuleConfig['default_days'] * 86400, "Y-m-d H:i:s");
        echo "<b>" . _MD_LT_SET_EXPIRE . "</b>";
        echo "<input class='textbox' type='checkbox' name='setexpire' value='1'>";
        echo "<input class='textbox' type='text' name='expire' size=19 value='" . $expire . "'><br>";
        echo "<b>" . _MD_LT_CURRENT_TIME . "</b>: " . $currenttime . "<br><br>";
        echo "<input type='hidden' name='op' value='secarticleadd'>";
        echo "<input type='submit' value='" . _MD_DOADDARTICLE . "'>";
        echo "</form>";
        echo "<br>";

        echo "<hr><h4>" . _MD_LAST20ART . "</h4>";
        echo "<form action='main.php' method='post'>";
        echo "<b>" . _MD_SECNAMEC . "</b>";
        $onchangestr = "onchange=\"location='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname()
            . "/admin/main.php?op=sections&secid='+this.options[this.selectedIndex].value\"";
        echo "<select name='secid'" . $onchangestr . ">";

        include '../module_prefix.php';
        $result = $xoopsDB->query(
            "SELECT secid, secname, display, expire FROM " . $xoopsDB->prefix($module_prefix . "_sections")
            . "  ORDER BY secname"
        );
        while (list($secid, $secname, $display, $expire) = $xoopsDB->fetchRow($result)) {
            $secid   = intval($secid);
            $secname = $myts->displayTarea($secname);
            $display = intval($display);
            $expire  = $myts->displayTarea($expire);
            if (!$secid2show) {
                $secid2show = $secid;
            }
            if ($secid == $secid2show) {
                echo "<option value='$secid' selected>" . $secname;
            } else {
                echo "<option value='$secid'>" . $secname;
            }
            if (!$display) {
                echo " (" . _MD_LT_HIDDEN . ")";
            }
            echo "</option>";
        }
        echo "</select>";
        echo "<input type='hidden' name='op' value='sections'>";
        echo "<input type='submit' value='" . _MD_GO . "'>";
        echo "</form>";

        echo "<form enctype='multipart/form-data' action='main.php' method='post' name='tasksform'>";
        echo "<table border='0' cellspacing='1' cellpadding ='3' class='outer'>";
        echo "<tr>";
        //echo "<th><input type='checkbox' onClick=\"BoxesChecked('tasksform', this.checked);\"></th>";
        echo "<th>" . _MD_TITLEC . "</th>";
        echo "<th>" . _MD_LT_POSTED . "</th>";
        echo "<th>" . _MD_LT_DISPLAY . "</th>";
        echo "<th>" . _MD_LT_EXPIRE . "</th>";
        echo "<th COLSPAN='2'>" . _MD_LT_ACTION . "</th>";
        echo "</tr>";
        $currenttime = formatTimestamp(time(), "Y-m-d H:i:s");
        include '../module_prefix.php';
        $qiz    = $xoopsDB->prefix($module_prefix . "_quiz");
        $result = $xoopsDB->query(
            "SELECT artid, secid, title, posted, display, expire FROM $qiz WHERE secid=" . intval($secid2show)
            . " ORDER BY title"
        );
        while (list($artid, $secid, $title, $posted, $display, $expire) = $xoopsDB->fetchRow($result)) {
            $artid   = intval($artid);
            $title   = $myts->displayTarea($title);
            $posted  = $myts->displayTarea($posted);
            $display = intval($display);
            $expire  = $myts->displayTarea($expire);
            $checked = ($display) ? "checked" : "";
            echo "<tr>" . "<input type='hidden' name='id[$artid]' value='$artid' />"
                //."<td class='even'><input type='checkbox' name='selected' value='$artid' /></td>"
                . "<td class='even'><b>$title</b></td>" . "<td class='even'>$posted</td>" . "<td class='even'><input type='checkbox' name='display[$artid]' "
                . $checked . " /></td>";
            if ($expire != '0000-00-00 00:00:00') {
                if ($expire > $currenttime) {
                    echo "<td class='even'>" . $expire . "</td>";
                } else {
                    echo "<td class='even'>" . $expire . "<span style='color:#ff0000;'>(" . _MD_LT_EXPIRED
                        . ")</span></td>";
                }
            } else {
                echo "<td class='even'>" . '-------------------' . "</td>";
            }
            echo "<td class='even'><a href=main.php?op=secartedit&amp;artid=$artid>" . _MD_EDIT . "</a></td>" . "<td class='even'><a href=main.php?op=secartdelete&amp;artid=$artid>"
                . _MD_DELETE . "</a></td>" . "</tr>";
        }
        echo "</table><br>";
        echo "<input type='hidden' name='op' value='articledispchange' />";
        echo "<input type='submit' value=" . _MD_SAVECHANGES . " />";
        echo "</form>";
    }

    echo "<br />";
    echo "<hr /><h4>" . _MD_ADDNEWSEC . "</h4>";
    echo "<form action='main.php' method='post'>";
    echo "<b>" . _MD_SECNAMEC . "</b>  " . _MD_MAXCHAR . "<br />";
    echo "<input class='textbox' type='text' name='secname' size='40' maxlength='40' /><br /><br />";
    echo "<b>" . _MD_SECDESC . "</b>  " . _MD_EXDESC . "<br />";
    echo "<input class='textbox' type='text' name='secdesc' size='40' maxlength='255' /><br /><br />";
    echo "<b>" . _MD_LT_DISPLAY . "</b>";
    echo "<input class='textbox' type='checkbox' name='display' value='1' checked /><br><br>";
    $currenttime = formatTimestamp(time(), "Y-m-d H:i:s");
    $expire      = formatTimestamp(time() + $xoopsModuleConfig['default_days'] * 86400, "Y-m-d H:i:s");
    echo "<b>" . _MD_LT_SET_EXPIRE . "</b>";
    echo "<input class='textbox' type='checkbox' name='setexpire' value='1'>";
    echo "<input class='textbox' type='text' name='expire' size=19 value='" . $expire . "'><br>";
    echo "<b>" . _MD_LT_CURRENT_TIME . "</b>: " . $currenttime . "<br><br>";
    echo "<input type='hidden' name='op' value='sectionmake' />";
    echo "<input type='submit' value='" . _MD_GOADDSECTION . "' />";
    echo "</form>";

}

function secartedit($artid)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;
    $myts =& MyTextSanitizer::getInstance();
    xoops_cp_header();
    echo "<h4>" . _AM_SECCONF . "</h4>";
    $artid = intval($artid);
    include '../module_prefix.php';
    $result = $xoopsDB->query(
        "SELECT artid, secid, title, content, display, expire FROM " . $xoopsDB->prefix($module_prefix . "_quiz") . " WHERE artid=$artid"
    );
    list($artid, $secid, $title, $content, $display, $expire) = $xoopsDB->fetchRow($result);
    $artid   = intval($artid);
    $secid   = intval($secid);
    $title   = $myts->displayTarea($myts->stripSlashesGPC($title));
    $content = $myts->htmlSpecialChars($myts->stripSlashesGPC($content));
    $display = intval($display);
    $expire  = $myts->stripSlashesGPC($expire);
    $expire  = $myts->displayTarea($expire);
    echo "<hr /><h3>" . _MD_EDITARTICLE . "</h3>";
    echo "<form enctype='multipart/form-data' action='main.php' method='post'>";
    echo "<b>" . _MD_EDITARTID . "&nbsp;&nbsp;" . $artid . "</b><br /><br />";
    echo "<b>" . _MD_TITLEC . "</b><input class='textbox' type='text' name='title' size='40' value='" . $title
        . "' /><br /><br />";
    echo "<b>" . _MD_SECNAMEC . "</b> <select name='secid'>";
    include '../module_prefix.php';
    $result2 = $xoopsDB->query(
        "SELECT secid, secname, display, expire FROM " . $xoopsDB->prefix($module_prefix . "_sections")
        . "  ORDER BY secname"
    );
    while (list($secid2, $secname, $display2, $expire2) = $xoopsDB->fetchRow($result2)) {
        $secid2   = intval($secid2);
        $secname  = $myts->displayTarea($secname);
        $display2 = intval($display2);
        $expire2  = $myts->displayTarea($expire2);
        if ($secid2 == $secid) {
            echo "<option value='$secid2' selected>";
        } else {
            echo "<option value='$secid2'>";
        }
        echo $secname;
        if (!$display2) {
            echo " (" . _MD_LT_HIDDEN . ")";
        }
        echo "</option>";
    }
    echo "</select>";
    echo "<br /><br />";
    echo "<b>" . _MD_LT_DISPLAY . "</b>";
    $checked = ($display) ? "checked" : "";
    echo "<input type='checkbox' name='display' " . $checked . " /><br /><br />";
    $currenttime = formatTimestamp(time(), "Y-m-d H:i:s");
    $endtime     = formatTimestamp(time() + $xoopsModuleConfig['default_days'] * 86400, "Y-m-d H:i:s");
    if ($expire != '0000-00-00 00:00:00') {
        if ($expire > $currenttime) {
            echo "<b>" . _MD_LT_SET_EXPIRE . "</b>: ";
            echo "<input class='textbox' type='checkbox' name='setexpire' value='1' checked>";
            echo "<input class='textbox' type='text' name='expire' size=19 value='" . $expire . "'> <br>";
            echo "<b>" . _MD_LT_CURRENT_TIME . "</b>: " . $currenttime . "<br><br>";
        } else {
            echo "<b>" . _MD_LT_EXPIRE . "</b>: ";
            echo $expire . "<span style='color:#ff0000;'>(" . _MD_LT_EXPIRED . ")</span><br>";
            echo "<b>" . _MD_LT_SET_EXPIRE . "</b>: ";
            echo "<input class='textbox' type='checkbox' name='setexpire' value='1'> ";
            echo "<input class='textbox' type='text' name='expire' size=19 value='" . $endtime . "'><br>";
            echo "<b>" . _MD_LT_CURRENT_TIME . "</b>: " . $currenttime . "<br><br>";
        }
    } else {
        echo "<b>" . _MD_LT_SET_EXPIRE . "</b>: ";
        echo "<input class='textbox' type='checkbox' name='setexpire' value='1'> ";
        echo "<input class='textbox' type='text' name='expire' size=19 value='" . $endtime . "'><br>";
        echo "<b>" . _MD_LT_CURRENT_TIME . "</b>: " . $currenttime . "<br><br>";
    }
    echo "<b>" . _MD_CONTENTC . "</b>" . _MD_READONLY . "<br>";
    //echo "<a href='../main.php?op=viewarticle&amp;artid=$artid' target='quiz_window'><b>Preview</b></a>";

    echo "<textarea class='textbox' name='content' cols='60' rows='10' readonly>$content</textarea>";
    echo "<input type='hidden' name='MAX_FILE_SIZE' value='200000'><br>";
    echo "<b>" . _MD_FILE2REPLACE . "</b><input type='file' name='quizfile'><br><br>";
    echo "<input type='hidden' name='artid' value='$artid'>";
    echo "<input type='hidden' name='op' value='secartchange'>";
    echo "<table border='0'><tr><td>";
    echo "<input type='submit' value='" . _MD_SAVECHANGES . "'>";
    echo "</td></form>";
    echo "<form action='main.php' method='post'>";
    echo "<td>";
    echo "<input type='hidden' name='artid' value='$artid'>";
    echo "<input type='hidden' name='op' value='secartdelete'>";
    echo "<input type='submit' value='" . _MD_DELETE . "'>";
    echo "</td></form></tr></table>";
}

function sectionedit($secid)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;
    xoops_cp_header();
    echo "<h4>" . _AM_SECCONF . "</h4><br />";
    $myts  =& MyTextSanitizer::getInstance();
    $secid = intval($secid);
    include '../module_prefix.php';
    $result = $xoopsDB->query(
        "SELECT secid, secname, secdesc, display, expire FROM " . $xoopsDB->prefix($module_prefix . "_sections") . " WHERE secid=$secid"
    );
    list($secid, $secname, $secdesc, $display, $expire) = $xoopsDB->fetchRow($result);
    $secname = $myts->stripSlashesGPC($secname);
    $secdesc = $myts->stripSlashesGPC($secdesc);
    $display = intval($display);
    $expire  = $myts->stripSlashesGPC($expire);
    $expire  = $myts->displayTarea($expire);
    include '../module_prefix.php';
    $result2 = $xoopsDB->query(
        "select artid from " . $xoopsDB->prefix($module_prefix . "_quiz") . " where secid=$secid"
    );
    $number  = $xoopsDB->getRowsNum($result2);

    echo "<h4>";
    printf(_MD_EDITTHISSEC, $myts->displayTarea($secname));
    echo "</h4>";
    echo "<br />";
    printf(_MD_THISSECHAS, $number);

    echo "<br /><br />";
    echo "<form action='main.php' method='post'><br />";
    echo "<b>" . _MD_SECNAMEC . "</b> " . _MD_MAXCHAR . "<br />";
    echo "<input class='textbox' type='text' name='secname' size='40' maxlength='40' value='" . $myts->displayTarea(
            $secname
        ) . "' /><br /><br />";
    echo "<b>" . _MD_SECDESC . "</b> " . _MD_EXDESC . "<br />";
    echo "<input class='textbox' type='text' name='secdesc' size='40' maxlength='50' value='" . $myts->displayTarea(
            $secdesc
        ) . "' /><br /><br />";
    echo "<input type='hidden' name='secid' value='" . $secid . "' />";
    echo "<b>" . _MD_LT_DISPLAY . "</b>";
    $checked = ($display) ? "checked" : "";
    echo "<input type='checkbox' name='display' value='1' " . $checked . " /><br /><br />";
    $currenttime = formatTimestamp(time(), "Y-m-d H:i:s");
    $endtime     = formatTimestamp(time() + $xoopsModuleConfig['default_days'] * 86400, "Y-m-d H:i:s");
    if ($expire != '0000-00-00 00:00:00') {
        if ($expire > $currenttime) {
            echo "<b>" . _MD_LT_SET_EXPIRE . "</b>: ";
            echo "<input class='textbox' type='checkbox' name='setexpire' value='1' checked>";
            echo "<input class='textbox' type='text' name='expire' size=19 value='" . $expire . "'> <br>";
            echo "<b>" . _MD_LT_CURRENT_TIME . "</b>: " . $currenttime . "<br><br>";
        } else {
            echo "<b>" . _MD_LT_EXPIRE . "</b>: ";
            echo $expire . "<span style='color:#ff0000;'>(" . _MD_LT_EXPIRED . ")</span><br>";
            echo "<b>" . _MD_LT_SET_EXPIRE . "</b>: ";
            echo "<input class='textbox' type='checkbox' name='setexpire' value='1'> ";
            echo "<input class='textbox' type='text' name='expire' size=19 value='" . $endtime . "'><br>";
            echo "<b>" . _MD_LT_CURRENT_TIME . "</b>: " . $currenttime . "<br><br>";
        }
    } else {
        echo "<b>" . _MD_LT_SET_EXPIRE . "</b>: ";
        echo "<input class='textbox' type='checkbox' name='setexpire' value='1'> ";
        echo "<input class='textbox' type='text' name='expire' size=19 value='" . $endtime . "'><br>";
        echo "<b>" . _MD_LT_CURRENT_TIME . "</b>: " . $currenttime . "<br><br>";
    }
    echo "<input type='hidden' name='op' value='sectionchange' />";

    echo "<table border='0'><tr><td>";
    echo "<input type='submit' value='" . _MD_SAVECHANGES . "' />";
    echo "</td></form>";
    echo "<form action='main.php' method='post'>";
    echo "<td>";
    echo "<input type='hidden' name='secid' value='" . $secid . "' />";
    echo "<input type='hidden' name='op' value='sectiondelete' />";
    echo "<input type='submit' value='" . _MD_DELETE . "' />";
    echo "</td></form></tr></table>";

}

function cgi_replace($content)
{
    global $xoopsDB, $xoopsModule;
    if (!ereg(_XD_FB_CODE4RESULTS_MARKER, $content)) {
        $content = ereg_replace(
            _XD_FB_CODE4RESULTS_INSERT,
            _XD_FB_CODE4RESULTS . "\n\n" . _XD_FB_CODE4RESULTS_INSERT,
            $content
        );
        if (!ereg(_XD_FB_CODE4STARTUP, $content)) {
            $content = ereg_replace(_XD_FB_CODE4STARTUP_INSERT, "\\0\n\n" . _XD_FB_CODE4STARTUP . "\n", $content);
        } else {
            redirect_header("main.php", 3, _MD_ERRORQUIZFILE);
        }
        if (!ereg(_XD_FB_CODE4SEND, $content)) {
            $content = ereg_replace(_XD_FB_CODE4SEND_INSERT, "\\0\n\n" . _XD_FB_CODE4SEND, $content);
        } else {
            redirect_header("main.php", 3, _MD_ERRORQUIZFILE);
        }
    }

    $content = ereg_replace("toLocaleString", "toGMTString", $content);
    $action  = XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/process_form.php";

    return (ereg_replace(
        "var ResultForm = '<html><body><form name=\"Results\" action=\"[^\"]*\"",
        "var ResultForm = '<html><body><form name=\"Results\" action=\"$action\" accept-charset=\"EUC-JP\"",
        $content
    ));
}

// URL GET_VARS OPTION
$op = '';

if (isset($HTTP_GET_VARS['op'])) {
    $op     = trim($HTTP_GET_VARS['op']);
    $artid  = (isset($HTTP_GET_VARS['artid'])) ? intval($HTTP_GET_VARS['artid']) : 0;
    $secid  = (isset($HTTP_GET_VARS['secid'])) ? intval($HTTP_GET_VARS['secid']) : 0;
    $res_id = (isset($HTTP_GET_VARS['res_id'])) ? intval($HTTP_GET_VARS['res_id']) : 0;
} elseif (!empty($_POST['op'])) {
    $op     = $_POST['op'];
    $artid  = !empty($_POST['artid']) ? intval($_POST['artid']) : 0;
    $secid  = !empty($_POST['secid']) ? intval($_POST['secid']) : 0;
    $res_id = !empty($_POST['res_id']) ? intval($_POST['res_id']) : 0;
}

switch ($op) {
    case "sections":
        sections($secid);
        break;

    case "sectionedit":
        sectionedit($secid);
        break;

    case "sectionmake":
        $myts    =& MyTextSanitizer::getInstance();
        $secname = !empty($_POST['secname']) ? $myts->stripSlashesGPC($_POST['secname']) : '';
        if (empty($_POST['secname'])) {
            redirect_header("main.php", 2, _MD_ERRORSECNAME);
        } else {
            $secname = $myts->stripSlashesGPC($_POST['secname']);
        }
        $secdesc   = !empty($_POST['secdesc']) ? $myts->stripSlashesGPC($_POST['secdesc']) : '';
        $display   = intval(empty($_POST['display']) ? 0 : 1);
        $setexpire = intval(empty($_POST['setexpire']) ? 0 : 1);
        $expire    = ($setexpire) ? $myts->stripSlashesGPC($_POST['expire']) : '';
        $expire    = $xoopsDB->quoteString($expire);
        $secname   = $xoopsDB->quoteString($secname);
        $secdesc   = $xoopsDB->quoteString($secdesc);
        include '../module_prefix.php';
        $newid = $xoopsDB->genId($xoopsDB->prefix($module_prefix . "_sections") . "_secid_seq");
        include '../module_prefix.php';
        $mytable = $xoopsDB->prefix($module_prefix . "_sections");
        $result  = $xoopsDB->query(
            "INSERT INTO " . $mytable . " (secid, secname, secdesc, display, expire) VALUES ($newid, $secname, $secdesc, $display, $expire)"
        );
        if ($result) {
            redirect_header("main.php?op=sections", 2, _MD_DBUPDATED);
            break;
        } else {
            var_dump($result, $module_prefix, $newid, $display, $expire);
            redirect_header("main.php?op=sections", 2, _AM_MSG_UPDATE_FAILED);
        }

    case "secartdelete":
        xoops_cp_header();
        echo "<h4>" . _AM_SECCONF . "</h4>";
        $myts =& MyTextSanitizer::getInstance();
        if (!empty($_POST['artid'])) {
            $artid = intval($_POST['artid']);
        } elseif (!empty($_GET['artid'])) {
            $artid = intval($_GET['artid']);
        } else {
            $artid = 0;
        }
        $artid = intval($artid);
        include '../module_prefix.php';
        $result = $xoopsDB->query(
            "SELECT title FROM " . $xoopsDB->prefix($module_prefix . "_quiz") . " WHERE artid=$artid"
        );
        list($title) = $xoopsDB->fetchRow($result);
        $title = $myts->displayTarea($title);
        xoops_confirm(
            array('op' => 'secartdelete_ok', 'artid' => $artid),
            'main.php',
            sprintf(_MD_DELETETHISART, $title) . '<br /><br />' . _MD_RUSUREDELART
        );
        break;

    case 'secartdelete_ok':
        $artid = !empty($_POST['artid']) ? intval($_POST['artid']) : 0;
        if ($artid <= 0) {
            redirect_header("main.php?op=sections", 2, _MD_DBNOTUPDATED);
        }
        include '../module_prefix.php';
        $xoopsDB->query("DETELE FROM " . $xoopsDB->prefix($module_prefix . "_quiz") . " WHERE artid=$artid");
        include '../module_prefix.php';
        $xoopsDB->query("DETELE FROM " . $xoopsDB->prefix($module_prefix . "_results") . " WHERE quiz_id=$artid");
        redirect_header("main.php?op=sections", 2, _MD_DBUPDATED);
        break;

    case "sectionchange":
        if ($secid <= 0) {
            redirect_header("main.php?op=sections", 2, _MD_DBNOTUPDATED);
        }
        $myts =& MyTextSanitizer::getInstance();
        if (empty($_POST['secname'])) {
            redirect_header("main.php", 2, _MD_ERRORSECNAME);
        } else {
            $secname = $myts->stripSlashesGPC($_POST['secname']);
        }
        $secdesc   = !empty($_POST['secdesc']) ? $myts->stripSlashesGPC($_POST['secdesc']) : '';
        $secname   = $xoopsDB->quoteString($secname);
        $secdesc   = $xoopsDB->quoteString($secdesc);
        $display   = intval(empty($_POST['display']) ? 0 : 1);
        $setexpire = intval(empty($_POST['setexpire']) ? 0 : 1);
        $expire    = ($setexpire) ? $myts->stripSlashesGPC($_POST['expire']) : '';
        $expire    = $xoopsDB->quoteString($expire);
        include '../module_prefix.php';
        $secid = intval($secid);
        $xoopsDB->query(
            "UPDATE " . $xoopsDB->prefix($module_prefix . "_sections") . " SET secname=$secname, secdesc=$secdesc, display=$display, expire=$expire WHERE secid=$secid"
        );
        redirect_header("main.php?op=sections", 2, _MD_DBUPDATED);
        break;

    case "sectiondispchange":
        foreach ($_POST['id'] as $secid) {
            $secid   = intval($secid);
            $display = intval(empty($_POST['display'][$secid]) ? 0 : 1);
            include '../module_prefix.php';
            $xoopsDB->query(
                "UPDATE " . $xoopsDB->prefix($module_prefix . "_sections") . " set display=$display WHERE secid=$secid"
            );
        }
        redirect_header("main.php?op=sections", 2, _MD_DBUPDATED);
        break;

    case "articledispchange":
        foreach ($_POST['id'] as $artid) {
            $artid   = intval($artid);
            $display = intval(empty($_POST['display'][$artid]) ? 0 : 1);
            include '../module_prefix.php';
            $xoopsDB->query(
                "UPDATE " . $xoopsDB->prefix($module_prefix . "_quiz") . " set display=$display WHERE artid=$artid"
            );
        }
        redirect_header("main.php?op=sections", 2, _MD_DBUPDATED);
        break;

    case "secarticleadd":
        if ($secid <= 0) {
            redirect_header("main.php?op=sections", 2, _MD_ERRORSECNAME);
        }
        $myts =& MyTextSanitizer::getInstance();
        if (empty($_POST['title'])) {
            redirect_header("main.php?op=sections", 2, _MD_ERRORARTNAME);
        } else {
            $title = $myts->stripSlashesGPC($_POST['title']);
        }
        $title   = $xoopsDB->quoteString($title);
        $content = is_uploaded_file($_FILES['quizfile']['tmp_name']) ? implode(file($_FILES['quizfile']['tmp_name']))
            : '';
        if (empty($content)) {
            redirect_header("main.php?op=sections", 2, _MD_ERRORARTCONT);
        }
        $content    = cgi_replace($content);
        $content    = $xoopsDB->quoteString($content);
        $posted     = $xoopsDB->quoteString(date("Y-m-d H:i:s"));
        $poster     = $xoopsUser->getVar("uid");
        $results_to = $xoopsDB->quoteString($xoopsUser->getVar("email"));
        $display    = intval(empty($_POST['display']) ? 0 : 1);
        $setexpire  = intval(empty($_POST['setexpire']) ? 0 : 1);
        $expire     = ($setexpire) ? $myts->stripSlashesGPC($_POST['expire']) : '';
        $expire     = $xoopsDB->quoteString($expire);

        include '../module_prefix.php';
        $newid = $xoopsDB->genId($xoopsDB->prefix($module_prefix . "_quiz") . "_artid_seq");
        include '../module_prefix.php';
        $result = $xoopsDB->query(
            "INSERT INTO " . $xoopsDB->prefix($module_prefix . "_quiz") . " (artid, secid, title, content, posted, poster, results_to, counter, display, expire) VALUES ($newid, $secid, $title, $content, $posted, $poster, $results_to, 0, $display, $expire)"
        );
        if ($result) {
            redirect_header("main.php?op=sections", 2, _MD_DBUPDATED);
            break;
        } else {
            xoops_cp_header();
            echo "<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class='odd'>";
            echo "<a href='./main.php'><h4>" . _AM_SECCONF . "</h4></a>";
            echo _MD_DBNOTUPDATED;
            echo "<br>" . $success;
            echo "</td></tr></table>";
            xoops_cp_footer();
            exit();
        }

    case "secartedit":
        $artid = !empty($_REQUEST['artid']) ? intval($_REQUEST['artid']) : 0;
        if ($artid > 0) {
            secartedit($artid);
        }
        break;

    case "secartchange":
        $artid = !empty($_POST['artid']) ? intval($_POST['artid']) : 0;
        if ($artid <= 0) {
            redirect_header("main.php?op=sections", 2, _MD_DBNOTUPDATED);
        }
        $myts      =& MyTextSanitizer::getInstance();
        $secid     = intval($_POST['secid']);
        $title     = !empty($_POST['title']) ? $myts->stripSlashesGPC($_POST['title']) : '';
        $content   = is_uploaded_file($_FILES['quizfile']['tmp_name']) ? implode(file($_FILES['quizfile']['tmp_name']))
            : '';
        $display   = intval(empty($_POST['display']) ? 0 : 1);
        $setexpire = intval(empty($_POST['setexpire']) ? 0 : 1);
        $expire    = ($setexpire) ? $myts->stripSlashesGPC($_POST['expire']) : '';
        $expire    = $xoopsDB->quoteString($expire);
        $title     = $xoopsDB->quoteString($title);
        if (empty($content)) {
            include '../module_prefix.php';
            $xoopsDB->query(
                "UPDATE " . $xoopsDB->prefix($module_prefix . "_quiz") . " SET secid=$secid, title=$title, display=$display, expire=$expire WHERE artid=$artid"
            );
        } else {
            $content = cgi_replace($content);
            $content = $xoopsDB->quoteString($content);
            include '../module_prefix.php';
            $xoopsDB->query(
                "UPDATE " . $xoopsDB->prefix($module_prefix . "_quiz") . " SET secid=$secid, title=$title, content=$content, display=$display, expire=$expire WHERE artid=$artid"
            );
        }
        redirect_header("main.php?op=sections", 2, _MD_DBUPDATED);
        break;

    case "sectiondelete":
        xoops_cp_header();
        echo "<h4>" . _AM_SECCONF . "</h4>";
        xoops_confirm(
            array('op' => 'sectiondelete_ok', 'secid' => $secid),
            'main.php',
            _MD_RUSUREDELSEC . '<br />' . _MD_THISDELETESALL
        );
        break;

    case 'sectiondelete_ok':
        include '../module_prefix.php';
        $sql = sprintf("DELETE FROM %s WHERE secid = %u", $xoopsDB->prefix($module_prefix . "_quiz"), $secid);
        $xoopsDB->query($sql);
        include '../module_prefix.php';
        $sql = sprintf("DELETE FROM %s WHERE secid = %u", $xoopsDB->prefix($module_prefix . "_sections"), $secid);
        $xoopsDB->query($sql);
        redirect_header("main.php?op=sections", 2, _MD_DBUPDATED);
        break;

    case "resultdelete":
        xoops_cp_header();
        echo "<h4>" . _AM_SECCONF . "</h4>";
        $myts =& MyTextSanitizer::getInstance();
        if (!empty($_POST['res_id'])) {
            $res_id = intval($_POST['res_id']);
        } elseif (!empty($_GET['res_id'])) {
            $res_id = intval($_GET['res_id']);
        } else {
            $res_id = 0;
        }
        include '../module_prefix.php';
        $result = $xoopsDB->query(
            "SELECT quiz_id, uid, score, timestamp FROM " . $xoopsDB->prefix($module_prefix . "_results") . " WHERE id=$res_id"
        );
        list($quiz_id, $uid, $score, $timestamp) = $xoopsDB->fetchRow($result);
        include '../module_prefix.php';
        $result = $xoopsDB->query(
            "SELECT title FROM " . $xoopsDB->prefix($module_prefix . '_quiz') . " WHERE artid=$quiz_id"
        );
        list($title) = $xoopsDB->fetchRow($result);
        $message = "<center><br />" . _MD_RUSUREDELREC . "<br /><br />";
        $message .= "<table border='1'><th>" . _MD_LT_STUDENT . "</th><th>" . _MD_LT_TITLE . "</th><th>" . _MD_LT_SCORE
            . "</th><th>" . _MD_LT_DATE . "</th></tr>";
        $message .= "<tr><td align='center'>" . $xoopsUser->getUnameFromId(
                $uid
            ) . "</td><td align='center'>$title</td><td align='center'>$score</td><td align='center'>$timestamp</td></tr>";
        $message .= "</table></center>";
        xoops_confirm(array('op' => 'resultdelete_ok', 'res_id' => $res_id, 'artid' => $quiz_id), 'main.php', $message);
        break;

    case "resultdelete_ok":
        $res_id = !empty($_POST['res_id']) ? intval($_POST['res_id']) : 0;
        $artid  = !empty($_POST['artid']) ? intval($_POST['artid']) : 0;
        if ($res_id <= 0) {
            redirect_header("main.php?op=sections", 2, _MD_DBNOTUPDATED);
        }
        include '../module_prefix.php';
        $sql = sprintf("DELETE FROM %s WHERE id = %u", $xoopsDB->prefix($module_prefix . "_results"), $res_id);
        $xoopsDB->query($sql);
        redirect_header("../main.php?op=viewresults&amp;artid=$artid", 2, _MD_DBUPDATED);
        break;

    default:
        sections();
        break;
}

xoops_cp_footer();
?>
