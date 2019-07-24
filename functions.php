<?php
		
	function get_journal_id($name) {
		global $connection;
		echo "Ищем (".$name.")".PHP_EOL;
		$journal_id = mysqli_query($connection, "SELECT * FROM Journals WHERE name='$name'");
		if(mysqli_num_rows($journal_id) == 0)
		{
			echo "Журнал в базе не найден, добавляем его в базу:";
			$journal_id = mysqli_query($connection, "INSERT INTO Journals(name) VALUES ('$name')");
			if ($journal_id = 'true')  {
				echo "Журнал ".$name." добавлен в базу".PHP_EOL;
				return 0;
			} else {
				echo "Ошибка добавления в таблицу Journals".PHP_EOL;
				die;
			}
		} else {
			if( mysqli_num_rows($journal_id) > 1 ) {
				echo "Ошибка! (Несколько названий найдено)".PHP_EOL;
				die;
			}			
			while($row = mysqli_fetch_assoc($journal_id))
			{					
				return $row['id'];
			}
		}
		return -1;
	}
	
	function get_storage_id($storage) {
		global $connection;
		echo "Ищем место хранения(".$storage.")".PHP_EOL;
		$storage_id = mysqli_query($connection, "SELECT * FROM Storages WHERE name='$storage'");
		if(mysqli_num_rows($storage_id) == 0)
		{
			echo "Место хранения не найдено, добавляем его в базу:";
			$storage_id = mysqli_query($connection, "INSERT INTO Storages(name) VALUES ('$storage')");
			if ($storage_id = 'true')  {
				echo "Место хранения ".$storage." добавлено в базу".PHP_EOL;
				return 0;
			} else {
				echo "Ошибка добавления в таблицу Storages".PHP_EOL;
				die;
			}
		} else {
			if( mysqli_num_rows($storage_id) > 1 ) {
				echo "Ошибка! (Несколько названий найдено)".PHP_EOL;
				die;
			}			
			while($row = mysqli_fetch_assoc($storage_id))
			{					
				return $row['id'];
			}
		}
		return -1;
	}	
?>