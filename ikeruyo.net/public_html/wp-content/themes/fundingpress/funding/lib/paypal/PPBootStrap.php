<?php
/**
 * Include this file in your application. This file sets up the required classloader based on
* whether you used composer or the custom installer.
*/
global $f_paypal;
class Configuration
{
	// For a full list of configuration parameters refer in wiki page (https://github.com/paypal/sdk-core-php/wiki/Configuring-the-SDK)
	public static function getConfig()
	{
    global $f_paypal;
		$config = array(
				// values: 'sandbox' for testing
				//		   'live' for production
                //         'tls' for testing if your server supports TLSv1.2
				"mode" => $f_paypal['mode']
                // TLSv1.2 Check: Comment the above line, and switch the mode to tls as shown below
                // "mode" => "tls"

				// These values are defaulted in SDK. If you want to override default values, uncomment it and add your value.
				// "http.ConnectionTimeOut" => "5000",
				// "http.Retry" => "2",
			);
		return $config;
	}

	// Creates a configuration array containing credentials and other required configuration parameters.
	public static function getAcctAndConfig()
	{
    global $f_paypal;
		$config = array(
				// Signature Credential
				"acct1.UserName" => $f_paypal['api_username'],
				"acct1.Password" => $f_paypal['api_password'],
				"acct1.Signature" => $f_paypal['api_signature'],
				"acct1.AppId" => $f_paypal['app_id']

				// Sample Certificate Credential
				// "acct1.UserName" => "certuser_biz_api1.paypal.com",
				// "acct1.Password" => "D6JNKKULHN3G5B8A",
				// Certificate path relative to config folder or absolute path in file system
				// "acct1.CertPath" => "cert_key.pem",
				// "acct1.AppId" => "APP-80W284485P519543T"
				);
		return array_merge($config, self::getConfig());;
	}

}
//
//require_once 'Configuration.php';
/*
 * @constant PP_CONFIG_PATH required if credentoal and configuration is to be used from a file
* Let the SDK know where the sdk_config.ini file resides.
*/
//define('PP_CONFIG_PATH', dirname(__FILE__));

/*
 * use autoloader
*/

require 'PPAutoloader.php';
    PPAutoloader::register();
