<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	/**
	 * @API (api)
	 * 
	 * Return details about api
	 */
	public function index()
	{
		$this->rest_api->response([
			'domain'    => 'http://localhost/',
			'developer' => 'Themba Lucas Ngubeni',
			'framework' => 'CodeIgniter',
			'github'    => 'http://github.com/lucas11776/sahousedownload-api',
			'version'   => '2.0'
		]);
	}

	/**
	 * @API (api)
	 * 
	 * Return details about api
	 */
	public function page_not_found()
	{
		http_response_code(400); // set response header to page not found

		$this->rest_api->response([
			'message' => 'The request cannot be fulfilled because api point does not exist.'
		]);
	}
}
