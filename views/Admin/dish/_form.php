<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Dish */
/* @var $form yii\widgets\ActiveForm */

$products = \app\models\Product::find()->select(['id', 'title'])->asArray()->all();
$products = array_column($products, 'title', 'id');
?>

<div class="dish-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->errorSummary($model); ?>

    <?php
    echo $form->field($model, 'title')->textInput(['maxlength' => true]);
    echo $form->field($model, 'prep_time')->textInput();
    echo $form->field($model, 'products')->checkboxList($products);
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
