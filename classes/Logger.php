<?php

/**
 * Writes Log
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

class Logger {
    
    private $file = null;
    private $msgPrefix = null;
    
    public function __construct($logDir) {
		
        $this->file     = $logDir.'/'.date('Ym').'/'.date('Ymd').'.log';
        $this->confirmDirExists();
    }
    
    private function confirmDirExists() {
    
        $dirName = dirname($this->file);
        
        if (!is_dir($dirName)) {
            mkdir($dirName, 0777, true);
        }
    }
    
    public function setInfoLog($msg) {
        file_put_contents(
            $this->file, 
            date('Y-m-d H:i:s'). ' INFO : ' . $msg . PHP_EOL, 
            FILE_APPEND
        );
    }
    
    public function setErrLog($msg) {
        file_put_contents(
            $this->file, 
            date('Y-m-d H:i:s'). ' ERROR : ' . $msg . PHP_EOL, 
            FILE_APPEND
        );
    }
    
    public function setNoticeLog($msg) {
        file_put_contents(
            $this->file, 
            date('Y-m-d H:i:s'). ' NOTICE : ' . $msg . PHP_EOL, 
            FILE_APPEND
        );
    }
    
    public function setWarnLog($msg) {
        file_put_contents(
            $this->file, 
            date('Y-m-d H:i:s'). ' WARNING : ' . $msg . PHP_EOL, 
            FILE_APPEND
        );
    }
}
