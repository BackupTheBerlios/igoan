<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: logout.php,v 1.1 2004/04/08 21:14:45 cam Exp $
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

# taken from http://fr2.php.net/manual/en/function.html-entity-decode.php
function my_html_entity_decode($given_html, $quote_style = ENT_QUOTES) {
   $trans_table = array_flip(get_html_translation_table( HTML_SPECIALCHARS, $quote_style ));
   $trans_table['&#39;'] = "'";
   return (strtr( $given_html, $trans_table));
}

unset($_SESSION['id']); 
http_redir(empty($_GET['referer']) ? '/index.php' : urldecode(my_html_entity_decode($_GET['referer'])));
?>
