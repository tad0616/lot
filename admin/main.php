<?php
use Xmf\Request;
/**
 * Lot module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package    Lot
 * @since      2.5
 * @author     tad
 * @version    $Id $
 **/

/*-----------引入檔案區--------------*/
$isAdmin = true;
$xoopsOption['template_main'] = 'lot_adm_main.tpl';
include_once "header.php";
include_once "../function.php";

/*-----------功能函數區--------------*/

//
function f1()
{
    global $xoopsDB, $xoopsTpl;
    $main = "Hello World!";
    $xoopsTpl->assign('main', $main);
}

//
function f2()
{
    global $xoopsDB;
}

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$lot_sn = Request::getInt('lot_sn');
$files_sn = Request::getInt('files_sn');

switch ($op) {
    /*---判斷動作請貼在下方---*/

    case "f2":
        f2();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    default:
        f1();
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("isAdmin", true);
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tadtools/css/xoops_adm3.css');
include_once 'footer.php';
