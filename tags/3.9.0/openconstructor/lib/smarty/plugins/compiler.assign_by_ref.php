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
 * $Id: compiler.assign_by_ref.php,v 1.2 2007/02/27 11:23:21 sanjar Exp $
 */

function smarty_compiler_assign_by_ref($tag_attrs, &$compiler)
{
	$_params = $compiler->_parse_attrs($tag_attrs);
	if (!isset($_params['var'])) {
		$compiler->_syntax_error("assign_by_ref: missing 'var' parameter", E_USER_WARNING);
		return;
	}
	if (!isset($_params['value'])) {
		$compiler->_syntax_error("assign_by_ref: missing 'value' parameter", E_USER_WARNING);
		return;
	}
	if (!strlen($t = ltrim($_params['value'])) || $t{0} != '$') {
		$compiler->_syntax_error("assign_by_ref: invalid 'value' parameter", E_USER_WARNING);
		return;
	}
	return "\$this->assign_by_ref({$_params['var']}, {$_params['value']});";
}

?>