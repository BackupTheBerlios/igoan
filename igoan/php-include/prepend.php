<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: prepend.php,v 1.1 2004/12/30 21:48:40 cam Exp $
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

// stripslashing des arguments
if (!empty($_GET)) foreach ($_GET as $key => $value) {
	$_GET[$key] = stripslashes($value);
	if (substr($key, 0, 3) == "id_") {
		$_GET[$key] = (int)$value;
	}
}

// config PHP local
ini_set('session.use_cookies', 1);
#if (ereg("igoan.org", $_SERVER['SERVER_NAME'])) {
#	ini_set('session.save_path', '/data/www/org/n/a/igoan.org/a/t/beta/tmp');
#}

// sessions
session_start();
if (!isset($_SESSION['id']))
	$_SESSION['id'] = 0;
if (!isset($_SESSION['error']))
	$_SESSION['error'] = '';

require_once 'error.fct.php';
require_once 'layout.fct.php';
require_once 'divers.fct.php';
require_once 'database.fct.php';
require_once 'Igoandb.class.php';
$igoandb = new Igoandb();
?>
