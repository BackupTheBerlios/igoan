<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: projects.php,v 1.1 2004/04/08 21:15:23 cam Exp $
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

require_once 'igoan/User.class.php';
require_once 'igoan/Project.class.php';

$me = user_get_by_id($_SESSION['id']);
if (!$me)
	append_error_exit('User ID inexistent.');

if (!$me->is_global_admin()) {
	append_error_exit('Permission denied: global admin flag required');
}
?>
<h1>Gestion des projets</h1>

<p>
Voici la liste complète des projets.
</p>

<?php
$projs = project_get_all();

if (!count($projs)) {
	echo '<p><em>Il n\'y a pas de projet dans la base actuellement.</em></p>';
} else {
	echo '<table><tr><th>id_prj</th><th>name_prj</th><th>shortname</th><th>homepage</th><th>date_prj</th><th>description</th></tr>';
	foreach ($projs as $id_prj) {
		$prj = project_get_by_id($id_prj);
		echo '<tr><td>'.$id_prj.'</td><td>'.$prj->get_name_prj().'</td><td>'.$prj->get_shortname().'</td><td>'.$prj->get_url_prj().'</td><td>'.$prj->get_date_prj().'</td><td>'.$prj->get_desc_prj().'</td></tr>';
		$admins = $prj->list_admins();
		echo "poof";
		foreach ($admins as $id_adm) {
			$adm = user_get_by_id($id_adm);
			echo '<tr><th>Admin</th><td colspan="5">'.$adm->get_name_user().'</td></tr>';
		}
	}
	echo '</table>';
}
?>
