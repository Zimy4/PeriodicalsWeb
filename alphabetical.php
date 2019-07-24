<?php
	
	// Возвращает алфавитный список
	function get_alphabet() {
		global $connection;		
		$journals_names = mysqli_query($connection, "SELECT name FROM Journals");
		if(mysqli_num_rows($journals_names) > 0)
		{			
			while($row = mysqli_fetch_assoc($journals_names))
			{
				$name = $row['name'];				
				// разделяем название если, оно записано в русском и английском варианте (например Burda/Бурда)
				$sub_names = explode('/' , $name);
				foreach($sub_names as $sub) {
					$fl = mb_substr(trim($sub), 0, 1, 'utf-8');
					if ($fl <> '') 
						$alfb[$fl] = 1;
				}			
			}
			ksort($alfb, SORT_STRING);
			//print_r($alfb);
			foreach($alfb as $key => $val) {
				echo '<span> <a class="btn btn-default btn-xs alfabetical" href="listJournals.php?journal_name='.$key.'" role="button">'.$key.'</a> </span>';
				//echo '<span class="tag tag-pill tag-default">'.$key.'</span> ';
			}
			//print_r($alfb);
		}
		return $alfb;		
	}
?>