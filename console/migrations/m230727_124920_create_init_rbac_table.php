<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%init_rbac}}`.
 */
class m230727_124920_create_init_rbac_table extends Migration
{
    /**
     * {@inheritdoc}
     */

    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $supper_admin = $auth->createRole('super_admin');
        $supper_admin->description = 'Super admin';
        $auth->add($supper_admin);
        $auth->assign($supper_admin, 1);

        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator';
        $auth->add($admin);

        $teacher = $auth->createRole('moderator');
        $teacher->description = 'Moderator';
        $auth->add($teacher);

        $student = $auth->createRole('student');
        $student->description = 'O\'quvchi';
        $auth->add($student);
    }

    /**
     * {@inheritdoc}
     */

    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }
}
