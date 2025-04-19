<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * This is the model class for table "questions".
 *
 * @property int $id
 * @property int $subject_id
 * @property string|null $text
 * @property string|null $image
 * @property int|null $level
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int $type
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_deleted
 *
 * @property Options[] $options
 * @property Subjects $subject
 * @property Options $correct
 */
class Questions extends \yii\db\ActiveRecord
{
    use ResourceTrait;

    public $photo;
    public $photoMaxSize = 1024 * 1024 * 8; // 8 Mb
    public $photoExtension = 'jpg , png';

    public $variant;
    public $check;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject_id'], 'required'],
            [['subject_id', 'level', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted', 'type'], 'integer'],
            [['text'], 'string'],
            [['image'], 'string', 'max' => 255],
            [['photo'], 'file', 'skipOnEmpty' => true, 'extensions' => $this->photoExtension, 'maxSize' => $this->photoMaxSize],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subjects::class, 'targetAttribute' => ['subject_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'text' => Yii::t('app', 'Text'),
            'image' => Yii::t('app', 'Image'),
            'level' => Yii::t('app', 'Level'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    /**
     * Gets query for [[Options]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(Options::class, ['question_id' => 'id'])->where(['status' => 1, 'is_deleted' => 0]);
    }

    public function getCorrect()
    {
        return $this->hasOne(Options::class, ['question_id' => 'id'])->where(['status' => 1 , 'is_correct' => 1 ,'is_deleted' => 0]);
    }

    /**
     * Gets query for [[Subject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subjects::class, ['id' => 'subject_id']);
    }

    public function getJsonOption()
    {
        $options = $this->hasMany(Options::class, ['question_id' => 'id'])
            ->select('id')
            ->where(['is_deleted' => 0])->orderBy(new Expression('RAND()'))->all();

        return Json::encode($options);
    }

    public static function createItem($model , $post)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!isset($post['example'])) {
            $errors[] = ['Savol matnini yuborishda xatolik.'];
            return ['is_ok' => false , 'errors' => $errors];
        }

        $model->text = $post['example'];
        $model->status = 0;

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $photoFile = UploadedFile::getInstance($model, 'photo');
        if ($photoFile) {
            if (isset($photoFile->size)) {
                $photoFolderName = '@backend/web/uploads/questions';
                if (!file_exists(\Yii::getAlias($photoFolderName))) {
                    mkdir(\Yii::getAlias($photoFolderName), 0777, true);
                }

                $photoName = $model->subject_id ."_". time() . \Yii::$app->security->generateRandomString(20). '.' . $photoFile->extension;
                if ($photoFile->saveAs($photoFolderName."/".$photoName)) {
                    $model->image = $photoName;
                }
            }
        }

        if ($model->text == null && $model->image == null) {
            $errors[] = ['Matn , Rasm , Audio shulardan birini to\'ldirish shart!'];
        }

        if (count($errors) == 0) {
            $model->save(false);
            $transaction->commit();
            return ['is_ok' => true];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }


    public static function updateItem($model , $post, $oldModel)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

//        if ($model->status == 1) {
//            $errors[] = ['Tasdiqlangan savolni o\'zgartirib bo\'lmaydi.'];
//            return ['is_ok' => false , 'errors' => $errors];
//        }
        if (!isset($post['example'])) {
            $errors[] = ['Savol matnini yuborishda xatolik.'];
            return ['is_ok' => false , 'errors' => $errors];
        }

        $model->text = $post['example'];
        $model->status = 0;

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $photoFile = UploadedFile::getInstance($model, 'photo');
        if ($photoFile) {
            if (isset($photoFile->size)) {
                $photoFolderName = '@backend/web/uploads/questions';
                if (!file_exists(\Yii::getAlias($photoFolderName))) {
                    mkdir(\Yii::getAlias($photoFolderName), 0777, true);
                }

                $photoName = $model->subject_id ."_". time() . \Yii::$app->security->generateRandomString(20). '.' . $photoFile->extension;
                if ($photoFile->saveAs($photoFolderName."/".$photoName)) {
                    $model->image = $photoName;
                }
            }
        }

        if ($model->text == null && $model->image == null) {
            $errors[] = ['Matn , Rasm shulardan birini to\'ldirish shart!'];
        }

        if (count($errors) == 0) {
            $model->save(false);
            $transaction->commit();
            return ['is_ok' => true];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }


    public static function createBotItem($model , $post)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        $model->status = 0;
        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $photoFile = UploadedFile::getInstance($model, 'photo');
        if ($photoFile) {
            if (isset($photoFile->size)) {
                $photoFolderName = '@backend/web/uploads/questions';
                if (!file_exists(\Yii::getAlias($photoFolderName))) {
                    mkdir(\Yii::getAlias($photoFolderName), 0777, true);
                }

                $photoName = $model->subject_id ."_". time() . \Yii::$app->security->generateRandomString(20). '.' . $photoFile->extension;
                if ($photoFile->saveAs($photoFolderName."/".$photoName)) {
                    $model->image = $photoName;
                }
            }
        }
        if ($model->text == null && $model->image == null) {
            $errors[] = ['Matn , Rasm shulardan birini to\'ldirish shart!'];
        }
        $model->save(false);
        $options = $post['variant'];
        $check = $post['check'];
        foreach ($options as $key => $option) {
            $new = new Options();
            $new->question_id = $model->id;
            $new->subject_id = $model->subject_id;
            $new->text = $option;
            $new->order = $key;
            if ($key == $check) {
                $new->is_correct = 1;
            }
            $new->save(false);
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }


    public static function updateBotItem($model , $post, $oldModel)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $photoFile = UploadedFile::getInstance($model, 'photo');
        if ($photoFile) {
            if (isset($photoFile->size)) {
                $photoFolderName = '@backend/web/uploads/questions';
                if (!file_exists(\Yii::getAlias($photoFolderName))) {
                    mkdir(\Yii::getAlias($photoFolderName), 0777, true);
                }

                $photoName = $model->subject_id ."_". time() . \Yii::$app->security->generateRandomString(20). '.' . $photoFile->extension;
                if ($photoFile->saveAs($photoFolderName."/".$photoName)) {
                    $model->image = $photoName;
                }
            }
        }
        if ($model->text == null && $model->image == null) {
            $errors[] = ['Matn , Rasm shulardan birini to\'ldirish shart!'];
        }
        $model->save(false);

        $options = $post['variant'];
        $check = $post['check'];
        foreach ($options as $key => $option) {
            $query = Options::findOne([
                'question_id' => $model->id,
                'order' => $key,
                'is_deleted' => 0
            ]);
            if ($key == $check) {
                $query->is_correct = 1;
            } else {
                $query->is_correct = 0;
            }
            $query->text = $option;
            $query->save(false);
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }


    public static function checkItem($model)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if ($model->status != 0) {
            $errors[] = ['Savol avval tasdiqlangan.'];
        }

        $options = $model->options;
        $active = false;
        if (count($options) > 1) {
            foreach ($options as $option) {
                if ($option->is_correct == 1) {
                    $active = true;
                    break;
                }
            }
        }

        if (!$active) {
            $errors[] = ['Savol variantlarida kamchilik bor!'];
        }

        if (count($errors) == 0) {
            $model->status = 1;
            $model->save(false);
            $transaction->commit();
            return ['is_ok' => true];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }
}
