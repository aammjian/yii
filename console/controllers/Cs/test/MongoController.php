<?php
namespace console\controllers\Cs\test;

use yii\console\Controller;
use yii\helpers\ArrayHelper;
use Yii;

class MongoController extends Controller
{
    /**
     * 初始化
     */
    public function init()
    {
        // 路径
        $this->path = Yii::$app->basePath;

        parent::init(); // TODO: Change the autogenerated stub
        echo 'Help:' . PHP_EOL;
        echo 'PHP函数参数测试' . PHP_EOL;

        echo 'Usage:' . PHP_EOL;
        echo './yii Cs/test/mongo [action]' . PHP_EOL;

        echo 'Actions:' . PHP_EOL;

        echo 'insert                新建记录' . PHP_EOL;
    }

    public function actionInsert()
    {

    }
}