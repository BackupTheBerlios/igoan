<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: new.php,v 1.1 2004/04/08 21:14:37 cam Exp $
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

error_reporting(E_ALL);
unset ($login);

if (isset($_GET['submit']) && ($_GET['submit'] == 'Submit !')) {
	// verif login valide
	if (isset($_GET['login'])) {
		$login = strtolower($_GET['login']);
		if (empty($login)) {
			append_error('You must supply a login');
		} else for ($i=0; $i<strlen($login); $i++) {
			if ((($login[$i] < '0') or ($login[$i] > '9')) and (($login[$i] < 'a') or ($login[$i] > 'z'))) {
				append_error('Bad user name. Please use only letters or numbers');
				break;
			}
		}
	}
	// verif real name existant
	if (!isset($_GET['name']) or empty($_GET['name'])) {
		append_error('You must supply a real name.');
	}
	// verif email existant
	if (!isset($_GET['email']) or empty($_GET['email'])) {
		append_error('You must supply an email address.');
	}

	// si pas d'erreur,
	if (!errors()) {
		$newid = user_new($_GET['login'], $_GET['name'], $_GET['email']);
		if ($newid) {
			$new = user_get_by_id($newid);
			if ($new) {
				// envoi de l'email
				error_reporting(2047);
				mail($_GET['email'], '[igoan] Account registration confirmation', 
'This email is a confirmation of your registration to Igoan, 
the free directory project.

You have entered the following:
  Name: '.$_GET['name'].'
  Login name: '.$_GET['login'].'
  Email: '.$_GET['email'].'

Your automatically generated password is \''.$new->get_passwd().'\'. 
You have to login with your newly created account on the Igoan website
(http://www.igoan.org/user/login.php) and change your password to 
active your account. 
Then you will be able to register new projects on our databases.

Best regards,
The Igoan Team.
', 'From: Igoan Registration Process <register@igoan.org>');
			} else {
				append_error('Unable to fetch new user informations');
			}
		} else {
			append_error('Unable to create new user');
		}
	}
}

header_box('Igoan :: New User');
?>
<div id="main">
<?php
if (!errors() and isset($login)) { ?>
	<h2>Registration submitted</h2>
	<div class="abstract">
		<p>
			A mail has been sent to <em><?php echo $_GET['email']; ?></em>.
		</p>
		<p>
			Check your mailbox and follow the instructions.
		</p>
	</div>
<?php } else { ?>
<form class="admin" action="new.php">
<?php if (!errors()) { ?>
<h2> Register </h2>
	<div class="abstract">
		<p>
			Before you go on with the registration procedure, there are a few things you need to know about it.
		</p>
		<ol>
			<li><span> The only part of the site that requires you to log in is the project administration pages. <br />
			           Any other page/feature of igoan.org remains accessible wheter you are logged in or not. </span></li>
			<li><span> We strongly believe that our site should <strong>not</strong> be be supported by ads. Therefore, we won't sell any data we gather to anyone. ever. </span></li>
			<li><span> We need a valid email address inorder to be able to send you your password. </span></li>
			<li><span> The registration part only show the fields that are required to create an account. <br /> You will be able to add you photo, description and projects afterwards. </span></li>
		</ol>
	</div>
<?php } else {
flush_errors();
}
?>	
	<h2> Personal information </h2>
	<div class="description">
		<div class="block">
			<label for="login"> Login name: </label>
			<input title="This will be your Igoan login. Please use alphanumeric characters only." id="login" name="login" type="text" value="<?php echo isset($login)?$login:"";?>" />
		</div>
		<div class="block">
			<label for="name"> Name: </label>
			<input title="This is the name that we will display on igoan.org." id="name" name="name" type="text" value="<?php echo isset($_GET['name'])?$_GET['name']:"";?>" />
		</div>	
		<div class="block">
			<label for="email"> Email: </label>
			<input title="Email adress we will use to send you your password." id="email" name="email" type="text" value="<?php echo isset($_GET['email'])?$_GET['email']:"";?>" />
		</div>	
		<div class="block submit">
			<label for="submit"> Submit: </label>
			<input type="submit" id="submit" name="submit" value="Submit !" />
		</div>
		<p style="margin-top: 1.5em; font-size: small"> All fields are mandatory. <br />If everything is correct, your password will be sent to the email address you specified so that you can activate your account.</p>
	</div>
	</form>
</div>
<?php } 
footer_box();
?>
