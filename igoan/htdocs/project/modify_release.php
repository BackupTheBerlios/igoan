<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: modify_release.php,v 1.1 2004/04/08 21:15:49 cam Exp $
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

// est-ce que la release existe ?
if (!isset($_GET['idRel']) || ($_GET['idRel'] != '123460')) {
	append_error('No release specified or release unknown');
	flush_errors_exit();
}

// l'user concerné est-il admin du projet ?
if ($_SESSION['id'] != 34567) {
	append_error('Can\'t modify project: permission denied');
	http_redir('/project/view_release.php?idRel=123460');
}

if (isset($_GET['action']) && ($_GET['action'] == 'Apply')) {
	// do the job
	http_redir('/project/view_release.php?idRel=123460');
}
?>

<h1>Change release information</h1>

<form>
<table>
<tr><th>idRel</th><td>34567</td></tr>
<tr><th>Version</th><td><input type="text" name="version" value="graphtool-0.2pre1" /></td></tr>
<tr><th>Date</th><td>
	<input type="text" name="year" value="2003" size="4"
	/>-<input type="text" name="month" value="07" size="2"
	/>-<input type="text" name="day" value="30" size="2"></td></tr>
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
		<option value="">-- SELECT A PLATFORM --</option>
		<option selected="selected">Linux</option>
		<option>Solaris</option></select></td></tr>
<tr><th>&nbsp;</th><td>
	<select name="platform2">
		<option value="">-- SELECT A PLATFORM --</option>
		<option>Linux</option>
		<option selected="selected">Solaris</option></select></td></tr>
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
		<option value="">-- SELECT A CATEGORY --</option>
		<option>Administration</option>
		<option>Games</option>
		<option selected="selected">Scientific</option>
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

<tr><th>Languages</th><td>
	<select name="language1">
		<option value="">-- SELECT A LANGUAGE --</option>
		<option>ADA</option>
		<option>C</option>
		<option>C++</option>
		<option>Eiffel</option>
		<option>Fortran</option>
		<option>Java</option>
		<option>Lisp</option>
		<option>Perl</option>
		<option selected="selected">Python</option></select></td></tr>
<tr><th>&nbsp;</th><td>
	<select name="language1">
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
	<select name="language1">
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
	<select name="language1">
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

<tr><td colspan="2">
	<pre>&lt;!-- pour ajouter rapidement un devlo, je sais pas encore comment --&gt;</pre></td></tr>

<tr><td>&nbsp;</th><td><input type="submit" name="action" value="Apply" /></td></tr>

</table>
<input type="hidden" name="idRel" value="123460" />
</form>

<pre>&lt;!-- toujours 3 vierges serait une bonne moyenne ? --&gt;</pre>
<br /><br/><hr />
<a href="/project/view_project.php?idPrj=456789">Back to project view</a> || <a href="/user/view_user.php">Back to user page</a> || <a href="/">Back to homepage</a>
