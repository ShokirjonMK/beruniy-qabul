<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "options".
 *
 * @property int $id
 * @property int $question_id
 * @property int $subject_id
 * @property int $order
 * @property string|null $text
 * @property string|null $image
 * @property int $is_correct
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_deleted
 *
 * @property Questions $question
 * @property Subjects $subject
 */
class Options extends \yii\db\ActiveRecord
{

    use ResourceTrait;

    public $photo;

    public $photoMaxSize = 1024 * 1024 * 8; // 8 Mb
    public $photoExtension = 'jpg , png';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'options';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question_id', 'subject_id'], 'required'],
            [['question_id', 'subject_id', 'is_correct', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['text'], 'string'],
            [['image'], 'string', 'max' => 255],
            [['photo'], 'file', 'skipOnEmpty' => true, 'extensions' => $this->photoExtension, 'maxSize' => $this->photoMaxSize],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Questions::class, 'targetAttribute' => ['question_id' => 'id']],
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
            'question_id' => Yii::t('app', 'Question ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'text' => Yii::t('app', 'Text'),
            'image' => Yii::t('app', 'Image'),
            'is_correct' => Yii::t('app', 'Is Correct'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    /**
     * Gets query for [[Question]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Questions::class, ['id' => 'question_id']);
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


    public static function options($questionId , $jsonOptions)
    {
        $jsonOptions = json_decode($jsonOptions);
        $arrayId = [];
        foreach ($jsonOptions as $jsonOption) {
            $arrayId[] = $jsonOption->id;
        }
        $arrayId = array_reverse($arrayId);
        $order = 'FIELD(id, ' . implode(', ', $arrayId) . ')';
        $options = Options::find()
            ->where(['id' => $arrayId])
            ->orderBy(new \yii\db\Expression($order))
            ->all();
        return $options;
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

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $photoFile = UploadedFile::getInstance($model, 'photo');
        if ($photoFile) {
            if (isset($photoFile->size)) {
                $photoFolderName = '@backend/web/uploads/options';
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
            $errors[] = ['Matn , Rasm lardan birini to\'ldirish shart!'];
        }

        if ($model->is_correct == 1) {
            Options::updateAll(['is_correct' => 0] , ['question_id' => $model->question_id]);
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

//        if ($model->question->status == 1) {
//            $errors[] = ['Tasdiqlangan savolni variantini o\'zgartirib bo\'lmaydi.'];
//            return ['is_ok' => false , 'errors' => $errors];
//        }
        if (!isset($post['example'])) {
            $errors[] = ['Variant matnini yuborishda xatolik.'];
            return ['is_ok' => false , 'errors' => $errors];
        }
        $model->text = $post['example'];

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $photoFile = UploadedFile::getInstance($model, 'photo');
        if ($photoFile) {
            if (isset($photoFile->size)) {
                $photoFolderName = '@backend/web/uploads/options';
                if (!file_exists(\Yii::getAlias($photoFolderName))) {
                    mkdir(\Yii::getAlias($photoFolderName), 0777, true);
                }

                $photoName = $model->subject_id ."_". time() . \Yii::$app->security->generateRandomString(20). '.' . $photoFile->extension;
                if ($photoFile->saveAs($photoFolderName."/".$photoName)) {
                    $model->image = $photoName;
                }
            }
        }

        if ($model->is_correct == 1) {
            Options::updateAll(['is_correct' => 0] , ['question_id' => $model->question_id]);
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
}
