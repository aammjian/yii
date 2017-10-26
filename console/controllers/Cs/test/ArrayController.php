<?php
namespace console\controllers\Cs\test;

use yii\console\Controller;

class ArrayController extends Controller
{
    /**
     * 初始化
     */
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        echo 'Help:' . PHP_EOL;
        echo 'array函数参数测试' . PHP_EOL;

        echo 'Usage:' . PHP_EOL;
        echo './yii Cs/test/array [action]' . PHP_EOL;

        echo 'Actions:' . PHP_EOL;

        echo 'chunk   数组重新分组' . PHP_EOL;
        echo 'case   [upper|lower]   修改键名为全大写或小写' . PHP_EOL;
    }

    /**
     * 数组重新分组
     */
    public function actionChunk()
    {
        $arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
        echo '原数组：' . PHP_EOL;
        dump($arr);
        $res = array_chunk($arr, 4);
        dump($res);
        echo '4个一组分组：' . PHP_EOL;
        $res = array_chunk($arr, 20);
        dump($res);
        echo '20个一组分组：' . PHP_EOL;
    }

    /**
     * 修改键名为全大写或小写
     * [upper|lower]
     * @param $params string
     */
    public function actionCase($params = '')
    {
        if (strlen($params) == 0) {
            dump('请输入参数！upper|lower');
            return;
        }
        $type = $params == 'upper' ? CASE_UPPER : CASE_LOWER;
        $data = ['test' => 'tt', 'Tes' => 123, 'AA' => 'sdf'];
        echo '原数组' . PHP_EOL;
        dump($data);
        echo 'array_change_key_case(data,type)' . PHP_EOL;
        dump(array_change_key_case($data, $type));
    }

}