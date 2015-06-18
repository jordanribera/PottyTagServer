<?php

	//ini_set('display_errors', 1);

	function returnStatus()
	{
		$link = mysql_connect("localhost", "root", "potty_time", true);

		$query = "SELECT * FROM potty_tag.checkins WHERE time >= date_sub(now(), INTERVAL 5 MINUTE)";

		$result = mysql_query($query, $link);

		$male_pop = 0;
		$female_pop = 0;

		while ($row = mysql_fetch_assoc($result))
		{

			if ($row['gender'] == 'm') $male_pop++;
			if ($row['gender'] == 'f') $female_pop++;

		}

		$output = '{"m_population": ' . $male_pop . ', "f_population": ' . $female_pop . '}';

		cleanDatabase($link);

		return $output;

	}

	function performAction($action, $gender)
	{

		$success = false;

		switch ($action)
		{

			case 'checkin':
				$last_id = null;
				if(isset($_GET['last_checkin'])) $last_id = $_GET['last_checkin'];
				$success = processCheckIn($gender, $last_id);

				break;

			case 'checkout':
				$checkin_id = $_GET['checkin_id'];
				$success = processCheckOut($checkin_id);

				break;

		}

		$output = '{"result": "failure"}';
		if ($success) $output = '{"result": "success"}';

		return $output;

	}

	function processCheckIn($gender, $last_id=null)
	{
		$success = false;

		$link = mysql_connect("localhost", "root", "potty_time", true);

		if($last_id !== null)
		{
			processCheckout($last_id);
		}

		$query = "INSERT INTO potty_tag.checkins (gender, active) VALUES ('" . $gender . "', 1)";

		$result = mysql_query($query, $link);

		if ($gender == 'm') $success = true;
		if ($gender == 'f') $success = true;

		cleanDatabase($link);

		return $success;
	}

	function processCheckOut($checkin_id)
	{
		$success = false;

		$link = mysql_connect("localhost", "root", "potty_time", true);

		$query = "DELETE FROM potty_tag.checkins WHERE id='" . $checkin_id . "'";

		$result = mysql_query($query, $link);

		cleanDatabase($link);

		return $success;
	}

	function cleanDatabase($link)
	{
		$query = "DELETE FROM potty_tag.checkins WHERE time < date_sub(now(), INTERVAL 5 MINUTE)";

		$result = mysql_query($query, $link);
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