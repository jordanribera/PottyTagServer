<?php
	header("Access-Control-Allow-Origin: *");
	//ini_set('display_errors', 1);
	$db_host = "localhost";
	//$db_name = "potty_time";
	$db_user = "root";
	$db_password = "potty_time";

	function returnStatus()
	{
		global $db_host, $db_user, $db_password;
		$link = mysql_connect($db_host, $db_user, $db_password, true);

		//get bathroom population
		$population_query = "SELECT * FROM potty_tag.checkins WHERE time >= date_sub(now(), INTERVAL 5 MINUTE)";
		$population_result = mysql_query($population_query, $link);

		$male_pop = 0;
		$female_pop = 0;

		while ($row = mysql_fetch_assoc($population_result))
		{

			if ($row['gender'] == 'm') $male_pop++;
			if ($row['gender'] == 'f') $female_pop++;

		}

		// get toilet flags
		$flags_query = "SELECT * FROM potty_tag.flags";
		$left_toilet_status = "true";
		$right_toilet_status = "true";
		$flags_result = mysql_query($flags_query, $link);

		while ($row = mysql_fetch_assoc($flags_result))
		{

			if ($row['toilet_id'] == 0 && $row['status_ok'] == 0) $left_toilet_status = "false";
			if ($row['toilet_id'] == 1 && $row['status_ok'] == 0) $right_toilet_status = "false";

		}

		$output = '{"m_population": ' . $male_pop . ', "f_population": ' . $female_pop . ', "left_toilet": ' . $left_toilet_status . ', "right_toilet": ' . $right_toilet_status . '}';

		cleanDatabase($link);

		return $output;

	}

	function performAction($action)
	{

		$output = '{"success": false}';

		switch ($action)
		{

			case 'checkin':
				$gender = $_GET['gender'];
				$last_id = null;
				if(isset($_GET['last_checkin'])) $last_id = $_GET['last_checkin'];
				$output = processCheckIn($gender, $last_id);

				break;

			case 'checkout':
				$checkin_id = $_GET['checkin_id'];
				$output = processCheckOut($checkin_id);

				break;

			case 'addflag':
				$toilet_id = $_GET['toilet_id'];
				$output = processAddFlag($toilet_id);

				break;

			case 'removeflag':
				$toilet_id = $_GET['toilet_id'];
				$output = processRemoveFlag($toilet_id);

				break;

		}

		return $output;

	}

	function processAddFlag($toilet_id)
	{
		global $db_host, $db_user, $db_password;
		$link = mysql_connect($db_host, $db_user, $db_password, true);

		$query = "INSERT INTO potty_tag.flags (toilet_id, status_ok) VALUES(" . $toilet_id . ", 0) ON DUPLICATE KEY UPDATE status_ok=VALUES(status_ok)";
		$result = mysql_query($query, $link);
	}

	function processRemoveFlag($toilet_id)
	{
		global $db_host, $db_user, $db_password;
		$link = mysql_connect($db_host, $db_user, $db_password, true);

		$query = "INSERT INTO potty_tag.flags (toilet_id, status_ok) VALUES(" . $toilet_id . ", 1) ON DUPLICATE KEY UPDATE status_ok=VALUES(status_ok)";
		$result = mysql_query($query, $link);
	}

	function processCheckIn($gender, $last_id=null)
	{
		global $db_host, $db_user, $db_password;
		$link = mysql_connect($db_host, $db_user, $db_password, true);
		$output = '{"success": false}';
		
		if($last_id !== null)
		{
			processCheckout($last_id);
		}
		
		if ($gender == 'm' || $gender == 'f')
		{
			$query = "INSERT INTO potty_tag.checkins (gender, active) VALUES ('" . $gender . "', 1)";
			$result = mysql_query($query, $link);
			
			if ($result)
			{
				$id = mysql_insert_id($link);
				$output = '{"success": true, "id": ' . $id . '}';
			}
		}
		
		cleanDatabase($link);
		
		return $output;
	}

	function processCheckOut($checkin_id)
	{
		global $db_host, $db_user, $db_password;
		$link = mysql_connect($db_host, $db_user, $db_password, true);

		$query = "DELETE FROM potty_tag.checkins WHERE id='" . $checkin_id . "'";

		$result = mysql_query($query, $link);

		cleanDatabase($link);

		return '{"success": ' . ($result ?  'true' : 'false') . '}';
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

			echo performAction($action);

			break;

	}

?>
