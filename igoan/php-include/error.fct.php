<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: error.fct.php,v 1.1 2005/01/03 01:58:55 cam Exp $
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

function errors() {
	return (isset($_SESSION['error']) && !empty($_SESSION['error']));
}
function flush_errors($show_div = 1) {
	if (errors()) {
		if ($show_div)
			echo '<div class="abstract error">	';
	?>
	<p>
			There is a problem, please correct these issues:
		</p>
		<ol><?php
		echo $_SESSION['error']; ?>
		</ol>
		<?php
		if ($show_div)
			echo '</div>';
		$_SESSION['error'] = '';
	}
}
function append_error($str) {
	if (!empty($str)) {
		$_SESSION['error'] .= '<li><strong>'.$str.'</strong></li>';
	}
}
function flush_errors_exit() {
	echo 'FLUSH_ERRORS_EXIT';
/*	flush_errors();
	if ($_SESSION['id'] != 0) {
		echo '<a href="/user/view_user.php">Back to user configuration</a> || ';
	}
	echo '<a href="/">Back home</a>';
	exit; */
	require($_SERVER['DOCUMENT_ROOT'].'/error.php');
	exit;
}
function append_error_exit($str) {
	append_error($str);
	flush_errors_exit();
}


### Handling PHP errors
error_reporting(E_ALL | E_STRICT);

// error handler function
function myErrorHandler($errno, $errstr, $errfile, $errline) {
  switch ($errno) {
  case E_ERROR:
    echo("<b>FATALIGOAN</b> [$errno] $errstr<br/>Fatal error in line ".$errline." of file ".$errfile.PHP_VERSION." (".PHP_OS.")<br/>Aborting...<br/>");
    break;
  case E_WARNING:
    append_error('Error: '.$errstr);
    break;
  case E_NOTICE:
    append_error('Warning: '.$errstr);
    break;
  case E_STRICT:
    append_error('PHP5: '.$errstr);
    break;
  default:
    echo "UnkownIGOAN error type: [$errno] $errstr<br>\n";
    break;
  }
}
set_error_handler("myErrorHandler");

// exception handler function
function myExceptionHandler($exception) {
  append_error('Uncaught exception: '.$exception->getMessage().'<br/>');
  //$exception->getTraceAsString());
}
set_exception_handler('myExceptionHandler');
?>
