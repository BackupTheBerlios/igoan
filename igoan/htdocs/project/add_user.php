<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: add_user.php,v 1.1 2004/04/08 21:15:38 cam Exp $
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
require_once 'igoan/Release.class.php';
require_once 'igoan/User.class.php';

// PRELIMINARIES

$me = user_get_by_id($_SESSION['id']);

if (!$me) {
	append_error_exit('You must be logged to do this.');
}

unset ($login);
unset ($rel);
unset ($prj);
unset ($branch); // des fois qu'il trainerait un register_global qqpart

if (!empty($_GET['login'])) {
	$login = user_get_by_login($_GET['login']);
}

if (isset($_GET['id_prj'])) { # we add an admin
	$id_prj = $_GET['id_prj'];
	$prj = project_get_by_id($id_prj);
	if (!$prj) {
		append_error_exit('Invalid project number #'.$id_prj.'.');
	}
	if (!$prj->is_admin($me->get_id_user())) {
		append_error_exit('Sorry, you are not an admin for this project.');
	}
	// ACTION
	if (isset($login)) {
		if (!$login) {
			append_error('Login "'.$_GET['login'].'" doesn\'t exist.');
		} else {
			$prj->add_admin($login->get_id_user());
			http_redir('/project/view.php?id_prj='.$prj->get_id_prj());
		}
	}
} else if (isset($_GET['id_branch'])) { # we add a maintainer
	$id_branch = $_GET['id_branch'];
	$branch = branch_get_by_id($id_branch);
	if (!$branch) {
		append_error_exit('Invalid branch number #'.$id_branch.'.');
	}
	$prj = project_get_by_id($branch->get_id_prj());
	if (!$prj) {
		append_error_exit('Unable to fetch project informations.');
	}
	if (!$prj->is_admin($me->get_id_user())) {
		append_error_exit('Sorry, you are not an admin for this project.');
	}
	// ACTION
	if (isset($login)) {
		if (!$login) {
			append_error('Login "'.$_GET['login'].'" doesn\'t exist.');
		} else {
			$branch->add_maintainer($login->get_id_user());
			http_redir('/project/view.php?id_branch='.$branch->get_id_branch());
		}
	}
} else if (isset($_GET['id_rel'])) { # we add an author
	$id_rel = $_GET['id_rel'];
	$rel = release_get_by_id($id_rel);
	if (!$rel) {
		append_error_exit('Invalid release number #'.$id_release.'.');
	}
	$branch = branch_get_by_id($rel->get_id_branch());
	if (!$branch) {
		append_error_exit('Unable to fetch branch infos.');
	}
	$prj = project_get_by_id($branch->get_id_prj());
	if (!$prj) {
		append_error_exit('Unable to fetch project informations.');
	}
	if (!$branch->is_maintainer($me->get_id_user()) && !$prj->is_admin($me->get_id_user())) {
		append_error_exit('Sorry, you are not a maintainer for this project.');
	}
	// ACTION
	if (isset($login)) {
		if (!$login) {
			append_error('Login "'.$_GET['login'].'" doesn\'t exist.');
		} else {
			$rel->add_author($login->get_id_user());
			http_redir('/project/view.php?id_rel='.$rel->get_id_rel());
		}
	} else if (isset($_GET['name']) && isset($_GET['email'])) {
		if (empty($_GET['name']))
			append_error('The name is mandatory.');
		if (empty($_GET['email']))
			append_error('The e-mail address is mandatory.');
		if (!errors())
			$user_id = user_new_pseudo($_GET['name'], $_GET['email']);
		if (!errors()) {
			$user = user_get_by_id($user_id);
			if (!$user)
				append_error('Unable to create the pseudo-user '.$_GET['name']);
		}
		if (!errors()) {
			$rel->add_author($user->get_id());
			http_redir('/project/view.php?id_rel='.$rel->get_id_rel());
		}
	}
} else {
	append_error_exit('No action specified.');
}

?>



<?php

// OUTPUT

header_box('Igoan :: Adding a user as a project member');

flush_errors();
?>
<div id="main">
	<form class="admin" action="add_user.php"><?php

// case of 'AUTHOR'
if (isset($rel)) { ?>

	<input type="hidden" name="id_rel" value="<?php echo $id_rel; ?>" />
	<h2> Adding an author to a release </h2>
	<div class="abstract">
		<p>
			Blabla, introduction qui explique ce qu'est un auteur, tout ça ...
		</p>
	</div>
	<h2> Filling informations </h2>
	<div class="description">
		<p style="margin-bottom: 0.5em">
			You are about adding an author to the release: "<?php echo $prj->get_shortname(); ?>-<?php echo $branch->get_name_branch(); ?>: <?php echo $rel->get_name_rel(); ?>".
		</p>
		<p style="margin-bottom: 0.5em">
			If the user already exists, please enter its Igoan login name:
		</p>
		<div class="block">
			<label for="login"> Existing login name: </label>
			<input title="" id="login" name="login" type="text" value="<?php if (isset($_GET['login'])) echo $_GET['login']; ?>" />
		</div>
		<p style="margin-bottom: 0.5em">
			If the user isn't registered in Igoan, you have to do this for him. Just enter his name and email:
		</p>
		<div class="block">
			<label for="name"> User's name: </label>
			<input title="" id="name" name="name" type="text" value="<?php if (isset($_GET['name'])) echo $_GET['name']; ?>" />
		</div>
		<div class="block">
			<label for="email"> User's email: </label>
			<input title="" id="email" name="email" type="text" value="<?php if (isset($_GET['email'])) echo $_GET['email']; ?>" />
		</div>
	</div><?php

} else if (isset($branch)) { ?>

	<input type="hidden" name="id_branch" value="<?php echo $id_branch; ?>" />
	<h2> Adding a maintainer to a branch </h2>
	<div class="abstract">
		<p>
			Blabla, introduction qui explique ce qu'est un mainteneur, tout ça ...
		</p>
	</div>
	<h2> Filling informations </h2>
	<div class="description">
		<p style="margin-bottom: 0.5em">
			You are about adding a maintainer to the branch: "<?php echo $prj->get_shortname(); ?>-<?php echo $branch->get_name_branch(); ?>".
		</p>
		<p style="margin-bottom: 0.5em">
			Please enter the maintainer Igoan login name:
		</p>
		<div class="block">
			<label for="login"> Login name: </label>
			<input title="" id="login" name="login" type="text" value="<?php if (isset($_GET['login'])) echo $_GET['login']; ?>" />
		</div>
	</div><?php

} else if (isset($prj)) { ?>

	<input type="hidden" name="id_prj" value="<?php echo $id_prj; ?>" />
	<h2> Adding an admin to a project </h2>
	<div class="abstract">
		<p>
			Blabla, introduction qui explique ce qu'est un admin, les risques, tout ça ...
		</p>
	</div>
	<h2> Filling informations </h2>
	<div class="description">
		<p style="margin-bottom: 0.5em">
			You are about adding an admin for the project: "<?php echo $prj->get_shortname(); ?>".
		</p>
		<p style="margin-bottom: 0.5em">
			Please enter the admin Igoan login name:
		</p>
		<div class="block">
			<label for="login"> Login name: </label>
			<input title="" id="login" name="login" type="text" value="<?php if (isset($_GET['login'])) echo $_GET['login']; ?>" />
		</div>
	</div><?php

} else {
	append_error_exit('what\'s the fuck !!?');
} ?>
	<h2> Submit </h2>
	<div class="misc" style="color: #000000; padding-top: 0;">
		<p>
		Everybody likes submit buttons ! Blah.
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
