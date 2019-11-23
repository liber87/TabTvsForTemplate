<?php
defined('IN_MANAGER_MODE') or die();	
if ($modx->event->name == 'OnTempFormSave') 
{ 		
	
	foreach($_POST['tvadd'] as $tv)
	{
		if ($tv['name'])
		{	
			$tmplvarid = $modx->db->getValue('select id from '.$modx->getFullTableName('site_tmplvars').' where `name`="'.$modx->db->escape($tv['name']).'"');
			if ($tmplvarid)
			{				
				$check = $modx->db->getValue('Select count(*) from '.$modx->getFullTableName('site_tmplvar_templates').' 
				where `tmplvarid`='.$tmplvarid.' and `templateid`='.$id);
				if (!$check) $modx->db->insert(array('tmplvarid'=>$tmplvarid,'templateid'=>$id),$modx->getFullTableName('site_tmplvar_templates'));
			}
			else
			{
				$cat_name = $_POST['templatename'];
				$idcat = $modx->db->getValue('Select id from '.$modx->getFullTableName('categories').' 
				where `category`="'.$modx->db->escape($_POST['templatename']).'"');
				if (!$idcat) $idcat = $modx->db->insert(array('category'=>$modx->db->escape($_POST['templatename'])),$modx->getFullTableName('categories'));
				$tv_insert = array();
				foreach($tv as $key => $val) $tv_insert[$key] = $modx->db->escape($val);
				$tv_insert['category'] = $idcat;
				$tv_insert['createdon'] = time();			
				$tmplvarid = $modx->db->insert($tv_insert,$modx->getFullTableName('site_tmplvars'));
				$modx->db->insert(array('tmplvarid'=>$tmplvarid,'templateid'=>$id),$modx->getFullTableName('site_tmplvar_templates'));
			}
		}		
	}
	
}
if ($modx->event->name == 'OnTempFormRender') 
{ 
//if (!$_GET['id']) return;
$out = <<< OUT
<div class="tab-page" id="tabAddTVs">
	<h2 class="tab">Добавить параметры</h2>
	<script type="text/javascript">tp.addTabPage(document.getElementById("tabAddTVs"));</script>
	<input type="hidden" name="atvsDirty" id="atvsDirty" value="0">
	<div class="container container-body">
		<p>На этой вкладке вы можете добавить новые тв-параметры для данного шаблона</p>
		<div class="row">
			<div class="table-responsive">
				<table class="table data" cellpadding="1" cellspacing="1">
					<thead>
						<tr>					
							<td class="tableHeader">Имя</td>
							<td class="tableHeader">Заголовок</td>
							<td class="tableHeader">Описание</td>
							<td class="tableHeader">Тип</td>
							<td class="tableHeader">Значение по умолчанию</td>
							<td class="tableHeader" width="1%"> </td>					
						</tr>
					</thead>
					<tbody id="node">
						<tr>					
							<td class="tableItem">
								<input name="tvadd[0][name]" type="text" maxlength="45" value="" class="form-control" onchange="documentDirty=true;">
							</td>
							<td class="tableItem">
								<input name="tvadd[0][caption]" type="text" maxlength="45" value="" class="form-control" onchange="documentDirty=true;">
							</td>
							<td class="tableItem">
								<input name="tvadd[0][description]" type="text" maxlength="45" value="" class="form-control" onchange="documentDirty=true;">
							</td>
							
							
							<td class="tableItem">
								<select name="tvadd[0][type]" size="1" class="form-control" onchange="documentDirty=true;">							
									<option value="text" selected="selected">Text</option>									
									<option value="textarea">Textarea</option>									
									<option value="textareamini">Textarea (Mini)</option>
									<option value="richtext">RichText</option>
									<option value="dropdown">DropDown List Menu</option>
									<option value="listbox">Listbox (Single-Select)</option>
									<option value="listbox-multiple">Listbox (Multi-Select)</option>
									<option value="option">Radio Options</option>
									<option value="checkbox">Check Box</option>
									<option value="image">Image</option>
									<option value="file">File</option>
									<option value="url">URL</option>
									<option value="email">Email</option>
									<option value="number">Number</option>
									<option value="date">Date</option>						
								</select>
							</td>
							
							<td class="tableItem">
								<input name="tvadd[0][default_text]" type="text" maxlength="45" value="" class="form-control" onchange="documentDirty=true;">
							</td>
							
							<td class="tableItem">
								<ul class="elements_buttonbar">
									<li><a title="Добавить" class="add_tv" id="addRow"><i class="fa fa-plus fa-fw"></i></a></li>									
								</ul>
							</td>
						</tr>
					</tbody>
				</table>				
			</div>
		</div>		
	</div>	
</div>
<script type="text/javascript">
	i = 1;
	var table = document.getElementById('node');
	table.onclick = function(event) {
		let a = event.target.closest('.add_tv'); 		
		if (!a) 
		{
			let b = event.target.closest('.remove_tv');
			if (b) 
			{				
				event.target.closest('tr').remove();
			}
			return; 		
		}
		
		i = i+1;
		// По-человечески было делать лень =)
		
		var row = document.createElement("tr");
		row.innerHTML = '<td class="tableItem"><input name="tvadd['+i+'][name]" type="text" maxlength="45" value="" class="form-control" onchange="documentDirty=true;"></td><td class="tableItem"><input name="tvadd['+i+'][caption]" type="text" maxlength="45" value="" class="form-control" onchange="documentDirty=true;"></td><td class="tableItem"><input name="tvadd['+i+'][description]" type="text" maxlength="45" value="" class="form-control" onchange="documentDirty=true;"> </td>  <td class="tableItem"> 								<select name="tvadd['+i+'][type]" size="1" class="form-control" onchange="documentDirty=true;">							 									<option value="text" selected="selected">Text</option>									 									<option value="textarea">Textarea</option>									 									<option value="textareamini">Textarea (Mini)</option> 									<option value="richtext">RichText</option> 									<option value="dropdown">DropDown List Menu</option> 									<option value="listbox">Listbox (Single-Select)</option> 									<option value="listbox-multiple">Listbox (Multi-Select)</option> 									<option value="option">Radio Options</option> 									<option value="checkbox">Check Box</option> 									<option value="image">Image</option> 									<option value="file">File</option> 									<option value="url">URL</option> 									<option value="email">Email</option> 									<option value="number">Number</option> 									<option value="date">Date</option>						 								</select> 							</td> <td class="tableItem"> 								<input name="tvadd['+i+'][default_text]" type="text" maxlength="45" value="" class="form-control" onchange="documentDirty=true;"> 							</td> 							<td class="tableItem"> 								<ul class="elements_buttonbar"> 									<li><a title="Добавить" class="add_tv"><i class="fa fa-plus fa-fw"></i></a></li> 									<li><a title="Удалить" class="remove_tv"><i class="fa fa-minus fa-fw"></i></a></li> 								</ul> 							</td>';
		document.getElementById("node").appendChild(row);
	};		
</script>
OUT;
$modx->event->output($out);
}