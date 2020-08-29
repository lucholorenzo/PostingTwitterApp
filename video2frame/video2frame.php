<?php

if(!$sock = @fsockopen('www.google.com.ar', 80)){
    echo '!!!!!! NO HAY INTERNET !!!!!!';
}else{

//- Generar un nombre de archivo(episodio) random de las temporadas
	function count_digit($number) {
	  return strlen((string)$number);
	}

	$random_season = random_int ( 1 , 30 );
	$number_of_digits = count_digit($random_season);

	if($number_of_digits == 1) $random_season = "0".$random_season;
	else $random_season = (string)$random_season;

	$path_season = "/home/lucho/Desktop/Series/The.Simpsons/The.Simpsons.S".$random_season."/";

	$files_path_season  = scandir($path_season);
	$cont_files = count($files_path_season) - 1;
	$random_file = random_int ( 2 , $cont_files );

	$inputFile= '"'.$path_season . $files_path_season[$random_file].'"'; 

//-



//- Generar un nombre de archivo para frame destino

	$outputDirectory = "/var/www/html/PostingTwitterApp/video2frame/output/";
	$files_outputDirectory  = scandir($outputDirectory);

	$season_ep = explode(".", $files_path_season[$random_file]);
	if($season_ep[1] == "mp4") $patch_ep_name = "";
	else $patch_ep_name = "-".$season_ep[1];

	$outputFile = '"'.$outputDirectory."outputFrame-".$season_ep[0]."-".(count($files_outputDirectory) - 2).$patch_ep_name.".jpg".'"';
	
//-



//- Generar un numero de tiempo random(entre el inicio y fin del episodio) para la seleccion del frame

	//el tiempo de seleccion de frame esta en segundos.
	$start_time = 4 * 60; // 4 minutos de opening maximo.
	$end_time = 20 * 60; 
	$time = random_int ( $start_time , $end_time );

//-


//- Generar la imagen del frame seleccionado

	$ffmpg = 'ffmpeg -ss '.$time.' -i '.$inputFile.' -vframes 1 '.$outputFile;
	exec($ffmpg);

}

?>