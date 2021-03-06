<?php
namespace backend\controllers\api\role;

use backend\src\form\role\allotRoleForm;
use backend\src\form\role\powerForm;
use backend\src\form\role\roleForm;
use backend\src\repository\RoleRepository;
use backend\src\service\AuthAssignmentService;
use backend\src\service\AuthItemChildService;
use yii\web\Response;
use yii\web\Controller;
use Yii;

/**
 * Site controller
 */
class RbacController extends Controller
{
    /**
     * 创建角色
     * @return array
     */
    public function actionAdd()
    {
        $data = [];
        Yii::$app->response->format = Response::FORMAT_JSON;
        $role_from = new roleForm();
        $role_from->load(Yii::$app->request->post(), '');
        if ($role_from->validate()) {
            $role_repository = new RoleRepository();
            $role_repository->save($role_from->role_contacts);
            $data['code'] = 200;
            $data['msg'] = '添加成功';
        } else {
            Yii::$app->response->statusCode = 400;
            return $role_from->errors;
        }
        return $data;
    }

    /**
     * 分配权限
     * @return array
     */
    public function actionPower()
    {
        $data = [];
        Yii::$app->response->format = Response::FORMAT_JSON;
        $power_form = new powerForm();
        $param = Yii::$app->request->post();
        $power_form->load($param, '');
        if ($power_form->validate()) {
            $service = new AuthItemChildService();
            $service->addChild($param['children'], $param['name']);
        } else {
            Yii::$app->response->statusCode = 400;
            return $power_form->errors;
        }
        return $data;
    }

    /**
     * 分配角色
     * @return array
     */
    public function actionRole()
    {
        $data = [];
        Yii::$app->response->format = Response::FORMAT_JSON;
        $allot_role_form = new allotRoleForm();
        $param = Yii::$app->request->post();
        $allot_role_form->load($param, '');
        if ($allot_role_form->validate()) {
            $service = new AuthAssignmentService();
            $service->grant($param['id'], $param['children']);
        } else {
            Yii::$app->response->statusCode = 400;
            return $allot_role_form->errors;
        }
        return $data;
    }
}
