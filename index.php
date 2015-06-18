<?php

	function returnStatus()
	{

		$output = '{ "status": "free"}';

		return $output;

	}

	function performAction($action, $gender)
	{

		$success = false;

		switch ($action)
		{

			case 'checkin':

				$success = processCheckIn($gender);

				break;

			case 'checkout':

				$success = processCheckOut($gender);

				break;

		}

		$output = '{ "result": "failure" }';
		if ($success) $output = '{ "result": "success" }';

		return $output;

	}

	function processCheckIn($gender)
	{

		$success = false;
		if ($gender == 'male') $success = true;
		if ($gender == 'female') $success = true;

		return $success;

	}

	function processCheckOut($gender)
	{

		$success = false;
		if ($gender == 'male') $success = false;
		if ($gender == 'female') $success = true;

		return $success;
	}

	$requestMode = $_GET['r'];

	switch ($requestMode)
	{

		case 'status':

			echo returnStatus();

			break;

		case 'action':

			$action = $_GET['action'];
			$gender = $_GET['gender'];

			echo performAction($action, $gender);

			break;

	}

?>