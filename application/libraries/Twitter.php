<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Twitter/autoload.php');
use Codervex\TwitterOAuth;

class twitter {

	protected $CI;

	public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->CI->config->load('twitter');
        $this->connection= new TwitterOAuth($this->CI->config->item('twitter_consumer_key'),$this->CI->config->item('twitter_consumer_secret'));
    }

    public function get_login_url(){
    	$this->request_token = $this->connection->oauth('oauth/request_token', array('oauth_callback' => $this->CI->config->item('twitter_callback_url')));
       	$_SESSION['oauth_token'] = $this->request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $this->request_token['oauth_token_secret'];
    	return $this->connection->url('oauth/authenticate', array('oauth_token' => $this->request_token['oauth_token']));

    }

    public function validate(){
    	$this->request_token = [];
		$this->request_token['oauth_token'] = $_SESSION['oauth_token'];
		$this->request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

		if (isset($_REQUEST['oauth_token']) && $this->request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
		   	return "Something went wrong";
		}

		$this->connection = new TwitterOAuth($this->CI->config->item('twitter_consumer_key'),$this->CI->config->item('twitter_consumer_secret'), $this->request_token['oauth_token'], $this->request_token['oauth_token_secret']);
		$access_token = $this->connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
		
		$_SESSION['access_token'] =$access_token;
		unset($_SESSION['oauth_token']);
		unset($_SESSION['oauth_token_secret']);
		$this->connection = new TwitterOAuth($this->CI->config->item('twitter_consumer_key'),$this->CI->config->item('twitter_consumer_secret'), $access_token['oauth_token'], $access_token['oauth_token_secret']);
		
		$user = $this->connection->get("account/verify_credentials", ['include_email' => 'true']);
		return $user;

    }

}

