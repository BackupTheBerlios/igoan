<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: modify_project.php,v 1.1 2004/04/08 21:15:44 cam Exp $
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

// existence du projet
if (!isset($_GET['idPrj']) || ($_GET['idPrj'] != 456789)) {
	echo 'Bad Project ID<br />';
	echo '<a href="/user/index.php">Back to user configuration</a> || <a href="/index.php">Back home</a>';
	exit;
}

// permission du visiteur sur le projet
/*
if ($_SESSION['id'] != 34567) {
	append_error('Can\'t modify project: permission denied');
	http_redir('/project/view_project.php?idPrj=456789');
} */

flush_errors();
if (isset($_GET['action']) && ($_GET['action'] == "Apply")) {

if (!isset($_GET['description']) || !isset($_GET['homepage']) || !isset($_GET['screenshot']) || !isset($_GET['download'])) {
	append_error('Invalid arguments');
} else if (empty($_GET['homepage'])) {
	append_error('Warning: Homepage is mandatory!');
	http_redir('/project/modify_project.php?idPrj=456789');
} else {
	// do the job: modify the data
	http_redir('/project/view_project.php?idPrj=456789');
}

} else if (isset($_GET['action']) && ($_GET['action'] == "Add an admin for this project")) { ?>

<h1>Project Administration: GraphTool (<?php echo $_GET['idPrj']; ?>)</h1>

<h2>Add a developer</h2>
<p>Add a user by ID:</p>
<form>
<input type="text" name="byid" /><input type="submit" value="Add" />
</form>

<a href="/user/add_user.php">Add a new user</a>

<?php } else { 
	flush_errors();
?>

<h1>Project Administration: GraphTool (<?php echo $_GET['idPrj']; ?>)</h1>

<?php flush_errors(); ?>
<form>
<table>
<tr><th>Name</th><td>GraphTool (456789)</td></tr>
<tr><th>Description</th><td><textarea name="description" cols="60" rows="5">GraphTool is a tool for the 
creation and visual edition of any kind of graph written in Python.</textarea></td></tr>
<tr><th>Homepage</th><td><input type="text" size="60" name="homepage" value="http://www.nongnu.org/graphtool/" /></td></tr>
<tr><th>Screenshot</th><td><input type="text" size="60" name="screenshot" value="http://www.nongnu.org/graphtool/screenshots/color.png" /></td></tr>
<tr><th>Download URL</th><td><input type="text" size="60" name="download" value="http://www.nongnu.org/" /></td></tr>
<tr><td colspan="2"><input type="submit" name="action" value="Apply" /></td></tr>
</table>
<input type="hidden" name="idPrj" value="456789" />
</form>

<?php
}
?>
<br /><br/><hr />
<a href="/project/view_project.php?idPrj=456789">Back to project view</a> || <a href="/user/view_user.php">Back to user page</a> || <a href="/">Back to homepage</a>
