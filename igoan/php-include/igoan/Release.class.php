<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: Release.class.php,v 1.1 2005/01/03 02:36:50 cam Exp $
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

class Release
{
	// private
	protected $_id_rel;
	protected $_name_rel;
	protected $_date_rel;
	protected $_status;
	protected $_nb_projs;
	protected $_changes;
	protected $_download;
	protected $_valid_rel;
	protected $_id_branch;
	protected $_id_lic;

	// public
	# pour les accesseurs, si des fois faut les refaire: for i in id_rel name_rel date_rel status nb_projs changes download valid_rel id_branch id_lic; do echo -e "\tfunction set_$i(\$$i)\n\t{\n\t\t\$this->_$i = \$$i;\n\t}\n\tfunction get_$i(\$$i)\n\t{\n\t\treturn (\$this->_$i);\n\t}"; done
        function set_id_rel($id_rel)
        {
                $this->_id_rel = int($id_rel);
        }
        function get_id_rel()
        {
                return ($this->_id_rel);
        }
        function set_name_rel($name_rel)
        {
                $this->_name_rel = $name_rel;
        }
        function get_name_rel()
        {
                return ($this->_name_rel);
        }
        function set_date_rel($date_rel)
        {
                $this->_date_rel = $date_rel;
        }
        function get_date_rel()
        {
                return ($this->_date_rel);
        }
        function set_status($status)
        {
                $this->_status = int($status);
        }
        function get_status()
        {
                return ($this->_status);
        }
        function set_nb_projs($nb_projs)
        {
                $this->_nb_projs = int($nb_projs);
        }
        function get_nb_projs()
        {
                return ($this->_nb_projs);
        }
        function set_changes($changes)
        {
                $this->_changes = $changes;
        }
        function get_changes()
        {
                return ($this->_changes);
        }
        function set_download($download)
        {
                $this->_download = $download;
        }
        function get_download()
        {
                return ($this->_download);
        }
        function set_valid_rel($valid_rel)
        {
                $this->_valid_rel = (bool)$valid_rel;
        }
        function get_valid_rel()
        {
                return ($this->_valid_rel);
        }
        function set_id_branch($id_branch)
        {
                $this->_id_branch = int($id_branch);
        }
        function get_id_branch()
        {
                return ($this->_id_branch);
        }
        function set_id_lic($id_lic)
        {
                $this->_id_lic = int($id_lic);
        }
        function get_id_lic()
        {
                return ($this->_id_lic);
        }
	function add_author($id_user)
	{
		sql_do('INSERT INTO authors (id_user,id_rel) VALUES (\''.int($id_user).'\',\''.int($this->get_id_rel()).'\')');
	}
	function list_authors()
	{
		return (get_array_by_query('SELECT id_user FROM authors WHERE id_rel=\''.int($this->get_id_rel()).'\''));
	}
	function list_platforms()
	{
		return get_array_by_query('SELECT id_pf FROM runson WHERE id_rel=\''.int($this->get_id_rel()).'\'');
	}
	function list_languages()
	{
		return get_array_by_query('SELECT id_lang FROM written WHERE id_rel=\''.int($this->get_id_rel()).'\'');
	}
	function list_categories()
	{
		return get_array_by_query('SELECT id_cat FROM belongsto WHERE id_rel=\''.int($this->get_id_rel()).'\'');
	}
}

function release_get_by_id($id_rel)
{
	$result = sql_do('SELECT id_rel,name_rel,date_rel,status,nb_projs,changes,download,valid_rel,id_branch,id_lic FROM releases WHERE id_rel=\''.int($id_rel).'\'');
	if ($result->numRows() != 1) {
		return (0);
	}
	$row = $result->fetchRow();
	$rel = new Release;
	$rel->set_id_rel($row[0]);
	$rel->set_name_rel($row[1]);
	$rel->set_date_rel($row[2]);
	$rel->set_status($row[3]);
	$rel->set_nb_projs($row[4]);
	$rel->set_changes($row[5]);
	$rel->set_download($row[6]);
	$rel->set_valid_rel($row[7]);
	$rel->set_id_branch($row[8]);
	$rel->set_id_lic($row[9]);

	return ($rel);
}

function release_new($id_branch, $name_rel, $status, $changes, $download, $valid_rel)
{
	$id_rel = pick_id('releases_id_rel_seq');
	try {
		$result = sql_do('INSERT INTO releases (id_rel,name_rel,date_rel,status,nb_projs,changes,download,valid_rel) VALUES (\''.int($id_rel).'\',\''.str($name_rel).'\',\''.date('Y-m-d H:i:s').'\',\''.int($status).'\',\''.int($nb_projs).'\',\''.str($changes).'\',\''.str($download).'\',\''.(bool)int($valid_rel).'\')');
	} catch (DatabaseException $e) {
		return 0;
	}

	return ($id_rel);
}

$release_status = array (
	'Undefined',
	'Planning',
	'Pre-Alpha',
	'Alpha',
	'Beta',
	'Production/Stable',
	'Mature',
	'Inactive');
