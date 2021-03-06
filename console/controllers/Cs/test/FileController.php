<?php
namespace console\controllers\Cs\test;

use moonland\phpexcel\Excel;
use Phalcon\Image\Adapter\Gd;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use Yii;

class FileController extends Controller
{
    private $path;

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
        echo './yii Cs/test/file [action]' . PHP_EOL;

        echo 'Actions:' . PHP_EOL;

        echo 'save              文件储存' . PHP_EOL;
        echo 'img               图片裁剪' . PHP_EOL;
        echo 'WriteCsv          写入csv文件' . PHP_EOL;
    }

    /**
     * 写入csv文件
     */
    public function actionWriteCsv()
    {
        $file = $this->path . '/data/storage/output/' . date('Ymd') . '/';
        if (!is_dir($file)) {
            mkdir($file, 0777, true);
        }
        $file .= 'write_csv.csv';
        $fp = fopen($file, 'a');
        for ($i = 0; $i < 10; $i++) {
            fputcsv($fp, [$i, uniqid(), time()]);
        }
        fclose($fp);
    }

    /**
     * img
     */
    public function actionImg()
    {
        $file = $this->path . '/data/file/logo.jpg';
        $output = $this->path . '/data/file/logo1.jpg';
        $res = $this->imageResize($file, 500, 800, $output);
        print_r($res);

        $output = $this->path . '/data/file/logo2.png';
        $res = $this->imageResize($file, 500, 200, $output);
        print_r($res);
    }

    /**
     * @param $file
     * @param int $width
     * @param int $height
     * @param null $output
     * @return \Phalcon\Image\Adapter
     */
    protected function imageResize($file, $width = 500, $height = 500, $output = null)
    {
        $image = new Gd($file);
        $r_width = null;
        $r_height = null;

        $r1 = floatval($image->getWidth() / $image->getHeight());
        $r2 = floatval($width / $height);
        Yii::info(json_encode([$r1, $r2]));
        if ($r1 > $r2) {

            $offsetX = intval((floatval($image->getWidth() / $image->getHeight()) * $height - $width) / 2);
            $offsetY = 0;
            $r_height = $height;  //
            $master = \Phalcon\Image::HEIGHT;  //
        } else {
            $offsetX = 0;
            $offsetY = intval(floatval(($image->getHeight() / $image->getWidth()) * $width - $height) / 2);
            $r_width = $width;
            $master = \Phalcon\Image::WIDTH;
        }
        Yii::info(json_encode([$r_width, $r_height, $master, $width, $height, $offsetX, $offsetY]));
        if (!isset($output)) {
            $output = $file;
        }
        return $image->resize($r_width, $r_height, $master)
            ->crop($width, $height, $offsetX, $offsetY)
            ->save($output);
    }

    /**
     * 文件存储
     * @return int
     */
    public function actionSave()
    {
        try {
            $url = 'http://oni42o7kl.bkt.clouddn.com/0e03b7da-db9e-4819-ba10-9016ddfdaed3';
            $target = $this->path . '/data/uploads/';

            $data = file_get_contents($url);
            if (!is_dir($target)) {
                mkdir($target, 0777, true);
            }
            $name = uniqid() . ".jpg";
            return file_put_contents($target . $name, $data);
        } catch (\Exception $e) {
            dump($e->getMessage());
        }

    }
}