<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchDish */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Dishes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dish-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id',
            'title',
            'user_id',
            'created_at',
            'updated_at',
            'prep_time:integer',
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
