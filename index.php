<?php
use Xmf\Request;
use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\TadDataCenter;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;

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
include "header.php";
$xoopsOption['template_main'] = 'lot_index.tpl';
include_once XOOPS_ROOT_PATH . "/header.php";

/*-----------功能函數區--------------*/

//lot編輯表單
function lot_form($lot_sn = '')
{
    global $xoopsDB, $xoopsTpl, $xoopsUser, $isAdmin;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    //抓取預設值
    if (!empty($lot_sn)) {
        $DBV = get_lot($lot_sn);
    } else {
        $DBV = array();
    }

    //預設值設定

    //設定 lot_sn 欄位的預設值
    $lot_sn = !isset($DBV['lot_sn']) ? $lot_sn : $DBV['lot_sn'];
    $xoopsTpl->assign('lot_sn', $lot_sn);
    //設定 lot_title 欄位的預設值
    $lot_title = !isset($DBV['lot_title']) ? '' : $DBV['lot_title'];
    $xoopsTpl->assign('lot_title', $lot_title);
    //設定 lot_content 欄位的預設值
    $lot_content = !isset($DBV['lot_content']) ? '' : $DBV['lot_content'];
    $xoopsTpl->assign('lot_content', $lot_content);
    //設定 lot_teacher 欄位的預設值
    $lot_teacher = !isset($DBV['lot_teacher']) ? '' : $DBV['lot_teacher'];
    $xoopsTpl->assign('lot_teacher', $lot_teacher);
    //設定 lot_uid 欄位的預設值
    $user_uid = $xoopsUser ? $xoopsUser->uid() : "";
    $lot_uid = !isset($DBV['lot_uid']) ? $user_uid : $DBV['lot_uid'];
    $xoopsTpl->assign('lot_uid', $lot_uid);
    //設定 lot_date 欄位的預設值
    $lot_date = !isset($DBV['lot_date']) ? date("Y-m-d H:i:s") : $DBV['lot_date'];
    $xoopsTpl->assign('lot_date', $lot_date);
    //設定 lot_col 欄位的預設值
    $lot_col = !isset($DBV['lot_col']) ? '' : $DBV['lot_col'];
    $xoopsTpl->assign('lot_col', $lot_col);

    $op = empty($lot_sn) ? "insert_lot" : "update_lot";
    //$op = "replace_lot";

    //套用formValidator驗證機制
    $formValidator = new FormValidator("#myForm", true);
    $formValidator_code = $formValidator->render();

    //說明
    $ck = new CkEditor("lot", "lot_content", $lot_content);
    $ck->setHeight(400);
    $editor = $ck->render();
    $xoopsTpl->assign('lot_content_editor', $editor);
    $TadUpFiles = new TadUpFiles("lot");
    $TadUpFiles->set_col("lot_sn", $lot_sn);
    $up_lot_sn_form = $TadUpFiles->upform(true, "up_lot_sn", "");
    $xoopsTpl->assign('up_lot_sn_form', $up_lot_sn_form);

    //加入Token安全機制
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
    $token = new \XoopsFormHiddenToken();
    $token_form = $token->render();
    $xoopsTpl->assign("token_form", $token_form);
    $xoopsTpl->assign('action', $_SERVER["PHP_SELF"]);
    $xoopsTpl->assign('formValidator_code', $formValidator_code);
    $xoopsTpl->assign('now_op', 'lot_form');
    $xoopsTpl->assign('next_op', $op);
}

//以流水號取得某筆lot資料
function get_lot($lot_sn = '')
{
    global $xoopsDB;

    if (empty($lot_sn)) {
        return;
    }

    $sql = "select * from `" . $xoopsDB->prefix("lot") . "`
    where `lot_sn` = '{$lot_sn}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql);
    $data = $xoopsDB->fetchArray($result);
    return $data;
}

