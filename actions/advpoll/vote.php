<?php
/**
 * mod/advpoll/actions/vote.php
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
elgg_load_library('advpoll:model');
$guid = get_input('guid');
$poll = get_entity($guid);
$owner_guid = get_input('owner_guid');
$access_id = $poll->access_id;
$choices = polls_get_choice_array($poll);
$user = elgg_get_logged_in_user_guid();
$access_col = get_access_array($user);
$access_vote_id = $poll->access_vote_id;

if (!is_poll_on_date($poll)) {
	register_error(elgg_echo('advpoll:accion:advpoll:cerrada'));
} else {
	if (!in_array($access_vote_id, $access_col)) {
		register_error(elgg_echo('advpoll:accion:error:permisos'));
	} else {
		if (user_has_voted($user, $guid) && !$poll->can_change_vote) {
			register_error(elgg_echo('advpoll:accion:error:cant_change_vote'));
		} else {
			if ($poll->poll_type == 'normal') {
				$choice = get_input('response');
				$candidate_entity = get_entity($choice);
				foreach ($choices as $vote_guid){
					if (remove_annotation_by_entity_guid_user_guid('vote', $vote_guid, $owner_guid)){
						system_message(elgg_echo('advpoll:anteriores:borradas:ok'));
					}
				}
				
				if ($candidate_entity->annotate('vote', 1, $access_id, $owner_guid, 'int')){
					system_message(elgg_echo('advpoll:accion:voto:ok'));
				}
			} else { // condorcet
				$ballot = ballot_matrix($choices, get_input('opciones'));
				$str_ballot = ballot_matrix_to_string($ballot);
				
				if (remove_annotation_by_entity_guid_user_guid('vote_condorcet', $guid, $owner_guid)) {
					system_message(elgg_echo('advpoll:anteriores:borradas:ok'));
				}
				if ($poll->annotate('vote_condorcet', "$str_ballot", $access_id, $owner_guid)){
					system_message(elgg_echo('advpoll:accion:voto:ok'));
				}
			}
		}
	}
}
