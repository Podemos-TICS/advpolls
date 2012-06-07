<?php

elgg_load_library('advpoll:model');
$votacion = $vars['entity'];

$auditoria = $votacion->auditoria;
$tipo = $votacion->poll_tipo;
$titulo = $votacion->title;
$desc = $votacion->description;
$path = $votacion->path;
$acces_id = $votacion->access_id;
$owner_guid = $votacion->owner_guid;
$container_guid = $votacion->container_guid;
$tags = $votacion->tags;
$choices = polls_get_choice_array($votacion);
$full = elgg_extract('full_view', $vars, FALSE);
$owner =  $votacion->getOwnerEntity();
$fecha_inicio = $votacion->fecha_inicio;
$fecha_fin = $votacion->fecha_fin;
$time = time();
$mostrar_resultados = $votacion->mostrar_resultados;
if ($time < $fecha_fin ) {
	$poll_comparada_fin = 'menorfin';
} else {
	if ($time >= $fecha_fin ){
		$poll_comparada_fin = 'mayorfin';
	}
}

if ($time < $fecha_inicio) {
	$poll_comparada_ini = 'menorini';
}else {
	if ($time >= $fecha_inicio ){
		$poll_comparada_ini = 'mayorini';
	}
}


$entity_icon = elgg_view_entity_icon($votacion, 'small');

$metadata = elgg_view_menu('entity', array(
	'entity' => $votacion,
	'handler' => 'advpoll',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));


	
	$url = $votacion->path;
	$display_text = $url;
	$excerpt = elgg_get_excerpt($votacion->description);
	if ($excerpt) {
		$excerpt = " - $excerpt";
	}

	
	$link = elgg_get_site_url() . "profile/" . $owner->name ;
	$subtitle = elgg_echo('advpoll:trujaman');
	$subtitle .= elgg_view('output/url', array(
		'href' => $link,
		'text' => $owner->name,
	));
	
	
	
	$subtitle .= '<br>' . elgg_view('output/url', array(
		'href' => $votacion->path,
		'text' => elgg_echo('advpoll:debate:previo:link'),
	));

	$subtitle .= "<br>" . elgg_echo('advpoll:vistazo:finalizada:' . $poll_comparada_fin . ':' . $poll_comparada_ini ) . ',';
	if ($poll_comparada_ini == 'menorini') {
	$subtitle .= elgg_echo('advpoll:vistazo:tiempo:desde') . date('d - M - Y', $fecha_inicio) . ', ';
} 
	if ($poll_comparada_fin == 'menorfin') {
	$subtitle .= elgg_echo('advpoll:vistazo:tiempo:hasta') .date('d - M - Y', $fecha_fin);
} 
	$subtitle .= elgg_echo('advpoll:vistazo:auditoria') . elgg_echo('option:' . $auditoria) . ',';
	$subtitle .= elgg_echo('advpoll:vistazo:tipo') . elgg_echo('advpoll:tipo:' . $tipo) . ',';
	$subtitle .= elgg_echo('advpoll:vistazo:mostrar:resultados') . elgg_echo('advpoll:mostrar:' . $mostrar_resultados) . '.';

	
	$content .= elgg_view('votaciones/choices', array('choices' => $choices));
	$params = array(
		'entity' => $votacion,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $content,
	);
	$params = $params + $vars;
	

if ($full) {
	$body = elgg_view('output/longtext', array('value' => $votacion->description));
	$entity_icon = elgg_view_entity_icon($votacion, 'small');
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', array(
		'entity' => $votacion,
		'title' => '',
		'icon' => $entity_icon,
		'summary' => $summary,
		'body' => $body,
	));

} else {
	$body = elgg_view('object/elements/summary', $params);
	
	echo elgg_view_image_block($entity_icon, $body);
}

