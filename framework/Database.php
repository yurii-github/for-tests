<?php
namespace framework;

final class Database
{
	private $pdo;
	static private $self;
	public $dsn;
	public $dbname;
	
	private function __construct($cfg)
	{
		if (!empty($cfg) && is_array($cfg)) {
			$this->dsn = $cfg['dsn'];
			
			//parse dbname from dsn
			$s = strpos($this->dsn, 'dbname=') + strlen('dbname='); //start
			$e = strpos($this->dsn, ';', $s); // end
			$e = ($e === false ? strlen($this->dsn) : $e); //if last, maybe not be set
			$this->dbname = substr($this->dsn, $s, $e);
			//var_dump($s, $e, $this->dbname);

			$this->pdo = new \PDO($cfg['dsn'], $cfg['username'], $cfg['password'],
				[ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION ]);
		}
	}
	
	/**
	 * 
	 * @param string $cfg used on first request as init
	 * @return \framework\Database
	 */
	static public function getInstance($cfg = null)
	{
		if (!(self::$self instanceof Database)) {
			self::$self = new Database($cfg);
		}
		
		return self::$self;
	}
	
	
	public function getPdo()
	{
		return $this->pdo;
	}
}