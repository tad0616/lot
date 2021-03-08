<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

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

function xoops_module_install_lot(&$module)
{

    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/lot");
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/lot/file");
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/lot/image");
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/lot/image/.thumbs");

    return true;
}
