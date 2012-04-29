<?php
/**
 * Edit / add a poll
 *
 * @package Polls18
 *
 * Copyright 2012 DRY Team
 *              - aruberuto
 *              - joker
 *              - *****
 *              y otros
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 */

// once elgg_view stops throwing all sorts of junk into $vars, we can use extract()
elgg_load_library('votaciones:model');
$guid = get_input('guid');
$votacion = elgg_extract('entity', $vars, null);

$title = $votacion->title;
$desc = $votacion->description;
$access_id = $votacion->access_id;
$tags = $votacion->tags;
$container_guid = $votacion->container_guid;

$path = $votacion->path;

$auditoria = $votacion->auditoria;
$group = get_entity($container_guid);
$fecha_inicio = $votacion->fecha_inicio;
$fecha_fin = $votacion->fecha_fin;

/**
$group = get_entity($container_guid);
$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$tags = elgg_extract('tags', $vars, '');
$container_guid = elgg_extract('container_guid', $vars, elgg_get_page_owner_guid());
$guid = elgg_extract('guid', $vars, null);
$votacion = elgg_extract('entity', $vars, null);
$path = elgg_extract('path', $vars, '');
$poll_cerrada = elgg_extract('poll_cerrada', $vars, 'no');
$auditoria = elgg_extract('auditoria', $vars, 'no');
*/
if ($votacion){
	$opciones = polls_get_choice_array($votacion);
} else {
	$opciones = array();
}

	
$num_opciones = count($opciones);
?>

<div>
	<label><?php echo elgg_echo('votaciones:pregunta'); ?></label><br />
	<label><?php echo elgg_view('output/text', array('value' => $title)); ?></label><br />
	<?php echo elgg_echo('votaciones:advertencia:editar:titulo'); ?>
</div>

<div>
	<label><?php echo elgg_echo('votaciones:discusion:previa'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'path', 'value' => $path)); 
	//TODO Modificarlo para enviar una lista de las páginas de discusión de
	// la votacion en el grupo?>
</div>
<div>
	<label><?php echo elgg_echo('description'); ?></label>
	<?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => $desc)); ?>
</div>
<div>
	<label><?php echo elgg_echo('tags'); ?></label>
	<?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?>
</div>
<?php

$categories = elgg_view('input/categories', $vars);
if ($categories) {
	echo $categories;
}

?>


<?php 
/**
 * <div id="opciones"><?php echo elgg_view('input/button', array('id' => 'nueva_opcion', 'value' => elgg_echo('votaciones:nueva:opcion')));?>
 * <label><?php echo elgg_echo('votaciones:opciones'); ?></label><br />
 * <?php 
 */
 ?>
<div>
	<label><?php echo elgg_echo('votaciones:opciones'); ?></label><br />
</div>

<div><ul class='choices_ul'>
	<?php
	$i = 0;
	foreach ($opciones as $opcion_guid) {
		$opcion = get_entity($opcion_guid);
		$value = $opcion->text; ?>
		<li><?php echo elgg_view('output/text', array('value' => $value)); ?></li>
		<?php
		$i = $i+1;
	}
	?>
</ul></div>

</div>
<div> 
	<?php echo elgg_echo('votaciones:advertencia:editar'); ?>
</div>
<div>
	<label><?php echo elgg_echo('access'); ?></label><br />
	<?php echo elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id)); ?>
</div>

<div>
	<label><?php echo elgg_echo('votaciones:acceso:votar'); ?></label><br />
	<?php echo elgg_view('input/access', array('name' => 'access_votar_id', 'value' => $access_id)); ?>
</div>

<div>
	<label><?php echo elgg_echo('votaciones:fecha:inicio'); ?>
	<?php echo elgg_view('input/date', array(
		'name' => 'fecha_inicio',
		'value' => $fecha_inicio,
		'timestamp' => true,
		'class' => 'fecha-continua',
		)); ?>
	<?php echo elgg_echo('votaciones:fecha:fin'); ?>
	<?php echo elgg_view('input/date', array(
		'name' => 'fecha_fin',
		'value' => $fecha_fin,
		'timestamp' => true,
		'class' => 'fecha-continua',
		)); ?>
		</label><br>
	<?php echo elgg_echo('votaciones:fecha:ayuda'); ?>
</div>

<?php /**
<div>
	<label><?php echo elgg_echo('votaciones:cerrada'); ?></label><br />
	<?php echo elgg_view('input/radio', array(
		'name' => 'poll_cerrada',
		 'options' => array(
			elgg_echo('option:no') => 'no' ,
			elgg_echo('option:yes') => 'yes',
			),
		'value' => $poll_cerrada,
		)); ?>
</div>
*/?>

<div>
	<label><?php echo elgg_echo('votaciones:auditoria'); ?></label><br />
	<label><?php echo elgg_echo("option:$auditoria"); ?></label><br />
	<?php echo elgg_echo('votaciones:advertencia:editar:auditoria'); ?><br />
	
</div>
<div>
	<label><?php echo elgg_echo('votaciones:mostrar:resultados:durante'); ?></label><br />
	<label><?php echo elgg_echo("option:$mostrar_resultados"); ?></label><br />
	<?php echo elgg_echo('votaciones:advertencia:editar:mostrar:resultados'); ?><br />
	
</div>


<div class="elgg-foot">
<?php

//echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
echo elgg_view('input/hidden', array('name' => 'num_opciones', 'id' => 'num_opciones', 'value' => $num_opciones));

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));
}

echo elgg_view('input/submit', array('value' => elgg_echo("save")));

?>
</div>





