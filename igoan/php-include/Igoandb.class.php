<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: Igoandb.class.php,v 1.1 2005/01/03 01:43:46 cam Exp $
#
# This file is part of the Igoan project.
#
# Igoan is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation in the version 2 of the License.
#
# Igoan is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Igoan; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA	02111-1307	USA
#
?>
<?php

require 'Result.class.php';

final class Igoandb
{
	private $db;

	function __construct()
	{
		require 'db.php';
	}
	function __destruct()
	{
		if ($this->is_connected()) {
			$this->query('ROLLBACK');
			pg_close($this->db);
		}
	}
	protected function is_connected()
	{
		if (!$this->db) {
			return false;
		}
		if (pg_connection_status($this->db) != PGSQL_CONNECTION_OK) {
			return false;
		}
	}
	protected function connect()
	{
		if (!$this->is_connected()) {
			$this->db = pg_connect('host='.DB_HOST.' user='.DB_USER.' password='.DB_PASS.' port='.DB_PORT.' dbname='.DB_BASE);
			if (!$this->db) {
				throw new DatabaseException('Unable to connect to database'.pg_last_error());
			}
		}
	}
	function query($sql)
	{
		$this->connect();
		$result = pg_query($this->db, $sql);
		if (!$result) {
			throw new DatabaseException('Unable to query database with "'.$sql.'"'); //: "'.pg_last_error($this->db));
		}
		return new Result($result);
	}
}

class DatabaseException extends Exception{}
?>
