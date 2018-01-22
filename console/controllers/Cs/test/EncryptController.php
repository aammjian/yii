<?php
namespace console\controllers\Cs\test;

use yii\console\Controller;
use yii\helpers\ArrayHelper;
use Yii;

class EncryptController extends Controller
{
    static private $iv = '00000000000000000000000000000000';

    /**
     * 初始化
     */
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        echo 'Help:' . PHP_EOL;
        echo 'PHP加密测试' . PHP_EOL;

        echo 'Usage:' . PHP_EOL;
        echo './yii Cs/test/encrypt [action]' . PHP_EOL;

        echo 'Actions:' . PHP_EOL;

        echo 'rsa               RSA加密测试' . PHP_EOL;
        echo 'aes               AES加密测试' . PHP_EOL;
        echo 'hex               Hex Str 转化测试' . PHP_EOL;
        echo 'Mcrypt            aesMcrypt的AeS加密' . PHP_EOL;
    }

    public function actionMcrypt()
    {
        try {
            $data = "41e0442edf5e1071b4a514409fec46cc";
            $key = "41a10264c72a8fee55bc258ee24bc9c0";
            dump('密文：' . $data);
            dump('密钥：' . $key);
            dump('解密：' . $this->decryptData($data, $key));
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
    }

    /**
     * @param $content
     * @param $key
     * @return string
     */
    public function decryptData($content, $key)
    {
        $decrypt_data = mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $this->hexToStr($key),
            $this->hexToStr($content),
            MCRYPT_MODE_ECB,
            $this->hexToStr(self::$iv)
        );
        return $this->strToHex($decrypt_data);
    }

    /**
     * Hex Str 转化测试
     */
    public function actionHex()
    {
        $key = '313233343536373839616263646566';

        echo $this->hexToStr($key);
        echo $this->strToHex($this->hexToStr($key));
    }

    /**
     * @param $string
     * @return string
     */
    private function strToHex($string)
    {
        return bin2hex($string);
    }

    /**
     * @param $hex
     * @return string
     */
    private function hexToStr($hex)
    {
        $str = "";
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $str .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $str;
    }

    /**
     * AES加密测试
     */
    public function actionAes()
    {
        try {
            $data = 'phpbest';
            dump('内容:' . $data);
            $key = base64_encode(openssl_random_pseudo_bytes(32));
            dump('KEY:' . $key);
            $iv = base64_encode(openssl_random_pseudo_bytes(16)); //echo base64_encode(openssl_random_pseudo_bytes(16));
            dump('IV:' . $iv);

            $encrypted = openssl_encrypt($data, 'aes-256-cbc', base64_decode($key), OPENSSL_RAW_DATA, base64_decode($iv));
            $encrypted = base64_encode($encrypted);
            dump('加密:' . $encrypted);
            $decrypted = openssl_decrypt(base64_decode($encrypted), 'aes-256-cbc', base64_decode($key), OPENSSL_RAW_DATA, base64_decode($iv));
            dump('解密:' . $decrypted);


        } catch (\Exception $e) {
            dump($e->getMessage());
        }
    }

    /**
     * RSA加密测试
     */
    public function actionRsa()
    {
        $path = Yii::$app->basePath;
        $file = $path . '/data/rsa/test/helloworld.php';
        $this->encrypt($file);
        $file = $path . '/data/rsa/test/encrypted.php';
        $this->decrypt($file);
        dump($path);
    }

    /**
     * @param $file
     */
    private function encrypt($file)
    {
        $path = Yii::$app->basePath;
        $private_key = file_get_contents($path . '/data/rsa/rsa_private_key.pem');

        $msg = file_get_contents($file);
        $msg = base64_encode($msg);
        $len = strlen($msg);
        $step = 110;
        $result = [];
        for ($i = 0; $i < $len;) {
            $input = substr($msg, $i, $step);
            $private_key = openssl_pkey_get_private($private_key);
            // 私钥加密
            $ret = openssl_private_encrypt($input, $encrypted, $private_key);

            if (!$ret || empty($encrypted)) {
                echo "fail to encrypt file md5";
                return;
            }
            // 存储加密文件
            $encrypted = base64_encode($encrypted);
            $result[] = $encrypted;
            $i += $step;
        }
        file_put_contents($path . '/data/rsa/test/encrypted.php', json_encode($result));
    }

    /**
     * @param $file
     */
    private function decrypt($file)
    {
        $path = Yii::$app->basePath;
        $public_key = file_get_contents($path . '/data/rsa/rsa_public_key.pem');
        $msg = file_get_contents($file);
        $msg = json_decode($msg);
        $result = '';
        foreach ($msg as $item) {
            // 公钥解密
            openssl_public_decrypt(base64_decode($item), $decrypted, $public_key);
            $result .= $decrypted;
        }
        $result = base64_decode($result);
        // 存储解密文件
        file_put_contents($path . '/data/rsa/test/decrypted.php', $result);
    }
}