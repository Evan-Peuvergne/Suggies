<?php



	/**
	*
	*	RENDER
	*	Render a successfull api result
	*	@param code (INT) : Http response code for result
	*	@param response (Array) : Response content as PHP Array
	*	@return  JSON
	*
	**/


	function render($code, $response)
	{
		// HTTP Status code
		http_response_code($code);

		// Set up return content
		$return = [
			'status' => [
				'error' => ($code >= 200 && $code < 300 ) ? false : true,
				'code' => $code
			],
			'response' => $response
		];

		// Render
		echo json_encode($return);
		die();
	}