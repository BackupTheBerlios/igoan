<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: Category.class.php,v 1.1 2005/01/03 02:41:33 cam Exp $
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

class Category
{
	// private
	protected $_id_cat;
	protected $_name_cat;
	protected $_index;
	protected $_parent;
	protected $_valid_cat;

	// public
	function set_id_cat($id)
	{
		$this->_id_cat = int($id);
	}
	function get_id_cat()
	{
		return ($this->_id_cat);
	}
	function set_name_cat($name)
	{
		$this->_name_cat = $name;
	}
	function get_name_cat()
	{
		return ($this->_name_cat);
	}
	function set_index($index)
	{
		# warning, index is a text string
		$this->_index = $index;
	}
	function get_index()
	{
		return ($this->_index);
	}
	function set_parent($parent)
	{
		$this->_parent = $parent;
	}
	function get_parent()
	{
		return ($this->_parent);
	}
	function set_valid_cat($valid)
	{
		$this->_valid_cat = (bool)$valid;
	}
	function get_valid_cat()
	{
		return ((bool)$this->_valid_cat);
	}
	function delete()
	{
		sql_do('DELETE FROM categories WHERE id_cat=\''.int($this->get_id_cat()).'\'');
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

function category_get_by_id($id)
{
	$result = sql_do('SELECT id_cat,name_cat,index,parent,valid_cat FROM categories WHERE id_cat=\''.int($id).'\'');

	if ($result->numRows() != 1) {
		return (0);
	}
	$row = $result->fetchRow();
	$cat = new Category;
	$cat->set_id_cat($row[0]);
	$cat->set_name_cat($row[1]);
	$cat->set_index($row[2]);
	$cat->set_parent($row[3]);
	$cat->set_valid_cat($row[4]);

	return ($cat);
}

function category_new($index, $nom)
{
	# FIXME: increase the actual limit of 10 children by category

	/* recuperation des index existants et correspondants */
	$result = sql_do('SELECT index FROM categories WHERE index LIKE \''.str($index).'_\' ORDER BY index');
	for ($i = 0; $i < 10; $i++) {
		$row = $result->fetchRow();
		if ($row[0] != ($index . $i)) break;
	}

	if ($i == 10) return (-1);

	$id_cat = pick_id('categories_id_cat_seq');
	try {
		sql_do('INSERT INTO categories (id_cat,index,name_cat) VALUES (\''.int($id_cat).'\',\''.str($index).int($i).'\',\''.str($nom).'\')');
	} catch (DatabaseException $e) {
		return 0;
	}
	return $id_cat;
}

function category_list($level = '') 
{
	return get_array_by_query('SELECT id_cat,name_cat,index,parent FROM categories WHERE index LIKE \''.str($level).'%\' ORDER BY name');
}

function category_list_all()
{
	return get_array_by_query('SELECT id_cat,name_cat,index,parent FROM categories ORDER BY index');
}
