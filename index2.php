<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>TEST</title>
	</head>
	<body>
		<form action="">
			<span>Name: </label>
			<input type="text" name="name" />
			<br />
			<span>Temperature: </label>
			<input type="number" name="temperature" />
			<br />
			<span>Humidity: </label>
			<input type="number" name="humidity" />
			<br />
			<span>Last Read: </label>
			<input type="datetime-local" name="lastread" />
			<br />
			<button type="submit">Go!</button>
			<br />
		</form>
	</body>
</html>

<?php
	# Here you include the Request library.
	include_once('./Requests/library/Requests.php');
	Requests::register_autoloader();

	# Demo object named sensor, with 4 attributes related to weather stations
	class Sensor
	{
		public $name;
    	public $temperature;
    	public $humidity;
		public $lastread;
		
		public function __construct($name, $temperature, $humidity, $lastread)
		{
	        $this->name = $name;
	        $this->temperature = $temperature;
	        $this->humidity = $humidity;
	        $this->lastread = $lastread;
	    }
	}
	
	# Method to create a sensor.
	function createSensor()
	{
		# Here we initialize the sensor with the values.
		# Initialize the sensor with your own logic.
		$sensor = new Sensor($_GET['name'], $_GET['temperature'], $_GET['humidity'], $_GET['lastread']);
		
		# This is to make sure that the sensor has been sent successfully.
		# You can use your own logic to do this.
		$done = false;
		
		while ($done == false)
		{
			$done = sendData($sensor);
		}
	}
	
	# Method to send data to A2G InpuStream API
	function sendData($sensor)
	{
		$url = 'https://listen.a2g.io/v1/testing/inputstream';
		$headers = array(
			'x-api-key' => '[YOUR_API_KEY]'
		);
		$stationData = array(
			'Station' => $sensor->name, 
			'data' => array(
				'temperature' => $sensor->temperature,
				'humidity' => $sensor->humidity,
				'updated' => $sensor->lastread
			)
		);
			
		$data = array(
			'IKEY' => '[YOUR_INPUTSTREAM_KEY]',
			'Data' => json_encode($stationData)
		);
		
		$response = Requests::post($url, $headers, json_encode($data));
		
		if($response->status_code == true)
		{
			echo 'Success: ';
			var_dump($response->success);
			echo '<br />';
			echo 'Status code: ';
			var_dump($response->status_code);
			echo '<br />';
			echo 'Body: ';
			var_dump($response->body);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	# Here we call the method.
	if(!empty($_GET['name']) && !empty($_GET['temperature'])
		&& !empty($_GET['humidity']) && !empty($_GET['lastread']))
	{
		createSensor();
	}
?>