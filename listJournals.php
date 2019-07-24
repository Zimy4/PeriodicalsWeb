<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="css/jquery.formstyler.css" rel="stylesheet" />
	<link href="css/jquery.formstyler.theme.css" rel="stylesheet" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/hazzik-jquery.livequery/1.3.6/jquery.livequery.min.js"></script>
	<script src="css/jquery.formstyler.js"></script>

	<script src="selectbox.js"></script>
	<link href="selectbox.css" rel="stylesheet" />
	<link href="style.css" rel="stylesheet" />

</head>

<?

	require 'db.php';
	global $connection;

?>

<body>
<div id="wrapper">
	<div class="content-journal">
	<?php
		$journal_name = trim($_REQUEST['journal_name']);
		$sql = "SELECT * FROM Journals WHERE name LIKE '%$journal_name%'";	
		//echo "Ищем (".$journal_name.")<br/>";
		//echo $sql."<br/>";
		$journal_id = mysqli_query($connection, $sql);
		if(mysqli_num_rows($journal_id) == 0)
		{
			echo "Журнал в базе не найден!";
			
		} else {	
			while($row = mysqli_fetch_assoc($journal_id))
			{
				echo "<h1>".$row['name']."</h1>";
				$id = $row['id'];
				$sql = "SELECT * FROM Exemplars WHERE id_journal='$id'";
				$exemplars = mysqli_query($connection, $sql);
				if(mysqli_num_rows($exemplars) > 0)
				{
					while($row_ex = mysqli_fetch_assoc($exemplars))
					{			
						?>
						<div class="year"><? echo "<h2>".$row_ex['year']."</h2>" ?></div>
						<div class="issues">
						<?
							$ex_num = explode(",", $row_ex['Exemplars']);
							foreach ($ex_num as $key => $value) {
								echo "<span>".$value."</span>";
							}
							echo "<br>";
						?>
						</div>
						<?
					}
				}
			}
		}
	?>
	
	</div> <!-- .section -->
	<div class="section">
	</div> <!-- .section -->

	<div id="content" class="content">
		test content
	</div>
</div> <!-- content -->



</body>

</html>