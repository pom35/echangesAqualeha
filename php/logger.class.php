<?php

/**
 * Simple logger function.
 * Demonstrate how to work with elFinder event api.
 *
 * @package elFinder
 * @author Dmitry (dio) Levashov
 **/
class elFinderLogger {
	
	/**
	 * Log file path
	 *
	 * @var string
	 **/
	protected $file = '';
	
	/**
	 * constructor
	 *
	 * @return void
	 * @author Dmitry (dio) Levashov
	 **/
	public function __construct($path) {
		$this->file = $path;
		$dir = dirname($path);
		if (!is_dir($dir)) {
			mkdir($dir);
		}
	}
	
	/**
	 * Create log record
	 *
	 * @param  string   $cmd       command name
	 * @param  array    $result    command result
	 * @param  array    $args      command arguments from client
	 * @param  elFinder $elfinder  elFinder instance
	 * @return void|true
	 * @author Dmitry (dio) Levashov
	 **/
	public function log($cmd, $result, $args, $elfinder) {
		
		if($cmd != 'file'){
			$log = $_SESSION['ELFINDER_AUTH_USER'].' => '.$cmd.' ['.date('d.m H:s')."]\n";
		}else{
			$log = $_SESSION['ELFINDER_AUTH_USER'].' => download ['.date('d.m H:s')."] : ".$result['info']['path']."\n";
			if(isset($_SESSION['token'])){
				$log .= "ip => ".$_SERVER['REMOTE_ADDR']."\n";
				$log .= "token => ".$_SESSION['token']."\n";	
			}
		}
		
		if (!empty($result['error'])) {
			$log .= "\tERROR: ".implode(' ', $result['error'])."\n";
		}
		
		if (!empty($result['warning'])) {
			$log .= "\tWARNING: ".implode(' ', $result['warning'])."\n";
		}
		
		if (!empty($result['removed'])) {
			foreach ($result['removed'] as $file) {
				// removed file contain additional field "realpath"
				$log .= "\tREMOVED: ".$file['realpath']."\n";
			}
		}
		
		if (!empty($result['added'])) {
			foreach ($result['added'] as $file) {
				$log .= "\tADDED: ".$elfinder->realpath($file['hash'])."\n";
			}
		}
		
		if (!empty($result['changed'])) {
			foreach ($result['changed'] as $file) {
				$log .= "\tCHANGED: ".$elfinder->realpath($file['hash'])."\n";
			}
		}
		
		$this->write($log);
	}
	
	/**
	 * Write log into file
	 *
	 * @param  string  $log  log record
	 * @return void
	 * @author Dmitry (dio) Levashov
	 **/
	public function write($log) {
		if (($fp = @fopen($this->file, 'a'))) {
			fwrite($fp, $log."\n");
			fclose($fp);
		}
	}
}
