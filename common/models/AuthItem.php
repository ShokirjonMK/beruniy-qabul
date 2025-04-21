<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property int $type
 * @property int $status
 * @property int $branch_id
 * @property string|null $description
 * @property string|null $rule_name
 * @property resource|null $data
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property Menu[] $menus
 * @property AuthItem[] $parents
 * @property AuthRule $ruleName
 * @property Branch $branch
 */
class AuthItem extends \yii\db\ActiveRecord
{
    use ResourceTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name' ,'type', 'description'], 'required'],
            [['type', 'created_at', 'updated_at' , 'branch_id', 'status'], 'integer'],
            ['status', 'in', 'range' => [0, 1]],
            ['type', 'in', 'range' => [1, 2, 3, 4]],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::class, 'targetAttribute' => ['branch_id' => 'id']],
//            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::class, 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::class, ['id' => 'branch_id']);
    }

    /**
     * Gets query for [[AuthAssignments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::class, ['item_name' => 'name']);
    }

    /**
     * Gets query for [[AuthItemChildren]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::class, ['parent' => 'name']);
    }

    /**
     * Gets query for [[AuthItemChildren0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::class, ['child' => 'name']);
    }

    /**
     * Gets query for [[Children]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * Gets query for [[Menus]].
     *
     * @return \yii\db\ActiveQuery
     */
//    public function getMenus()
//    {
//        return $this->hasMany(Menu::class, ['role_id' => 'name']);
//    }

    /**
     * Gets query for [[Parents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    /**
     * Gets query for [[RuleName]].
     *
     * @return \yii\db\ActiveQuery
     */
//    public function getRuleName()
//    {
//        return $this->hasOne(AuthRule::class, ['name' => 'rule_name']);
//    }


    public static function createItem($model, $post) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        if (!$model->save(false)) {
            $errors[] = ['Model saqlashda xatolik yuz berdi.'];
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        self::processRoles($post, $model->name, $errors);

        if (empty($errors)) {
            $transaction->commit();
            return ['is_ok' => true];
        } else {
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }
    }

    public static function updateItem($model, $post) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        if (!$model->save(false)) {
            $errors[] = ['Model saqlashda xatolik yuz berdi.'];
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        AuthChild::deleteAll(['parent' => $model->name]);
        AuthChild::deleteAll(['child' => $model->name]);

        self::processRoles($post, $model->name, $errors);

        if (empty($errors)) {
            $transaction->commit();
            return ['is_ok' => true];
        } else {
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }
    }

    private static function processRoles($post, $modelName, &$errors) {
        if (isset($post['rol']) && is_array($post['rol']) && count($post['rol']) > 0) {
            $new = new AuthChild();
            $new->parent = "super_admin";
            $new->child = $modelName;
            $new->save(false);

            foreach ($post['rol'] as $rolName => $value) {
                if ($rolName === "super_admin") {
                    $errors[] = ["$rolName bunday rol mavjud emas."];
                    continue;
                }

                $authItem = AuthItem::findOne(['name' => $rolName]);
                if (!$authItem) {
                    $errors[] = ["$rolName bunday rol mavjud emas."];
                    continue;
                }

                $new = new AuthChild();
                if ($value == 1) {
                    $new->parent = $rolName;
                    $new->child = $modelName;
                } elseif ($value == 2) {
                    $new->parent = $modelName;
                    $new->child = $rolName;
                } else {
                    $errors[] = ['Qiymat xatoligi!'];
                    continue;
                }
                $new->save(false);
            }
        } else {
            $errors[] = ['Bo\'ysinuvchi va bo\'ysindiruvchi rollarni tanlang!!!'];
        }
    }

}
