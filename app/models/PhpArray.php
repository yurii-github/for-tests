<?php
namespace app\models;

use framework\Exception;

class PhpArray
{
	//abstract public function Convert_JSONToArray();  -- we can extend to support other formats etc
	
	
	public function Convert_PhpStringToArray($filename)
	{
		if (!file_exists($filename)) {
			throw new Exception("File '$filename' does not exist", 101);
		}

		$rawData = file_get_contents($filename);

		if (!preg_match('/^array\(/', $rawData)) { // dummy check on array
			throw new Exception("[102] File '$filename' does not contain valid data to include. Must be PHP array", 102);
		}
		
		// UNSECURE: eval() is unsecure. so far i don't have better solution to read exported var
		$data = '';
		@eval('$data = ' . $rawData);
		
		if (!is_array($data)) {
			throw new Exception("[103] File '$filename' does not contain valid data to include. Must be PHP array", 103);
		}

		return $data; 
	}
	
	
	/**
	 * user reference to save some memory for performance
	 * @param array $data
	 * @return multitype:
	 */
	public function buildIndexASC(array &$data)
	{
		$index = array();
		$count = count($data); //PERFORMANCE: PHP bug workaround for loops
		
		for ($i = 0; $i < $count; $i++) {
			$index = array_unique(array_merge_recursive($index, array_keys($data[$i])));
		}
		
		sort($index);

		reset($index);
		reset($data);
		
		return $index;
	}
	
	
	public function buildIndexPadding(array &$index, array &$data)
	{
		$paddingLength = array();
		$count = count($data); //PERFORMANCE: PHP bug workaround for loops
	
		foreach ($index as $idx) {
			$paddingLength[$idx] = strlen($idx);
				
			for ($i = 0; $i < $count; $i++) {
				if (!empty($data[$i][$idx]) && strlen($data[$i][$idx]) > $paddingLength[$idx]) {
					$paddingLength[$idx] = strlen($data[$i][$idx]);
				}
			}
		}
	
		reset($index);
		reset($data);
		return $paddingLength;
	}
	

}