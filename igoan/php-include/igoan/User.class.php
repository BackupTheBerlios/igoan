<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: User.class.php,v 1.1 2005/01/03 02:21:05 cam Exp $
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

class User
{
	protected $_id_user;
	protected $_name_user;
	protected $_mail;
	protected $_url_user;
	protected $_date_user;
	protected $_valid_user;
	protected $_login;
	protected $_passwd;
	protected $_desc_user;
	protected $_nb_logins;
	protected $_photo;

	function set_id_user($id)
	{
		$this->_id_user = int($id);
	}
	function get_id_user()
	{
		return ($this->_id_user);
	}
	function set_name_user($user)
	{
		if (!empty($user)) {
			$this->_name_user = $user;
		}
	}
	function get_name_user()
	{
		return ($this->_name_user);
	}
	function set_mail($mail)
	{
		if (!empty($mail)) {
			$this->_mail = $mail;
		}
	}
	function get_mail()
	{
		return ($this->_mail);
	}
	function set_url_user($url)
	{
		$this->_url_user = $url;
	}
	function get_url_user()
	{
		return ($this->_url_user);
	}
	function set_date_user($date)
	{
		$this->_date_user = $date;
	}
	function get_date_user()
	{
		return ($this->_date_user);
	}
	function set_valid_user($valid)
	{
		$this->_valid_user = (boolean)$valid;
	}
	function get_valid_user()
	{
		return ($this->_valid_user);
	}
	function set_login($login)
	{
		// FIXME: une meilleure vérification, que diable (pas diantre)!
		if (!empty($login)) {
			$this->_login = $login;
		}
	}
	function get_login()
	{
		return ($this->_login);
	}
	function set_passwd($pass)
	{
		if (!empty($pass)) {
			$this->_passwd = $pass;
		}
	}
	function get_passwd()
	{
		return ($this->_passwd);
	}
	function set_desc_user($desc)
	{
		$this->_desc_user = $desc;
	}
	function get_desc_user()
	{
		return ($this->_desc_user);
	}
	function set_nb_logins($nb)
	{
		$nb = int($nb);
		if ($nb > 0)
			$this->_nb_logins = $nb;
	}
	function get_nb_logins()
	{
		return ($this->_nb_logins);
	}
	function set_photo($photo)
	{
		$this->_photo = $photo;
	}
	function get_photo()
	{
		return ($this->_photo);
	}
	function is_global_admin()
	{
		$result = sql_do('SELECT id_user FROM igoan_admins WHERE id_user=\''.int($this->get_id_user()).'\'');
		return ($result->numRows() > 0);
	}
	function validate()
	{
		sql_do('UPDATE users SET valid_user=\'1\' WHERE id_user=\''.int($this->get_id_user()).'\'');
		$this->set_valid_user(1);
	}
	function write()
	{
		sql_do('UPDATE users SET name_user=\''.str($this->get_name_user()).'\',mail=\''.str($this->get_mail()).
		       '\',url_user=\''.str($this->get_url_user()).'\',passwd=\''.str($this->get_passwd()).
		       '\',desc_user=\''.str($this->get_desc_user()).
		       '\' WHERE id_user=\''.int($this->get_id_user()).'\'');
		$this->validate();
	}
	function list_projects()
	{
		return get_array_by_query('SELECT id_prj FROM admins WHERE id_user=\''.int($this->get_id_user()).'\'');
	}
	function list_branches()
	{
		return get_array_by_query('SELECT id_branch FROM maintainers WHERE id_user=\''.int($this->get_id_user()).'\'');
	}
	function list_releases()
	{
		return get_array_by_query('SELECT id_rel FROM authors WHERE id_user=\''.int($this->get_id_user()).'\'');
	}
}

function user_get_by_id($id_user)
{
	$result = sql_do('SELECT name_user,mail,url_user,date_user,valid_user,login,passwd,desc_user,nb_logins,photo FROM users WHERE id_user=\''.int($id_user).'\'');
	if ($result->numRows() != 1) {
		return (0);
	}
	$row = $result->fetchRow();
	$user = new User;
	$user->set_id_user($id_user);
	$user->set_name_user($row[0]);
	$user->set_mail($row[1]);
	$user->set_url_user($row[2]);
	$user->set_date_user($row[3]);
	$user->set_valid_user($row[4]);
	$user->set_login($row[5]);
	$user->set_passwd($row[6]);
	$user->set_desc_user($row[7]);
	$user->set_nb_logins($row[8]);
	$user->set_photo($row[9]);

	return ($user);
}

function user_get_by_password($login, $passwd)
{
	$result = sql_do('SELECT id_user FROM users WHERE login=\''.str($login).'\' AND passwd=\''.str($passwd).'\'');
	if ($result->numRows() != 1) {
		return (0);
	}
	$row = $result->fetchRow();
	return (user_get_by_id($row[0]));
}

function user_get_by_login($login)
{
	$result = sql_do('SELECT id_user FROM users WHERE login=\''.str($login).'\'');
	if ($result->numRows() != 1) {
		return (0);
	}
	$row = $result->fetchRow();
	return (user_get_by_id($row[0]));
}


function user_get_all($valid = -1)
{
	return (get_array_by_query('SELECT id_user FROM users'.(($valid != -1)?(' WHERE valid_user=\''.(bool)$valid.'\''):'')));
}


function user_new($login, $name, $email, $homepage = '', $desc = '')
{
	$result = sql_do('SELECT id_user FROM users WHERE login=\''.str($login).'\'');
	if ($result->numRows()) {
		append_error("Login name '$login' already taken. Please choose another.");
		return (0);
	}

	$result = sql_do('SELECT id_user FROM users WHERE mail=\''.str($email).'\'');
	if ($result->numRows()) {
		append_error('This email address is already registered. Please choose another.');
		return (0);
	}

	$passwd = substr(md5(rand()), 0, 8);

	$id_user = pick_id('users_id_user_seq');
	try {
		$result = sql_do('INSERT INTO users (id_user,name_user,mail,url_user,date_user,valid_user,login,passwd,desc_user) VALUES (\''.int($id_user).'\',\''.str($name).'\',\''.str($email).'\',\''.str($homepage).'\',\''.date('Y-m-d H:i:s').'\',0,\''.str($login).'\',\''.str($passwd).'\',\''.str($desc).'\')');
	} catch (DatabaseException $e) {
		//append_error($e->getMessage());
		return (0);
	}

	return ($id_user);
}

function user_new_pseudo($name, $email)
{
	$result = sql_do("SELECT id_user FROM users WHERE mail='$email'");
	if ($result->numRows()) {
		append_error("This email address is already registered. Please choose another.");
		return (0);
	}

	$id_user = pick_id('users_id_user_seq');
	try {
		$result = sql_do('INSERT INTO users (id_user,name_user,mail,date_user,valid_user) VALUES (\''.int($id_user).'\',\''.str($name).'\',\''.str($email).'\',\''.date('Y-m-d H:i:s').'\',0)');
	} catch (DatabaseException $e) {
		//append_error("Unknown error executing [$sql].");
		return (0);
	}
	return ($id_user);
}
?>
