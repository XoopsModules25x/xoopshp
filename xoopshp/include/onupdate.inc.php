<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

// referer check
$ref = xoops_getenv('HTTP_REFERER');
if ($ref == '' || strpos($ref, XOOPS_URL . '/modules/system/admin.php') === 0) {
    /* XoopsHP specific part */

    global $xoopsDB, $xoopsUser, $xoopsModule;
    include XOOPS_ROOT_PATH . "/modules/" . $modversion['dirname'] . "/module_prefix.php";

// Perms check
    if (!is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid())) {
        exit("Access Denied");
    }

// DB Maintenance

    if ($xoopsDB->query("SELECT display FROM " . $xoopsDB->prefix($module_prefix . "_quiz")) === false) {

        // quiz database
        $result = $xoopsDB->queryF(
            "ALTER TABLE " . $xoopsDB->prefix($module_prefix . "_quiz") . " ADD   display TINYINT NOT NULL DEFAULT  '1'"
        );
        if ($result) {
//	    	echo '<br />' .  _AM_MSG_UPDATE_FAILED." (".$xoopsDB->prefix($module_prefix."_quiz").".display) <br />";
            $errors++;
        } else {
//			echo _AM_MSG_UPDATE_SUCCEEDED." (".$xoopsDB->prefix($module_prefix."_quiz").".display) <br />";
        }

        $result = $xoopsDB->queryF(
            "ALTER TABLE " . $xoopsDB->prefix($module_prefix . "_quiz")
            . " ADD  expire DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00'"
        );
        if ($result) {
//	    	echo '<br />' .  _AM_MSG_UPDATE_FAILED." (".$xoopsDB->prefix($module_prefix."_quiz").".expire) <br />";
            $errors++;
        } else {
//			echo _AM_MSG_UPDATE_SUCCEEDED." (".$xoopsDB->prefix($module_prefix."_quiz").".expire) <br />";
        }

        // sections database
        $result = $xoopsDB->queryF(
            "ALTER TABLE " . $xoopsDB->prefix($module_prefix . "_sections")
            . " ADD   display TINYINT NOT NULL DEFAULT  '1'"
        );
        if ($result) {
            echo '<br />' . _AM_MSG_UPDATE_FAILED . " (" . $xoopsDB->prefix($module_prefix . "_sections")
                . ".display) <br />";
            $errors++;
        } else {
//			echo _AM_MSG_UPDATE_SUCCEEDED." (".$xoopsDB->prefix($module_prefix."_sections").".display) <br />";
        }

        $result = $xoopsDB->queryF(
            "ALTER TABLE " . $xoopsDB->prefix($module_prefix . "_sections")
            . " ADD  expire DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00'"
        );
        if ($result) {
//	    	echo '<br />' .  _AM_MSG_UPDATE_FAILED." (".$xoopsDB->prefix($module_prefix."_sections").".expire) <br />";
            $errors++;
        } else {
//			echo _AM_MSG_UPDATE_SUCCEEDED." (".$xoopsDB->prefix($module_prefix."_sections").".expire) <br />";
        }

//	echo "Database update ended with ".$errors."error(s)";
    }

    /* Misc */

    // Add other stuff below


}

?>