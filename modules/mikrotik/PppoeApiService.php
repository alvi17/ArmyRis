<?php

/**
 * Mikrotik PPPoe API Service. Contains all useful functions here.
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 * 
 * Note: in mikrotik, api with port 8728 need to be enabled first.
 * Todo that in mikrotik: IP -- > Service --> api 
 */

//use PEAR2\Net\RouterOS;
//require_once('classes/PEAR2/Autoload.php');

use PEAR2\Net\RouterOS;
require_once BASE_DIRECTORY.'/libs/PEAR2/Autoload.php';

class PppoeApiService{
	private $server = null;
	private $loginName = null;
	private $loginPass = null;
	
	public function __construct($server, $loginName, $loginPass)
    {
		$this->server = $server;
		$this->loginName = $loginName;
		$this->loginPass = $loginPass;
	}
	
	public function enableUser($username){
		 return $this->_enableDisableUser($username, 'enable');
	}
	
	public function disableUser($username){
		return $this->_enableDisableUser($username, 'disable');
	}
	
	private function _enableDisableUser($username, $status='disable'){
		try{
            $client = new RouterOS\Client($this->server, $this->loginName, $this->loginPass);	
            //$cmd = "/ppp secret {$status} [find user={$username}]";
            $cmd = "/ppp secret {$status} numbers={$username}";
			$request = new RouterOS\Request($cmd);
	     	$client($request);	     	
	     	return true;
		} catch(Exception $ex){
			return false;
		}
	}
	
	public function removeUserFromActive($username){
		$username = trim($username);
        try{
            $util = new RouterOS\Util($client = new RouterOS\Client($this->server, $this->loginName, $this->loginPass));
            //$util->setMenu('/ppp active')->remove($username);
            $util->setMenu('/ppp active')->remove(RouterOS\Query::where('name', $username)); 
            return true;
            
		}catch(Exception $e){
			throw $e;
		}						
	}

	public function removeUserFromCookie($username){
		try{
			$client = new RouterOS\Client($this->server, $this->loginName, $this->loginPass);	
			//$cmd = "/ip hotspot cookie remove [find user=$username]";
            $cmd = "/ppp secret cookie remove [find user=$username]";
			$request = new RouterOS\Request($cmd);
	     	$client($request);
	     	return true;
		}catch(Exception $e){
			throw $e;
		}								
	}
	
	public function changePassword($username, $password){
		if(empty($username) || empty($password)){
            return false;
		}
		
		try{
            $client = new RouterOS\Client($this->server, $this->loginName, $this->loginPass);
            //$cmd = "/ppp secret set password={$password} [find name={$username}]";
            $cmd = "/ppp secret set password={$password} numbers={$username}";
			$request = new RouterOS\Request($cmd);
	     	$client($request);
	     	return true;
		}catch(Exception $e){
			return false;
		}		
	}
		
	public function createUser ($username, $password, $local_ip, $remote_ip, $profile='default', $disabled=true)
    {
        $ret = [];
        $disabledCondStr = $disabled ? ' disabled=yes' : '';
        
		if(empty($comment)) $comment = "Created " . $username . " by sys-admin using API";
        $comment = str_replace(' ', '-', $comment);
		
		try{
			// hotspot command
			//$cmd = '/ip hotspot user add name="' . $username . '" password="' . $password . '" comment="' . $comment . '" server="' . $server . '" profile="'. $profile . '"'; 
			// pppoe command
            //$cmd = "/ppp secret add name=t-hasan password=t-hasan comment='Test-Comment' profile=default-encryption";
			//$cmd = "/ppp secret add name={$username} password={$password} comment='{$comment}' profile={$profile} disabled=yes";
            //$cmd = "/ppp secret add name=".trim($username)." password=".$password." comment='".trim($comment)."'{$localAddressStr}{$remoteAddressStr} profile=".trim($profile).$disabledCondStr;
            
            $cmd = "/ppp secret add name=".trim($username)." password=".$password.
			" local-address={$local_ip} remote-address={$remote_ip} profile=".trim($profile).$disabledCondStr;
			$ret['command'] = $cmd;
            
            if(empty($username) || empty($password) || empty($profile)){
                throw new Exception('Invalid Parameters to create Mikrotik PPPoe user');
            }
            $client = new RouterOS\Client($this->server, $this->loginName, $this->loginPass);
			$setRequest = new RouterOS\Request($cmd);
			$client($setRequest);
            $ret['success'] = true;
            
		} catch(Exception $e){
            $ret['error'] = 'PPPoe user create failed. Exception = '. $e->getMessage();
		}
        
        return $ret;
	}
	
	public function deleteUser ($username)
    {
        $username = trim($username);

        try{
            if(empty($username)){
                throw new Exception('PPPoe user delete failed. Exception = Invalid username');
            }
            $client = new RouterOS\Client($this->server, $this->loginName, $this->loginPass);
            $cmd = "/ppp secret remove numbers=$username";
			$setRequest = new RouterOS\Request($cmd);
			$client($setRequest);
			return true;
            
		}catch(Exception $e){
			return $msg = 'PPPoe user delete failed. Exception = '. $e;
		}
	}
    
    public function changeRemoteIp($username, $remoteIp){
        
        $username = trim($username);
        $remoteIp = trim($RemoteIp);
        if(empty($username) || empty($remoteIp)){
            return false;
		}
        try{
            $client = new RouterOS\Client($this->server, $this->loginName, $this->loginPass);	
            $cmd = "/ppp secret set remote-address={$remoteIp} numbers={$username}";
			$request = new RouterOS\Request($cmd);
	     	$client($request);	     	
	     	return true;
            
		}catch(Exception $e){
			return false;
		}
    }
    
    public function changeProfile($username, $profile){
		
        $username = trim($username);
        $profile = trim($profile);
        
        if(empty($username) || empty($profile)){
            return false;
		}
		
		try{
            $client = new RouterOS\Client($this->server, $this->loginName, $this->loginPass);	
            $cmd = "/ppp secret set profile={$profile} numbers={$username}";
			$request = new RouterOS\Request($cmd);
	     	$client($request);	     	
	     	return true;
            
		}catch(Exception $e){
			return false;
		}		
	}
	
	public function changeServerAndProfile($username, $server, $profile){
		
		$username = trim($username);
		$server = trim($server);
		$profile = trim($profile);
        
        if(empty($username) || empty($server) || empty($profile)){
            return false;
		}
		
		try{			
			$client = new RouterOS\Client($this->server, $this->loginName, $this->loginPass);			
	        $setRequest = new RouterOS\Request('/ip hotspot user set');
	        $client($setRequest
	            ->setArgument('numbers', $username)
	            ->setArgument('server', $server)
	            ->setArgument('profile', $profile)
	        );
			return true;
            
		}catch(Exception $e){
			return false;
		}		
	}	
	
	public function __destruct(){
       //
    }		
}
