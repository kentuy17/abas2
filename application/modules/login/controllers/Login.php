<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author https://www.roytuts.com
 */
class Login extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->view('home');
    }

}