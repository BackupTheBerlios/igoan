<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: layout.fct.php,v 1.1 2004/04/08 21:17:40 cam Exp $
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

// FIXME: à implémenter correctement:
function login_box($me) {
?>
	<div class="stuff">
	<div class="login"><?php
if ($me) { ?>
		<h4> Logged as <?php echo $me->get_login(); ?> </h4>
	<div class="loggued">
		<p>
			<a href="/user/view.php" title="Go to my personal page">My personal page</a><br />
			<a href="/user/edit.php" title="Edit my own informations">Edit my infos</a><br />
			<!-- <a href="/user/logout.php?referer=<?php echo htmlentities(urlencode($_SERVER['REQUEST_URI'])); ?>" title="Logout">Logout</a><br /> -->
			<a href="/user/logout.php" title="Logout">Logout</a><br />
		</p><?php
$projects_id = $me->list_projects();
if ($projects_id) {
	$my_projects = '<h5> My projects: </h5><ul>';
	foreach ($projects_id as $id_prj) {
		$prj = project_get_by_id($id_prj);
		if (!$prj) continue;
		$my_projects .= '<li><a href="/project/view.php?id_prj='.$prj->get_id_prj().'">'.$prj->get_name_prj().'</a><br />';
		$my_projects .= '</li>';
	}
	echo $my_projects.'</ul>';
}?>
<!--	<h5> My projects: </h5>
		<ul>
			<li> <a href="#">Arkhart</a><br />
			<ul>
			<li><a href="#">Add a branch</a></li>
			<li><a href="#">Add a release</a></li>
			</ul>
			</li>
			<li> <a href="#">Igoan</a><br />
			<ul>
			<li><a href="#">Add a release</a></li>
			</ul>
			</li>
		</ul> -->
		<p>
			<a href="/project/new_project.php" title="Register a new project">New project</a>
		</p>
	</div><?php
} else { ?>
	<h4> Login: </h4>
	<form action="/user/login.php">
	<div>
		<label for="username"> Username: </label><br />
		<input title="Your igoan user name." id="username" name="login" type="text" /><br />
		<label for="password"> Password: </label><br />
		<input title="Your igoan user password." id="password" name="passwd" type="password" /><br />
		<input type="submit" style="margin-top: 0.2em" name="submit" value="Submit !" />
		<input type="hidden" name="referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
	</div>
	</form>
	<h4><small><a href="/user/new.php">Register</a></small></h4>
<?php	} ?>
		</div>
	<br style="clear: both;" />
<?php
}

function categories_box() {
?>
	<div class="categories">
		<h4> Categories: </h4>
		<ul>
			<li> <a href="#">Accessibility</a> </li>
			<li> <a href="#">Audio</a> </li>
			<li> <a href="#">Business and Productivity</a> </li>
			<li> <a href="#">Database</a> </li>
			<li> <a href="#">Design and Manufacturing</a> </li>
			<li> <a href="#">E-commerce</a> </li>
			<li> <a href="#">Education</a> </li>
			<li> <a href="#">Games</a> </li>
			<li> <a href="#">Graphics</a> </li>
			<li> <a href="#">GUI</a> </li>
			<li> <a href="#">Hobbies</a> </li>
			<li> <a href="#">Live Communications</a> </li>
			<li> <a href="#">Localization</a> </li>
			<li> <a href="#">Miscellaneous</a> </li>
			<li> <a href="#">Network Applications</a> </li>
			<li> <a href="#">Printing</a> </li>
			<li> <a href="#">Science</a> </li>
			<li> <a href="#">Security</a> </li>
			<li> <a href="#">Software development</a> </li>
			<li> <a href="#">Software Libraries</a> </li>
			<li> <a href="#">System administration</a> </li>
			<li> <a href="#">Telephony</a> </li>
			<li> <a href="#">Text creation and manipulation</a> </li>
			<li> <a href="#">Video</a> </li>
			<li> <a href="#">Web Authoring</a> </li>
		</ul>
		<br style="clear: both" />
	</div>
	</div>
<?php
}

function header_box($title) {
echo '<?xml version="1.0" encoding="iso-8859-15"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
                      "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" title="Igoan - Default Stylesheet" href="/style/default.css" />
</head>
<body id="www_igoan_org" class="add_project">
<div id="header">
<h1><img src="/images/logo.png" width="300" height="80" alt="Igoan.org" title="Igoan, Free Software Directory Project" /></h1>
	<div class="menu links">
		<a href="/index.php"><img src="/images/home.png" alt="home" /></a>
		<img src="/images/separator.png" alt=" " />
		<a href="/updates.php"><img src="/images/updates.png" alt="latest updates" /></a>
		<img src="/images/separator.png" alt=" " />
		<a href="/browse.php"><img src="/images/browse.png" alt="browse categories " /></a>
		<img src="/images/separator.png" alt=" " />
		<a href="/igoan/about.php"><img src="/images/about.png" alt="about " /></a>
		<img src="/images/separator.png" alt=" " />
		<a href="/igoan/contact.php"><img src="/images/contact.png" alt="contact " /></a>
	</div>
	<br style="clear: both;" />
</div><?php
}

function footer_box() { ?>
<div id="footer">
	This page was generated in 0.2seconds. (Igoan v0.42, 256 projects in the database) <br />
	&copy; Igoan 2003 - <a href="http://validator.w3.org/check/referer">check xhtml</a>
</div>
</body>
</html><?php
}
?>
