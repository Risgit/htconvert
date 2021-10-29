<?php 

/* Получаем рекурсивно все пути к файлам .htaccess на сайте */
function glob_tree_search($path, $pattern, $_base_path = null)
{
	if (is_null($_base_path)) {
		$_base_path = '';
	} else {
		$_base_path .= basename($path) . '/';
	}
 
	$out = array();
	foreach(glob($path . '/' . $pattern, GLOB_BRACE) as $file) {
		$out[] = $_base_path . basename($file);
	}
	
	foreach(glob($path . '/*', GLOB_ONLYDIR) as $file) {
		$out = array_merge($out, glob_tree_search($file, $pattern, $_base_path));
	}
 
	return $out;
}
 
$path = __DIR__ . '/';
$htaccess = glob_tree_search($path, '.htaccess');

/* Перебираем массив файлов и выводим текст из них */
foreach ($htaccess as $ht){
	
	$apache_rules = htmlspecialchars(file_get_contents($ht));
	$apache_rules = str_replace("\r", "", str_replace("\n", "<br>", $apache_rules));
	
	echo '<table border= "1" cellspacing="0" width="100%">';
	echo '<tr><th width="300px"> Файл htaccess </th><th> Содержимое htaccess </th></tr>';
	echo '<tr align="center"><td>' . $ht . '</td>';
	echo '<td>' . $apache_rules . '</td></tr>';
	echo '</table>';
	
}
