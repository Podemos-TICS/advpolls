<?php

elgg_load_library('votaciones:model');
$votacion = $vars['entity'];
$titulo = $votacion->title;
$desc = $votacion->description;
$path = $votacion->path;
$acces_id = $votacion->access_id;
$owner_guid = $votacion->owner_guid;
$container_guid = $votacion->container_guid;
$tags = $votacion->tags;
$choices = polls_get_choice_array($votacion);

/**
echo "<br />";
echo $titulo;
echo "<br />";
echo $desc;
echo "<br />";
echo $path;
echo "<br />";
echo $acces_id;
echo "<br />";
echo $owner_guid;
echo "<br />";
echo $container_guid;
echo "<br />";
echo $tags;
echo "<br />";
*/

$full = elgg_extract('full_view', $vars, FALSE);
$owner =  $votacion->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'tiny');

$metadata = elgg_view_menu('entity', array(
	'entity' => $votacion,
	'handler' => 'votaciones',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));


	
	$url = $votacion->path;
	$display_text = $url;
	$excerpt = elgg_get_excerpt($votacion->description);
	if ($excerpt) {
		$excerpt = " - $excerpt";
	}

	$link = elgg_view('output/url', array(
		'href' => $votacion->path,
		'text' => elgg_echo('votaciones:debate:previo:link'),
	));

	
	
	$subtitle = elgg_view('votaciones/choices', array('choices' => $choices));

	$params = array(
		'entity' => $votacion,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $link,
	);
	$params = $params + $vars;
	

if ($full) {
	$body = elgg_view('output/longtext', array('value' => $votacion->description));
	$owner_icon = elgg_view_entity_icon($owner, 'small');
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', array(
		'entity' => $votacion,
		'title' => '',
		'icon' => $owner_icon,
		'summary' => $summary,
		'body' => $body,
	));

} else {
	$body = elgg_view('object/elements/summary', $params);
	
	echo elgg_view_image_block($owner_icon, $body);
}
