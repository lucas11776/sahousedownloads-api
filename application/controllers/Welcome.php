<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->rest_api->response([
			'domain'  => 'http://localhost/',
			'version' => 'v2'
		]);
	}
}
