<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: new_branch.php,v 1.1 2004/04/08 21:15:35 cam Exp $
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
require_once 'igoan/Branch.class.php';
require_once 'igoan/User.class.php';

// PRELIMINARIES

$me = user_get_by_id($_SESSION['id']);

if (!$me) {
	append_error_exit('You must be logged to add a new branch.');
}

$id_prj = empty($_GET['id_prj']) ? 0 : $_GET['id_prj'];

if (!$id_prj) {
	append_error_exit('You have to specify a project.');
}

$prj = project_get_by_id($id_prj);

if (!$prj) {
	append_error_exit('Invalid project number #'.$id_prj.'.');
}

if (!$prj->is_admin($me->get_id_user())) {
	append_error_exit('Sorry, you are not an admin for this project.');
}

// ADDING A BRANCH

if (!empty($_GET['name_branch'])) {
	$id_branch = branch_new($_GET['name_branch'], $prj->get_id_prj());
	$branch = branch_get_by_id($id_branch);
	if (!$branch) {
		append_error('Unable to create a new branch');
	}
	if (!errors()) {
		http_redir('/project/view.php?id_branch='.$branch->get_id_branch());
	}
}
?>



<?php

// OUTPUT

header_box("Igoan :: Adding a new branch to a project");

flush_errors();
?>
<div id="main">
	<form class="admin" action="new_branch.php">
	<input type="hidden" name="id_prj" value="<?php echo $id_prj; ?>" />
	<h2> Adding a new branch to a project </h2>
	<div class="abstract">
		<p>
			Blabla, introduction qui explique a quoi sert une branche, tout ça ...
		</p>
	</div>
	<h2> Filling informations </h2>
	<div class="description">
		<p style="margin-bottom: 0.5em">
			You are about adding a new branch to the project "<?php echo $prj->get_name_prj(); ?>".
		</p>
		<p style="margin-bottom: 0.5em">
			A branch has a name. The default branch is already named "main". Examples of others names are: "devel", "doc", "unstable"...
		</p>
		<div class="block">
			<label for="branch_name"> Branch name: </label>
			<input title="The name of the branch" id="branch_name" name="name_branch" type="text" value="<?php if (isset($_GET['branch_name'])) echo $_GET['branch_name']; ?>" />
			
		</div>
	</div>
	<h2> Submit </h2>
	<div class="misc" style="color: #000000; padding-top: 0;">
		<p>
		Everybody likes submit buttons ! This one add the branch to your project in our database. <br />
		You may want to add some "maintainer" to allow users to add releases in this branch.
		</p>
		<div class="block">
			<input type="submit" id="submit" name="submit" value="Submit !" />
		</div>
	</div>
	</form>
</div>

<?php
footer_box();
?>
