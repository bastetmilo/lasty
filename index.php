<?php   
require_once ('../mysql_connect.php');  
if (isset($_POST['send'])){
	if ($_FILES['last_file']['error'] > 0){
		echo 'Problem: ';
		switch ($_FILES['last_file']['error'])  //obsługa błędów przy przesyłaniu pliku
		{
			case 1: echo 'Rozmiar pliku przekroczył wartość upload_max_file_size'; break;
			case 2: echo 'Rozmiar pliku przekroczył 2MB'; break;
			case 3: echo 'Plik wysłany tylko częściowo'; break;
			case 4: echo 'Nie wysłano żadnego pliku'; break;
		}
		exit;
	}        
	//Sprawdzam czy wgrywam plik jpg. 
	//Jak to zmienić, żeby warunek obejmował png i gif? 
	if ( !($_FILES['last_file']['type'] == 'image/jpeg' || $_FILES['last_file']['type'] == 'image/png') )  
	{
		echo 'Zły typ pliku: '.$_FILES['last_file']['type'];
		exit;
	} 
	//zapisywanie pliku
	$fileName = $_FILES['last_file']['name'];
	$lokalizacja = 'pliki/'.$_FILES['last_file']['name'];
	
	if (is_uploaded_file($_FILES['last_file']['tmp_name'])){
		if (!move_uploaded_file($_FILES['last_file']['tmp_name'], $lokalizacja)){
			echo 'Problem: Plik nie moze byc skopiowany do katalogu';
			exit;
		}
	}            
	else {
		echo 'Problem: możliwy atak podczas wysyłania pliku'; //WTF? Tego nie rozumiem.
		exit;
	}
	$query = "INSERT INTO `pliki` (nazwa) VALUES ('$fileName')"; //Wrzucamy nazwe pliku do bazy
	 															 //Takie ułatwienie, żeby łatwiej wyświetlać
	$result = @mysql_query($query);         
	echo 'Plik został wysłany';
}

if (isset($_GET['kasuj']))     //Kasowanie plików 
{ 
 
$ile = count ($_GET['pliki']);
for ($x=0 ; $x<$ile ; $x++)
	{
	$index = $_GET['pliki'][$x];
	$query = "SELECT * FROM `pliki` WHERE ID=$index";
	$result = @mysql_query($query);
	$row = mysql_fetch_array($result);
	unlink ('pliki/'.$row['nazwa']);
	$query = "DELETE FROM `pliki` WHERE ID=$index";
	$result = @mysql_query($query);
	}

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Oferty last minute</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Helena Świderska">
	<!-- Date: 2011-12-18 -->
</head>
<body>
 <form action="index.php" method="post" accept-charset="utf-8" enctype="multipart/form-data" accept="image/jpeg,image/png,image/gif">
       <input type="hidden" name="MAX_FILE_SIZE" value="2000000" id="MAX_FILE_SIZE">
		<label for="wybierz_plik">Wybierz plik</label><input type="file" name="last_file" value="" id="wybierz_plik">
		<input type="submit" value="Wyślij" name="send">
 </form>
<form action="index.php" method="get" accept-charset="utf-8">
<?php
	echo '<h2>Wgrane Lasty</h2> <ul>';
    $query = "SELECT * FROM `pliki` ORDER BY ID DESC";
	$result = @mysql_query($query);
    while ($row = mysql_fetch_array($result))
	{
echo '<li><img src="pliki/'.$row['nazwa'].'" width="50" height="50"><br><i>'.$row['nazwa'].'</i><input type="checkbox" name="pliki[]" value="'.$row['ID'].'"></li>';
	}

	echo '</ul>';
?>
 <input type="submit" value="Kasuj" name="kasuj"> 
</form>
</body>
</html>