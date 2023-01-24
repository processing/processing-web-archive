<?php

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Parse contributions.txt
 * Jon Gacnik (jmgacnik@gmail.com)
 * 04.16.2013
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

require('../config.php');
//$benchmark_start = microtime_float();

//Grab the contributions data from the text file
$contributions  = "\n".file_get_contents('../contrib_generate/contributions.txt');
//$contributions .= file_get_contents('../contrib_generate/legacy.txt');

//Split the contributions text file by contribution type
$types	= "/(\ntool|\nlibrary|\nmode)/";
$flags	= PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY;
$items	= preg_split( $types, $contributions, -1, $flags);

$byType = array(
	'library' 	=> array(),
	'tool' 		=> array(),
	'mode' 		=> array()
);

for($i = 0; $i < count($items); $i+=2){
	$items[$i] = preg_replace("/\n/", "", $items[$i]);
	array_push($byType[$items[$i]], $items[$i+1]);
}

//After this we are left the $clean array holding all parsed contribution data
foreach($byType as $type => &$contents){
	$count = 0;
	foreach($contents as &$entity){
		$entity = preg_split( "/(\n)/", $entity);
		array_shift($entity); 
		array_pop($entity);

		foreach($entity as &$line){
			$line = preg_replace('/=/', '===', $line, 1);
			$line = explode('===', $line);

			$clean[$type][$count][$line[0]] = $line[1];
		}
		$count++;
	}
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Additional Parse Functions
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

function linkParse($input){
	return preg_replace('/\[([^\[]+)\]\(([^\)]+)\)/', '<a href=\'\2\'>\1</a>', $input);
}

function anchorSafe($input){
	return str_replace(' ','',strtolower($input));
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Build Pages
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

///////////////
//Libraries
///////////////

//Push all libraries into array sorted by category
$categories = array();
foreach($clean['library'] as $library){
	array_push($categories, $library['category']);
}
$categories = array_unique($categories);
sort($categories);

//Create a nav from each category (Except Legacy)
$librariesHTML = '<div class="contributions">'."\n".'<div class="categories">'."\n";
foreach($categories as $category){ 
	if($category != 'Legacy'){
		$librariesHTML .= "\t".'<a href="#'.anchorSafe($category).'">'.$category.'</a><br>'."\n";
	}
}
$librariesHTML .= '</div>'."\n";

//List out the Libraries by category (Except Legacy)
foreach($categories as $category){
	if($category != 'Legacy'){
		$librariesHTML .= '<div id="'.anchorSafe($category).'">'."\n\t";
		$librariesHTML .= '<h4>'.$category.'</h4>'."\n";
		$librariesHTML .= "\t<ul>\n";
		$libs = array_filter($clean['library'], function($obj) use($category){ return $obj['category'] == $category; });
		
		foreach($libs as $lib){
			$librariesHTML .= "\t\t<li>\n";
			$librariesHTML .= "\t\t\t".'<h5><a href="'.$lib['url'].'">'.$lib['name'].'</a></h5>'."\n";
			$librariesHTML .= "\t\t\t".'<span>by '.linkParse($lib['authorList']).'</span>'."\n";
			$librariesHTML .= strlen(trim($lib['sentence'])) ? "\t\t\t".'<p>'.linkParse($lib['sentence']).'</p>'."\n" : "\n";
			$librariesHTML .= "\t\t</li>\n";
		}
		$librariesHTML .= "\t</ul>\n";
		$librariesHTML .= '</div>'."\n\n";
	}
}

//List out the Legacy Libraries
//$legacy = array_filter($clean['library'], function($obj) { return $obj['category'] == 'Legacy'; });
//$librariesHTML .= "<div class='legacy'>\n\t<h4>Legacy Libraries</h4>\n";
//$librariesHTML .= "\t<p>The libraries in this category haven't been updated by their creators to be compatible with the Processing 2 library manager and/or updated to work with Processing 2. We hope to have all libraries working with Processing 2; if one of your favorite libraries hasn't been updated, we encourage you to contact the library creator or to update it yourself and share it. Instructions for creating a Processing 2 library <a href='https://github.com/processing/processing/wiki'>are on the Processing GitHub site.</a>.</p>\n\t<ul>\n";
//foreach($legacy as $lib){
//	$librariesHTML .= "\t\t<li>\n";
//	$librariesHTML .= "\t\t\t".'<h5><a href="'.$lib['url'].'">'.$lib['name'].'</a></h5>'."\n";
//	$librariesHTML .= "\t\t\t".'<span>by '.linkParse($lib['authorList']).'</span>'."\n";
//	$librariesHTML .= "\t\t</li>\n";
//}
//$librariesHTML .= "</ul></div></div>";

// Added this line when CR made change to remove Legacy libraries
$librariesHTML .= "</div>";

//Write LibrariesHTML to libraries.html file
$file_dir 	= CONTENTDIR.'static/';
$file_name 	= strip_tags('libraries.html');
$file 		= $file_dir.$file_name; 

$create_file = fopen($file, "w+"); 
$chmod = chmod($file, 0755);
fwrite($create_file, $librariesHTML);
fclose($create_file);


///////////////
//Tools
///////////////

$toolsHTML 	= "<div class='contributions'>\n<ul>\n";
//$legacy 	= "<div class='legacy'>\n<h4>Legacy Tools</h4>\n<p>The tools in this category haven't been updated by their creators to be compatible with the Processing 2 library manager and/or updated to work with Processing 2. We hope to have all tools working with Processing 2; if one of your favorite tools hasn't been updated, we encourage you to contact the tool creator or to update it yourself and share it. Instructions for creating a Processing 2 library <a href='https://github.com/processing/processing/wiki'>are on the Processing GitHub site.</a>.</p>\n<ul>\n";

foreach($clean['tool'] as $tool){
	//if($tool['category'] != 'Legacy'){
		$toolsHTML .= "\t<li>\n";
		$toolsHTML .= "\t\t".'<h5><a href="'.$tool['url'].'">'.$tool['name'].'</a></h5>'."\n";
		$toolsHTML .= "\t\t".'<span>by '.linkParse($tool['authorList']).'</span>'."\n";
		$toolsHTML .= strlen(trim($tool['sentence'])) ? "\t\t".'<p>'.linkParse($tool['sentence']).'</p>'."\n" : "\n";
		$toolsHTML .= "\t</li>\n";
	//} else {
	//	$legacy .= "\t<li>\n";
	//	$legacy .= "\t\t".'<h5><a href="'.$tool['url'].'">'.$tool['name'].'</a></h5>'."\n";
	//	$legacy .= "\t\t".'<span>by '.linkParse($tool['authorList']).'</span>'."\n";
	//	$legacy .= "\t</li>\n";
	//}
}
//$toolsHTML .= "</ul>\n\n".$legacy."</ul>\n</div>\n</div>";

// Added this line when CR made change to remove Legacy libraries
$toolsHTML .= "</div>";

//Write toolsHTML to tools.html file
$file_name 	= strip_tags('tools.html');
$file 		= $file_dir.$file_name; 

$create_file = fopen($file, "w+"); 
$chmod = chmod($file, 0755);
fwrite($create_file, $toolsHTML);
fclose($create_file);


?>