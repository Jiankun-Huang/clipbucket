<?php
/* 
 *************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan								
 | @ Software  : ClipBucket , © PHPBucket.com
 | $Id: signup.php 596 2011-04-21 14:41:57Z ahzulfi $				
 *************************************************************
*/

define("THIS_PAGE","signup");
define("PARENT_PAGE","signup");

require 'includes/config.inc.php';
	
if($userquery->login_check('',true)){
	redirect_to(BASEURL);
}

	/**
	 * Function used to call all signup functions
	 */
	if(cb_get_functions('signup_page')){
		cb_call_functions('signup_page'); 
	} 
	
	
	/**
	 * Signing up new user
	 */
	if(!config('allow_registeration')){
		assign('allow_registeration',lang('usr_reg_err'));
        //
	}
			
	if(isset($_POST['signup'])){
		pex($_POST,true);
		if(!config('allow_registeration')){
			e(lang('usr_reg_err'));
		}
			
		else
		{
			$form_data = $_POST;
			$signup_data = $form_data;
			$signup_data['password'] = mysql_clean(clean($signup_data['password']));
			$signup_data['cpassword'] = mysql_clean(clean($signup_data['cpassword']));
			$signup_data['email'] = mysql_clean($signup_data['email']);
			$signup = $userquery->signup_user($signup_data,true);
			if($signup)
			{
				$udetails = $userquery->get_user_details($signup);
				$eh->flush();
				assign('udetails',$udetails);
				assign('mode','signup_success');
			}
		}
	}


//Login User

if(isset($_POST['login'])){
	$username = $_POST['username'];
	$username = mysql_clean(clean($username));
	$password = mysql_clean(clean($_POST['password']));
	
	$remember = false;
	if($_POST['rememberme'])
		$remember = true;
		
	if($userquery->login_user($username,$password,$remember))
	{
		if($_COOKIE['pageredir'])
			redirect_to($_COOKIE['pageredir']);
		else
			redirect_to(cblink(array('name'=>'my_account')));
	}
	
}

//Checking Ban Error
if(!isset($_POST['login']) && !isset($_POST['signup'])){
	if(@$_GET['ban'] == true){
		$msg = lang('usr_ban_err');
	}
}



subtitle(lang("signup"));
//Displaying The Template
template_files('signup.html');
display_it()
?>