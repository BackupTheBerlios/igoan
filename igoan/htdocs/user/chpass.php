<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: chpass.php,v 1.1 2005/01/03 02:29:33 cam Exp $
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

if (isset($_GET['action']) &&
    ($_GET['action'] == 'Change Password') &&
    isset($_GET['oldpass']) &&
    isset($_GET['passwd1']) &&
    isset($_GET['passwd2'])) {

	$me = user_get_by_id($_SESSION['id']);
	if (!$me) {
		append_error_exit('User ID incorrect.');
	}

	// check the old password
	if ($_GET['oldpass'] != $me->get_passwd()) {
		append_error('Wrong old password.');
	}
	
	// check the new passwords
	else if ($_GET['passwd1'] != $_GET['passwd2']) {
		append_error('Passwords mismatch');
	} else if (empty($_GET['passwd1'])) {
		append_error('Aha. Yes of course ...');
	}

	if (errors()) {
		flush_errors();
	} else {
		// do the job
		$me->set_passwd($_GET['passwd1']);
		$me->write();
		http_redir('/user/view.php');
	}
}


?>
<h1>Changing your user password</h1>
<form>
Please give your <strong>actual</strong> password first: <input type="password" name="oldpass" /><br />
Then your new password: <input type="password" name="passwd1" /><br />
Twice for verification: <input type="password" name="passwd2" /><br />
<input type="submit" name="action" value="Change Password" />
</form>
