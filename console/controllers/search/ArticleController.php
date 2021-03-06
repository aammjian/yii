<?php
namespace console\controllers\search;

use Carbon\Carbon;
use console\controllers\utils\Elasticsearch\Client;
use yii\console\Controller;
use Yii;

class ArticleController extends Controller
{
    private $index = 'article';

    private $type = 'user';

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        echo 'create        创建index' . PHP_EOL;
        echo 'mapping       创建mapping' . PHP_EOL;
        echo 'getMapping    读取mapping' . PHP_EOL;
        echo 'del           删除整个索引' . PHP_EOL;
        echo 'add           增加数据' . PHP_EOL;
        echo 'term          根据term进行搜索' . PHP_EOL;
    }

    /**
     * 创建index
     */
    public function actionCreate()
    {
        $client = Client::getInstance();
        $indices = $client->indices();

        $params = [
            'index' => $this->index,
        ];
        try {
            $res = $indices->create($params);
            if ($res['acknowledged']) {
                dump('Index 创建成功');
            }
        } catch (\Exception $ex) {
            $res = json_decode($ex->getMessage(), true);
            dump($res);
        }
    }

    /**
     * mapping
     */
    public function actionMapping()
    {
        $client = Client::getInstance();
        $indices = $client->indices();

        $params = [
            'index' => $this->index,
            'type' => $this->type,
            'body' => [
                'properties' => [
                    'articleID' => ['type' => 'string'],
                    'userId' => ['type' => 'integer'],
                    'hidden' => ['type' => 'boolean'],
                    'postDate' => ['type' => 'date'],
                ],
            ],
        ];
        try {
            $res = $indices->putMapping($params);
            if ($res['acknowledged']) {
                dump('Mapping 设置成功');
            }
        } catch (\Exception $ex) {
            $res = json_decode($ex->getMessage(), true);
            dump($res);
        }
    }

    /**
     * 读取mapping
     */
    public function actionGetMapping()
    {
        $client = Client::getInstance();
        $indices = $client->indices();

        $params = [
            'index' => $this->index,
            'type' => $this->type,
        ];
        try {
            $res = $indices->getMapping($params);
            dump($res);
        } catch (\Exception $ex) {
            $res = json_decode($ex->getMessage(), true);
            dump($res);
        }
    }

    /**
     * 删除整个索引
     */
    public function actionDel()
    {
        $client = Client::getInstance();
        $indices = $client->indices();

        $params = [
            'index' => $this->index,
        ];
        try {
            $res = $indices->delete($params);
            if ($res['acknowledged']) {
                dump('删除索引成功');
            }
        } catch (\Exception $ex) {
            $res = json_decode($ex->getMessage(), true);
            dump($res);
        }
    }

    /**
     * 创建数据
     */
    public function actionAdd()
    {
        $client = Client::getInstance();
        for ($i = 0; $i < 5; $i++) {
            $params = [
                'index' => $this->index,
                'type' => $this->type,
                'id' => $i,
                'body' => [
                    'articleID' => uniqid(),
                    'hidden' => $i % 2 ? true : false,
                    'postDate' => time(),
                    'userId' => $i,
                ],
            ];
            try {
                $res = $client->index($params);
                dump($res);
                if ($res['created']) {
                    echo '用户DOC创建成功' . PHP_EOL;
                } else {
                    echo '用户DOC创建失败（可能已存在））' . PHP_EOL;
                }
            } catch (\Exception $ex) {
                dump($ex->getMessage());
            }
        }
    }

    /**
     * 根绝userId、hidden搜索
     */
    public function actionTerm()
    {
        $client = Client::getInstance();
        $res = [];
        try {
            $params = [
                'index' => $this->index,
                'type' => $this->type,
                'body' => [
                    'query' => [
                        'constant_score' => [
                            'filter' => [
                                'term' => [
//                                    'userId' => 1,
                                    'hidden' => true
                                ],
                            ],
                        ],
                    ],
                    'from' => 0,
                    'size' => 5,
                ],
            ];
            $res = $client->search($params);
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
        dd($res);

    }


}