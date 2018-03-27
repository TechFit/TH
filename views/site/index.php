<?php

/* @var $this yii\web\View */

use yii\grid\GridView;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>List of users:</h2>
            </div>
            <div class="col-lg-12">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'username',
                        'bill.updated_at:datetime',
                        'bill.total',
                    ],
                ]); ?>
            </div>
        </div>

    </div>
</div>
