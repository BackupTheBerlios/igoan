<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: index.php,v 1.1 2004/04/08 21:15:58 cam Exp $
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

require_once 'igoan/Project.class.php';
require_once 'igoan/User.class.php';

$me = user_get_by_id($_SESSION['id']);
$result = sql_do('SELECT id_prj FROM projects');

// show the data (NO PROCESSING HERE PLEASE, ONLY ECHOs)
header_box('Igoan :: The Free Directory Project');
flush_errors(); ?>
<div id="main"><?php
// these are the "stuff" section
login_box($me);
//categories_box();
?>
</div>

<div class="item soft">

<h2>Projects of the month:</h2>

<ul>
<?php

if (($result->numRows() == 0)) {
	echo '<li>Sorry, no project in the database.</li>';
} else for ($i=0; $i<$result->numRows(); $i++) {
	$row = $result->fetchRow();
	$prj = project_get_by_id($row[0]);
	echo '<li><a href="/project/view.php?id_prj='.$prj->get_id_prj().'">'.$prj->get_name_prj().'</a></li>';
}
?>
</ul>
</div>
</div>
<?php footer_box(); ?>