//以流水號取得某筆lot_data資料
function get_lot_data($lot_sn = '', $user = '')
{
    global $xoopsDB, $xoopsTpl;

    if (empty($lot_sn)) {
        return;
    }
    //取得資料陣列：
    $TadDataCenter = new TadDataCenter('lot');
    $and_user = empty($user) ? '' : "and `user`='{$user}'";
    $sql = "select * from `" . $xoopsDB->prefix("lot_data") . "`
    where `lot_sn` = '{$lot_sn}' $and_user
    order by log_date desc";

    //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = Utility::getPageBar($sql, 100, 10, null, null, 3);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];
    $total = $PageBar['total'];

    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('total', $total);

    $result = $xoopsDB->query($sql) or Utility::web_error($sql);
    while ($all = $xoopsDB->fetchArray($result)) {

        $TadDataCenter->set_col('lot_data_sn', $all['lot_data_sn']);

        $all['data'] = $TadDataCenter->getData();
        $data[] = $all;
    }
    return $data;
}
//新增資料到lot中
function insert_lot()
{
    global $xoopsDB, $xoopsUser, $isAdmin;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    //XOOPS表單安全檢查
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode("<br />", $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header($_SERVER['PHP_SELF'], 3, $error);
    }

    $myts = \MyTextSanitizer::getInstance();

    $lot_sn = intval($_POST['lot_sn']);
    $lot_title = $myts->addSlashes($_POST['lot_title']);
    $lot_content = $myts->addSlashes($_POST['lot_content']);
    $lot_teacher = $myts->addSlashes($_POST['lot_teacher']);
    //取得使用者編號
    $lot_uid = ($xoopsUser) ? $xoopsUser->uid() : "";
    $lot_uid = !empty($_POST['lot_uid']) ? intval($_POST['lot_uid']) : $lot_uid;
    $lot_date = date("Y-m-d H:i:s", xoops_getUserTimestamp(time()));
    $lot_col = $myts->addSlashes($_POST['lot_col']);

    $sql = "insert into `" . $xoopsDB->prefix("lot") . "` (
        `lot_title`,
        `lot_content`,
        `lot_teacher`,
        `lot_uid`,
        `lot_date`,
        `lot_col`
    ) values(
        '{$lot_title}',
        '{$lot_content}',
        '{$lot_teacher}',
        '{$lot_uid}',
        '{$lot_date}',
        '{$lot_col}'
    )";
    $xoopsDB->query($sql) or Utility::web_error($sql);

    //取得最後新增資料的流水編號
    $lot_sn = $xoopsDB->getInsertId();

    $TadUpFiles = new TadUpFiles("lot");
    $TadUpFiles->set_col("lot_sn", $lot_sn);
    $TadUpFiles->upload_file('up_lot_sn', '', '', '', '', true, false);
    return $lot_sn;
}

//更新lot某一筆資料
function update_lot($lot_sn = '')
{
    global $xoopsDB, $isAdmin, $xoopsUser;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    //XOOPS表單安全檢查
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode("<br />", $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header($_SERVER['PHP_SELF'], 3, $error);
    }

    $myts = \MyTextSanitizer::getInstance();

    $lot_sn = intval($_POST['lot_sn']);
    $lot_title = $myts->addSlashes($_POST['lot_title']);
    $lot_content = $myts->addSlashes($_POST['lot_content']);
    $lot_teacher = $myts->addSlashes($_POST['lot_teacher']);
    //取得使用者編號
    $lot_uid = ($xoopsUser) ? $xoopsUser->uid() : "";
    $lot_uid = !empty($_POST['lot_uid']) ? intval($_POST['lot_uid']) : $lot_uid;
    $lot_date = date("Y-m-d H:i:s", xoops_getUserTimestamp(time()));
    $lot_col = $myts->addSlashes($_POST['lot_col']);

    $sql = "update `" . $xoopsDB->prefix("lot") . "` set
       `lot_title` = '{$lot_title}',
       `lot_content` = '{$lot_content}',
       `lot_teacher` = '{$lot_teacher}',
       `lot_uid` = '{$lot_uid}',
       `lot_date` = '{$lot_date}',
       `lot_col` = '{$lot_col}'
    where `lot_sn` = '$lot_sn'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql);

    $TadUpFiles = new TadUpFiles("lot");
    $TadUpFiles->set_col("lot_sn", $lot_sn);
    $TadUpFiles->upload_file('up_lot_sn', '', '', '', '', true, false);
    return $lot_sn;
}

