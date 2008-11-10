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
 * @author Sanjar Akhmedov
 * 
 * $Id: function.treewidget.php,v 1.2 2007/02/27 11:23:21 sanjar Exp $
 */

function smarty_function_treewidget($params, &$smarty) {
    if(!isset($params['field'])) {
        $smarty->trigger_error("treewidget: missing 'field' parameter");
        return;
    }
	$doc = &$smarty->get_template_vars('doc');
    if(!array_key_exists(@substr($params['field'], 2), $doc)) {
        $smarty->trigger_error("treewidget: field '{$params['field']}' doesn't exists");
        return;
    }
	$ctl = 'ctl_'.$params['field'];
	if($params['width'])
		$width = "width='{$params['width']}'";
	if($params['height'])
		$height = "height='{$params['height']}'";
	ob_start();
	echo '<nobr class="tool">';
	wysiwygtoolbar($ctl);
	echo '</nobr>';
	?>
	<iframe id='iframe.<?=$params['field']?>' <?=$width.' '.$height?>></iframe>
	<script>
		var <?=$ctl?> = new WYSIWYGController();
		addListener(window, 'onload', function() {
			new WYSIWYGWidget(
				document.getElementById('<?=$params['field']?>'),
				document.getElementById('iframe.<?=$params['field']?>'),
				<?=$ctl?>,
				'@import url("http://<?=$_SERVER['HTTP_HOST']?>/css/content.css");'
			);
		});
	</script>
	<?php
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}
?>
