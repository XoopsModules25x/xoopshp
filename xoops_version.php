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

if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}
$mydirname = basename(__DIR__);

$modversion['name']        = $mydirname;
$modversion['version']     = '1.14';
$modversion['description'] = _MI_XHP_DESC;
$modversion['credits']     = 'The XOOPS Project';
$modversion['author']      = 'AWAJI Yoshimasa (http://www.awajis.net/) and Kazuo Sudow (http://www.mailpark.co.jp/)';
$modversion['help']        = 'sections.html';
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0 or later';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html';
$modversion['license']     = 'GPL see LICENSE';
$modversion['official']    = 0;
$modversion['image']       = 'images/logo_module.png';
$modversion['hasMain']     = 1;
$modversion['dirname']     = $mydirname;

$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['icons16']        = '../../Frameworks/moduleclasses/icons/16';
$modversion['icons32']        = '../../Frameworks/moduleclasses/icons/32';
//about
$modversion['release_date']        = '2016/06/17';
$modversion['module_website_url']  = 'www.xoops.org';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status']       = 'Beta 2';
$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = '2.5.8';
$modversion['min_admin']           = '1.1';
$modversion['min_db']              = array(
    'mysql'  => '5.0.7',
    'mysqli' => '5.0.7'
);

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
//$modversion['sqlfile']['postgresql'] = "sql/pgsql.sql";

// Tables created by sql file (without prefix!)
include __DIR__ . '/module_prefix.php';
$modversion['tables'][0] = $module_prefix . '_quiz';
$modversion['tables'][1] = $module_prefix . '_results';
$modversion['tables'][2] = $module_prefix . '_sections';
$modversion['tables'][3] = $module_prefix . '_config';

// Install script to add anonymous access on installation
$modversion['onInstall'] = 'install_funcs.php';

// Admin things
$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex']  = 'admin/index.php';
$modversion['adminmenu']   = 'admin/menu.php';

// Menu
$modversion['hasMain'] = 1;

// Notification
$modversion['hasNotification'] = 0;

// Configs
$modversion['config'][1] = array(
    'name'        => 'has_license',
    'title'       => '_MI_XHP_LICENSE',
    'description' => '_MI_XHP_LICENSE_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
);

$modversion['config'][2] = array(
    'name'        => 'welcome',
    'title'       => '_MI_XHP_WELCOME_T',
    'description' => '',
    'formtype'    => 'text',
    'valuetype'   => 'text',
    'default'     => _MI_XHP_WELCOME
);

$modversion['config'][3] = array(
    'name'        => 'welcome_desc',
    'title'       => '_MI_XHP_DESC_T',
    'description' => '',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => _MI_XHP_DESC_N
);

$modversion['config'][4] = array(
    'name'        => 'mail_teacher',
    'title'       => '_MI_XHP_MAIL_TEACHER',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
);

$modversion['config'][5] = array(
    'name'        => 'mail_user',
    'title'       => '_MI_XHP_MAIL_USER',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
);

$modversion['config'][6] = array(
    'name'        => 'mail_owner',
    'title'       => '_MI_XHP_MAIL_OWNER',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
);

$modversion['config'][7] = array(
    'name'        => 'max_file_size',
    'title'       => '_MI_XHP_MAX_FILE_SIZE',
    'description' => '',
    'formtype'    => 'text',
    'valuetype'   => 'int',
    'default'     => 200000
);

$modversion['config'][8] = array(
    'name'        => 'default_days',
    'title'       => '_MI_XHP_DEF_DAYS',
    'description' => '',
    'formtype'    => 'text',
    'valuetype'   => 'int',
    'default'     => 7
);

// Blocks
$modversion['blocks'][1]['file']        = 'xhp_block_latest.php';
$modversion['blocks'][1]['name']        = _MI_XHP_BLATEST;
$modversion['blocks'][1]['description'] = 'Shows latest items';
$modversion['blocks'][1]['show_func']   = 'b_XHP_latest_show';
$modversion['blocks'][1]['edit_func']   = 'b_XHP_latest_edit';
$modversion['blocks'][1]['options']     = "DESC|10|{$mydirname}";
$modversion['blocks'][1]['template']    = 'xhp_block_latest.html';

$modversion['blocks'][2]['file']        = 'xhp_block_ranking.php';
$modversion['blocks'][2]['name']        = _MI_XHP_BRANKING;
$modversion['blocks'][2]['description'] = 'Shows average ranking';
$modversion['blocks'][2]['show_func']   = 'b_XHP_ranking_show';
$modversion['blocks'][2]['edit_func']   = 'b_XHP_ranking_edit';
$modversion['blocks'][2]['options']     = "DESC|10|85|{$mydirname}";
$modversion['blocks'][2]['template']    = 'xhp_block_ranking.html';

$modversion['blocks'][3]['file']        = 'xhp_block_completed.php';
$modversion['blocks'][3]['name']        = _MI_XHP_BCOMPLETED;
$modversion['blocks'][3]['description'] = 'Shows completed tasks ranking';
$modversion['blocks'][3]['show_func']   = 'b_XHP_completed_show';
$modversion['blocks'][3]['edit_func']   = 'b_XHP_completed_edit';
$modversion['blocks'][3]['options']     = "DESC|10|1|{$mydirname}";
$modversion['blocks'][3]['template']    = 'xhp_block_completed.html';

$modversion['blocks'][4]['file']        = 'xhp_block_courseranking.php';
$modversion['blocks'][4]['name']        = _MI_XHP_BCOURSERANK;
$modversion['blocks'][4]['description'] = 'Shows average ranking by course';
$modversion['blocks'][4]['show_func']   = 'b_XHP_courseranking_show';
$modversion['blocks'][4]['edit_func']   = 'b_XHP_courseranking_edit';
$modversion['blocks'][4]['options']     = "DESC|10|20|1|{$mydirname}";
$modversion['blocks'][4]['template']    = 'xhp_block_courseranking.html';

// onUpdate
if (!empty($_POST['fct']) && !empty($_POST['op']) && $_POST['fct'] === 'modulesadmin' && $_POST['op'] === 'update_ok'
    && $_POST['dirname'] == $modversion['dirname']
) {
    include __DIR__ . '/include/onupdate.inc.php';
}