//刪除lot某筆資料資料
function delete_lot($lot_sn = '')
{
    global $xoopsDB, $isAdmin;
    if (!$isAdmin) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    if (empty($lot_sn)) {
        return;
    }

    $sql = "delete from `" . $xoopsDB->prefix("lot") . "`
    where `lot_sn` = '{$lot_sn}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql);

    $TadUpFiles = new TadUpFiles("lot");
    $TadUpFiles->set_col("lot_sn", $lot_sn);
    $TadUpFiles->del_files();
}

//以流水號秀出某筆lot資料內容
function show_one_lot($lot_sn = '', $user = '')
{
    global $xoopsDB, $xoopsTpl, $isAdmin;

    if (empty($lot_sn)) {
        return;
    } else {
        $lot_sn = intval($lot_sn);
    }

    $myts = \MyTextSanitizer::getInstance();

    $sql = "select * from `" . $xoopsDB->prefix("lot") . "`
    where `lot_sn` = '{$lot_sn}' ";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql);
    $all = $xoopsDB->fetchArray($result);

    //以下會產生這些變數： $lot_sn, $lot_title, $lot_content, $lot_teacher, $lot_uid, $lot_date, $lot_col
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    //將 uid 編號轉換成使用者姓名（或帳號）
    $uid_name = XoopsUser::getUnameFromId($lot_uid, 1);
    if (empty($uid_name)) {
        $uid_name = XoopsUser::getUnameFromId($lot_uid, 0);
    }

    $TadUpFiles = new TadUpFiles("lot");
    $TadUpFiles->set_col("lot_sn", $lot_sn);
    $show_lot_sn_files = $TadUpFiles->show_files('up_lot_sn', true, 'thumb', true, false, null, null, false);
    $xoopsTpl->assign('show_lot_sn_files', $show_lot_sn_files);

    //過濾讀出的變數值
    $lot_title = $myts->htmlSpecialChars($lot_title);
    $lot_content = $myts->displayTarea($lot_content, 1, 1, 0, 1, 0);
    $lot_teacher = $myts->htmlSpecialChars($lot_teacher);
    $lot_col = $myts->displayTarea($lot_col, 0, 1, 0, 1, 1);

    $xoopsTpl->assign('lot_sn', $lot_sn);
    $xoopsTpl->assign('lot_title', $lot_title);
    $xoopsTpl->assign('lot_content', $lot_content);
    $xoopsTpl->assign('lot_teacher', $lot_teacher);
    $xoopsTpl->assign('lot_uid_name', $uid_name);
    $xoopsTpl->assign('lot_date', $lot_date);
    $lot_col = str_replace(' ', '', $lot_col);
    $xoopsTpl->assign('lot_col', $lot_col);

    $items = explode(';', $lot_col);
    $api = '';
    foreach ($items as $item) {
        list($var, $var_title) = explode('=', $item);
        $vars[$var] = $var_title;
        $api[] = "{$var}=<span style='color: #8e2323;'>{$var_title}值</span>";
    }
    $xoopsTpl->assign('lot_api', implode('&', $api));
    $xoopsTpl->assign('vars', $vars);

    $sweet_alert_obj = new SweetAlert();
    $delete_lot_func = $sweet_alert_obj->render('delete_lot_func', "{$_SERVER['PHP_SELF']}?op=delete_lot&lot_sn=", "lot_sn");
    $xoopsTpl->assign('delete_lot_func', $delete_lot_func);

    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('now_op', 'show_one_lot');

    $data = get_lot_data($lot_sn, $user);
    // die(var_export($data));
    $xoopsTpl->assign('log_data', $data);
    $xoopsTpl->assign('user', $user);

}

