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

		$output = '{"success": false}';

		switch ($action)
		{

			case 'checkin':
				$last_id = null;
				if(isset($_GET['last_checkin'])) $last_id = $_GET['last_checkin'];
				$output = processCheckIn($gender, $last_id);

				break;

			case 'checkout':
				$checkin_id = $_GET['checkin_id'];
				$output = processCheckOut($checkin_id);

				break;

		}

		return $output;

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
			$gender = $_GET['gender'];

			echo performAction($action, $gender);

			break;

	}

?>
