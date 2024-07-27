<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_data}}`.
 */
class m240723_073015_create_user_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%user_data}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(255)->notNull(),
            'last_name' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull(),
            'phone' => $this->string(255)->notNull(),
            'gender' => $this->string(255)->notNull(),
            'education_qualification' => $this->string(255)->notNull(),
            'hobbies' => $this->string(255)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'is_active' => $this->boolean()->defaultValue(true)
        ]);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_data}}');
    }
}
