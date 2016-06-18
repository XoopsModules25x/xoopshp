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

// HTTP_REFERER ACTION CHECK -> DBQuery -> function.php error $_SERVER[$key]
// If can't use command "$_SERVER["HTTP_REFERER"]" , don't save portfolio DB.
// analyze by kazuo sudow.
//
// TEST-TEST-TEST-TEST
// global $referer;
// $referer = $_SERVER["HTTP_REFERER"];
// if (!empty($referer)) {
//	$_SERVER["HTTP_REFERER"] = "dummy";
//	$referer = $_SERVER["HTTP_REFERER"];
//}

include_once dirname(dirname(__DIR__)) . '/mainfile.php';
include_once(XOOPS_ROOT_PATH . '/include/cp_functions.php');
global $db, $xoopsConfig, $xoopsUser, $xoopsModule, $xoopsObject, $xoopsDB;

if ($_SERVER['HTTP_HOST'] != $_SERVER['SERVER_NAME']) {
    exit('Access Denied');
} elseif (!isset($_COOKIE['xoopsHP_file_id'])) {
    exit('Oops, a problem in the cookie!');
}

// Get the file number
$quiz_id = (int)$_COOKIE['xoopsHP_file_id'];

// Delete cookie -> $quiz_id
setcookie('xoopsHP_file_id', '', time() - 3600);

// kazuo sudow
// - non pair Open-Close Table.. More Header Written BUG.
// OpenTable();

$myts       = MyTextSanitizer::getInstance();
$uid        = $xoopsUser ? $xoopsUser->getVar('uid') : 0;
$user_email = $xoopsUser ? $xoopsUser->getVar('email') : '';
$uname      = $xoopsUser ? $xoopsUser->getVar('uname') : '';
$username   = $xoopsUser ? $xoopsUser->getVar('name') : '';

// Get the instructor's email addy etc
$teacher_email = '';
include __DIR__ . '/module_prefix.php';
$result = $xoopsDB->query('SELECT secid, title, results_to FROM ' . $xoopsDB->prefix($module_prefix . '_quiz') . " WHERE artid=$quiz_id");
list($secid, $quiz_title, $teacher_email) = $xoopsDB->fetchRow($result);
$secid = (int)$secid;
include __DIR__ . '/module_prefix.php';
$result = $xoopsDB->query('SELECT secname FROM ' . $xoopsDB->prefix($module_prefix . '_sections') . " WHERE secid=$secid");
list($secname) = $xoopsDB->fetchRow($result);

// Get the form data
// kazuo sudow --> EUC-JP (1.04) Sanitizer & mb_convert_encoding
$userid = $myts->stripSlashesGPC($_POST['realname']);
// Check if mbstring is supported --Yoshi
if (XOOPS_USE_MULTIBYTES && function_exists('mb_convert_encoding') && $xoopsConfig['language'] === 'japanese') {
    $userid = mb_convert_encoding($userid, 'EUC-JP', 'auto');
}

// Get Score --> %
// kazuo sudow --> EUC-JP (1.04) Sanitizer
$score = $myts->stripSlashesGPC($_POST['Score']);

// kazuo sudow --> EUC-JP (1.04) Sanitizer
$start_time = $myts->stripSlashesGPC($_POST['Start_Time']);
$start_time = date('Y/m/d H:i:s', strtotime($start_time));

// kazuo sudow --> EUC-JP (1.04) Sanitizer
$end_time = $myts->stripSlashesGPC($_POST['End_Time']);
$end_time = date('Y/m/d H:i:s', strtotime($end_time));

$timestamp = date('Y/m/d H:i:s');
$comment   = '';

// Write in the db
if ($xoopsUser) {
    include __DIR__ . '/module_prefix.php';
    $query = 'INSERT INTO ' . $xoopsDB->prefix($module_prefix . '_results') . " (quiz_id, uid, score, start_time, end_time, timestamp, host, ip, comment) VALUES ('";
    $query .= $quiz_id . "','";
    $query .= $uid . "','";
    $query .= $score . "','";
    $query .= $start_time . "','";
    $query .= $end_time . "','";
    $query .= $timestamp . "','";
    $query .= gethostbyaddr($_SERVER['REMOTE_ADDR']) . "','";
    $query .= $_SERVER['REMOTE_ADDR'] . "','";
    $query .= $comment . "')";
    $result = $xoopsDB->query($query);
    // Count up the counter for the completion
    $quiz_id = (int)$quiz_id;
    include __DIR__ . '/module_prefix.php';
    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix($module_prefix . '_quiz') . " SET counter=counter+1 WHERE artid=$quiz_id");
}

