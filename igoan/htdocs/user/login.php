<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: login.php,v 1.1 2004/04/08 21:14:31 cam Exp $
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

if (isset($_GET['login']) && isset($_GET['passwd'])) {

	$me = user_get_by_password($_GET['login'], $_GET['passwd']);
	if (!$me) {
		append_error('Login incorrect.');
	} else {
		$_SESSION['id'] = $me->get_id_user();
	}
	if (!errors()) {
		http_redir(empty($_GET['referer']) ? '/index.php' : $_GET['referer']);
	}
}

header_box('Igoan :: Login');
?>

<div id="main">
	<form class="admin" action="login.php">
	<?php flush_errors(); ?>
	<h2> Login </h2>
	<div class="description">
		<p style="margin-bottom: 1em;">
			Enter your login and password to continue.
		</p>
		<div class="block">
			<label for="username"> Login: </label>
			<input title="Your igoan user name." id="username" name="login" type="text" <?php if (!empty($_GET['login'])) echo 'value="'.$_GET['login'].'"'; ?> />
		</div>
		<div class="block">
			<label for="password"> Password: </label>
			<input title="Your igoan user password." id="password" name="passwd" type="password" />
		</div>
		<div class="block submit">
			<label for="submit"> Submit: </label>
			<input type="submit" id="submit" name="submit" value="Submit !" />
		</div>
	</div>
	</form>
</div><?php
footer_box();
?>
