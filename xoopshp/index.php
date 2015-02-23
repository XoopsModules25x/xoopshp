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
include 'header.php';

// License check: Add access permission to the guest group if license hasn't been purchased
$groupperm_handler =& xoops_gethandler('groupperm', 'xoopshp');
if (!$xoopsModuleConfig['has_license']
    && !$groupperm_handler->checkRight(
        'module_read',
        $xoopsModule->getVar('mid'),
        XOOPS_GROUP_ANONYMOUS
    )
) {
    //    $groupperm_handler->addRight('module_read', $xoopsModule->getVar('mid'), XOOPS_GROUP_ANONYMOUS);
    // Heck, can't figure out how to get around the restriction in the kernel, so here's a tentative workaround.
    $query  = "INSERT INTO " . $xoopsDB->prefix('group_permission')
        . " (gperm_name, gperm_itemid, gperm_groupid, gperm_modid) VALUES (" . $xoopsDB->quoteString('module_read')
        . ", " . $xoopsModule->getVar('mid') . ", " . XOOPS_GROUP_ANONYMOUS . ", 1)";
    $result = $xoopsDB->queryF($query);
}

global $isModAdmin;
if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $isModAdmin = true;
} else {
    $isModAdmin = false;
}

function listsections()
{
    global $xoopsConfig, $xoopsModuleConfig, $xoopsDB, $xoopsUser, $xoopsTheme, $xoopsLogger, $xoopsModule, $xoopsTpl, $isModAdmin, $xoopsUserIsAdmin, $xoopsModuleConfig;
    include XOOPS_ROOT_PATH . '/header.php';
    $myts =& MyTextSanitizer::getInstance();
    include 'module_prefix.php';
    $result = $xoopsDB->query(
        "SELECT secid, secname, secdesc, display, expire FROM " . $xoopsDB->prefix($module_prefix . "_sections")
        . " ORDER BY secname"
    );
    echo "<div style='text-align: center;'>";
    echo "<h2 align='center'>";
    printf($xoopsModuleConfig['welcome'], htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES));
    echo "</h2>";
    echo "<h4 align='center'>" . $xoopsModuleConfig['welcome_desc'] . '</h4>';
    echo "<div id='content'>";
    echo "<table border='0' cellspacing='1' cellpadding ='3' class='outer' width ='100%'><tr>";
    echo "<td align='left' valign='top'><b>" . _MD_RETURN2INDEX . "</b></td>";
    if ($xoopsUser) {
        echo
            "<td align='right' valign='center'><a href='index.php?op=portfolio&amp;secid=0&amp;sort_key=timestamp'><span style='font-weight:bold;font-size:larger;'>"
            . _MD_LT_PORTFOLIO . "</span></a></td>";
    }
    echo "</tr></table>";

    echo "<table border='0' cellspacing='1' cellpadding ='3' class='outer' width ='100%'>";
    echo "<tr>";
    echo "<th>" . _MD_SECNAMEC . "</th>";
    echo "<th>" . _MD_SECDESC . "</th>";
    echo "<th>" . _MD_SECQNUM . "</th>";
    if ($xoopsUser) {
        echo "<th>" . _MD_SECDNUM . "</th>";
    }
    echo "<th size=19>" . _MD_LT_EXPIRE . "</th>";
    echo "</tr>";

    while (list($secid, $secname, $secdesc, $display, $expire) = $xoopsDB->fetchRow($result)) {
        if ($display) {
            $secid       = intval($secid);
            $secname     = $myts->stripSlashesGPC($secname);
            $secdesc     = $myts->stripSlashesGPC($secdesc);
            $expire      = $myts->stripSlashesGPC($expire);
            $currenttime = formatTimestamp(time(), "Y-m-d H:i:s");
            echo "<tr>";
            if ($expire != '0000-00-00 00:00:00' && $expire < $currenttime) {
                echo "<td class='even'>" . $myts->displayTarea($secname) . "</td>";
            } else {
                echo "<td class='even'><a href='index.php?op=listarticles&amp;secid=$secid'><b>$secname</b></a></td>";
            }
            echo "<td class='even'>" . $myts->displayTarea($secdesc) . "</td>";
            include 'module_prefix.php';
            $result_db = $xoopsDB->prefix($module_prefix . '_results');
            include 'module_prefix.php';
            $quiz_db = $xoopsDB->prefix($module_prefix . '_quiz');
            $qnum    = $xoopsDB->query("SELECT * FROM $quiz_db WHERE secid=$secid");
            $qnum    = $xoopsDB->getRowsNum($qnum);
            echo "<td class='even' align='center'>$qnum</td>";
            if ($xoopsUser) {
                include 'module_prefix.php';
                $quiz_db = $xoopsDB->prefix($module_prefix . '_quiz');
                if ($isModAdmin) {
                    $query
                        = "SELECT DISTINCT $result_db.quiz_id, $quiz_db.artid, $quiz_db.secid FROM $result_db, $quiz_db WHERE $quiz_db.artid = $result_db.quiz_id AND $quiz_db.secid = $secid";
                } else {
                    $query = "SELECT DISTINCT $result_db.quiz_id, $quiz_db.artid, $quiz_db.secid FROM $result_db, $quiz_db WHERE $quiz_db.artid = $result_db.quiz_id AND $quiz_db.secid = $secid AND uid="
                        . $xoopsUser->getVar('uid');
                }
                $results = $xoopsDB->query($query);
                $done    = $xoopsDB->getRowsNum($results);
                echo "<td class='even' align='center'>$done</td>";
            }
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
            echo "</tr>";
        }
    }
    echo "</table>";

    echo "<table border='0' cellspacing='1' cellpadding ='3' width ='100%'><tr>";
    echo "<td align='right'><a href='" . _MD_CREDITSITE . "' target='_credit'/ > Version " . round(
            $xoopsModule->getVar('version') / 100,
            2
        ) . "</a></td>";
    echo "</tr></table>";
    echo "</div>";
    echo "</div>";
    include '../../footer.php';
}

