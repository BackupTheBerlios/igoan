<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: Platform.class.php,v 1.1 2005/01/03 02:38:12 cam Exp $
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

class Platform
{
	// private
	protected $_id_pf;
	protected $_name_pf;
	protected $_valid_pf;

	// public
	function set_id_pf($id)
	{
		$this->_id_pf = int($id);
	}
	function get_id_pf()
	{
		return ($this->_id_pf);
	}
	function set_name_pf($name)
	{
		$this->_name_pf = $name;
	}
	function get_name_pf()
	{
		return ($this->_name_pf);
	}
	function set_valid_pf($valid)
	{
		$this->_valid_pf = (bool)$valid;
	}
	function get_valid_pf()
	{
		return ((bool)$this->_valid_pf);
	}
	function delete()
	{
		sql_do('DELETE FROM platforms WHERE id_pf=\''.int($this->get_id_pf()).'\'');
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

function platform_get_by_id($id)
{
	$result = sql_do('SELECT id_pf,name_pf,valid_pf FROM platforms WHERE id_pf=\''.int($id).'\'');

	if ($result->numRows() != 1) {
		return (0);
	}
	$row = $result->fetchRow();
	$pf = new Platform;
	$pf->set_id_pf($row[0]);
	$pf->set_name_pf($row[1]);
	$pf->set_valid_pf($row[2]);

	return ($pf);
}

function platform_new($nom)
{
	$id_pf = pick_id('platforms_id_pf_seq');
	try {
		$result = sql_do('INSERT INTO platforms (id_pf,name_pf) VALUES ('.int($id_pf).',\''.int($nom).'\')');
	} catch (DatabaseException $e) {
		return 0;
	}
	return $id_pf;
}

function platform_list()
{
	return get_array_by_query("SELECT id_pf,name_pf FROM platforms ORDER BY name_pf");
}
