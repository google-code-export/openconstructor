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
 * $Id: shared.foreach_cell.php,v 1.4 2007/02/27 11:23:21 sanjar Exp $
 */

define('SFC_REG_GET_NEW', 0);
define('SFC_REG_GET_LAST', 1);
define('SFC_REG_DESTROY_LAST', 2);

class Smarty_Foreach_Cell {
	
	function row_open($tag_args, &$compiler, $flip = false) {
		$handler = & Smarty_Foreach_Cell::_getNew($flip);
		return $handler->row_open($tag_args, $compiler);
	}
	
	function row_close($tag_args, &$compiler, $flip = false) {
		$handler = & Smarty_Foreach_Cell::_getLast();
		if($handler->flip != $flip)
			return $compiler->_syntax_error("mismatched tag {/foreach_".($flip ? 'col' : 'row')."}", E_USER_ERROR, __FILE__, __LINE__);
		$result = $handler->row_close($tag_args, $compiler);
		Smarty_Foreach_Cell::_destoyLast();
		return $result;
	}
	
	function col_open($tag_args, &$compiler) {
		return Smarty_Foreach_Cell::row_open($tag_args, $compiler, true);
	}
	
	function col_close($tag_args, &$compiler) {
		return Smarty_Foreach_Cell::row_close($tag_args, $compiler, true);
	}
	
	function cell_open($tag_args, &$compiler) {
		$handler = & Smarty_Foreach_Cell::_getLast();
		if($handler == null)
			return $compiler->_syntax_error("'foreach_cell' can be used only inside of 'foreach_row' or 'foreach_col'", E_USER_ERROR, __FILE__, __LINE__);
		return $handler->cell_open($tag_args, $compiler);
	}
	
	function cell_close($tag_args, &$compiler) {
		$handler = & Smarty_Foreach_Cell::_getLast();
		if($handler == null)
			return '';
		return $handler->cell_close($tag_args, $compiler);
	}
	
	function register(&$compiler) {
		$compiler->register_compiler_function('foreach_row', array('Smarty_Foreach_Cell', 'row_open'));
		$compiler->register_compiler_function('/foreach_row', array('Smarty_Foreach_Cell', 'row_close'));
		$compiler->register_compiler_function('foreach_col', array('Smarty_Foreach_Cell', 'col_open'));
		$compiler->register_compiler_function('/foreach_col', array('Smarty_Foreach_Cell', 'col_close'));
		$compiler->register_compiler_function('foreach_cell', array('Smarty_Foreach_Cell', 'cell_open'));
		$compiler->register_compiler_function('/foreach_cell', array('Smarty_Foreach_Cell', 'cell_close'));
	}
	
	function &_getNew($flip) {
		return Smarty_Foreach_Cell::_registry(SFC_REG_GET_NEW, $flip);
	}
	
	function &_getLast() {
		return Smarty_Foreach_Cell::_registry(SFC_REG_GET_LAST);
	}
	
	function &_destoyLast() {
		return Smarty_Foreach_Cell::_registry(SFC_REG_DESTROY_LAST);
	}
	
	function &_registry($action = -1, $flip = false) {
		static $reg, $index = -1;
		$result = null;
		switch($action) {
			case SFC_REG_GET_NEW:
				$reg[++$index] = new Smarty_Foreach_Cell_Compiler($index, $flip);
			case SFC_REG_GET_LAST:
				if($index != -1)
					$result = &$reg[$index];
			break;
			case SFC_REG_DESTROY_LAST:
				if($index != -1)
					unset($reg[$index--]);
			break;
		}
		return $result;
	}
}

class Smarty_Foreach_Cell_Compiler {
	var $id = null;
	var $props = null;
	var $flip = false;
	var $_tag;
	var $_from, $_keys;
	var $_total, $_i, $_it;
	var $_cols, $_rows, $_col, $_row;
	var $_item, $_key = null;
	var $_table = 'true', $_tr = 'true', $_td = 'true';
		
	function Smarty_Foreach_Cell_Compiler($id, $flip = false) {
		$this->id = $id;
		$this->props = "\$this->_fr[{$this->id}]";
		$this->flip = (bool) $flip;
		$this->_tag = $this->flip ? 'foreach_col' : 'foreach_row';
		
		$this->_from = "{$this->props}['_from']";
		$this->_keys = "{$this->props}['_keys']";
		$this->_total = "{$this->props}['total']";
		$this->_i = "{$this->props}['_i']";
		$this->_it = "{$this->props}['iteration']";
		$this->_cols = "{$this->props}['cols']";
		$this->_rows = "{$this->props}['rows']";
		$this->_row = "{$this->props}['row']";
		$this->_col = "{$this->props}['col']";
		$this->_table = "{$this->props}['_table']";
		$this->_tr = "{$this->props}['_tr']";
		$this->_td = "{$this->props}['_td']";
	}
	
