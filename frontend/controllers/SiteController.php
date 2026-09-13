<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Author;
use common\models\Book;
use common\models\Guest;
use common\models\LoginForm;
use frontend\components\FrontendController;
use frontend\models\search\BookSearch;
use frontend\models\forms\SubscriptionForm;
use frontend\models\SignupForm;
use frontend\models\VerifyEmailForm;
use InvalidArgumentException;
use Yii;
use yii\filters\AccessControl;
use yii\mail\MailerInterface;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Actions for anonymous guests: browsing books and subscribing to authors.
 */
class SiteController extends FrontendController
{

    private MailerInterface $mailer;

    public function __construct(
        $id,
        $module,
        MailerInterface $mailer,
        $config = [],
    ) {
        $this->mailer = $mailer;
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionSubscribe(int $id): string|Response
    {
        $author = Author::findOne($id);
        $guest = Guest::getCurrent();

        $model = new SubscriptionForm([
            'author' => $author,
            'guest' => $guest
        ]);
        if ($model->isAlreadySubscribed()) {
            $this->addAlert( 'Вы уже подписаны на этого автора.');
            return $this->redirectBack();
        }
        // If the guest already has a phone, subscribe immediately.
        if ($model->validate() && $model->save()) {
            $this->addAlert('Вы подписаны на новые книги автора.');
            return $this->redirectBack(['index']);
        }
        // Else go to modal to get phone
        return $this->render('subscribe', [
            'author' => $author,
            'model' => $model,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return string|Response
     */
    public function actionLogin(): string|Response
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

    public function actionSignup(): string|Response
    {
        $model = new SignupForm();

        $signed = $model->load(Yii::$app->request->post()) && $model->signup(
                $this->mailer,
                Yii::$app->params['supportEmail'],
                Yii::$app->name,
            );

        if ($signed) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @return Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail(string $token): Response
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->verifyEmail()) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }
}
