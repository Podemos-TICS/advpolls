<?php

/*
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

$desc = get_input('description');
$path = get_input('path');
$tags = string_to_tag_array(get_input('tags'));
$access_id = get_input('access_id');
$guid = intval(get_input('guid'));
$access_vote_id = get_input('access_vote_id');
$can_change_vote = get_input('can_change_vote');
$poll_closed = get_input('poll_closed');
$poll = get_entity($guid);
$fecha_inicio = get_input('fecha_inicio');
$fecha_fin = get_input('fecha_fin');


if (!$fecha_fin) {
	$fecha_fin = time() + 31536000 ;
	
	}

if (!$fecha_inicio) {
	$fecha_inicio = time();
	
}

if ($fecha_inicio > $fecha_fin) {
	register_error(elgg_echo('advpoll:error:fechas:mal'));
} else {
	
	//escribimos en base de datos	
	$poll->description = $desc;
	$poll->path = $path;
	$poll->access_id = $access_id;
	$poll->tags = $tags;
	$poll->guid = $guid;
	$poll->end_date = $fecha_fin;
	$poll->start_date = $fecha_inicio;
	$poll->access_vote_id = $access_vote_id;
	$poll->can_change_vote = $can_change_vote;
	
	$guid2 = $poll->save();
	
	if ($guid2){
		system_message(elgg_echo('advpoll:guardada'));
		forward($poll->getURL());
	}
	else {
		register_error(elgg_echo('advpoll:error:guardar'));
		forward(REFERER); // REFERER is a global variable that defines the previous page
	}
}





