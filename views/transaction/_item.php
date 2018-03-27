<div class="item">
    <?php if ($model->sender_id === Yii::$app->user->identity->id): ?>
        <div class="alert alert-danger" role="alert">
            <p>For: <?= $model->recipient->username ?></p>
            <p><?= $model->amount ?></p>
            <p><?= Yii::$app->formatter->asDatetime($model->created_at) ?></p>
        </div>
    <?php else:?>
        <div class="alert alert-success" role="alert">
            <p>From: <?= $model->sender->username ?></p>
            <p><?= $model->amount ?></p>
            <p><?= Yii::$app->formatter->asDatetime($model->created_at) ?></p>
        </div>
    <?php endif;?>
</div>