// E-MAIL SEND
// send mail only if user or teacher email option is checked
if ($xoopsModuleConfig['mail_teacher'] || $xoopsModuleConfig['mail_user']) {
    //generate message
    // kazuo sudow 1.04 (add ID)
    // original
    // $subject = "XoopsHP Feedback: $uname, $quiz_title";
    // $subject = mb_encode_mimeheader(mb_convert_kana($subject,"KV"),"ISO-2022-JP","B");
    $subject = htmlspecialchars($xoopsConfig['sitename']) . ' ' . $xoopsModule->getVar('name') . ':' . $quiz_title . ' ' . $uname;
    if (!empty($username)) {
        $subject .= '(' . $username . ')';
    }
    if (!empty($userid)) {
        $subject .= ' (ID:' . $userid . ')';
    }

    // message
    $msg = _XD_FB_ID . "\t$userid\n";
    $msg .= _XD_FB_USERNAME . "\t$uname";
    if (!empty($username)) {
        $msg .= '(' . $username . ")\n";
    } else {
        $msg .= "\n";
    }
    // ks	$msg .= _XD_FB_UNAME . "\t$uname\n";
    // ks	$msg .= _MD_SECNAMEC . "\t$secname\n";
    $msg .= _MD_LT_COURSE . "\t$secname\n";
    $msg .= _XD_FB_QTITLE . "\t$quiz_title\n";
    $msg .= _XD_FB_SCORE . "\t$score\n";
    $msg .= _XD_FB_START . "\t$start_time\n";
    $msg .= _XD_FB_END . "\t$end_time\n";
    $msg .= _XD_FB_TIMESTAMP . "\t$timestamp\n";
    //	$msg .= _XD_FB_CMT . "\t$comment";
    $msg = multibyte($msg); // clean up for multybyte lang esp JP

    $xoopsMailer =& getMailer();
    $xoopsMailer->useMail();
    $xoopsMailer->setFromEmail($myts->oopsStripSlashesGPC($xoopsConfig['adminmail']));
    $xoopsMailer->setFromName($myts->oopsStripSlashesGPC($xoopsConfig['sitename']));
    $xoopsMailer->setSubject($myts->oopsStripSlashesGPC($subject));
    $xoopsMailer->setBody($myts->oopsStripSlashesGPC($msg));

    if ($xoopsModuleConfig['mail_teacher']) {
        $xoopsMailer->setToEmails($teacher_email);
    }
    if ($xoopsModuleConfig['mail_user']) {
        $xoopsMailer->setToEmails($user_email);
    }
}

// WWW
if (!$xoopsUser) {
    $msg = '<H3>' . _XD_FB_GUEST . "</H3>\n";
} elseif ($xoopsMailer->send() != false) {
    $msg = '<H3>' . _XD_FB_OK . "</H3>\n";
} else {
    $msg = '<H3>' . _XD_FB_NG . "</H3>\n";
}

// kazuo sudow 1.04
// TD CLASS=EVEN
// ks	$msg .= "<TABLE BORDER=1>\n";
$msg .= "<TABLE cellspacing='0' cellpadding='0' BORDER='1'>\n";
$msg .= "<TR><TD class='even'>" . _XD_FB_ID . "</TD><TD class='even'>$userid</TD></TR>\n";

if ($xoopsUser) {
    $msg .= "<TR><TD class='even'>" . _XD_FB_USERNAME . "</TD><TD class='even'>$uname";
    if (!empty($username)) {
        $msg .= '(' . $username . ")</TD></TR>\n";
    } else {
        $msg .= "</TD></TR>\n";
    }
} else {
    $msg .= "<TR><TD class='even'>" . _XD_FB_USERNAME . "</TD><TD class='even'>" . _MD_LT_GUEST . "</TD></TR>\n";
}

