<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use App\Utils;
use Smarty;

final class View
{
    public static $connection;
    public static $beginTime;

    public static function getSmarty(): Smarty
    {
        $smarty = new Smarty(); //实例化smarty

        $user = Auth::getUser();

        if ($user->isLogin) {
            $theme = $user->theme;
        } else {
            $theme = $_ENV['theme'];
        }

        $can_backtoadmin = 0;
        if (Utils\Cookie::get('old_uid') && Utils\Cookie::get('old_email') && Utils\Cookie::get('old_key') && Utils\Cookie::get('old_ip') && Utils\Cookie::get('old_expire_in') && Utils\Cookie::get('old_local')) {
            $can_backtoadmin = 1;
        }

        $smarty->settemplatedir(BASE_PATH . '/resources/views/' . $theme . '/'); //设置模板文件存放目录
        $smarty->setcompiledir(BASE_PATH . '/storage/framework/smarty/compile/'); //设置生成文件存放目录
        $smarty->setcachedir(BASE_PATH . '/storage/framework/smarty/cache/'); //设置缓存文件存放目录
        // add config
        $smarty->assign('config', Config::getPublicConfig());
        $smarty->assign('public_setting', Setting::getPublicConfig());
        $smarty->assign('user', $user);
        $smarty->assign('can_backtoadmin', $can_backtoadmin);

        return $smarty;
    }
}