	function row_open($tag_args, &$compiler) {
		$compiler->_push_tag($this->_tag);
		
		$attrs = $compiler->_parse_attrs($tag_args);
		$arg_list = array();
		
		if (empty($attrs['from'])) {
			return $compiler->_syntax_error("{$this->_tag}: missing 'from' attribute", E_USER_ERROR, __FILE__, __LINE__);
		}
		$from = $attrs['from'];
		
		if (empty($attrs['item'])) {
			return $compiler->_syntax_error("{$this->_tag}: missing 'item' attribute", E_USER_ERROR, __FILE__, __LINE__);
		}
		$item = $compiler->_dequote($attrs['item']);
		if (!preg_match('~^\w+$~', $item)) {
			return $compiler->_syntax_error("{$this->_tag}: 'item' must be a variable name (literal string)", E_USER_ERROR, __FILE__, __LINE__);
		}
		$this->_item = "\$this->_tpl_vars['$item']";
		
		if (isset($attrs['key'])) {
			$key = $compiler->_dequote($attrs['key']);
			if (!preg_match('~^\w+$~', $key)) {
				return $compiler->_syntax_error("{$this->_tag}: 'key' must to be a variable name (literal string)", E_USER_ERROR, __FILE__, __LINE__);
			}
			$this->_key = "\$this->_tpl_vars['$key']";
		}
		
		if (isset($attrs['status'])) {
			$status = $compiler->_dequote($attrs['status']);
			if (!preg_match('~^\w+$~', $status)) {
				return $compiler->_syntax_error("{$this->_tag}: 'status' must be a variable name (literal string)", E_USER_ERROR, __FILE__, __LINE__);
			}
			$_status = "\$this->_tpl_vars['$status']";
		} else
			$_status = null;
		
		if (empty($attrs['cols'])) {
			return $compiler->_syntax_error("{$this->_tag}: missing 'cols' attribute", E_USER_ERROR, __FILE__, __LINE__);
		}
		$cols = "(int) {$attrs['cols']}";
		
		$_table = isset($attrs['table']) ? "(bool) {$attrs['table']}" : 'true';
		$_tr = isset($attrs['tr']) ? "(bool) {$attrs['tr']}" : 'true';
		
		$_expand = isset($attrs['expand']) ? "(bool) {$attrs['expand']}" : 'false';
		
		$result = '';
		$result .= "{$this->_from} = $from; if (!is_array({$this->_from}) && !is_object({$this->_from})) { settype({$this->_from}, 'array'); }\n";
		$result .= "{$this->_total} = sizeof({$this->_from}); {$this->_i} = 0; {$this->_it} = 0;\n";
		$result .= "{$this->_cols} = $cols;\n";
		$result .= "if ({$this->_total} > 0 && {$this->_cols} > 0):\n";
		$result .= "	{$this->_table} = $_table;\n";
		$result .= "	{$this->_tr} = $_tr;\n";
		$result .= "	{$this->_rows} = ceil({$this->_total} / {$this->_cols});\n";
		$result .= "	if(($_expand) && ({$this->_total} % {$this->_cols}))\n";
		$result .= "		for(\$_i = 0, \$_l = {$this->_cols} - ({$this->_total} % {$this->_cols}); \$_i < \$_l; \$_i++) {\n";
		$result .= "			{$this->_total}++;\n";
		$result .= "			{$this->_from}[] = null;\n";
		$result .= "		}\n";
		$result .= "	{$this->_keys} = array_keys({$this->_from});\n";
		$result .= "	if({$this->_table}) echo '<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">';\n";
		$result .= "	for ({$this->_row} = 1; {$this->_row} <= {$this->_rows}; {$this->_row}++):\n";
		$result .= "		if({$this->_tr}) echo '<tr>';\n";
		if($this->_key != null) {
			$result .= "		{$this->_key} = & {$this->_keys}[{$this->_i}];\n";
			$result .= "		{$this->_item} = & {$this->_from}[{$this->_key}];\n";
		} else {
			$result .= "		{$this->_item} = & {$this->_from}[{$this->_keys}[{$this->_i}]];\n";
		}
		if($_status != null)
			$result .= "		$_status = array('col' => &{$this->_col}, 'row' => &{$this->_row}, 'cols' => &{$this->_cols}, 'rows' => &{$this->_rows}, 'index' => &{$this->_i}, 'iteration' => &{$this->_it}, 'total' => &{$this->_total});\n";
		return $result;
	}
	
	function row_close($tag_args, &$compiler) {
		$open_tag = $compiler->_pop_tag($this->_tag);
		
		$result = '';
		$result .= "		if({$this->_tr}) echo '</tr>';\n";
		$result .= "	endfor;\n";
		$result .= "	if({$this->_table}) echo '</table>';\n";
		$result .= "endif; unset({$this->props});";
		return $result;
	}
	
	function cell_open($tag_args, &$compiler) {
		$compiler->_push_tag('foreach_cell');
		
		$attrs = $compiler->_parse_attrs($tag_args);
		
		$_td = isset($attrs['td']) ? "(bool) {$attrs['td']}" : 'true';
		
		$result = '';
		$result .= "		if(!isset({$this->_td})) {$this->_td} = $_td;\n";
		if($this->flip)
			$result .= "			{$this->_i} = {$this->_row} - 1;\n";
		$result .= "		for ({$this->_col} = 1; {$this->_col} <= {$this->_cols} && {$this->_i} < {$this->_total}; {$this->_col}++):\n";
		$result .= "			{$this->_item} = & {$this->_from}[{$this->_keys}[{$this->_i}]];\n";
		if($this->_key != null)
			$result .= "			{$this->_key} = {$this->_item} === null ? null : {$this->_keys}[{$this->_i}];\n";
		$result .= "			if({$this->_td}) echo '<td>';\n";
		
		return $result;
	}
	
	function cell_close($tag_args, &$compiler) {
		$open_tag = $compiler->_pop_tag('foreach_cell');
		
		$result = '';
		$result .= "			if({$this->_td}) echo '</td>';\n";
		if($this->flip)
			$result .= "			{$this->_i} = {$this->_col} * {$this->_rows} + {$this->_row} - 1;\n";
		else
			$result .= "			{$this->_i}++;\n";
		$result .= "			{$this->_it}++;\n";
		$result .= "		endfor;\n";
		$result .= "		if({$this->_col} <= {$this->_cols}) echo '<td colspan=\"'.({$this->_cols} - {$this->_col} + 1).'\">&nbsp;</td>';\n";
		
		return $result;
	}
}
?>