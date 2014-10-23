<?php
/**
 * Polls plugin for elgg-1.8
 * Copyright 2012 Lorea, DRY Team
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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

elgg_load_library('advpoll:model');
$poll = get_entity((int)get_input('guid'));
if (elgg_instanceof($poll, 'object', 'advpoll')) {
    $username = elgg_get_logged_in_user_entity()->username;
    $candidate = $poll->getCandidates($username);
    if (count($candidate)) {
        $candidate[0]->delete();
    } else {
        $poll->addCandidates(array($username));
    }
    system_message(elgg_echo('advpoll:candidature:success'));
} else {
    register_error(elgg_echo('advpoll:candidature:error'));
}
forward(REFERER);