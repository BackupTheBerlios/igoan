<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: users.php,v 1.1 2004/04/08 21:15:26 cam Exp $
#
# This file is part of the Igoan project.
#
# Igoan is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation in the version 2 of the License.
#
# Igoan is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Igoan; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#
?>
<?php

require_once 'igoan/User.class';

$me = user_get_by_id($_SESSION['id']);
if (!$me)
	append_error_exit('User ID inexistent.');

if (!$me->is_global_admin()) {
	append_error_exit('Permission denied: global admin flag required');
}
?>
<h1>Gestion des users</h1>

<p>
Voici la liste complète des utilisateurs.
</p>

<?php
$users = user_get_all();

if (!count($users)) {
	echo '<p><em>Il n\'y a pas d\'utilisateur dans la base actuellement.</em></p>';
} else {
	echo '<table><tr><th>id_user</th><th>name_user</th><th>mail</th><th>url</th><th>login</th><th>passwd</th></tr>';
	foreach ($users as $user) {
		echo "<tr><td>{$user->id_user}</td><td>{$user->name_user}</td><td>{$user->mail}</td><td>{$user->url}</td><td>{$user->login}</td><td>{$user->passwd}</td></tr>";
	}
	echo '</table>';
}
?>
