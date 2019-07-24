<?php

	
	//global $connection;
	$connection = mysqli_connect('127.0.0.1', 'zimy4', '123', 'per');
	if (!$connection) {
		echo "Не удалось подключится к БД!<br>";
		echo mysqli_connect_error();
		exit();
	}
	//echo "Соединение с MySQL установлено!" . PHP_EOL;
	//echo "Информация о сервере: " . mysqli_get_host_info($connection) . PHP_EOL;

?>