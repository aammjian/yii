<?php
namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    protected $actions = ['index', 'error'];

    protected $except = ['login', 'logout'];

    /**
     * 首页
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->view('index');
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        return $this->view('login');
    }

    /**
     * 退出登录
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(Url::toRoute('site/login', true));
    }

    public function actionError()
    {
        return $this->view('error');
    }

}