function listarticles($secid)
{
    global $xoopsConfig, $xoopsModuleConfig, $xoopsUser, $xoopsDB, $xoopsTheme, $xoopsLogger, $xoopsModule, $xoopsTpl, $isModAdmin, $xoopsUserIsAdmin;
    include '../../header.php';
    $myts  =& MyTextSanitizer::getInstance();
    $secid = intval($secid);
    include 'module_prefix.php';
    $result = $xoopsDB->query(
        "SELECT secname, secdesc, display, expire FROM " . $xoopsDB->prefix($module_prefix . "_sections") . " WHERE secid=$secid"
    );
    list($secname, $secdesc, $display, $expire) = $xoopsDB->fetchRow($result);
    $secname = $myts->displayTarea($myts->stripSlashesGPC($secname));
    $secdesc = $myts->displayTarea($myts->stripSlashesGPC($secdesc));
    $display = intval($display);
    $expire  = $myts->displayTarea($myts->stripSlashesGPC($expire));
    // Trap for hidden or expired items
    if (!$display) {
        redirect_header("index.php", 2, _AM_MSG_ACCESS_ERROR);
    } elseif ($expire != '0000-00-00 00:00:00' && $expire < formatTimestamp(time(), "Y-m-d H:i:s")) {
        redirect_header("index.php", 2, _AM_MSG_ACCESS_ERROR);
    }
    include 'module_prefix.php';
    $result = $xoopsDB->query(
        "SELECT artid, secid, title, posted, counter, display, expire FROM " . $xoopsDB->prefix(
            $module_prefix . "_quiz"
        ) . " WHERE secid=$secid" . " ORDER BY title"
    );
    echo "<div style='text-align: center;'>";
    echo "<h2 align='center'>$secname</h2>";
    echo "<h4 align='center'>" . _MD_THEFOLLOWING . "</h4>";
    echo "<div id='content'>";
    echo "<table border='0' cellspacing='1' cellpadding ='3' class='outer' width ='100%'><tr>";
    echo "<td align='left' valign='top'><b><a href=index.php>" . _MD_RETURN2INDEX . "</a> -> " . _MD_RETURN2QUIZ
        . "</b></td>";
    if ($xoopsUser) {
        echo "<td align='right' valign='center'><a href='index.php?op=portfolio&amp;secid=$secid&amp;sort_key=timestamp'><span style='font-weight:bold;font-size:larger;'>"
            . _MD_LT_PORTFOLIO . "</span></a></td>";
        $alert = "";
    } else {
        $alert = " onClick='alert(\"" . _MD_ALERTGUEST . "\")'";
    }
    echo "</tr></table>";
    echo "<table border='0' cellspacing='1' cellpadding ='3' class='outer' width ='100%'>";
    echo "<tr>";
    echo "<th>" . _MD_LT_TITLE . "</th>";
    echo "<th>" . _XD_FB_FINISHED_BY . "</th>";
    if ($isModAdmin) {
        echo "<th>" . _MD_LT_SITEAVG . "</th>";
    } elseif ($xoopsUser) {
        echo "<th>" . _MD_LT_MYMAX . "</th>";
    }
    echo "<th>" . _MD_LT_SITEMAX . "</th>";
    echo "<th>" . _MD_LT_EXPIRE . "</th>";
    if ($xoopsUser) {
        echo "<th colspan=3>" . _MD_LT_ACTION . "</th>";
    }
    echo "</tr>";
    $currenttime = formatTimestamp(time(), "Y-m-d H:i:s");
    while (list($artid, $secid, $title, $posted, $counter, $display, $expire) = $xoopsDB->fetchRow($result)) {
        if ($display) {
            $title  = $myts->displayTarea($title);
            $expire = $myts->stripSlashesGPC($expire);
            echo "<tr>";
            if ($expire != '0000-00-00 00:00:00' && $expire < $currenttime) {
                echo "<td class='even'>$title</td>";
            } else {
                echo "<td class='even'><a href='index.php?op=viewarticle&amp;artid=$artid' target='quiz_window' $alert><b>$title</b></a></td>";
            }
            if ($xoopsUser) {
                $uid = $xoopsUser->getVar('uid');
                include 'module_prefix.php';
                $query1 = "SELECT DISTINCT uid FROM " . $xoopsDB->prefix($module_prefix . '_results')
                    . " WHERE quiz_id=$artid";
                include 'module_prefix.php';
                $query2 = "SELECT score FROM " . $xoopsDB->prefix($module_prefix . '_results')
                    . " WHERE quiz_id=$artid AND uid=$uid";
                if ($isModAdmin) {
                    $results_exist = $xoopsDB->query($query1);
                    $done_by       = $xoopsDB->query($query1);
                } else {
                    $results_exist = $xoopsDB->query($query2);
                    $done_by       = $xoopsDB->query($query1);
                }
                $results_exist = $xoopsDB->getRowsNum($results_exist);
            } else {
                include 'module_prefix.php';
                $query1  = "SELECT DISTINCT uid FROM " . $xoopsDB->prefix($module_prefix . '_results')
                    . " WHERE quiz_id=$artid";
                $done_by = $xoopsDB->query($query1);
            }
            $done_by = $xoopsDB->getRowsNum($done_by);
            echo "<td class='even' align='center'>$done_by</td>";
            include 'module_prefix.php';
            $site_max = $xoopsDB->query(
                "SELECT MAX(score), AVG(score) FROM " . $xoopsDB->prefix($module_prefix . '_results') . " WHERE quiz_id = $artid"
            );
            list($site_max, $site_avg) = $xoopsDB->fetchRow($site_max);
            if ($isModAdmin) {
                echo "<td class='even' align='center'>" . round($site_avg) . "</td>";
            } elseif ($xoopsUser) {
                include 'module_prefix.php';
                $my_max = $xoopsDB->query(
                    "SELECT MAX(score) FROM " . $xoopsDB->prefix($module_prefix . '_results') . " WHERE uid = $uid AND quiz_id = $artid"
                );
                list($my_max) = $xoopsDB->fetchRow($my_max);
                echo "<td class='even' align='center'>$my_max</td>";
            }
            echo "<td class='even' align='center'>$site_max</td>";
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
            if ($xoopsUser) {
                if ($results_exist) {
                    echo "<td class='odd' align='center'><a href='index.php?op=viewresults&amp;artid=$artid&amp;sort_key=timestamp'>"
                        . _MD_LT_RESULTS . "</a></td>";
                } else {
                    echo "<td class='odd' align='center'>&nbsp;</td>";
                }
            }
            if ($isModAdmin) {
                echo "<td class='odd' align='center'><a href='admin/index.php?op=secartedit&amp;artid=$artid'>"
                    . _MD_EDIT . "</a></td>";
                echo "<td class='odd' align='center'><a href='admin/index.php?op=secartdelete&amp;artid=$artid'>"
                    . _MD_DELETE . "</a></td>";
            }
            echo "</tr>";
        }
    }
    echo "</table>";
    echo "<table border='0' cellspacing='1' cellpadding ='3' width ='100%'><tr>";
    echo "<td align='right'><a href='" . _MD_CREDITSITE . "' target='_credit'/ > Version " . round(
            $xoopsModule->getVar('version') / 100,
            2
        ) . "</a></td>";
    echo "</tr></table>";
    echo "</div>";
    echo "</div>";
    include '../../footer.php';
}

