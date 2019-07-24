<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Выше 3 Мета-теги ** должны прийти в первую очередь в голове; любой другой руководитель контент *после* эти теги -->  
    <title>Периодические издания</title>

    <!-- Bootstrap -->  
    <!-- <link href="css/bootstrap.min.css" rel="stylesheet">	-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
	<link href="style.css" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css?family=PT+Mono&display=swap" rel="stylesheet"> 
	
    <!-- HTML5 Shim and Respond.js for IE8 support of HTML5 elements and media queries -->  
    <!-- Предупреждение: Respond.js не работает при просмотре страницы через файл:// -->  
    <!--[if lt IE 9]>
 <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script >
 <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
 <![endif]-->  
  </head>

<?
	require 'db.php';
	require 'alphabetical.php';
	global $connection;
?>

  <body>   

    <!-- на jQuery (необходим для Bootstrap - х JavaScript плагины) -->  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Включают все скомпилированные плагины (ниже), или включать отдельные файлы по мере необходимости -->  
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <!--<script src="js/bootstrap.min.js"></script>-->
	
	<div class="container" id="content">
		<div class="row">
			<div class="col-lg-6">
			<h1><center>Поиск по алфавиту</center></h1>
				<? get_alphabet(); ?>
			</div><!-- /.col-lg-8 -->  
		</div><!-- /row -->
		
		<div class="row">
			<div class="col-lg-6">
			<h1><center>Поиск по названию</center></h1>
			<div class="form-group">
			<form action="listJournals.php" method="POST" role="form" class="form-horizontal">
				<div class="input-group input-group-sm">					
					<input name="journal_name" type="text" class="form-control" placeholder="Введите название журнала...">
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit">Поиск</button>
					</span>					
				</div><!-- /input-группа -->  
			</form>
			</div>			
			</div><!-- /.col-lg-8 -->  
		</div><!-- /row -->		
		
		<div class="row">
			<div class="col-lg-6">
			<h1><center>Список изданий</center></h1>
				<? ?>
			</div><!-- /.col-lg-8 -->  
		</div><!-- /row -->


	</div> <!-- /conteiner -->  
  </body>
</html>