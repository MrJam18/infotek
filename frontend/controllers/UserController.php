<?php

namespace frontend\controllers;

use frontend\components\FrontendController;
use frontend\models\forms\BookForm;
use frontend\models\search\BookSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserController extends FrontendController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex(): string
    {
        $searchModel = new BookSearch([
            'user_id' => Yii::$app->user->id,
        ]);
        $dataProvider = $searchModel->search();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateBook()
    {
        $form = new BookForm();
        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            $this->addAlert('Книга успешно создана');
            return $this->redirectBack();
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdateBook($id)
    {
        $form = $this->getBookForm($id);
        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            $this->addAlert('Книга успешно изменена');
            return $this->redirectBack();
        }
        return $this->render('update', [
            'model' => $form,
        ]);
    }

    public function actionDeleteBook($id)
    {
        $form = $this->getBookForm($id);
        if (!$form->delete()) {
            $this->addAlert('Невозможно удалить книгу', 'error');
        }
        $this->addAlert('Книга удалена');
        return $this->redirectBack();
    }



    /**
     * Logs out the current user.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    private function getBookForm(int $id): BookForm
    {
        $form = BookForm::findOne([
            'user_id' => Yii::$app->user->id,
            'id' => $id,
        ]);
        if (!$form) {
            throw new NotFoundHttpException('Страница не найдена');
        }
        return $form;
    }
}
