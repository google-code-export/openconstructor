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
 * $Id: compiler.set.php,v 1.2 2007/02/27 11:23:21 sanjar Exp $
 */

function smarty_compiler_set($tag_attrs, &$compiler) {
	$result = '';
	$_params = $compiler->_parse_attrs($tag_attrs);
	if(isset($_params['ctx']) && ($_params['ctx'] === 'true' || $_params['ctx'] == 1)) {
		unset($_params['ctx']);
		foreach($_params as $k => $v)
			$result .= "\$this->_ctx->setParam('$k', $v);";
	} else
		foreach($_params as $k => $v)
			$result .= "\$this->assign('$k', $v);";
	return $result;
}

?>