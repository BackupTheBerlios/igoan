<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: new_project.php,v 1.1 2004/04/08 21:15:55 cam Exp $
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
require_once 'igoan/Branch.class.php';

if (isset($_GET['login']) && isset($_GET['passwd'])) {
	http_redir('/user/login.php?login='.$_GET['login'].':passwd='.$_GET['passwd'].':referer.php');
}

$me = user_get_by_id($_SESSION['id']);

/*
if (isset($_GET['submit'])) {
	// verif des données du champs login si pas logué
	if (!$me) {
		if (!empty($_GET['login']) && !empty($_GET['password'])) {
			$me = user_get_by_password($_GET['login'], $_GET['password']);
		}
	}
	if (!$me) {
		append_error('Login incorrect.');
	} else {
		$_SESSION['id'] = $me->get_id_user();
	}
}
*/

if (isset($_GET['submit']) && $me) {
	// verif shortname valide
	if (isset($_GET['shortname'])) {
		$short = strtolower($_GET['shortname']);
		if (empty($short)) {
			append_error('You must supply a shortname');
		} else for ($i=0; $i<strlen($short); $i++) {
			if ((($short[$i] < '0') or ($short[$i] > '9')) and (($short[$i] < 'a') or ($short[$i] > 'z'))) {
				append_error('Bad shortname. Please use only letters or numbers.');
				break;
			}
		}
	}

	if (!isset($_GET['name']) or empty($_GET['name'])) {
		append_error('You must supply a project name.');
	}
	if (!isset($_GET['description']) or empty($_GET['description'])) {
		append_error('You must supply a description.');
	}

	// si pas d'erreur,
	if (!errors()) {
		sql_do('BEGIN');
		$id_prj = project_new($_GET['name'], $short, $_GET['description'], $_GET['homepage']);
		if (!$id_prj) {
			sql_do('ROLLBACK');
			append_error('Unable to create the project, please verify the informations.');
		}
	}
	if (!errors()) {
		$prj = project_get_by_id($id_prj);
		if (!$prj) {
			sql_do('ROLLBACK');
			append_error('Unable to retrieve the created project, please contact the administrator.');
		}
	}
	// on place un admin/owner
	if (!errors()) {
		$prj->add_admin($me->get_id_user(), 1);
	}
	// on crée une branche ...
	if (!errors()) {
		$id_branch = branch_new('main', $prj->get_id_prj());
		if (!$id_branch) {
			sql_do('ROLLBACK');
			append_error('Unable to create the default branch, please contact the administrator.');
		}
	}
	// ... par défaut
	if (!errors()) {
		$prj->set_default_branch($id_branch);
		$prj->write();
	}
	if (!errors()) {
		sql_do('COMMIT');
		http_redir('/project/view.php?id_prj='.$id_prj);
	} else {
		sql_do('ROLLBACK');
	}
}

header_box("Igoan :: Adding a new project");

flush_errors();
?>
<div id="main">
	<form class="admin" action="new_project.php">
	<h2> Adding a project </h2>
	<div class="abstract">
		<p>
			Adding a project to our database is as easy as 1, 2, 3:
		</p>
		<ol>
			<li><em> Register, and then log in :)</em></li>
			<li><em> Tell us about your project: select a project name, add a description and an homepage. </em></li>
			<li><em> Submit ! </em></li>
		</ol>
	</div>
	<h2> Register / log in </h2>
	<div class="description">
	<?php
		if ($me)
		{
			echo '<p> You are currently logged in as ' . $me->get_name_user();
			echo '.<br />You can proceed to the next step as this user,';
			echo ' or <a href="/user/logout.php?referer=/project/new_project.php">logout</a> in order to register/login as a another user.</p>';
		}
		else
		{
			echo '<p> If you already have an account, enter your login/password in the fields below. <br />';
			echo 'If you don\'t, please <a href="/user/new.php">register</a>. </p>';
			echo '<div class="block">';
			echo '<label for="login"> Login: </label>';
			echo '<input title="Your igoan login." id="login" name="login" type="text" value="'.(!empty($_GET['login'])?$_GET['login']:"").'"/>';
			echo '<br />';
			echo '<label for="password"> Password: </label>';
			echo '<input title="Your igoan password." id="password" name="password" type="password" />';
			echo '</div>';
		}
	
	?>
	</div>
	<h2> So, what is your project about ? </h2>
	<div class="description">
		<p style="margin-bottom: 0.5em"> We need 2 names: a project name, which is the full name for, erm, your project :), and a short name,
		    which we will use in URLs for instance. </p>
		<div class="block">
			<label for="shortname"> Short project name: </label>
			<input title="Short, easy-to-remember name, with alphanumeric characters only, i.e. 'coolproject'." id="shortname" name="shortname" type="text" value="<?php if (isset($_GET['shortname'])) echo $_GET['shortname']; ?>" />
			
		</div>
		<div class="block">
			<label for="name"> Full project name: </label>
			<input title="Whatever you like, i.e. 'My Cool Project'." id="name" name="name" type="text" value="<?php if (isset($_GET['name'])) echo $_GET['name']; ?>" />
		</div>
		<div class="block">
			<label for="homepage"> Homepage: </label>
			<input title="URL for your project as it will be displayed by us, i.e. 'http://www.myisp.net/mycoolproject/home.html'." id="homepage" name="homepage" type="text" value="<?php if (isset($_GET['homepage'])) echo $_GET['homepage']; ?>" />
			
		</div>
		<div class="block">
			<label for="description"> Description: </label>
			<textarea title="A few lines to describe your project." cols="40" rows="7" id="description" name="description"><?php if (isset($_GET['description'])) echo $_GET['description']; ?></textarea>
			
		</div>
		<p style="margin-top: 0.5em; font-size: 85%"> All fields except homepage are mandatory. Use plain text everywhere, any HTML code will be stripped. </p>
	</div>
	<h2> Submit </h2>
	<div class="misc" style="color: #000000; padding-top: 0;">
		<p> Everybody likes submit buttons ! This one add your project to our database, and creates a default
		branch for it. <br /> You'll be able to modify everything, add branches, releases... later in the admin
		page. </p>
		<div class="block">
			<input type="submit" id="submit" name="submit" value="Submit !" />
		</div>
	</div>
	</form>
</div>
<?php
footer_box();
?>
