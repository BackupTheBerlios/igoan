<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: api.php,v 1.1 2004/04/08 21:15:20 cam Exp $
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
$DIR = '../../php-include/igoan';

$dir = opendir($DIR);
while ($file = readdir($dir)) {
	if (substr($file, -10) != ".class.php") continue;
	echo '<p><strong>Fichier : '.$file.'</strong><br/>';
	$public = 1;
	$fd = fopen($DIR.'/'.$file, "r");
	while ($line = fgets($fd)) {
		if (ereg('// ?public', $line)) $public = 1;
		elseif (ereg('// ?private', $line)) $public = 0;
		elseif (ereg('^class', $line)) echo $line.'<br/>';
		elseif (ereg('.(function|var)', $line, $reg) && $public) echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($reg[1]=='var')?'attribut':'méthode').' : '.$line.'<br/>';
		elseif (ereg('^function', $line)) echo 'Fonction (statique) : '.$line.'<br/>';
	}
	fclose($fd);
	echo '</p>';
}
closedir($dir);
