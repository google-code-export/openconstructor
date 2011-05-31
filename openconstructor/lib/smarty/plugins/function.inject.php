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
 * $Id: function.inject.php,v 1.2 2007/02/27 11:23:21 sanjar Exp $
 */

function smarty_function_inject($params, &$smarty) {
	if(isset($params['block']) && !empty($params['block'])) {
		if(isset($params['field']) && !empty($params['field'])) {
			if($obj = $smarty->_ctx->getObjectAt($params['block'])) {
				if(isset($params['value']))
					$smarty->_ctx->inject($obj, $params['field'], $params['value']);
				elseif(isset($params['param']))
					$smarty->_ctx->injectParam($obj, $params['field'], $params['param']);
			}
		} else
			$smarty->trigger_error('inject: Missing "field" param');
	} else
		$smarty->trigger_error('inject: Missing "block" param');
	return '';
}
?>
