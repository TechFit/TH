<?php

use app\models\Transaction;
use app\models\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */
/* @var $form ActiveForm */
/* @var $billTotal integer */

?>

<div class="transaction-form">

    <?php if ($billTotal <= Transaction::LIMITATION): ?>

        <h1>Limit -1000 exceeded.</h1>

    <?php else: ?>
        <?php $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div><div class=\"col-lg-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]); ?>

        <?= $form->field($model, 'recipient')->widget(Select2::className(), [
            'pluginLoading' => false,
            'showToggleAll' => false,
            'initValueText' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
            'options' => ['placeholder' => 'Select recipient'],
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => false,
                'tags' => true,
                'minimumInputLength' => 2,
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['get-users']),
                    'dataType' => 'json',
                    'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                ],
            ],
        ])?>

        <?= $form->field($model, 'amount')->textInput(['placeholder' => 'For example 100, 50.00 or 0.77']) ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Send', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>
