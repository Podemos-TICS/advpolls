<?php

elgg_load_library('votaciones:model');
elgg_push_breadcrumb(elgg_echo('votaciones:activas'));

$contexto = get_input('contexto');
$title = elgg_echo('votaciones:titulo');

$votaciones = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'poll',
	'limit' => 0,
	));

$filtradas = elgg_get_votaciones_por_estado($votaciones, $contexto);
$content = elgg_view_entity_list(
	$filtradas,
	$vars = array(), 
	$offset = 0, 
	$limit = 5, 
	$full_view = false, 
	$list_type_toggle = true, 
	$pagination = true
	); 	

elgg_register_title_button('advpoll', 'nueva');

$filtros = elgg_view('advpoll/filtros', array(
	'filter_context' => $contexto,
	'context' => 'votaciones'
	));

// llama a la vista 'content' del core registrada en el archivo
// views/default/pages/layout/content.php
$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => $filtros,
	'filter_context' => $contexto,
	'sidebar' => ''
));


echo elgg_view_page($title, $body);