//列出所有lot資料
function list_lot()
{
    global $xoopsDB, $xoopsTpl, $isAdmin;

    $myts = \MyTextSanitizer::getInstance();

    $TadUpFiles = new TadUpFiles("lot");
    $sql = "select * from `" . $xoopsDB->prefix("lot") . "` ";

    //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = Utility::getPageBar($sql, 20, 10);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];
    $total = $PageBar['total'];

    $result = $xoopsDB->query($sql) or Utility::web_error($sql);

    $all_content = '';
    $i = 0;
    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $lot_sn, $lot_title, $lot_content, $lot_teacher, $lot_uid, $lot_date, $lot_col
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        //過濾讀出的變數值
        $lot_title = $myts->htmlSpecialChars($lot_title);
        $lot_content = $myts->displayTarea($lot_content, 1, 1, 0, 1, 0);
        $lot_teacher = $myts->htmlSpecialChars($lot_teacher);
        $lot_col = $myts->displayTarea($lot_col, 0, 1, 0, 1, 1);

        $all_content[$i]['lot_sn'] = $lot_sn;
        $all_content[$i]['lot_title'] = $lot_title;
        $all_content[$i]['lot_content'] = $lot_content;
        $all_content[$i]['lot_teacher'] = $lot_teacher;
        $all_content[$i]['lot_uid'] = $lot_uid;
        $all_content[$i]['lot_uid_name'] = $uid_name;
        $all_content[$i]['lot_date'] = $lot_date;
        $all_content[$i]['lot_col'] = $lot_col;
        $TadUpFiles->set_col("lot_sn", $lot_sn);
        $show_files = $TadUpFiles->show_files('up_lot_sn', true, 'small', true, false, null, null, false);
        $all_content[$i]['list_file'] = $show_files;
        $i++;
    }

    //刪除確認的JS
    $sweet_alert_obj = new SweetAlert();
    $delete_lot_func = $sweet_alert_obj->render('delete_lot_func',
        "{$_SERVER['PHP_SELF']}?op=delete_lot&lot_sn=", "lot_sn");
    $xoopsTpl->assign('delete_lot_func', $delete_lot_func);

    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('isAdmin', $isAdmin);
    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('now_op', 'list_lot');
}

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$lot_sn = Request::getInt('lot_sn');
$files_sn = Request::getInt('files_sn');
$user = Request::getString('user');

switch ($op) {
    /*---判斷動作請貼在下方---*/

    //替換資料
    //case "replace_lot":
    //    replace_lot();
    //    header("location: {$_SERVER['PHP_SELF']}?lot_sn=$lot_sn");
    //    exit;
    //break;

    //新增資料
    case "insert_lot":
        $lot_sn = insert_lot();
        header("location: {$_SERVER['PHP_SELF']}?lot_sn=$lot_sn");
        exit;

    //更新資料
    case "update_lot":
        update_lot($lot_sn);
        header("location: {$_SERVER['PHP_SELF']}?lot_sn=$lot_sn");
        exit;

    case "lot_form":
        lot_form($lot_sn);
        break;

    case "delete_lot":
        delete_lot($lot_sn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //下載檔案
    case "tufdl":
        $TadUpFiles = new TadUpFiles("lot");
        $TadUpFiles->add_file_counter($files_sn, false);
        exit;
        break;

    default:
        if (empty($lot_sn)) {
            list_lot();
            //$main .= lot_form($lot_sn);
        } else {
            show_one_lot($lot_sn, $user);
        }
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("toolbar", Utility::toolbar_bootstrap($interface_menu));
$xoopsTpl->assign("isAdmin", $isAdmin);
include_once XOOPS_ROOT_PATH . '/footer.php';
