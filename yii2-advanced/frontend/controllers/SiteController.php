<?php
namespace frontend\controllers;

use common\models\Login;
use frontend\models\ResendVerificationEmailForm;
//use common\models\User;
use frontend\models\User;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

class Bearer extends HttpBearerAuth
{
    public function handleFailure($response)
    {
        Yii::$app->response->setStatusCode( 403);
        return Yii::$app->response->data = [
            'message' => 'You need authorization'
        ];
    }
}
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
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => Bearer::className(),
            'except' => ['login', 'signup']
        ];

        return $behaviors;
    }

    /*public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
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
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
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
        if(Yii::$app->request->isPost)
        {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user = new User();
            $user->username = $_POST['username'];
            $user->email = !empty($_POST['email']) ? $_POST['email'] : 'Not Email';
            $user->password = $_POST['password'];
            $user->setPassword($_POST['password']);
            //return $_POST['password'];
            //return $user->setPassword($_POST['password']);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();

            if ($user->validate())
            {
                Yii::$app->response->setStatusCode( 201);
                //$model->save() && $this->sendEmail($model);
                $user->save();
                $data = $user::findOne(
                    ['email' => $user->email]
                );

                return [
                'id' => $data['id'],
                'username' => $data['username'],
                'email' => $data['email'],
                ];
            }
            else
            {
                Yii::$app->response->setStatusCode( 422);
                return $user->getErrors();
            }
        }
        else
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

    }
    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $login = new Login();

        if(Yii::$app->request->isPost)
        {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $login->username = $_POST['username'];
            $login->password = $_POST['password'];

            $user = User::findOne([
                'username' => $login->username
            ]);


            if(!$login->validate())
            {
                Yii::$app->response->setStatusCode( 422);
                return $login->getErrors();
            }

            if(!empty($user) || $login->validatePassword($_POST['password'], $user->password_hash ))
            {
                $user = User::findOne($user['id']);
                $token = substr(Yii::$app->getRequest()->getCsrfToken(), 0, 20 );
                $user->token = $token;
                $user->save(false);
                return ['token' => $user->token];
            }
            else
            {
                Yii::$app->response->setStatusCode( 422);
                return 'Incorrect login or password';
            }
        }
        else
        {
            if (!Yii::$app->user->isGuest) {
                return $this->goHome();
            }

            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->goBack();
            } else {
                $model->password = '';

                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        $token = substr(Yii::$app->request->headers->get('Authorization'), 7);
        $user = User::findOne([
            'token' => $token
        ]);
        $user->token = '';
        $user->save(false);

        return 'You were successfully logged out';

        //Yii::$app->user->logout();
        //return $this->goHome();
    }

    public function beforeAction($action)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
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
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
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
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
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
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
