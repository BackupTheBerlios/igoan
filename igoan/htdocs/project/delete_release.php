<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: delete_release.php,v 1.1 2004/04/08 21:15:47 cam Exp $
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

// existence de la release
if (!isset($_GET['idRel']) || ($_GET['idRel'] != 123460)) {
	append_error_exit('Bad Release ID');
}

if (isset($_GET['confirm']) && ($_GET['confirm'] == 'Yes')) {
	// do the job
	append_error('Release successfully deleted');
	http_redir('/project/view_project.php?idPrj=456789');
}

?>

<h1>Release Deletion</h1>

Do you really want to delete this release 'GraphTool' version 'graphtool-0.2-pre1' ?
<form>
	<input type="submit" name="confirm" value="Yes" />
	<input type="hidden" name="idRel" value="123460" /></form>
<form action="/project/view_release.php">
	<input type="submit" value="No" />
	<input type="hidden" name="idRel" value="123460" /></form>
	
<br /><br/><hr />
<a href="/project/view_project.php?idPrj=456789">Back to project view</a> || <a href="/">Back to homepage</a>
