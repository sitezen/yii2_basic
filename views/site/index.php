<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">

        <div class="row">
            <div class="col-md-4">
                <label for="airport">Введите аэропорт вылета:</label>
            </div>
            <form method="get"><? /* Раз по заданию get - сожно обойтись без ActiveForm */?>
            <div class="col-md-6">
                <input id="airport" placeholder="например, Домодедово, Москва"
                       value="Домодедово, Москва"
                       style="width: 100%;" type="text" name="airport" />
            </div>
            <div>
                <input type="submit" class="btn-success" value="Отправить" />
            </div>
            </form>
        </div>
    </div>

    <div class="body-content">
<?php
if(!empty($dataProvider)) {
    echo \yii\grid\GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => \yii\grid\SerialColumn::class,
                ],
                'id',
                [
                    'attribute' => 'created_at',
                    'format' => ['datetime', 'php:d.m.Y h:i:s'],
                ],
                'number',
                'status',
                'user_id',
                [
                    'attribute' => 'coordination_at',
                    'value' => function ($model, $key, $index, $column) {
                        $is_set = $model->{$column->attribute} > 0;
                        return ($is_set ? date('d.m.Y H:i'):'Не согласовано');
                    },
                ],
                'trip_purpose_id'
            ],
        ]
    );
}
?>
    </div>
</div>
