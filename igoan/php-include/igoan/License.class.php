<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: License.class.php,v 1.1 2005/01/03 02:39:41 cam Exp $
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

require_once 'PEAR.php';

class License
{
	// private
	protected $_id_lic;
	protected $_name_lic;
	protected $_valid_lic;

	// public
	function set_id_lic($id)
	{
		$this->_id_lic = int($id);
	}
	function get_id_lic()
	{
		return ($this->_id_lic);
	}
	function set_name_lic($name)
	{
		$this->_name_lic = $name;
	}
	function get_name_lic()
	{
		return ($this->_name_lic);
	}
	function set_valid_lic($valid)
	{
		$this->_valid_lic = (bool)$valid;
	}
	function get_valid_lic()
	{
		return ((bool)$this->_valid_lic);
	}
	function delete()
	{
		sql_do('DELETE FROM licenses WHERE id_lic=\''.int($this->get_id_lic()).'\'');
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

function license_get_by_id($id)
{
	$result = sql_do('SELECT id_lic,name_lic,valid_lic FROM licenses WHERE id_lic=\''.int($id).'\'');

	if ($result->numRows() != 1) {
		return (0);
	}
	$row = $result->fetchRow();
	$lic = new License;
	$lic->set_id_lic($row[0]);
	$lic->set_name_lic($row[1]);
	$lic->set_valid_lic($row[2]);

	return ($lic);
}

function license_new($nom, $term)
{
	$id_lic = pick_id('licenses_id_lic_seq');
	try {
		sql_do('INSERT INTO licenses (id_lic,name_lic,terms) VALUES (\''.int($id_lic).'\',\''.str($nom).'\',\''.str($term).'\')');
	} catch (DatabaseException $e) {
		return 0;
	}
	return $id_lic;
}

function license_list()
{
	return get_array_by_query('SELECT id_lic,name_lic,terms FROM licenses ORDER BY name_lic');
}
