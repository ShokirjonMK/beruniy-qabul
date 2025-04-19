<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "permission".
 *
 * @property int $id
 * @property string $role_name
 * @property int $action_id
 * @property int|null $status
 *
 * @property Actions $action
 * @property AuthItem $roleName
 */
class Permission extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permission';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_name', 'action_id'], 'required'],
            [['action_id', 'status'], 'integer'],
            [['role_name'], 'string', 'max' => 255],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => Actions::class, 'targetAttribute' => ['action_id' => 'id']],
            [['role_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::class, 'targetAttribute' => ['role_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'role_name' => Yii::t('app', 'Role Name'),
            'action_id' => Yii::t('app', 'Action ID'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[Action]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasOne(Actions::class, ['id' => 'action_id']);
    }

    /**
     * Gets query for [[RoleName]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoleName()
    {
        return $this->hasOne(AuthItem::class, ['name' => 'role_name']);
    }

    public static function createPermission($post , $role) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        Permission::updateAll(['status' => 2], ['role_name' => $role]);

        foreach ($post['action'] as $key => $action) {
            $query = Permission::findOne([
                'role_name' => $role,
                'action_id' => $key,
            ]);
            if (isset($query)) {
                $query->status = 1;
                if (!$query->save()) {
                    $errors[] = ['permission' => 'Permission qayta saqlanmadi.'];
                }
            } else {
                $model = new Permission();
                $model->role_name = $role;
                $model->action_id = $key;
                $model->status = 1;
                if (!$model->save()) {
                    $errors[] = ['permission' => 'Permission saqlanmadi.'];
                }
            }
        }


        Permission::deleteAll(['status' => 2, 'role_name' => $role]);


        if (count($errors) == 0) {
            $transaction->commit();
            return true;
        } else {
            $transaction->rollBack();
            return false;
        }
    }

    public static function isPermission($action, $user) {
        $isPermission = Permission::findOne([
            'role_name' => $user->user_role,
            'action_id' => $action,
            'status' => 1
        ]);
        if ($isPermission) {
            return true;
        }
        return false;
    }


}
