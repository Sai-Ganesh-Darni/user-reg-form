<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\UserData;
use yii\data\Pagination;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                // Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            // Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    public function actionFormEntry()
    {
        $model = new UserData();
        if (Yii::$app->request->isAjax) {
            if(Yii::$app->request->isPost){
                $postData = Yii::$app->request->post();

                // Check if 'hobbies' field exists and is an array
                if (isset($postData['hobbies']) && is_array($postData['hobbies'])) {
                    // Convert the array to a comma-separated string
                    $postData['hobbies'] = implode(',', $postData['hobbies']);

                }

                $email_check = UserData::findOne(['email' => $postData['email']]);
                $phone_check = UserData::findOne(['phone' => $postData['phone']]);
                if(isset($email_check)){
                    // Yii::$app->session->setFlash('error','Email already registered');
                    return json_encode([
                        'success' => false,
                        'message' => 'Email already registered'
                    ]);
                }else if(isset($phone_check)){
                    // Yii::$app->session->setFlash('error','Phone number already registered');
                    return json_encode([
                        'success' => false,
                        'message' => 'Phone number already registered',
                    ]);
                }
                else{
                    $model->attributes = $postData;
                    if ($model->validate() && $model ->save()) {
                        // Yii::$app->session->setFlash('success','User added successfully');
                        return json_encode([
                            'success' => true,
                            'model' => $model,
                            'message' => 'User added successfully',
                        ]);
                    } else {
                        // Yii::$app->session->setFlash('error','an error occured');
                        return json_encode([
                            'success' => false,
                            'message' => 'An error occurred',
                            'errors' => $model->errors, 
                        ]);
                    }
                }
            }
            
        }

        return $this->render('registration_form',['model' => $model]);
    }

    public function actionGetUsers()
    {
        // $model = new UserData();
        // $users = array();
        // if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
        //     $users = $model->find()->all();
        //     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //     return $users;
        // }
        // return $this->render('registered_users', ['model' => $model, 'users' => $users]);

        $query = UserData::find();
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count,'defaultPageSize' => 10]);
        $models = $query->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('registered_users',['models' => $models,'pagination' => $pagination,]);
    }
    public function actionGetUser()
    {
        if(Yii::$app->request->isGet){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $id =  Yii::$app->request->get()['id'];
            $user_data = UserData::find()->where(['id' => $id])->one();
            $user_data['hobbies'] = explode(',',$user_data['hobbies']);
            return $user_data;
        }
        else if(Yii::$app->request->isPut){
            // return "<h1>Page not found</h1>";
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            

        }

    }
}