function viewarticle($artid)
{
    global $xoopsConfig, $xoopsModuleConfig, $xoopsUser, $xoopsDB, $xoopsTpl, $isModAdmin, $xoopsUserIsAdmin;
    $myts  =& MyTextSanitizer::getInstance();
    $artid = intval($artid);
    include 'module_prefix.php';
    $result = $xoopsDB->query(
        "SELECT secid, title, content, display, expire FROM " . $xoopsDB->prefix($module_prefix . "_quiz") . " WHERE artid=$artid"
    );
    list($secid, $title, $content, $display, $expire) = $xoopsDB->fetchRow($result);
    $secid       = intval($secid);
    $display     = intval($display);
    $expire      = $myts->stripSlashesGPC($expire);
    $currenttime = formatTimestamp(time(), "Y-m-d H:i:s");
    if ($display) {
        include 'module_prefix.php';
        $result2 = $xoopsDB->query(
            "SELECT display, expire FROM " . $xoopsDB->prefix($module_prefix . "_sections") . " WHERE secid=$secid"
        );
        list($display2, $expire2) = $xoopsDB->fetchRow($result2);
        $display2 = intval($display2);
        $expire2  = $myts->stripSlashesGPC($expire2);
        if ($display2) {
            if ($expire2 == '0000-00-00 00:00:00' || $expire2 > $currenttime) {
                if ($expire == '0000-00-00 00:00:00' || $expire > $currenttime) {
                    setcookie("xoopsHP_file_id", $artid);
                    $title = $myts->displayTarea($title);
                    // Can't decide an appropriate sanitizer...
                    //$content = $myts->displayTarea($content, 1);
                    echo $content;
                } else {
                    redirect_header("index.php", 2, _AM_MSG_ACCESS_ERROR);
                }
            } else {
                redirect_header("index.php", 2, _AM_MSG_ACCESS_ERROR);
            }
        } else {
            redirect_header("index.php", 2, _AM_MSG_ACCESS_ERROR);
        }
    } else {
        redirect_header("index.php", 2, _AM_MSG_ACCESS_ERROR);
    }
}

