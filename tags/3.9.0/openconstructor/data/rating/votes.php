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
 * $Id: votes.php,v 1.8 2007/04/24 21:49:07 sanjar Exp $
 */
	require_once($_SERVER['DOCUMENT_ROOT'].'/openconstructor/lib/wccommons._wc');
	WCS::requireAuthentication();
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/main._wc');
	require_once(LIBDIR.'/languagesets/'.LANGUAGE.'/editors._wc');
	require_once(LIBDIR.'/wcdatasource._wc');
	require_once(LIBDIR.'/rating/dsrating._wc');
	
	
	
	$_ds = & new DSRating();
	$_ds->load($_GET['ds_id']);
	$_doc = $_ds->get_record($_GET['id']);
	assert($_doc !== null);
	
	require_once('../../include/sections._wc');
	
	function get_votes_headline($votes, $pagesize, $page = 1, $clause = '') {
		$db = &WCDB::bo();
		$result = array(0, array());
		$page = intval($page);
		if(--$page < 0)
			$page = 0;
		$query = 
			'SELECT SQL_CALC_FOUND_ROWS l.user_id AS id, u.name AS uname, u.login, l.active, l.fake, l.rating, l.votes, l.date'.
			' FROM dsratinglog l LEFT JOIN wcsusers u ON (l.user_id = u.id) '.
			' WHERE l.id = '.$_GET['id'].' '.$clause.
			' ORDER BY l.date DESC '.
			' LIMIT '.($page * $pagesize).','.$pagesize;
		$res = $db->query($query);
		if(mysql_num_rows($res) > 0) {
			$r = $db->query('SELECT FOUND_ROWS()');
			list($result[0]) = mysql_fetch_row($r);
			mysql_free_result($r);
				
			while($r = mysql_fetch_assoc($res)) {
				$rate = round($r['rating'] / $r['votes']);
				$hl[$r['id']] = array(
					'id' => $r['id'],
					'header' => $r['uname'] ? ($r['fake'] ? "<i>{$r['uname']}</i>" : $r['uname']) : 'N/A',
					'published' => $r['active'],
					'description' => (
						$r['fake']
							? "<u class='inf'>{$r['votes']} ".H_REC_VOTES.($r['active'] ? ', '.round(100 * $r['votes']/ $votes, 2).'%' : '')
							: "<u>{$r['login']}"
						).'</u>',
					'rate' => "<h5>$rate</h5>",
					'date' => date('d.m.Y H:i:s', $r['date'])
				);
			}
			$result[1] = &$hl;
		}
		mysql_free_result($res);
		return $result;
	}
	
	header("Content-type: text/xml; charset=utf-8");
	echo '<?xml version="1.0" encoding="utf-8"?>';
	echo '<?xml-stylesheet type="text/xsl" href="'.WCHOME.'/skins/'.SKIN.'/docs.xsl"?>';
	
	$pagesize = 100;
	$clause = '';
	$keyword = trim((string) @$_GET['keyword']);
	$onlyFake = @$_GET['onlyFake'] > 0;
	$from = @$_GET['from'] ? strtotime($_GET['from']) : -1;
	$to = $from != -1 && @$_GET['to'] ? strtotime($_GET['to']) : -1;
	
	if($keyword != "") {
		$pattern = '%'.str_replace('%', '%%', $keyword).'%';
		$clause = sprintf(
			' AND ('.(is_numeric($keyword) ? 'l.rating / l.votes = "%1$d" OR ' : '').'l.comment LIKE "%2$s" OR u.login LIKE "%2$s" OR u.email LIKE "%2$s" OR u.name LIKE "%2$s") '
			, $keyword, addslashes($pattern)
		);
	} elseif($onlyFake) {
		$clause = ' AND l.fake ';
	} elseif($from != -1 && $to != -1 && $from <= $to) {
		$clause = sprintf(' AND l.date BETWEEN %d AND %d', $from, $to);
	}
	list($items, $hl) = get_votes_headline($_doc['raters'], $pagesize, @$_GET['page'], $clause);
	$icon = 'rate';
	$editor = 'editvote.php?ds_id='.$_doc['ds_id'].'&amp;rid='.$_doc['id'];
	$fields = array(
		'header' => HL_AUTHOR,
		'description' => true,
		'rate' => HL_RATING.'&#160;',
		'date' => HL_DATE
	);
	$fieldnames = array(
		//'date'=>RP_SHOW_DATE
	);
?>
<documentsframe insight="<?=WCHOME?>">
	<?php
		set_xslt_vars(array(
			'SELECT_ALL',
			'GOTO_FIRST_PAGE',
			'GOTO_PREVIOUS_PAGE',
			'GOTO_NEXT_PAGE',
			'GOTO_LAST_PAGE',
			'TOTAL'
		));
	?>
	<script>
	<![CDATA[
		if(window.parent.chk) {
			var chk = function(obj) {
				chk_(obj);
				window.parent.chk(obj, ch_doc);
			}
		} else {
			var chk = function(obj) {
				chk_(obj);
			}
		}
	]]>	
	</script>
	<documents server="i_rating.php"
		type="<?=$icon?>"
		defaultaction="delete_vote"
		size="<?=intval(@$_COOKIE['pagesize']) > 0 ? $_COOKIE['pagesize'] : $pagesize?>"
		>
		<editor href="<?=$editor?>" width="780" height="400"/>';
		<hidden name="ds_id" value="<?=$_ds->ds_id?>"/>
		<hidden name="id" value="<?=$_doc['id']?>"/>
		<hidden name="id" value="<?=$_doc['id']?>"/>
		<?php
			print_headline($hl);
			require_once(LIBDIR.'/pager._wc');
			noSqlPager($items, 'page', $pagesize, 10);
		?>
	</documents>
</documentsframe>
