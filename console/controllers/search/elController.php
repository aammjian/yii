<?php
namespace console\controllers\search;

use console\controllers\utils\Elasticsearch\Client;
use console\controllers\utils\Elasticsearch\ES;
use yii\console\Controller;
use Yii;

class ElController extends Controller
{

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

    }

    public function actionList()
    {
        $client = Client::getInstance();
        try {
            $cat = $client->cat();
            $res = $cat->indices();
            $indices = $client->indices();
            $res = $indices->getMapping([
                'index' => ES::ES_INDEX
            ]);

            dump($res);
        } catch (\Exception $ex) {
            $res = json_decode($ex->getMessage(), true);
            dump($res);
        }
    }
}