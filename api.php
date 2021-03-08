<?php
use XoopsModules\Tadtools\TadDataCenter;
use XoopsModules\Tadtools\Utility;

include_once '../../mainfile.php';
include_once XOOPS_ROOT_PATH . '/modules/lot/function.php';

if (!add_lot()) {
    $file = XOOPS_ROOT_PATH . '/uploads/shit.html';

    $current = file_get_contents($file);
    $new = date("Y-m-d H:i:s") . " ({$_SERVER['REMOTE_ADDR']}-{$_SERVER["HTTP_USER_AGENT"]}}) <hr>";
    $val_arr = '';
    foreach ($_REQUEST as $key => $value) {
        $new .= "<p>{$key} = {$value};</p>";
    }
    $new .= "<hr>";
    file_put_contents($file, $new . $current);
    send_now('tad0616@gmail.com', '惡搞紀錄', '<p>' . XOOPS_URL . '/uploads/shit.html</p>' . $new);
    // header('location:index.php');
    exit;
}

//新增資料到lot中
function add_lot()
{
    global $xoopsDB;
    $TadDataCenter = new TadDataCenter('lot');

    //http://網址/api.php?schoolcode=xxx&t=10&h=30&pm25=22&gps=22.xxx,120.xxx
    $myts = \MyTextSanitizer::getInstance();
    $user = $myts->addSlashes($_REQUEST['user']);
    $lot_sn = intval($_REQUEST['sn']);
    $log_date = date("Y-m-d H:i:s");
    $from_ip = $_SERVER['REMOTE_ADDR'];

    if (empty($user)) {
        return false;
    }

    $sql = "insert into `" . $xoopsDB->prefix("lot_data") . "` (
        `lot_sn`,
        `user`,
        `log_date`,
        `from_ip`
    ) values(
        '{$lot_sn}',
        '{$user}',
        '{$log_date}',
        '{$from_ip}'
    )";
    $xoopsDB->queryF($sql) or Utility::web_error($sql);

    //取得最後新增資料的流水編號
    $lot_data_sn = $xoopsDB->getInsertId();
    $TadDataCenter->set_col('lot_data_sn', $lot_data_sn);
    foreach ($_REQUEST as $var => $val) {
        if ($var == 'user' or $var == 'sn') {
            continue;
        }
        $var = $myts->addSlashes($var);
        $data[$var] = $myts->addSlashes($val);
    }
    $TadDataCenter->saveCustomData($data);
    return true;
}

//立即寄出
function send_now($email = "", $title = "", $content = "", $address = "", $name = "")
{
    global $xoopsConfig, $xoopsDB, $xoopsModuleConfig;

    $xoopsMailer = &getMailer();
    $xoopsMailer->multimailer->ContentType = "text/html";
    $xoopsMailer->addHeaders("MIME-Version: 1.0");
    if (!empty($address)) {
        $xoopsMailer->AddReplyTo($address, $name);
    }
    $msg = ($xoopsMailer->sendMail($email, $title, $content, $headers)) ? true : false;
    return $msg;
}
