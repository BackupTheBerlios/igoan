<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: add_release.php,v 1.1 2004/04/08 21:15:41 cam Exp $
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

// le projet existe ?
if (!isset($_GET['idPrj']) || ($_GET['idPrj'] != 456789)) {
	append_error("Can't add a new release: project ID doesn't exist");
	http_redir('/');
}

// user est-il admin du projet ?
if ($_SESSION['id'] != 34567) {
	append_error("Can't add a new release: permission denied");
	http_redir('/project/view.php?id_prj=456789');
}

// ajout de la release
if (isset($_GET['action']) && ($_GET['action'] == 'Submit')) {
	if (isset($_GET['version']) && isset($_GET['year']) && isset($_GET['month']) && isset($_GET['day']) && isset($_GET['status']) && isset($_GET['license'])) {
		// faire les verifications de types et autre ...
		if (empty($_GET['version']))
			append_error('Version cannot be empty');
		if (empty($_GET['year']))
			append_error('Year cannot be empty');
		if (empty($_GET['month']))
			append_error('Month cannot be empty');
		if (empty($_GET['day']))
			append_error('Day cannot be empty');
		if (!errors() && (mktime(0, 0, 0, $_GET['month'], $_GET['day'], $_GET['year']) > (time()-10000/* un certain offset */))) {
			append_error('Date should not be in the future');
		}
			
		#blabla
		
		if (!errors()) {
			// do the job
			// recuperer l'idRel fraichement créé
			http_redir('/project/view.php?id_rel=FIXME');
		}
	} else {
		append_error('Malformed form, please use the correct html page');
	}
}

?>
<h1>Add a new release to the project 'GraphTool'</h1>

<?php flush_errors(); ?>

<form>
<table>
<tr><th>Version name</th><td><input type="text" name="version" value="<?php echo isset($_GET['version'])?$_GET['version']:"";?>"/></td></tr>
<tr><th>Release Date</th><td>
	<input type="text" name="year" size="4"
	/>-<input type="text" name="month" size="2"
	/>-<input type="text" name="day" size="2"></td></tr>
<tr><th>Status</th><td>
	<select name="status">
		<option selected="selected">Beta</option>
		<option>Stable</option></select></td></tr>
<tr><th>Nb subprojects</th><td>?</td></tr>
<tr><th>License</th><td>
	<select name="license">
		<option selected="selected">GPL 2</option>
		<option>BSD</option></select></td></tr>

<tr><th>Platforms</th><td>
	<select name="platform1">
		<option selected="selected" value="">-- SELECT A PLATFORM --</option>
		<option>Linux</option>
		<option>Solaris</option></select></td></tr>
<tr><th>&nbsp;</th><td>
	<select name="platform2">
		<option selected="selected" value="">-- SELECT A PLATFORM --</option>
		<option>Linux</option>
		<option>Solaris</option></select></td></tr>
<tr><th>&nbsp;</th><td>
	<select name="platform3">
		<option value="" selected="selected">-- SELECT A PLATFORM --</option>
		<option>Linux</option>
		<option>Solaris</option></select></td></tr>
<tr><th>&nbsp;</th><td>
	<select name="platform4">
		<option value="" selected="selected">-- SELECT A PLATFORM --</option>
		<option>Linux</option>
		<option>Solaris</option></select></td></tr>
<tr><th>&nbsp;</th><td>
	<select name="platform5">
		<option value="" selected="selected">-- SELECT A PLATFORM --</option>
		<option>Linux</option>
		<option>Solaris</option></select></td></tr>

<tr><th>Categories</th><td>
	<select name="category1">
		<option selected="selected" value="">-- SELECT A CATEGORY --</option>
		<option>Administration</option>
		<option>Games</option>
		<option>Scientific</option>
		<option>Zoophile</option></select></td></tr>
<tr><th>&nbsp;</th><td>
	<select name="category2">
		<option value="" selected="selected">-- SELECT A CATEGORY --</option>
		<option>Administration</option>
		<option>Games</option>
		<option>Scientific</option>
		<option>Zoophile</option></select></td></tr>
<tr><th>&nbsp;</th><td>
	<select name="category3">
		<option value="" selected="selected">-- SELECT A CATEGORY --</option>
		<option>Administration</option>
		<option>Games</option>
		<option>Scientific</option>
		<option>Zoophile</option></select></td></tr>
<tr><th>&nbsp;</th><td>
	<select name="category4">
		<option value="" selected="selected">-- SELECT A CATEGORY --</option>
		<option>Administration</option>
		<option>Games</option>
		<option>Scientific</option>
		<option>Zoophile</option></select></td></tr>
<tr><th>&nbsp;</th><td>
	<select name="category5">
		<option value="" selected="selected">-- SELECT A CATEGORY --</option>
		<option>Administration</option>
		<option>Games</option>
		<option>Scientific</option>
		<option>Zoophile</option></select></td></tr>

<tr><th>Languages</th><td>
	<select name="language1">
		<option selected="selected" value="">-- SELECT A LANGUAGE --</option>
		<option>ADA</option>
		<option>C</option>
		<option>C++</option>
		<option>Eiffel</option>
		<option>Fortran</option>
		<option>Java</option>
		<option>Lisp</option>
		<option>Perl</option>
		<option>Python</option></select></td></tr>
<tr><th>&nbsp;</th><td>
	<select name="language2">
		<option value="" selected="selected">-- SELECT A LANGUAGE --</option>
		<option>ADA</option>
		<option>C</option>
		<option>C++</option>
		<option>Eiffel</option>
		<option>Fortran</option>
		<option>Java</option>
		<option>Lisp</option>
		<option>Perl</option>
		<option>Python</option></select></td></tr>
<tr><th>&nbsp;</th><td>
	<select name="language3">
		<option value="" selected="selected">-- SELECT A LANGUAGE --</option>
		<option>ADA</option>
		<option>C</option>
		<option>C++</option>
		<option>Eiffel</option>
		<option>Fortran</option>
		<option>Java</option>
		<option>Lisp</option>
		<option>Perl</option>
		<option>Python</option></select></td></tr>
<tr><th>&nbsp;</th><td>
	<select name="language4">
		<option value="" selected="selected">-- SELECT A LANGUAGE --</option>
		<option>ADA</option>
		<option>C</option>
		<option>C++</option>
		<option>Eiffel</option>
		<option>Fortran</option>
		<option>Java</option>
		<option>Lisp</option>
		<option>Perl</option>
		<option>Python</option></select></td></tr>
<tr><th>&nbsp;</th><td>
	<select name="language5">
		<option value="" selected="selected">-- SELECT A LANGUAGE --</option>
		<option>ADA</option>
		<option>C</option>
		<option>C++</option>
		<option>Eiffel</option>
		<option>Fortran</option>
		<option>Java</option>
		<option>Lisp</option>
		<option>Perl</option>
		<option>Python</option></select></td></tr>
</table>
<input type="hidden" name="idPrj" value="456789" />
<input type="submit" name="action" value="Submit" />
</form>

<br /><br/><hr />
<a href="/project/view.php?idPrj=456789">Back to project view</a> || <a href="/user/view_user.php">Back to user page</a> || <a href="/">Back to homepage</a>
