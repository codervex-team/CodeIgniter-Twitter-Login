<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends FrontEndController
{
    /**
    ** constructor of the class
    **/
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->database();
        $this->load->library('session');

        // Load twitter library
        $this->load->library('twitter');
    }

   
    /**
    ** Twitter login
    **/
    public function twitter_login()
    {
        redirect($this->twitter->get_login_url());
    }

    /**
    ** Twitter Auth
    **/
    public function twitter()
    {
        
        $twitter_data = $this->twitter->validate();
        
        // Preparing data for database insertion
        $oauth_provider             = 'twitter';
        $oauth_uid                  = $twitter_data->id;
        $name                 = $twitter_data->name;
        $email                = $twitter_data->email;
        $picture              = $twitter_data->profile_image_url;
        $link                 = 'https://twitter.com/'.$twitter_data->screen_name;

    }

    
}

?>