<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $role_id
 * @property string $name
 * @property int $action_id
 * @property string $icon
 * @property int|null $parent_id
 * @property int|null $status
 * @property int|null $order
 *
 * @property Actions $action
 * @property Menu[] $menus
 * @property Menu $parent
 * @property AuthItem $role
 */
class Menu extends \yii\db\ActiveRecord
{
    const SCENARIO_MENU = 'menu';
    const SCENARIO_SUB_MENU = 'sub_menu';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['name','icon'], 'required' , 'on' => self::SCENARIO_MENU],
            [['name','action_id', 'parent_id'], 'required' , 'on' => self::SCENARIO_SUB_MENU],
            [['action_id', 'parent_id', 'status' , 'order'], 'integer'],
            [['role_id', 'name_uz', 'name_en', 'name_ru', 'name_kr', 'icon'], 'string', 'max' => 255],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => Actions::class, 'targetAttribute' => ['action_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::class, 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_MENU => ['name','icon', 'action_id','status' ,'order'],
            self::SCENARIO_SUB_MENU => ['name', 'action_id', 'parent_id', 'status', 'order'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'action_id' => Yii::t('app', 'Action ID'),
            'icon' => Yii::t('app', 'Icon'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'status' => Yii::t('app', 'Status'),
            'order' => Yii::t('app', 'Order'),
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
     * Gets query for [[Menus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::class, ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Menu::class, ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getRole()
    {
        return $this->hasOne(AuthItem::class, ['name' => 'role_id']);
    }

    public static function createItem($model , $post) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if ($post['Menu']['order'] != "") {
            if ($post['Menu']['order'] <= 0) {
                $orderDescOne = Menu::find()
                    ->where(['status' => 0])
                    ->orderBy('order desc')
                    ->one();
                if (isset($orderDescOne)) {
                    $model->order = $orderDescOne->order + 1;
                } else {
                    $model->order = 1;
                }
            } else {
                $order = $post['Menu']['order'];
                $data = [
                    'status' => 0
                ];
                $orderDescOne = Menu::find()
                    ->where($data)
                    ->orderBy('order desc')
                    ->one();
                if (isset($orderDescOne)) {
                    if ($orderDescOne->order+1 < $order) {
                        $model->order = $orderDescOne->order+1;
                    } elseif ($orderDescOne->order > $order) {
                        $orderUpdate = Menu::find()->where([
                            'between', 'order', $order, $orderDescOne->order
                        ])
                            ->andWhere($data)
                            ->all();
                        if (isset($orderUpdate)) {
                            foreach ($orderUpdate as $orderItem) {
                                $orderItem->order = $orderItem->order + 1;
                                $orderItem->save(false);
                            }
                        }
                    } elseif ($orderDescOne->order == $order) {
                        $orderDescOne->order = $orderDescOne->order + 1;
                        $orderDescOne->save(false);
                    }
                } else {
                    $model->order = 1;
                }
            }
        } else {
            $orderDescOne = Menu::find()
                ->where(['status' => 0])
                ->orderBy('order desc')
                ->one();
            if (isset($orderDescOne)) {
                $model->order = $orderDescOne->order + 1;
            } else {
                $model->order = 1;
            }
        }

        if (!$model->validate()) {
            $errors[] = $model->errors;
        }

        if ($model->save()) {
            $transaction->commit();
            return ['is_ok' => true ];
        } else {
            $transaction->rollBack();
            return ['is_ik' => false , 'errors' => $errors];
        }
    }

    public static function updateItem($model , $post , $orderOld) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        // update order dinamik
        if (isset($post['Menu']['order'])) {
            if ($post['Menu']['order'] <= 0) {
                $orderDescOne = Menu::find()
                    ->where(['status' => 0])
                    ->orderBy('order desc')
                    ->one();
                if (isset($orderDescOne)) {
                    $model->order = $orderDescOne->order + 1;
                } else {
                    $model->order = 1;
                }
            } else {
                $order = $post['Menu']['order'];
                $data = [
                    'status' => 0
                ];
                $tableName = new Menu();
                $tableName->scenario = Menu::SCENARIO_MENU;
                $tableName->orderUpdate($order, $data, $tableName, $orderOld);
            }
        }
        // update order dinamik

        if (!$model->validate()) {
            $errors[] = $model->errors;
        }

        if ($model->save()) {
            $transaction->commit();
            return true;
        } else {
            $transaction->rollBack();
            return false;
        }

    }

    public static function error() {
        $errors = [];

        $errors[] = ["edu dasdasd" => Yii::t('app', 'salom')];
        $errors[] = ["edu iiiiasdasdasd" => Yii::t('app', 'salom')];
        $errors[] = ["edu asdasd" => Yii::t('app', 'salom')];
        $errors[] = ["edu semasdasdasdestr" => Yii::t('app', 'salom')];

        return $errors;
    }

    public static function updateSubMenu($model , $post , $orderOld) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        // update order dinamik
        if (isset($post['Menu']['order'])) {
            if ($post['Menu']['order'] <= 0) {
                $orderDescOne = Menu::find()
                    ->where(['parent_id' => $model->parent_id,'status' => 1])
                    ->orderBy('order desc')
                    ->one();
                if (isset($orderDescOne)) {
                    $model->order = $orderDescOne->order + 1;
                } else {
                    $model->order = 1;
                }
            } else {
                $order = $post['Menu']['order'];
                $data = [
                    'parent_id' => $model->parent_id,
                    'status' => 1
                ];
                $tableName = new Menu();
                $tableName->scenario = Menu::SCENARIO_SUB_MENU;
                $tableName->orderUpdate($order, $data, $tableName, $orderOld);
            }
        }
        // update order dinamik

        if (!$model->validate()) {
            $errors[] = $model->errors;
        }

        if ($model->save()) {
            $transaction->commit();
            return true;
        } else {
            $transaction->rollBack();
            return false;
        }

    }

    public function orderUpdate($order, $data, $tableName, $modelOrder) {

        if ($order < $modelOrder) {
            $orderUpdate = $tableName->find()->where([
                'between', 'order', $order, $modelOrder-1
            ])->andWhere($data)->all();

            if (isset($orderUpdate)) {
                foreach ($orderUpdate as $orderItem) {
                    $orderItem->order = $orderItem->order + 1;
                    $orderItem->save(false);
                }
            }
        }

        if ($order > $modelOrder) {
            $orderUpdate = $tableName->find()->where([
                'between', 'order', $modelOrder+1, $order
            ])->andWhere($data)->all();

            if (isset($orderUpdate)) {
                foreach ($orderUpdate as $orderItem) {
                    $orderItem->order = $orderItem->order - 1;
                    $orderItem->save(false);
                }
            }
        }

    }

    public static function createSubMenu($model , $post) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$model->validate()) {
            $errors[] = $model->errors;
        }

        if ($post['Menu']['order'] != "") {
            if ($post['Menu']['order'] <= 0) {
                $orderDescOne = Menu::find()
                    ->where([
                        'parent_id' => $model->parent_id,
                        'status' => 1
                    ])
                    ->orderBy('order desc')
                    ->one();
                if (isset($orderDescOne)) {
                    $model->order = $orderDescOne->order + 1;
                } else {
                    $model->order = 1;
                }
            } else {
                $order = $post['Menu']['order'];
                $data = [
                    'parent_id' => $model->parent_id,
                    'status' => 1
                ];
                $orderDescOne = Menu::find()
                    ->where($data)
                    ->orderBy('order desc')
                    ->one();
                if (isset($orderDescOne)) {
                    if ($orderDescOne->order+1 < $order) {
                        $model->order = $orderDescOne->order+1;
                    } elseif ($orderDescOne->order > $order) {
                        $orderUpdate = Menu::find()->where([
                            'between', 'order', $order, $orderDescOne->order
                        ])
                            ->andWhere($data)
                            ->all();
                        if (isset($orderUpdate)) {
                            foreach ($orderUpdate as $orderItem) {
                                $orderItem->order = $orderItem->order + 1;
                                $orderItem->save(false);
                            }
                        }
                    } elseif ($orderDescOne->order == $order) {
                        $orderDescOne->order = $orderDescOne->order + 1;
                        $orderDescOne->save(false);
                    }
                } else {
                    $model->order = 1;
                }
            }
        } else {
            $orderDescOne = Menu::find()
                ->where([
                    'parent_id' => $model->parent_id,
                    'status' => 1
                ])
                ->orderBy('order desc')
                ->one();
            if (isset($orderDescOne)) {
                $model->order = $orderDescOne->order + 1;
            } else {
                $model->order = 1;
            }
        }

        if (!$model->validate()) {
            $errors[] = $model->errors;
        }

        if ($model->save()) {
            $transaction->commit();
            return true;
        } else {
            $transaction->rollBack();
            return false;
        }
    }
}
