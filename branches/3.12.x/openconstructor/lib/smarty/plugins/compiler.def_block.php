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
 * Плагин заглушка, нужен только при прекомпиляции.
 * См. /lib/templates/wctparsehelper._wc#smarty_def_block
 * 
 * @author Sanjar Akhmedov
 * 
 * $Id: compiler.def_block.php,v 1.2 2007/02/27 11:23:21 sanjar Exp $
 */

function smarty_compiler_def_block($tag_attrs, &$compiler)
{
	return '';
}

?>