function viewresults($artid, $sort_key)
{
    global $xoopsConfig, $xoopsModuleConfig, $xoopsUser, $xoopsDB, $xoopsTheme, $xoopsLogger, $xoopsModule, $xoopsTpl, $isModAdmin, $xoopsUserIsAdmin;
    include '../../header.php';
    $myts =& MyTextSanitizer::getInstance();

    //Retrieve table data by users
    $artid = intval($artid);
    include 'module_prefix.php';
    $result2 = $xoopsDB->query(
        "SELECT title, posted, secid FROM " . $xoopsDB->prefix($module_prefix . "_quiz") . " WHERE artid=$artid"
    );
    list($title, $posted, $secid) = $xoopsDB->fetchRow($result2);
    $title  = $myts->displayTarea($title);
    $posted = $myts->displayTarea($posted);
    include 'module_prefix.php';
    $result_db = $xoopsDB->prefix($module_prefix . '_results');
    $users_db  = $xoopsDB->prefix('users');
    if ($isModAdmin) {
        $query = "SELECT $result_db.id, $result_db.quiz_id, $result_db.uid, $result_db.score, $result_db.timestamp, $result_db.comment, $users_db.uname, $users_db.name FROM $result_db, $users_db WHERE $result_db.uid = $users_db.uid AND $result_db.quiz_id = $artid ORDER BY "
            . $sort_key;
    } elseif ($xoopsUser) {
        $uid   = $xoopsUser->getVar('uid');
        $query = "SELECT $result_db.id, $result_db.quiz_id, $result_db.uid, $result_db.score, $result_db.timestamp,  $result_db.comment, $users_db.uname, $users_db.name FROM $result_db, $users_db WHERE $result_db.uid = $uid AND $result_db.uid = $users_db.uid AND $result_db.quiz_id = $artid ORDER BY "
            . $sort_key;
    }
    $result = $xoopsDB->query($query);

    echo "<div style='text-align: center;'>";
    echo "<h2 align='center'>" . _MD_LT_RESULTS
        . ": <a href='index.php?op=viewarticle&amp;artid=$artid' target='quiz_window'><span style='font-weight:bold;font-size:larger;'>$title</span></a></h2>";
    echo "<div id='content'>";
    echo "<table border='0' cellspacing='1' cellpadding ='3' class='outer' width ='100%'><tr>";
    echo "<td align='left' valign='top'><b><a href=index.php>" . _MD_RETURN2INDEX . "</a> -> <a href='index.php?op=listarticles&amp;secid=$secid'>"
        . _MD_RETURN2QUIZ . "</a> -> " . _MD_RESULTLIST . " (" . _MD_RESULT_SIMPLE . ") </b></td>";
    echo "<td align='right' valign='center'><a href='index.php?op=viewdetails&amp;artid=$artid&amp;sort_key=end_time'><span style='font-weight:bold;font-size:larger;'>"
        . _MD_RESULT_DETAIL . "</span></a></td>";
    echo "</tr></table>";
    echo "<table border='0' cellspacing='1' cellpadding ='3' class='outer' width ='100%'>";
    echo "<tr>";
    echo "<th><a href='index.php?op=viewresults&amp;artid=$artid&amp;sort_key=uname'>" . _MD_LT_STUDENT . "</a></th>";
    echo "<th><a href='index.php?op=viewresults&amp;artid=$artid&amp;sort_key=score'>" . _MD_LT_SCORE . "</a></th>";
    echo "<th><a href='index.php?op=viewresults&amp;artid=$artid&amp;sort_key=timestamp'>" . _MD_LT_DATE . "</a></th>";
    if ($isModAdmin) {
        echo "<th colspan=2 align='center'>" . _MD_LT_ACTION . "</th>";
    }
    echo "</tr>";
    while (list($res_id, $quiz_id, $uid, $score, $timestamp, $comment, $uname, $name) = $xoopsDB->fetchRow($result)) {
        echo "<tr>";
        if ($xoopsUser) {
            echo "<td class='even'>" . $uname;
            if (!empty($name)) {
                echo " (" . $name . ")";
            }
            echo "</td>";
        }
        echo "<td class='even' align='center'>$score</td>";
        echo "<td class='even' align='center'>$timestamp</td>";
        if ($isModAdmin) {
            echo "<td class='odd' align='center'><a href='admin/index.php?op=resultdelete&amp;res_id=$res_id'>"
                . _MD_DELETE . "</a></td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    echo "<table border='0' cellspacing='1' cellpadding ='3' width ='100%'><tr>";
    echo "<td align='right'><a href='" . _MD_CREDITSITE . "' target='_credit'/ > Version " . round(
            $xoopsModule->getVar('version') / 100,
            2
        ) . "</a></td>";
    echo "</tr></table>";
    echo "</div>";
    echo "</div>";
    include '../../footer.php';
}

function viewdetails($artid, $sort_key)
{
    global $xoopsConfig, $xoopsModuleConfig, $xoopsUser, $xoopsDB, $xoopsTheme, $xoopsLogger, $xoopsModule, $xoopsTpl, $isModAdmin, $xoopsUserIsAdmin;
    include '../../header.php';
    $myts     =& MyTextSanitizer::getInstance();
    $artid    = intval($artid);
    $sort_key = $myts->addSlashes($sort_key);
    //Retrieve table data by users
    include 'module_prefix.php';
    $result2 = $xoopsDB->query(
        "SELECT title, posted, secid FROM " . $xoopsDB->prefix($module_prefix . "_quiz") . " WHERE artid=$artid"
    );
    list($title, $posted, $secid) = $xoopsDB->fetchRow($result2);
    $title  = $myts->displayTarea($title);
    $posted = $myts->displayTarea($posted);
    $uid    = ($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
    include 'module_prefix.php';
    $result_db = $xoopsDB->prefix($module_prefix . '_results');
    $users_db  = $xoopsDB->prefix('users');
    if ($isModAdmin) {
        $query = "SELECT $result_db.id, $result_db.quiz_id, $result_db.uid, $result_db.score, $result_db.start_time, $result_db.end_time, $result_db.timestamp, $result_db.host, $result_db.ip, $result_db.comment, $users_db.uname, $users_db.name FROM $result_db, $users_db WHERE $result_db.uid = $users_db.uid AND $result_db.quiz_id = $artid ORDER BY "
            . $sort_key;
    } elseif ($xoopsUser) {
        $query = "SELECT $result_db.id, $result_db.quiz_id, $result_db.uid, $result_db.score, $result_db.start_time, $result_db.end_time, $result_db.timestamp, $result_db.host, $result_db.ip, $result_db.comment, $users_db.uname, $users_db.name FROM $result_db, $users_db WHERE $result_db.uid = $uid AND $result_db.uid = $users_db.uid AND $result_db.quiz_id = $artid ORDER BY "
            . $sort_key;
    }
    $result = $xoopsDB->query($query);

    echo "<div style='text-align: center;'>";
    echo "<h2 align='center'>" . _MD_RESULT_DETAIL . ": <a href='index.php?op=viewarticle&amp;artid=$artid' target='quiz_window'><span style='font-weight:bold;font-size:larger;'>"
        . $title . "</span></a></h2>";
    echo "<div id='content'>";
    echo "<table border='0' cellspacing='1' cellpadding ='3' class='outer' width ='100%'><tr>";
    echo "<td align='left' valign='top'><b><a href=index.php>" . _MD_RETURN2INDEX . "</a> -> <a href='index.php?op=listarticles&amp;secid=$secid'>"
        . _MD_RETURN2QUIZ . "</a> -> " . _MD_RESULTLIST . " (" . _MD_RESULT_DETAIL . ") </b></td>";
    if ($xoopsUser) {
        echo "<td align='right' valign='center'><a href='index.php?op=viewresults&amp;artid=$artid&amp;sort_key=timestamp'><span style='font-weight:bold;font-size:larger;'>"
            . _MD_RESULT_SIMPLE . "</span></a></td>";
    }
    echo "</tr></table>";

    echo "<table border='0' cellspacing='1' cellpadding ='3' class='outer' width ='100%'>";
    echo "<tr>";
    echo "<th><a href='index.php?op=viewdetails&amp;artid=$artid&amp;sort_key=uname'>" . _MD_LT_STUDENT . "</a></th>";
    echo "<th><a href='index.php?op=viewdetails&amp;artid=$artid&amp;sort_key=score'>" . _MD_LT_SCORE . "</th>";
    echo
        "<th><a href='index.php?op=viewdetails&amp;artid=$artid&amp;sort_key=start_time'>" . _XD_FB_START . "</a></th>";
    echo "<th><a href='index.php?op=viewdetails&amp;artid=$artid&amp;sort_key=end_time'>" . _XD_FB_END . "</a></th>";
    echo "<th><a href='index.php?op=viewdetails&amp;artid=$artid&amp;sort_key=host'>" . _XD_FB_HOST . "</a></th>";
    echo "<th><a href='index.php?op=viewdetails&amp;artid=$artid&amp;sort_key=ip'>" . _XD_FB_IP . "</a></th>";
    if ($isModAdmin) {
        echo "<th>" . _MD_LT_ACTION . "</th>";
    }
    echo "</tr>";
    while (
    list($res_id, $quiz_id, $uid, $score, $start_time, $end_time, $timestamp, $host, $ip, $comment, $uname, $name)
        = $xoopsDB->fetchRow($result)) {
        echo "<tr>";
        if ($xoopsUser) {
            echo "<td nowrap class='even'>" . $uname;
            if (!empty($name)) {
                echo " (" . $name . ")";
            }
            echo "</td>";
        }
        echo "<td class='even' align='center'>$score</td>";
        echo "<td class='even' align='center'>$start_time</td>";
        echo "<td class='even' align='center'>$end_time</td>";
        echo "<td class='even' align='center'>$host</td>";
        echo "<td class='even' align='center'>$ip</td>";
        if ($isModAdmin) {
            echo "<td class='odd' align='center' nowrap><a href='admin/index.php?op=resultdelete&amp;res_id=$res_id'>"
                . _MD_DELETE . "</td>";
        }
        echo "</tr>";
    }

    echo "</table>";

    echo "<table border='0' cellspacing='1' cellpadding ='3' width ='100%'><tr>";
    echo "<td align='right'><a href='" . _MD_CREDITSITE . "' target='_credit'/ > Version " . round(
            $xoopsModule->getVar('version') / 100,
            2
        ) . "</a></td>";
    echo "</tr></table>";
    echo "</div>";
    echo "</div>";
    include '../../footer.php';
}

function portfolio($sort_key, $secid)
{
    global $xoopsConfig, $xoopsModuleConfig, $xoopsUser, $xoopsDB, $xoopsTheme, $xoopsLogger, $xoopsModule, $xoopsTpl, $isModAdmin, $xoopsUserIsAdmin;
    include '../../header.php';
    $myts     =& MyTextSanitizer::getInstance();
    $secid    = intval($secid);
    $sort_key = $myts->addSlashes($sort_key);
    include 'module_prefix.php';
    $result_db = $xoopsDB->prefix($module_prefix . '_results');
    include 'module_prefix.php';
    $quiz_db  = $xoopsDB->prefix($module_prefix . '_quiz');
    $users_db = $xoopsDB->prefix('users');
    if ($secid == 0) {
        $section_query = "";
    } else {
        $section_query = "AND $quiz_db.secid = $secid ";
    }
    if ($isModAdmin) {
        $query     = "SELECT $result_db.id, $result_db.quiz_id, $result_db.uid, $result_db.score, $result_db.start_time, $result_db.end_time, $result_db.timestamp, $result_db.host, $result_db.ip, $result_db.comment, $quiz_db.artid, $quiz_db.secid, $quiz_db.title, $users_db.uid, $users_db.uname, $users_db.name FROM $result_db, $quiz_db, $users_db WHERE $quiz_db.artid = $result_db.quiz_id AND $result_db.uid = $users_db.uid "
            . $section_query . " ORDER BY " . $sort_key;
        $user_name = "";
    } elseif ($xoopsUser) {
        $user_id   = $xoopsUser->getVar('uid');
        $user_name = " (" . $xoopsUser->getVar('uname') . ")";
        $query     = "SELECT $result_db.id, $result_db.quiz_id, $result_db.uid, $result_db.score, $result_db.start_time, $result_db.end_time, $result_db.timestamp, $result_db.host, $result_db.ip, $result_db.comment, $quiz_db.artid, $quiz_db.secid, $quiz_db.title, $users_db.uid, $users_db.uname, $users_db.name FROM $result_db, $quiz_db, $users_db WHERE $quiz_db.artid = $result_db.quiz_id AND $result_db.uid = $users_db.uid AND $result_db.uid=$user_id "
            . $section_query . " ORDER BY " . $sort_key;
    } else {
        $user_name = "";
    }
    $result = $xoopsDB->query($query);

    echo "<div style='text-align: center;'>";
    echo "<h2 align='center'>" . _MD_LT_PORTFOLIO . $user_name . "</h2>";
    echo "<div id='content'>";
    echo "<table border='0' cellspacing='1' cellpadding ='3' class='outer' width ='100%'>";
    echo "<form action='index.php?' method='get'><tr>";
    echo "<td align='left' valign='top'><b><a href=index.php>" . _MD_RETURN2INDEX . "</a> -> " . _MD_LT_PORTFOLIO
        . "</td>";
    echo "<td align='right' valign='center'>" . _MD_SECNAMEC . "<input type='hidden' name='op' value='portfolio'>"
        . "<input type='hidden' name='sort_key' value='timestamp'>" . "<select name='secid'>";

    if ($secid == 0) {
        echo "<option value='0' selected>" . _MD_ALL . "</option>";
    } else {
        echo "<option value='0'>" . _MD_ALL . "</option>";
    }
    include 'module_prefix.php';
    $courses = $xoopsDB->query(
        "SELECT secid, secname FROM " . $xoopsDB->prefix($module_prefix . "_sections") . " ORDER BY secname"
    );
    while (list($secid2list, $secname) = $xoopsDB->fetchRow($courses)) {
        $secname = $myts->displayTarea($secname);
        if ($secid2list == $secid) {
            echo "<option value='$secid2list' selected>$secname</option>";
        } else {
            echo "<option value='$secid2list'>$secname</option>";
        }
    }

    echo "</select><input type='submit' value='" . _MD_GO . "'></td>";
    echo "</tr></form></table>";
    echo "<table border='0' cellspacing='1' cellpadding ='3' class='outer' width ='100%'>";
    echo "<tr>";
    if ($isModAdmin) {
        echo "<th><a href='index.php?op=portfolio&amp;sort_key=uname'>" . _MD_LT_STUDENT . "</a></th>";
    }
    echo "<th><a href='index.php?op=portfolio&amp;sort_key=title'>" . _MD_LT_TITLE2 . "</a></th>";
    echo "<th><a href='index.php?op=portfolio&amp;sort_key=score'>" . _MD_LT_SCORE . "</a></th>";
    echo "<th><a href='index.php?op=portfolio&amp;sort_key=timestamp'>" . _MD_LT_DATE . "</a></th>";
    if ($isModAdmin) {
        echo "<th colspan=2 align='center'>" . _MD_LT_ACTION . "</th>";
    }
    echo "</tr>";
    while (
    list($res_id, $quiz_id, $uid, $score, $start_time, $end_time, $timestamp, $host, $ip, $comment, $artid, $secid,
        $title, $uid2, $uname, $name)
        = $xoopsDB->fetchRow($result)) {
        echo "<tr>";
        if ($isModAdmin) {
            echo "<td class='even'>" . $uname;
            if (!empty($name)) {
                echo " (" . $name . ")";
            }
            echo "</td>";
        }
        echo "<td class='even'><a href='index.php?op=viewarticle&amp;artid=$artid' target='quiz_window'>$title</a></td>";
        echo "<td class='even' align='center'>$score</td>";
        echo "<td class='even' align='center'>$timestamp</td>";
        if ($isModAdmin) {
            echo "<td class='odd' align='center'><a href='admin/index.php?op=resultdelete&amp;res_id=$res_id'>"
                . _MD_DELETE . "</a></td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    echo "<table border='0' cellspacing='1' cellpadding ='3' width ='100%'><tr>";
    echo "<td align='right'><a href='" . _MD_CREDITSITE . "' target='_credit'/ > Version " . round(
            $xoopsModule->getVar('version') / 100,
            2
        ) . "</a></td>";
    echo "</tr></table>";
    echo "</div>";
    echo "</div>";
    include '../../footer.php';
}

$op = isset($HTTP_GET_VARS['op']) ? trim($HTTP_GET_VARS['op']) : '';
$secid = isset($HTTP_GET_VARS['secid']) ? intval($HTTP_GET_VARS['secid']) : 0;
$page = isset($HTTP_GET_VARS['page']) ? intval($HTTP_GET_VARS['page']) : 0;
$artid = isset($HTTP_GET_VARS['artid']) ? intval($HTTP_GET_VARS['artid']) : 0;
$uid = isset($HTTP_GET_VARS['uid']) ? intval($HTTP_GET_VARS['uid']) : 0;
$sort_key = isset($HTTP_GET_VARS['sort_key']) ? trim($HTTP_GET_VARS['sort_key']) : "uname";

switch ($op) {
    case "viewarticle":
        viewarticle($artid);
        break;
    case "listarticles":
        listarticles($secid);
        break;
    case "viewresults":
        viewresults($artid, $sort_key);
        break;
    case "viewdetails":
        viewdetails($artid, $sort_key);
        break;
    case "portfolio":
        portfolio($sort_key, $secid);
        break;
    default:
        listsections();
        break;
}
?>
