<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: view.php,v 1.1 2004/04/08 21:14:41 cam Exp $
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
require_once 'igoan/Project.class.php';
require_once 'igoan/Branch.class.php';
require_once 'igoan/Release.class.php';

if (isset($_GET['id'])) {
	$requested = user_get_by_id($_GET['id']);
	if (!$requested) {
		append_error("Error: unknow user id ({$_GET['id']})");
	}
} else if ($_SESSION['id']) {
	$requested = user_get_by_id($_SESSION['id']);
	if (!$requested) {
		append_error("Error: unknow user id ({$_SESSION['id']})");
	}
} else {
	append_error('Error: no user id given');
}

if (errors()) {
	flush_errors_exit();
}

// MISC
$d_misc = '';
$igoan_admin = '';
$valid_account = '';
if ($requested->is_global_admin())
	$igoan_admin = '<li>This user is an igoan admin. </li>';
if (!$requested->get_valid_user())
	$valid_account = '<li>This user hasn\'t activated his account yet. </li>';
if ($igoan_admin || $valid_account)
	$d_misc = '<dl><dt> Misc: </dt><dd><ul>'.$igoan_admin.$valid_account.'</ul></dd></dl>';

// USER DESCRIPTION
$d_user_desc = '<dl><dt> User description: </dt><dd><p>'.($requested->get_desc_user() ? nl2br($requested->get_desc_user()) : 'None submitted yet.').'</p></dd></dl>';

// LIST OF ADMINed PROJECTS
$d_myprj = '';
$list = $requested->list_projects();
if ($list) {
  foreach ($list as $tmpid) {
    $tmp = project_get_by_id($tmpid);
    if ($tmp) {
      $rel = $tmp->get_last_release();
      if ($rel) {
	$version = $rel->get_name_rel();
	$date = $rel->get_date_rel();
      } else {
	$version = '-';
	$date = '-';
      }
      $d_myprj .= '<tr><td><a href="/project/view.php?id_prj='.$tmp->get_id_prj().'">'.$tmp->get_name_prj().'</a></td><td>'.$version.'</td><td>'.$date.'</td><td>'.(($tmp->get_owner()==$requested->get_id_user())?'Owner':'').'</td></tr>';
    }
  }
  $d_myprj = '<div class="historique"><dl><dt> Projects managed by '.$requested->get_name_user().': </dt>'
    .'<dd><table><thead><tr><th class="short"> Project name </th><th class="short"> Last version </th>'
    .'<th class="short"> Last update </th><th>&nbsp;</th></tr></thead><tbody>'
    .$d_myprj
    .'</tbody></table></dd></dl></div>';
}

// LIST OF MAINTAINED BRANCHES
$d_mybranches = '';
$list = $requested->list_branches();
if ($list) {
  foreach ($list as $tmpid) {
    $tmp = branch_get_by_id($tmpid);
    if ($tmp) {
      $relid = $tmp->get_last_release();
      if ($relid) {
	$rel = release_get_by_id($relid);
	if ($rel) {
	  $version = $rel->get_name_rel();
	  $date = $rel->get_date_rel();
	  $prj = project_get_by_id($tmp->get_id_prj());
	  // FIXME: no verification on $prj
	} else {
	  $version = '-';
	  $date = '-';
	}
	$d_mybranches .= '<tr><td><a href="/project/view.php?id_branch='.$tmp->get_id_branch().'">'.$prj->get_name_prj().'</a></td><td><a href="/project/view.php?id_branch='.$tmp->get_id_branch().'">'.$tmp->get_name_branch().'</a></td><td>'.$version.'</td><td>'.$date.'</td></tr>';
      }
    }
  }
  $d_mybranches = '<div class="historique"><dl><dt> Branches maintained by '.$requested->get_name_user().': </dt>'
    .'<dd><table><thead><tr><th class="short"> Project name </th><th class="short"> Branch name </th>'
    .'<th class="short"> Last version </th><th class="short"> Last update </th></tr></thead><tbody>'
    .$d_mybranches
    .'</tbody></table></dd></dl></div>';
}

// LIST OF CONTRIBUTED RELEASES (those the user appears as an author)
$d_myrel = '';
$list = $requested->list_releases();
if ($list) {
  foreach ($list as $tmpid) {
    $rel = release_get_by_id($tmpid);
    if ($rel) {
      $version = $rel->get_name_rel();
      $date = $rel->get_date_rel();
      $b = branch_get_by_id($tmp->get_id_branch());
      if ($b) {
	$prj = project_get_by_id($b->get_id_prj());
	if ($prj) {
	  $d_myrel .= '<tr><td><a href="/project/view.php?id_rel='.$rel->get_id_rel().'">'.$prj->get_name_prj().' ('.$b->get_name_branch().') '.$version.'</a></td><td>'.$date.'</td></tr>';
	}
      }
    }
  }
  $d_myrel = '<div class="historique"><dl><dt> Releases contributed by '.$requested->get_name_user().': </dt>'
    .'<dd><table><thead><tr><th class="short"> Complete release name </th>'
    .'<th class="short"> Release date </th></tr></thead><tbody>'
    .$d_myrel
    .'</tbody></table></dd></dl></div>';
}      


// Apres les entetes, on affiche les erreurs
if (errors()) {
	flush_errors_exit();
}

// un chti flag
if ($_SESSION['id'] == $requested->get_id_user()) $myself = true;
else $myself = False;

header_box('Igoan :: View User :: ' . $requested->get_name_user());
?>
<div id="main">
<?php login_box($myself ? $requested : 0); categories_box(); ?>
	<div class="item soft">
		<h4> View User :: <?php echo $requested->get_name_user(); ?></h4>
		<div class="infos">
			<div class="screenshot">
			<?php 
				if (!$requested->get_photo())
				{
					echo '<img src="/images/gnome-screenshot.png" title="This user did not submit a photo." alt="No photo" />';
				}
				else
				{
					echo '<img src="/photos/'.$requested->get_login().'/1.png" alt="'.$requested->get_name_user().'\'s Photo" title="'.$requested->get_name_user().'" />';
				}
			?>
			</div>
			<div class="misc">
				<ul>
					<li><strong> Registered: </strong> <?php echo $requested->get_date_user(); ?> </li>
				</ul>
			</div>
			<?php
			if ($requested->get_url_user() != '' || $requested->get_mail() != '')
			{
			?>
				<div class="links">
					<ul>
						<?php
							if ($requested->get_url_user() != '')
								echo '<li><a href="' . $requested->get_url_user() . '">homepage</a></li>';
							if ($requested->get_mail() != '')
								echo '<li><a href="mailto:' . $requested->get_mail() . '">e-mail</a></li>';
						?>
					</ul>
				</div>
			<?php
			}
			?>
		</div>
		<div class="description">
			<dl>
				<dt> Real name: </dt>
				<dd> <?php echo $requested->get_name_user(); ?> </dd>
			</dl>
			<dl>
				<dt> User id: </dt>
				<dd> #<?php echo $requested->get_id_user(); ?> </dd>
			</dl>
			<?php echo $d_user_desc; ?>
			<?php echo $d_misc; ?>
		</div>
		<?php 
		echo $d_myprj;
                echo $d_mybranches;
                echo $d_myrel; ?>
		<br style="clear: both" />	
	</div>
	<br style="clear: both" />
</div>
<?php
footer_box();
?>