// ks	$msg .= "<TR><TD class='even'>" . _XD_FB_UNAME . "</TD><TD class='even'>$uname</TD></TR>\n";
// ks	$msg .= "<TR><TD class='even'>" . _MD_SECNAMEC . "</TD><TD class='even'>$secname</TD></TR>\n";
$msg .= "<TR><TD class='even'>" . _MD_LT_COURSE . "</TD><TD class='even'>$secname</TD></TR>\n";
$msg .= "<TR><TD class='even'>" . _XD_FB_QTITLE . "</TD><TD class='even'>$quiz_title</TD></TR>\n";
$msg .= "<TR><TD class='even'>" . _XD_FB_SCORE . "</TD><TD class='even'>$score</TD></TR>\n";
$msg .= "<TR><TD class='even'>" . _XD_FB_START . "</TD><TD class='even'>$start_time</TD></TR>\n";
$msg .= "<TR><TD class='even'>" . _XD_FB_END . "</TD><TD class='even'>$end_time</TD></TR>\n";
$msg .= "<TR><TD class='even'>" . _XD_FB_TIMESTAMP . "</TD><TD class='even'>$timestamp</TD></TR>\n";
//	$msg .= "<TR><TD class='even'>" . _XD_FB_CMT . "</TD><TD class='even'>$comment</TD></TR>\n";
$msg .= "</TABLE>\n";
$msg .= "<br>\n";
$msg .= "<center>\n";
$msg .= "<INPUT type='button' name='close' value='" . _XD_FB_CLSBTN . "' onClick='window.opener.close();window.close()'>\n";
$msg .= "</center>\n";

// TEST-TEST-TEST-TEST
// test	$msg .= $quiz_id; // -> get cookie xoopsHP_file_id
// test	$msg .= $referer; // -> check browser has referer

// WWW-PAGE written
my_wrapper($msg);
exit();

/**
 * @param $s
 * @return mixed|string
 */
function multibyte($s)
{
    // kazuo sudow global app
    global $xoopsConfig;

    if (XOOPS_USE_MULTIBYTES && function_exists('mb_convert_encoding') && $xoopsConfig['language'] == 'japanese') {
        if (get_magic_quotes_gpc()) {
            // kazuo sudow - encode auto & EUC-JP
            // return mb_convert_encoding(stripslashes($s), _CHARSET, "EUC-JP,UTF-8,Shift_JIS,JIS");
            return mb_convert_encoding(stripslashes($s), 'EUC-JP', 'auto');
        } else {
            // kazuo sudow - encode auto
            // return mb_convert_encoding($s, _CHARSET, "EUC-JP,UTF-8,Shift_JIS,JIS");
            return mb_convert_encoding($s, 'EUC-JP', 'auto');
        }
    } else {
        if (get_magic_quotes_gpc()) {
            return stripslashes($s);
        } else {
            return $s;
        }
    }
}

/**
 * @param $msg
 */
function my_wrapper($msg)
{
    global $xoopsConfig, $xoopsTheme, $xoopsConfigMetaFooter;

    $myts = MyTextSanitizer::getInstance();
    if ($xoopsConfig['gzip_compression'] == 1) {
        ob_start('ob_gzhandler');
    } else {
        ob_start();
    }
    if (!headers_sent()) {
        header('Content-Type:text/html; charset=' . _CHARSET);
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cache, max-age=1, s-maxage=1, must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');
    }

    echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";

    echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . _LANGCODE . '" lang="' . _LANGCODE . '">
    <head>
    <meta http-equiv="content-type" content="text/html; charset=' . _CHARSET . '" />
    <meta http-equiv="content-language" content="' . _LANGCODE . '" />
    <meta name="robots" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_robots']) . '" />
    <meta name="keywords" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_keywords']) . '" />
    <meta name="description" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_desc']) . '" />
    <meta name="rating" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_rating']) . '" />
    <meta name="author" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_author']) . '" />
    <meta name="copyright" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_copyright']) . '" />
    <meta name="generator" content="XOOPS" />
    <title>' . _MD_LT_RESULTS . '</title>';
    $themecss = getcss($xoopsConfig['theme_set']);
    echo '<link rel="stylesheet" type="text/css" media="all" href="' . XOOPS_URL . '/xoops.css" />';
    if ($themecss) {
        echo '<link rel="stylesheet" type="text/css" media="all" href="' . $themecss . '" />';
        //echo '<style type="text/css" media="all"><!-- @import url('.$themecss.'); --></style>';
    }
    echo '</head><body>';
    echo '<br>'; // kazuo sudow <BR> code app
    echo $msg;
    echo '</body></html>';
}
