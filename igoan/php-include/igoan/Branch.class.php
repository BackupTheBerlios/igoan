<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: Branch.class.php,v 1.1 2005/01/03 02:42:04 cam Exp $
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

class Branch
{
	// private
	protected $_id_branch;
	protected $_name_branch;
	protected $_id_prj;
	protected $_date_branch;

	// public
	function set_id_branch($id)
	{
		$this->_id_branch = int($id);
	}
	function get_id_branch()
	{
		return ($this->_id_branch);
	}
	function set_name_branch($name)
	{
		$this->_name_branch = $name;
	}
	function get_name_branch()
	{
		return ($this->_name_branch);
	}
	function set_id_prj($id)
	{
		$this->_id_prj = int($id);
	}
	function get_id_prj()
	{
		return ($this->_id_prj);
	}
	function set_date_branch($date)
	{
		$this->_date_branch = $date;
	}
	function get_date_branch()
	{
		return ($this->_date_branch);
	}
	function write()
	{
		sql_do('UPDATE branches SET name_branch=\''.int($this->get_name_branch()).' WHERE id_branch=\''.int($this->get_id_branch()).'\'');
	}
	function add_maintainer($id_user)
	{
		sql_do('INSERT INTO maintainers (id_branch,id_user) VALUES (\''.int($this->get_id_branch()).'\',\''.int($id_user).'\')');
	}
	function del_maintainer($id_user)
	{
		sql_do('DELETE FROM maintainers WHERE id_branch=\''.int($this->get_id_branch()).'\' AND id_user=\''.int($id_user).'\'');
	}
	function is_maintainer($id_user)
	{
		$dummy = sql_do("SELECT id_user FROM maintainers WHERE id_user='".int($id_user)."' AND id_branch='".int($this->get_id_branch())."'");
		return ($dummy->numRows() > 0);
	}
	function list_authors()
	{
		return get_array_by_query("SELECT distinct(id_user) FROM authors JOIN releases USING (id_rel) WHERE id_branch='".int($this->get_id_branch())."'");
	}
	function list_platforms()
	{
		return get_array_by_query("SELECT distinct(id_pf) FROM runson JOIN releases USING (id_rel) WHERE id_branch='".int($this->get_id_branch())."'");
	}
	function list_languages()
	{
		return get_array_by_query("SELECT distinct(id_lang) FROM written JOIN releases USING (id_rel) WHERE id_branch='".int($this->get_id_branch())."'");
	}
	function list_categories()
	{
		return get_array_by_query("SELECT distinct(id_cat) FROM belongsto JOIN releases USING (id_rel) WHERE id_branch='".int($this->get_id_branch())."'");
	}
	function list_maintainers()
	{
		return (get_array_by_query("SELECT id_user FROM maintainers WHERE id_branch='".int($this->get_id_branch())."'"));
	}
	function list_admins()
	{
	  return get_array_by_query("SELECT id_user FROM maintainers WHERE id_branch='".int($this->get_id_branch())."' UNION SELECT id_user FROM admins JOIN projects USING (id_prj) JOIN branches USING (id_prj) WHERE id_branch='".int($this->get_id_branch())."'");
	}
	function list_releases($except_rel=0)
	{
		return (get_array_by_query('SELECT id_rel FROM releases WHERE id_branch=\''.int($this->get_id_branch()).'\' AND id_rel<>'.int($except_rel).' ORDER BY date_rel DESC'));
	}
	function get_last_release()
	{
		$result = sql_do('SELECT id_rel FROM releases WHERE date_rel like (SELECT max(date_rel) FROM releases) AND id_branch=\''.int($this->get_id_branch()).'\'');
		if ($result->numRows() == 0)
			return (0);
		else {
			$row = $result->fetchRow();
			return ($row[0]);
		}
	}
}

function branch_get_by_id($id_branch)
{
	$result = sql_do('SELECT id_branch,name_branch,id_prj,date_branch FROM branches WHERE id_branch=\''.int($id_branch).'\'');
	if ($result->numRows() != 1) {
		return (0);
	}
	$row = $result->fetchRow();
	$branch = new Branch;
	$branch->set_id_branch($row[0]);
	$branch->set_name_branch($row[1]);
	$branch->set_id_prj($row[2]);
	$branch->set_date_branch($row[3]);

	return ($branch);
}

function branch_new($name, $id_prj)
{
	$id_branch = pick_id('branches_id_branch_seq');

	try {
		sql_do('INSERT INTO branches (id_branch,name_branch,id_prj,date_branch) VALUES (\''.int($id_branch).'\',\''.str($name).'\',\''.int($id_prj).'\',\''.date('Y-m-d H:i:s').'\')');
	} catch (DatabaseException $e) {
		return (0);
	}
	return ($id_branch);
}
