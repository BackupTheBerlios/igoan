<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: Language.class.php,v 1.1 2005/01/03 02:40:45 cam Exp $
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

class Language
{
	// private
	protected $_id_lang;
	protected $_name_lang;
	protected $_valid_lang;

	// public
	function set_id_lang($id)
	{
		$this->_id_lang = int($id);
	}
	function get_id_lang()
	{
		return ($this->_id_lang);
	}
	function set_name_lang($name)
	{
		$this->_name_lang = $name;
	}
	function get_name_lang()
	{
		return ($this->_name_lang);
	}
	function set_valid_lang($valid)
	{
		$this->_valid_lang = (bool)$valid;
	}
	function get_valid_lang()
	{
		return ((bool)$this->_valid_lang);
	}
	function delete()
	{
		sql_do('DELETE FROM languages WHERE id_lang=\''.int($this->get_id_lang()).'\'');
	}
#  function dumpXML()
#  {
#    $xml = "<!-- THIS FILE IS AUTO-GENERATED. PLEASE DO NOT EDIT IT BY HAND -->\n\n<".$this->is."s>\n";
#
#    $list = $this->listAll();
#    
#    while (list(,$tuple) = each ($list)) {
#      print_r($tuple);
#      $xml .= "<".$this->is." id='".$tuple[0]."' name='".$tuple[1]."'";
#      for ($i=0; $i<count($this->options); $i++) {
#	$xml .= " ".$this->options[$i]."='".$tuple[$i+2]."'";
#      }
#      $xml .= "/>\n";
#    }
#    
#    return ($xml . "</".$this->is."s>\n");
#  }
}

function language_get_by_id($id)
{
	$result = sql_do('SELECT id_lang,name_lang,valid_lang FROM languages WHERE id_lang=\''.int($id).'\'');

	if ($result->numRows() != 1) {
		return (0);
	}
	$row = $result->fetchRow();
	$lang = new Language;
	$lang->set_id_lang($row[0]);
	$lang->set_name_lang($row[1]);
	$lang->set_valid_lang($row[2]);

	return ($lang);
}

function language_new($nom)
{
	$id_lang = pick_id('languages_id_lang_seq');
	try {
		sql_do('INSERT INTO languages (id_lang,name_lang) VALUES ('.int($id_lang).',\''.str($nom).'\')');
	} catch (DatabaseException $e) {
		return 0;
	}
	return $id_lang;
}

function language_list()
{
	return get_array_by_query('SELECT id_lang,name_lang FROM languages ORDER BY name_lang');
}
