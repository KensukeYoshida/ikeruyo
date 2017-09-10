<?php


//the php handler for the social auth stuff
if(session_save_path() != '/tmp'){
session_save_path('/tmp');}
@session_start();

if (!isset ($_GET['action']) OR !($_GET['action'] == "logout")) {
	if (isset ($_SESSION['auth_cfg'])) {
		$config = $_SESSION['auth_cfg']; //get session config for the auth we got from wordpress
	}
	if (!isset($config)) { //if there's no config there's nothing we can do
		die();
	}

	require_once( "../Hybrid/Auth.php" ); //include the auth libraries
	if (isset($_GET['returnto'])) { //save return to for later
		if (isset($_GET['proceed'])) {
			$_SESSION['returnto'] = $_GET['returnto']."?proceed=".$_GET['initiatelogin'];
		} else {

			$_SESSION['returnto'] = $_GET['returnto'];
		}
		$fulluri = 'http://' . $_SERVER['SERVER_NAME']. str_replace("&returnto=".encodeURIComponent($_GET['returnto']), "", $_SERVER['REQUEST_URI']) ;
		header("Location: ".$fulluri);
		die();
	}
}

//determine further course of action
if (isset($_GET['initiatelogin'])) {
	//initiatelogin is set, proceed to find out which one
	try {
		if ($_GET['initiatelogin'] == "facebook") {
			//time to do facebook login
			$hybridauth = new Hybrid_Auth( $config );
			$adapter = $hybridauth->authenticate( "facebook" );
			$user_profile = $adapter->getUserProfile();

		} elseif ($_GET['initiatelogin'] == "twitter") {
			//time to do twitter login
			$hybridauth = new Hybrid_Auth( $config );
			$adapter = $hybridauth->authenticate( "twitter" );
			$user_profile = $adapter->getUserProfile();
			$provider = "twitter";
		} elseif ($_GET['initiatelogin'] == "google") {
			//time to do tumblr login
			$hybridauth = new Hybrid_Auth( $config );
			$adapter = $hybridauth->authenticate( "google" );
			$user_profile = $adapter->getUserProfile();
		} else {
			//nothing valid set
			die("Error, action not correctly set");
		}

		if (isset($user_profile)) {
			$_SESSION['facebookuid'] = "";
			$_SESSION['social_login_new']['social_login_added'] = true;
			$_SESSION['social_login_new']['provider'] = $_GET['initiatelogin'];

			if ($_GET['initiatelogin'] == "facebook") {
				//get extended token and store it
				$my_url = 'http://' . $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
				$token = $adapter->getAccessToken();
				$actualtoken = $token['access_token'];
				$token_url = "https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=".$config['providers']['Facebook']['keys']['id']."&client_secret=".$config['providers']['Facebook']['keys']['secret']."&fb_exchange_token=".$actualtoken;
				$response = file_get_contents($token_url);
				$params = null;
				parse_str($response, $params);
				$_SESSION['social_login_new']['token']['access_token'] = $params['access_token'];
				$_SESSION['social_login_new']['token']['expires_at'] = time() + $params['expires'];
				$hybridauth->storage()->set("hauth_session.facebook.token.access_token", $params['access_token']);
			} else {
				$_SESSION['social_login_new']['token'] = $adapter->getAccessToken();

			}
			if ($_GET['initiatelogin'] == "twitter") {
                $_SESSION['social_login_new']['name'] = $user_profile->displayName;
			} else if ($_GET['initiatelogin'] == "google"){
				$_SESSION['social_login_new']['name'] = $user_profile->displayName;
			} else if ($_GET['initiatelogin'] == "facebook"){
				$_SESSION['facebookuid'] = $user_profile->identifier;
				$_SESSION['social_login_new']['firstName'] = $user_profile->firstName;
				$_SESSION['social_login_new']['lastName'] = $user_profile->lastName;
				$_SESSION['social_login_new']['name'] = $user_profile->firstName." ".$user_profile->lastName;
			}
			$_SESSION['social_login_new']['id'] = $user_profile->identifier;
			$_SESSION['social_login_new']['photo'] = $user_profile->photoURL;
			$_SESSION['social_login_new']['sessiondata'] = $hybridauth->getSessionData();

            header("Location: ".$_SESSION['returnto']);
			die();
		}
	} catch (Exception $e) {
		if (isset($_SESSION['returnto'])) {
			$_SESSION['social_success'] = 0;
			$_SESSION['social_error'] = $e->getMessage();
			header("Location: ".$_SESSION['returnto']);

		}
		die($e->getMessage() );
	}

} else if (isset($_GET['action'])) {
	if ($_GET['action'] == "logout") {
		//we do the logout here
		unset($_SESSION['social_login_new']); //just in case unset it
		unset($_SESSION['social_user']); //unset the user data
		$return = $_SESSION['returnto'];
		session_destroy();
		session_start();
		$_SESSION['loggedout'] = true;
		//print_r ($_SESSION);
		//die();
        header("Location: ".$return);
		die();
	} else {
		die();
	}

}



function encodeURIComponent($str) {
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
}
?>
