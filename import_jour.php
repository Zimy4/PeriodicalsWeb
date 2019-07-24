<?php

	require 'db.php';
	require 'functions.php';
	
	ini_set('display_errors', 1); 
	error_reporting(E_ALL);

	header('Content-Type: text/html; charset=utf8');
	ob_start();

	$time_start = microtime(true);
	$time_show = microtime(true);
	echo '<style>PRE {font-size: 9pt; }</style><pre>';
	echo 'Время начала: ' . date('H:i:s', time()) . PHP_EOL;

	set_time_limit(2400); // 40 минут
	// Инициализация вывода

	// Обработчик ошибок
	error_reporting(E_ALL);
	$errors = array();
	function custom_error_handler($errno, $errstr, $errfile, $errline)
	{
		global $errors;
		$errors[] = 'Error: ' . $errstr . ' in ' . $errfile . ' (line: ' . $errline . ')';
		return true;
	}
	set_error_handler('custom_error_handler');

	// Подключение к БД	
	mysqli_query($db, "SET NAMES utf8");
	mysqli_query('TURNCATE TABLE IF EXISTS Exemplars');
	
	// Загрузка последней сессии
//	$book_cnt = 0;
//	if (file_exists('session.txt')) {
//		$book_cnt = (int)file_get_contents('session.txt');
//	} else {
//		// Новая сессия - делаем бекап
//		mysql_query('DROP TABLE IF EXISTS books_copy');
//		mysql_query('DROP TABLE IF EXISTS books_ex_copy');
//		mysql_query('CREATE TABLE books_copy LIKE books');
//		mysql_query('CREATE TABLE books_ex_copy LIKE books_ex');
//		mysql_query('INSERT books_copy SELECT * FROM books');
//		mysql_query('INSERT books_ex_copy SELECT * FROM books_ex');
//	}

	// Вывод данных о количестве записей
	echo 'Загрузка записей из файла... ';
	ob_flush(); flush();

	// Обработка партии книг
	$records = load_records('per_site.txt');
	echo 'ОК' . PHP_EOL;
	echo 'Количество записей полученых из ИРБИСА: '.count($records). PHP_EOL;
	echo 'Формирование таблицы журналов... ';
	ob_flush(); flush();

	$journal_add = array();
	$cnt = 0;

	foreach ($records as $record) {
		$field = parse_record($record); 		

		// Заглавие, авторы
		$journal_title = '';
		$journal_year = '';
		$journal_numbers = '';
		$storage = '';
		
		if ($field[920] == 'J') {
			// Запись журнал
			$journal_title = parse_field($field[200])['A'];
			// Экземпляры
			foreach($field[909] as $exemplar) {
				$numbers_ex = array();
				$exemplar = parse_field($exemplar);
				
				$journal_year = $exemplar['Q'];			
				$storage = $exemplar['D'];
				
				//echo $storage . ' - ' . $journal_year . PHP_EOL;
				//echo $exemplar['H'] . PHP_EOL;
				
				$numbers = explode(",", $exemplar['H']);
				//print_r($numbers);
				
				foreach($numbers as $num)
				{
					$beg_end = explode("-", $num);					
					//print_r($beg_end);
					if (count($beg_end) > 1 ){
						$numbers_ex = array_merge($numbers_ex, range($beg_end[0],$beg_end[1]));
						//var_dump($num_ex);
					}
					else {
						$numbers_ex[] = $beg_end[0];
					}
				}
				asort($numbers_ex);
				$journal_numbers = implode(", ", $numbers_ex);
				$journal_add[] = '(' . $journal_title . ', "' . $journal_year . '", "' . $storage . '", "'. $journal_numbers .'")';
				// ищем заголовок журнала в таблице 
				$journal_id = get_journal_id($journal_title);
				$storage_id = get_storage_id($storage);
				if ( $journal_id > 0 and $storage_id > 0 )
				{
					echo "Заносим экземпляры в таблицу:";
					//echo "INSERT INTO Exemplars(id_journal, year, id_storage, exemplars) VALUES ('$journal_id, $journal_year, $storage_id, ".$journal_numbers." ')";					
					$exemplars_id = mysqli_query($connection, "INSERT INTO Exemplars(id_journal, year, id_storage, Exemplars) VALUES ('$journal_id', '$journal_year', '$storage_id','$journal_numbers')");
					if ($exemplars_id = 'true')  {
						echo "Экземпляры добавлены в базу".PHP_EOL;
					} else {
						echo "Ошибка добавления экземпляров".PHP_EOL;
						die;
					}
				}
				else
				{
					echo "Неверные данные: journal_id = ".$journal_id . " или ".$storage_id.PHP_EOL;
					die;					
				}
				//print_r($numbers_ex);
			}

		} else {
			// Запись номер
		}
		
		//echo $journal_title . ' : ' . PHP_EOL;
			
		
		$cnt++;
//		if ($cnt > 5000) break;
	}
//	print_r(array_count_values ($books_ids));
	print_r($journal_add); 
	exit;
	
	echo 'ОК' . PHP_EOL;
	echo 'Пропущено экземпляров которые отсутствуют(не подлежат выдаче): '. $cnt_skip . PHP_EOL;
	echo 'Книг на добавление: '. count($books_add) . PHP_EOL;
	echo 'Обновление таблицы книг в базе... ';
	ob_flush(); flush();

	// Очистка таблицы книг, кроме выданных без электронного каталога
//	mysql_query('DELETE FROM books WHERE noek = 0');

	// Добавить обработанные книги в базу
//	if (count($books_add) > 0) {
//		$query = 'INSERT INTO books (id, title, bbk) VALUES ' . implode(',', $books_add);
//		mysql_query($query);
//		if(mysql_error()) {
//			echo 'MYSQL error: ' . mysql_error() . PHP_EOL;
//			var_dump($books_add);			
//			exit;
//		}
//	}

//	echo 'ОК' . PHP_EOL;
//	print_r($books_add); exit;

	function load_records($file) {
		$records = array();
		$content = file_get_contents($file);
		preg_match_all("/.*\*\*\*\*\*/siU", $content, $records, PREG_SET_ORDER);
		unset($content);		
		return $records;
	}

	function parse_record(&$record) {
//		global $fields_excluded;
		$journal = array();
		preg_match_all("/.*\n/siU", $record[0], $fields, PREG_SET_ORDER);
		foreach($fields as $field) {
			if (strlen($field[0]) < 4)
				continue;
			
			preg_match("/#(\d+?):\s(.*?)\r\n/U", $field[0], $matches);
			$field_num = (int)$matches[1];
			$field_val = $matches[2];
	
			switch($field_num) {
			case 931:
			case 930:  
			case 481: 
			case 910:
			case 937:
			case 922:
			case 210:
			case 11: 
			case 101:
			case 919:
			case 909: // Зарегистрированные поступления
			case 905:
			case 907: // Каталогизатор
			case 901: // Заказанные экземпляры
			case 938: // Сведения о заказах(поквартальные)
				$journal[$field_num][] = $field_val;
				break;
			default:
				$journal[$field_num] = $field_val;
			}		
		}
		//var_dump($journal);
		return $journal;
	}
	function parse_field(&$field) {
		$ret = array();
//		preg_match_all("/[\^](.)([^\^]+?)/U", $field, $matches, PREG_SET_ORDER);
		preg_match_all("/[\1f\^](.)([^\1f\^]+?)/U", $field, $matches, PREG_SET_ORDER);
		foreach ($matches as $match) {
			$ret[(string)$match[1]] = $match[2];
		}
		return $ret;
	}
?>