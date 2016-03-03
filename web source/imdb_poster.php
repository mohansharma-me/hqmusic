<?php
//$link="http://ia.media-imdb.com/images/M/MV5BODAzMDgxMDc1MF5BMl5BanBnXkFtZTgwMTI0OTAzMjE@._V1_SX300.jpg";
header("Content-Type: image/jpeg");
try {
	if(isset($_GET["imdb_id"])) {
		$link=$_GET["imdb_id"];
		$data=file_get_contents("http://www.omdbapi.com/?i=$link&plot=full&r=json");
		$json=json_decode($data,true);
		//header("Content-Length: $");
		header('Content-Disposition: attachment; filename='.basename($json["Poster"]).'');
		$data=file_get_contents($json["Poster"]);
		print_r($data);
	} else {
		//not found
	}
} catch(Exception $ex)  {

}