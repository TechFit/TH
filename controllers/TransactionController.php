<?php

namespace app\controllers;

use app\models\Bill,
    app\models\User,
    app\models\Transaction;
use app\models\form\TransactionForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl,
    yii\filters\VerbFilter;
use yii\web\Controller,
    yii\web\Response,
    yii\web\NotFoundHttpException;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'get-users', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'get-users'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Transaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Transaction::find()->where(['sender_id' => Yii::$app->user->identity->id])->orWhere(['recipient_id' => Yii::$app->user->identity->id]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'itemView' => '_item',
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $model = new TransactionForm();

        $billTotal = Bill::find()->select('total')->where(['user_id' => Yii::$app->user->identity->id])->scalar();

        if ($model->load(Yii::$app->request->post()) && $model->send()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'billTotal' => $billTotal,
        ]);
    }

    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * List of users without current user(exists in select2 transaction page)
     *
     * @param null $q
     * @return array
     */
    public function actionGetUsers($q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'results' => User::find()->select(['user.id', 'user.username AS text'])
                ->where(['<>', 'user.id', Yii::$app->user->identity->id])
                ->andFilterWhere(['like', 'user.username', $q])->asArray()->all()
        ];
    }
}
