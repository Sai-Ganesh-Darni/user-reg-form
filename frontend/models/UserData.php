<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user_data".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $gender
 * @property string $education_qualification
 * @property string $hobbies
 * @property string $created_at
 * @property int|null $is_active
 */
class UserData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'phone', 'gender', 'education_qualification', 'hobbies'], 'required'],
            [['created_at'], 'safe'],
            [['is_active'], 'integer'],
            [['first_name', 'last_name', 'email', 'phone', 'gender', 'education_qualification', 'hobbies'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'gender' => 'Gender',
            'education_qualification' => 'Education Qualification',
            'hobbies' => 'Hobbies',
            'created_at' => 'Created At',
            'is_active' => 'Is Active',
        ];
    }
}