<?php
namespace tests\unit\models;

use app\models\PhpArray;
use app\models\app\models;

class PhpArrayTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @expectedExceptionCode 101
	 * @expectedException \framework\Exception
	 */
	function test_Convert_PhpStringToArray_FILE_NOT_EXIST()
	{
		$m = new PhpArray();
		$m->Convert_PhpStringToArray('not-existed-file');
	}

	/**
	 * @expectedExceptionCode 102
	 * @expectedException \framework\Exception
	 */
	function test_Convert_PhpStringToArray_NOT_ARRAY()
	{
		$m = new PhpArray();
		$m->Convert_PhpStringToArray(TESTS_BASEDIR . '/data/bad-data-array.txt');
	}

	
	function test_Convert_PhpStringToArray()
	{
		$data = array(
			array(
				'House' => 'Baratheon',
				'Sigil' => 'A crowned stag',
				'Motto' => 'Ours is the Fury'
			),
			array(
				'Leader' => 'Eddard Stark',
				'House' => 'Stark',
				'Motto' => 'Winter is Coming',
				'Sigil' => 'A grey direwolf'
			),
			array(
				'House' => 'Lannister',
				'Leader' => 'Tywin Lannister',
				'Sigil' => 'A golden lion'
			),
			array(
				'Q' => 'Z'
			)
		);
		
		$m = new PhpArray();
		$this->assertArraySubset($data, $m->Convert_PhpStringToArray(TESTS_BASEDIR . '/data/data-array.txt'));
	}
	
	function test_buildIndexASC()
	{
		// from task file
		// |     House |           Leader |            Motto | Q |           Sigil |
		$expected = array('House', 'Leader', 'Motto', 'Q', 'Sigil');
		
		$m = new PhpArray();
		$data = $m->Convert_PhpStringToArray(TESTS_BASEDIR . '/data/data-array.txt');
		$index =  $m->buildIndexASC($data);
		
		//var_dump($expected,$index);
		$this->assertArraySubset($expected, $index);
	}
	
	
	function test_buildIndexPadding()
	{
		$expectedPadding = array("House"=>9,"Leader"=>15,"Motto"=>16, "Q"=>1, "Sigil"=> 15);
		
		$m = new PhpArray();
		$data = $m->Convert_PhpStringToArray(TESTS_BASEDIR . '/data/data-array.txt');
		$index = $m->buildIndexASC($data);
		$padding = $m->buildIndexPadding($index, $data);
		
		$this->assertEquals(count($expectedPadding), count($padding));
		
		foreach ($expectedPadding as $k => $v) {
			$this->assertEquals($v, $padding[$k], 'Wrong padding value');
		}

	}

	
	
}