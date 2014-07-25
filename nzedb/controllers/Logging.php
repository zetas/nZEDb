<?php

use nzedb\db\Settings;

/**
 * Logs/Reports stuff
 */
class Logging
{
	/**
	 * @var string If windows "\r\n" if unix "\n".
	 * @access private
	 */
	private $newLine;

	/**
	 * @var object DB Class instance.
	 * @access public
	 */
	public $pdo;

	/**
	 * @var object Class instance.
	 * @access public
	 */
	public $colorCLI;

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct(array $options = array())
	{
		$defOptions = [
			'ColorCLI' => null,
			'Settings' => null,
		];
		$defOptions = array_replace($defOptions, $options);

		$this->pdo = ($defOptions['Settings'] instanceof Settings ? $defOptions['Settings'] : new Settings());
		$this->colorCLI = ($defOptions['ColorCLI'] instanceof ColorCLI ? $defOptions['ColorCLI'] : new ColorCLI());

		$this->newLine = PHP_EOL;
	}

	/**
	 * Get all rows from logging table.
	 *
	 * @return array
	 *
	 * @access public
	 */
	public function get()
	{
		return $this->pdo->query('SELECT * FROM logging');
	}

	/**
	 * Log bad login attempts.
	 *
	 * @param string $username
	 * @param string $host
	 *
	 * @return void
	 *
	 * @access public
	 */
	public function LogBadPasswd($username='', $host='')
	{
		// If logggingopt is = 0, then we do nothing, 0 = logging off.
		$loggingOpt = $this->pdo->getSetting('loggingopt');
		$logFile = $this->pdo->getSetting('logfile');
		if ($loggingOpt == '1') {
			$this->pdo->queryInsert(sprintf('INSERT INTO logging (time, username, host) VALUES (NOW(), %s, %s)',
				$this->pdo->escapeString($username), $this->pdo->escapeString($host)));
		}
		else if ($loggingOpt == '2')
		{
			$this->pdo->queryInsert(sprintf('INSERT INTO logging (time, username, host) VALUES (NOW(), %s, %s)',
				$this->pdo->escapeString($username), $this->pdo->escapeString($host)));
			$logData = date('M d H:i:s ')."Login Failed for ".$username." from ".$host . "." . $this->newLine;
			if (isset($logFile) && $logFile != "") {
				file_put_contents($logFile, $logData, FILE_APPEND);
			}
		}
		else if ($loggingOpt == '3')
		{
			$logData = date('M d H:i:s ')."Login Failed for ".$username." from ".$host . "." . $this->newLine;
			if (isset($logFile) && $logFile != '') {
				file_put_contents($logFile, $logData, FILE_APPEND);
			}
		}
	}

	/**
	 * @return array
	 *
	 * @access public
	 */
	public function getTopCombined()
	{
		return $this->pdo->query('SELECT MAX(time) AS time, username, host, COUNT(host) AS count FROM logging GROUP BY host, username ORDER BY count DESC LIMIT 10');
	}

	/**
	 * @return array
	 *
	 * @access public
	 */
	public function getTopIPs()
	{
		return $this->pdo->query('SELECT MAX(time) AS time, host, COUNT(host) AS count FROM logging GROUP BY host ORDER BY count DESC LIMIT 10');
	}
}
