<?php
namespace app\controllers;

use framework\Controller;
use app\models\PhpArray;

class PrintTable extends Controller
{
	public function actionIndex($params)
	{
		$model = new PhpArray();
		$data = array();
		$index = array();
		$padding = array();
		
		if (!empty($params) && !empty($params['file'])) {
			$data = $model->Convert_PhpStringToArray($params['file']);
			
			if (count($data) > 0) {
				$index = $model->buildIndexASC($data);
				$padding = $model->buildIndexPadding($index, $data);
			}

		}

		$this->render('index', array('data' => $data, 'index' => $index, 'padding' => $padding));
	}
	
	
}