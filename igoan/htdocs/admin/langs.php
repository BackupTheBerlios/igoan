<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: langs.php,v 1.1 2004/04/08 21:15:32 cam Exp $
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
require_once 'igoan/Language.class.php';

// permission de l'user (admin global)
$me = user_get_by_id($_SESSION['id']);
if (!$me || !$me->is_global_admin()) {
	append_error_exit('Permission denied: global admin flag required');
}

/* ajout */
if (isset($_GET['action']) && ($_GET['action'] == "Ajouter") && isset($_GET['nom'])) {
  append_error('inserting language: '.$_GET['nom']);
  if (language_new($_GET['nom']) == -1) {
    append_error('error');
  }
}

/* suppression */
if (isset($_GET['action']) && ($_GET['action'] == "Effacer") && isset($_GET['idLang'])) {
  append_error('deleting language: '.$_GET['idLang']);
  $lang = language_get_by_id($_GET['idLang']);
  $lang->delete();
}

/* recuperation de la liste */
$list = language_list();
$select = "<select name='idLang'>\n";
while (list(,$tuple) = each ($list)) {
  $select .= "<option value='".$tuple[0]."'>".$tuple[0]." ".$tuple[1]."</option>\n";
}


?>
<h2>Gestion des langages de prog</h2>

<?php flush_errors(); ?>

<h3>Ajout d'un langage</h3>

<form>
Nom : <input type="text" name="nom" /><br />
<input type='submit' value='Ajouter' name='action' />
</form>

<h3>Suppression d'un langage</h3>

<form>
Selectionnez le langage a effacer : <?= $select ?></select>
<input type='submit' value='Effacer' name='action' />
</form>

<hr />
<a href="/admin/">Retour au menu</a>
