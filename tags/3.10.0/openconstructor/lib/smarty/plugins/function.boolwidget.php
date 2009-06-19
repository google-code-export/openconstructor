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
 * $Id: function.boolwidget.php,v 1.2 2007/02/27 11:23:21 sanjar Exp $
 */

function smarty_function_boolwidget($params, &$smarty) {
    if(!isset($params['field'])) {
        $smarty->trigger_error("boolwidget: missing 'field' parameter");
        return;
    }
	$doc = &$smarty->get_template_vars('doc');
	$field = strpos($params['field'], 'f_') === 0 ? @substr($params['field'], 2) : '_'.$params['field']; 
    if(!array_key_exists($field, $doc)) {
        $smarty->trigger_error("boolwidget: field '{$params['field']}' doesn't exists");
        return;
    }
	$val = $doc[$field];
	$yes = $params['yes'] ? $params['yes'] : 'Yes';
	$no = $params['no'] ? $params['no'] : 'No';
	$result = '<span class="yesno"><a href="javascript: // Yes" onclick="this.childNodes[0].click();"><input type="radio" name="'.$params['field'].'" value="1"'.($val ? ' checked': '').'> '.$yes.'</a></span>'.
		'<span class="yesno"><a href="javascript: // No" onclick="this.childNodes[0].click();"><input type="radio" name="'.$params['field'].'" value="0" '.(!$val ? ' checked': '').'> '.$no.'</a></span>';
	return $result;
}
?>
