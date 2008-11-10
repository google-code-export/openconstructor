{*$objs|@debug_print_var*}
{assign var="ocm_home" value="/openconstructor"}
{assign var="skinhome" value="$ocm_home/i/newskin"}
{assign var="img" value="$skinhome/images"}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="{$skinhome}/css/style.css" rel="stylesheet" type="text/css" />
		<title>{$smarty.const.WC} | {$smarty.const.ADD_OBJECT}</title>
		<script type="text/javascript" src="{$skinhome}/js/jquery-1.2.6.js"></script>
		<script type="text/javascript" src="{$skinhome}/js/common.js"></script>
	</head>
	<body>
		<script>
			var
				wchome = "{$ocm_home}",
				skin = "{$smarty.const.SKIN}",
				imghome = wchome+'/i/'+skin+'/images'
				;
			var	dis = {$disabled};
			{literal}
			$(document).ready(function(){				$("#close").click(function(){
					window.close();
				});

				if(!dis){
					$(".obj_table div").click(function(){
						$(".obj_table div.selecteditem").removeClass("selecteditem");
						$(this).addClass("selecteditem");
						$("#add").attr('disabled',false);
						$("form[name='add_obj'] input[name='obj_id']").val($(this).children("input").val());
					});

					$(".obj_table div").dblclick(function(){
						$("form[name='add_obj']").submit();
					});
				}
			});
			{/literal}
		</script>
		<div class="add_wrapper">
			<h3 class="head_title">{$smarty.const.ADD_OBJECT}</h3>
			<form name="add_obj" method="POST" action="i_structure.php">
				<input type="hidden" name="action" value="add_object" />
				<input type="hidden" name="uri_id" value="{$node}" />
				<input type="hidden" name="obj_id" value="" />
				<div class="add_form" onselectstart="return false;">
					{foreach from=$objs item=obj_ds key=name}
						<h3 class="l1">{$name}</h3>
						<div class="odj_blocks">
							{foreach from=$obj_ds item=obj_type key=name name=add}
								<h3 class="l2">{$name}</h3>
        						<table class="obj_table">
									{foreach_row from=$obj_type item=obj key=id cols=6 table=no}
										{foreach_cell}
											<div class="obj_item" title="{$obj.description|escape}">
												<span>{$obj.name|escape}</span>
												<input type="radio" value="{$id}" class="none" />
											</div>
										{/foreach_cell}
									{/foreach_row}
         						</table>
         						{if !$smarty.foreach.add.last}<hr class="obj" />{/if}
							{/foreach}
						</div>
					{/foreach}
				</div>
				<br/>
				<div class="right_btns">
					<input type="submit" value="{$smarty.const.BTN_ADD_OBJECT}" id="add" name="add" disabled />
					<input type="button" value="{$smarty.const.BTN_CANCEL}" id="close" />
				</div>
			</form>
		</div>
	</body>
</html>