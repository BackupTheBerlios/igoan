<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: error.php,v 1.1 2004/04/08 21:16:01 cam Exp $
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

// annule une eventuelle transaction en cours
require_once 'divers.fct.php';

$short = 'Fatal Error';
if (!empty($_GET['code'])) switch ($_GET['code']) {
case '404':
	$short = 'Error 404 : Not Found';
	$long = 'The page you requested does not exist.';
	break;
case '500':
	$short = 'Error 500 : Internal Server Error';
	$long = 'The server encountered an unexpected condition which prevented it from fulfilling the request.<br/>Please contact the <a href="mailto:www@igoan.org">webmaster</a>.';
	break;
default:
	$short = 'Error';
	$long = 'Your action has generated an error.';
}

header_box('Igoan :: '.$short);
?>
<div id="main">
	<div class="abstract error"><?php
	if (errors()) {
		flush_errors(0); ?>
		<p>
		Your action has generated a fatal error and had been stopped.
		</p><?php
	} else { ?>
		<h4><?php echo $short; ?></h4>
		<p><?php echo $long; ?></p>
		<?php
	} ?>
	<p>
	You can <script type="text/javascript">document.write('<a href="javascript:history.back()">browse back</a> or ');</script> go to
	the <a href="/index.php">main page</a>.
	</p>
	</div>
</div>

<?php
footer_box();
?>
