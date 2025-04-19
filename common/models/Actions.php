<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "actions".
 *
 * @property int $id
 * @property string $controller
 * @property string $action
 * @property string $description
 * @property int|null $status
 *
 * @property Menu[] $menus
 */
class Actions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'actions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['controller', 'action'], 'required'],
            [['status'], 'integer'],
            [['controller', 'action' , 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'controller' => Yii::t('app', 'Controller'),
            'action' => Yii::t('app', 'Action'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[Menus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::class, ['action_id' => 'id']);
    }

    public function getActions()
    {
        $query = Actions::find()
            ->where([
                'controller' => $this->controller,
                'status' => 0,
            ])
            ->all();
        if (isset($query)) {
            return $query;
        }
        return null;
    }

    public function getIsPermission() {


    }

    public static function deleteItem($model)
    {
        $transaction = Yii::$app->db->beginTransaction();

        Menu::deleteAll(['action_id' => $model->id]);
        Permission::deleteAll(['action_id' => $model->id]);
        $model->delete();

        $transaction->commit();
        return ['is_ok' => true];
    }
}