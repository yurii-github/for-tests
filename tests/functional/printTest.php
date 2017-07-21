<?php

namespace tests\functional;

use framework\Application;
use app\controllers\PrintTable;


class PrintTest extends \PHPUnit_Framework_TestCase
{
	function test_Print()
	{
		Application::run(false);
		
		$controller = new PrintTable();
		
		ob_start();
		$controller->actionIndex(array('file' => TESTS_BASEDIR.'/data/data-array.txt'));
		$c = ob_get_clean();
		
		file_put_contents('ssss', $c);
		$this->assertEquals(
<<<TXT
----------------------------------------------------------
		NXTE test
----------------------------------------------------------

=============================================================================
|      House |           Leader |             Motto |  Q |            Sigil |
-----------------------------------------------------------------------------
|  Baratheon |                  |  Ours is the Fury |    |   A crowned stag |
|      Stark |     Eddard Stark |  Winter is Coming |    |  A grey direwolf |
|  Lannister |  Tywin Lannister |                   |    |    A golden lion |
|            |                  |                   |  Z |                  |
=============================================================================

TXT
			, $c);

	}
}