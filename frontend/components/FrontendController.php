<?php

namespace frontend\components;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class FrontendController extends Controller
{
    protected function redirectBack($default = null): Response
    {
        if (!$default) {
            $default = Yii::$app->homeUrl;
        }
        return $this->redirect(Yii::$app->request->referrer ?: $default);
    }

    protected function addAlert(string $text, string $key = 'success'): void
    {
        Yii::$app->session->setFlash($key, $text);
    }
}