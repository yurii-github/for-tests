<?php

namespace app\components;

use yii\base\BootstrapInterface;
use yii\base\Controller;

class Bootstrap implements BootstrapInterface
{
    private static $isFrontend = true;

    public function bootstrap($app)
    {

        $app->on(Controller::EVENT_BEFORE_ACTION, function ($ev) use (&$app) {
            if (strpos($app->controller->getUniqueId(), 'Admin', 0) === 0) {
                self::$isFrontend = false;
            } else {
                self::$isFrontend = true;
            }
        });
    }

    public static function isFrontend()
    {
        return self::$isFrontend;
    }

    public static function isBackend()
    {
        return !self::$isFrontend;
    }
}