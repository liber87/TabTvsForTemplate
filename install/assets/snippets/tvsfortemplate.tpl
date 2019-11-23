//<?php
/**
 * TvsForTemplate
 *
 * <strong>1.0</strong> Вывод тв-параметров созданных через TabTvsForTemplate
 *
 * @category	snippet
 * @internal	@modx_category Content
 * @internal	@installset base
 * @internal	@overwrite true
 * @internal	@properties {}
 */

$id = isset($id) ? (int)$id : $modx->documentObject['id'];
$template = isset($template) ? (int)$template : $modx->documentObject['template'];

include_once(MODX_BASE_PATH.'assets/snippets/DocLister/lib/DLTemplate.class.php');
$dltpl = DLTemplate::getInstance($modx);

$tpl = isset($tpl) ? $tpl : '@CODE: <p><b>[+caption+]</b>: [+value+]</p>';

$out = '';
$sql = 'SELECT tv.name,tv.caption,tv.description,value FROM '.$modx->getFullTableName('categories').' as cat
left join '.$modx->getFullTableName('site_templates').' as tpl
on cat.category = tpl.templatename
left join '.$modx->getFullTableName('site_tmplvars').' as tv
on tv.category = cat.id
left join '.$modx->getFullTableName('site_tmplvar_contentvalues').' as tvv
on tvv.tmplvarid = tv.id
where tpl.id='.$template.' and tvv.contentid='.$id;

$res = $modx->db->query($sql);
while ($row = $modx->db->getRow($res)) $out.=$dltpl->parseChunk($tpl,$row);	
return $out;
