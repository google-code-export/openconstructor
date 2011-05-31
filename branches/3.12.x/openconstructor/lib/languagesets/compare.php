<?php
/**
 * Copyright 2003 - 2007 eSector Solutions, LLC
 * 
 * All rights reserved.
 * 
 * This file is part of Open Constructor (http://www.openconstructor.org/).
 * 
 * Open Constructor is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 2
 * as published by the Free Software Foundation.
 * 
 * Open Constructor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html
 * 
 * $Id: compare.php,v 1.8 2007/03/02 10:06:41 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
?>
<html>
<head>
<title>Open Constructor Languagesets</title>
<style>
TABLE {
	background:#aaa;
}
TABLE TD {
	padding:2px 3px;
}
TABLE TBODY TD {
	background:white;
	font-family:courier new, monospace;
	font-size:16px;
}
TABLE THEAD TD {
	background:#efefef;
	font-weight:bold;
	padding:5px 5px 5px 10px;;
}
TR.diff TD {
	font-weight:bold;
	color:red;
}
H3 {
	border-bottom:#ccc 1px solid;
}
</style>
</head>
<body>
<?php
	$languages=array();
	echo '<h1><a href="../..">Open Constructor</a> Languagesets</h1><ul>';
	$d=dir(".");
	while (false !== ($entry = $d->read()))
	    if(is_dir($entry)&&substr($entry,0,1)!='.'&&$entry!='CVS'){
	    	$languages[$entry]=$entry;
	    	echo '<li>'.$entry;
	    	$fl=$entry;
	    }
	$d->close();
	echo '</ul>';
	if(!sizeof($languages))
		die('Empty');
	function diff($l,$f,&$in){
		if($f=='NULL') return '';
		global $languages;
		if(!isset($in[$l][$f]))
			return '<b>-</b>';
		$i=0;
		foreach($languages as $id)
			if(isset($in[$id][$f]))
				$i++;
		if(sizeof($languages)==$i)
			return '&nbsp;';
		return '<b>+</b>';
	}
	function diffj($f,&$in){
		global $languages;
		foreach($languages as $id)
			if(diff($id,$f,$in)!='&nbsp;')
				return ' class="diff"';
		return '';
	}
?>
<h2>Languageset differencies [files]</h2>
<table border="0" cellpadding="0" cellspacing="1">
	<thead>
	<tr>
	<?php
		$allfiles=array();
		foreach($languages as $id){
			echo '<td colspan=2>'.$id.'</td>';
			$files[$id]=array();
			$d=dir($id);
			while (false !== ($entry = $d->read()))
			    if(is_file($id.'/'.$entry)){
			    	$files[$id][$entry]=$entry;
			    }
			$allfiles=array_merge($allfiles,$files[$id]);
			$d->close();
		}
	?>
	</tr>
	</thead>
	<tbody>
	<?php
		foreach($allfiles as $file){
			echo '<tr'.diffj($file,$files).'>';
			foreach($languages as $id)
				echo '<td width="10">'.diff($id,$file,$files).'</td><td>'.$file.'</td>';
			echo '</tr>';
		}
	?>
	</tbody>
</table>
<h2>Languageset differencies [entries]</h2>
<?php
	function conc_at(&$w,$i,&$at){
		$w=$at[$i].$w;
	}
	function symm(&$w,$i){
		$w=str_replace('@','',$i);
	}
	foreach($allfiles as $file)
		if(!diffj($file,$files)&&substr($file,strrpos($file,'.')+1)!='html')
		{
			echo '<h3>'.$file.'</h3>';
			$words=array();
			$allwords=array();
			echo '<table border="0" cellpadding="0" cellspacing="1"><thead><tr>';
			foreach($languages as $id){
				echo '<td colspan=2>'.$id.'/'.$file.'</td>';
				$def=file_get_contents($id.'/'.$file);
				$matches=NULL;
				preg_match_all('/\n\s*(@?)define\(\'([A-Za-z0-9_]+)\',\s*\'(.*)\'\);/u',$def,$matches);
				array_walk($matches[2],'conc_at',$matches[1]);
				$words[$id]=array_flip($matches[2]);
				foreach($words[$id] as $k=>$v)
					$values[$id][$k]=$matches[3][$v];
				array_walk($words[$id],'symm');
//				$words[$id]=array_flip($words[$id]);
				$allwords=array_merge($allwords,$words[$id]);
			}
			echo '</tr></thead><tbody>';
			foreach($allwords as $word=>$j){
				echo '<tr'.diffj($word,$words).'>';
				foreach($languages as $id){
					$d=diff($id,$word,$words);
					if($d=='<b>-</b>')
						foreach($languages as $l)
							if(diff($l,$word,$words)=='<b>+</b>'){
								$mustadd[$id][$file][$word]=$l.': '.$values[$l][$word];
								break;
							}
					echo '<td width="10">'.$d.'</td><td>'.$word.'</td>';
				}
				echo '</tr>';
			}
			echo '</tbody></table>';
		}
		
		echo '<h1>MustAdd</h1><pre>';
		foreach((array)@$mustadd as $lang=>$v){
			echo "\n\n\n// -------------------------------------------\n";
			echo "// Language: $lang\n";
			echo "// -------------------------------------------\n\n";
			foreach($v as $file=>$v1){
				echo "\n\t// File: $file\n\n";
				foreach($v1 as $key=>$value)
					echo "\t\t".($key[0]=='@'?'@':'')."define('".str_replace('@','',$key)."','".htmlspecialchars($value, ENT_COMPAT, 'UTF-8')."');\n";
			}
		}
		echo '</pre>';
//		echo '<pre>';
	//	print_r($mustadd);
		//echo '</pre>';
?>
</body>
</html>