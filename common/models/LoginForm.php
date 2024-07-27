<?php

// namespace app\models;

// use Yii\base\Model;

// class LoginForm extends Model
// {
//     public $first_name;
//     public $last_name;
//     public $gender;
//     public $email;
//     public $phone;
//     public $education_qualification;
//     public $hobbies;

//     public function rules()
//     {
//         return [
//             [['first_name','last_name','gender','email','phone','education_qualification','hobbies'],'required'],
//             ['first_name','matches','pattern' => '/[a-zA-Z]+/'],
//             ['last_name','matches','pattern' => '/[a-zA-Z]+/'],
//             ['gender','in','range' => ['male','female','other']],
//             ['email','email'], 
//             ['phone','matches','pattern' => '/\+\d+/'],
//             [['education_qualification','hobbies'],'string','max' => 255],           
//         ];
//     }
// }