<?php
#
# Copyright (c) 2003-2004 Igoan.
# Please see the file CREDITS supplied with Igoan to see the full list
# of copyright holders.
#
# $Id: view.php,v 1.1 2004/04/08 21:15:52 cam Exp $
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

error_reporting(E_ALL);

require_once 'igoan/User.class.php';
require_once 'igoan/Project.class.php';
require_once 'igoan/Branch.class.php';
require_once 'igoan/Release.class.php';

$me = user_get_by_id($_SESSION['id']);

// 3 param?tres (exclusifs) sont possibles: id_prj, id_branch et id_rel
// le choix se fera dans cet ordre de priorit? d?croissant: id_rel id_branch id_prj
$my_rel = $my_branch = $my_prj = 0;

if (!empty($_GET['id_rel'])) {
	$my_rel = release_get_by_id($_GET['id_rel']);
	if (!$my_rel) {
		append_error('Invalid release ID.');
		http_redir('/index.php');
	}
	$my_branch = branch_get_by_id($my_rel->get_id_branch());
	if (!$my_branch) {
		append_error('Unable to fetch branch information.');
		http_redir('/index.php');
	}
	$my_prj = project_get_by_id($my_branch->get_id_prj());
	if (!$my_prj) {
		append_error('Unable to fetch project information.');
		http_redir('/index.php');
	}
	$request = $my_rel;
} elseif (!empty($_GET['id_branch'])) {
	$my_branch = branch_get_by_id($_GET['id_branch']);
	if (!$my_branch) {
		append_error('Invalid branch ID.');
		http_redir('/index.php');
	}
	$my_prj = project_get_by_id($my_branch->get_id_prj());
	if (!$my_prj) {
		append_error('Unable to fetch project information.');
		http_redir('/index.php');
	}
	$my_rel_id = $my_branch->get_last_release();
	if ($my_rel_id) 
		$my_rel = release_get_by_id($my_rel_id);
		// c'est possible qu'il n'y ait pas de release
	$request = $my_branch;
} elseif (!empty($_GET['id_prj'])) {
	$my_prj = project_get_by_id($_GET['id_prj']);
	if (!$my_prj) {
		append_error('Invalid project ID.');
		http_redir('/index.php');
	}
	$my_branch_id = $my_prj->get_default_branch();
	if ($my_branch_id) {
		$my_branch = branch_get_by_id($my_branch_id);
	}
	// c'est possible qu'il n'y ait pas de branche :(
	if ($my_branch) {
		$my_rel_id = $my_branch->get_last_release();
		if ($my_rel_id) {
			$my_rel = release_get_by_id($my_rel_id);
		}
	}
	$request = $my_prj;
} else {
	append_error('No project requested.');
	http_redir('/index.php');
}

// is the visitor an admin/maintainer ?
$isadmin = $me && $my_prj->is_admin($me->get_id_user());
$ismaint = $isadmin || $me && $my_branch->is_maintainer($me->get_id_user());

// the branches and releases to show
$releases = $my_branch
	? $my_branch->list_releases() //($my_rel && !$ismaint) ? $my_rel->get_id_rel() : -1)
	: 0;
$branches = $my_prj->list_branches(); //($my_branch && !$isadmin) ? $my_branch->get_id_branch() : -1);


// processing datas to be shown

// PAGE TITLE
$d_full_title = $my_prj->get_name_prj();
if ($my_rel) $d_full_title .= ' - '.$my_rel->get_name_rel();
if ($branches && $my_branch) $d_full_title .= ' ('.$my_branch->get_name_branch().')';

// SCREENSHOT DIV
$d_screenshot = ($my_prj->get_screenshot() != "")
	? '<div class="screenshot"><a href="'.$my_prj->get_screenshot().'"><img src="/minishots/'.$my_prj->get_shortname().'.png" width="150" alt="" /></a></div>'
	: '';

// MISC INFOS DIV
$d_misc = '<div class="misc"><ul><li><strong> Updated: </strong> '.($my_rel?($my_rel->get_date_rel()):($my_branch?($my_branch->get_date_branch()):$my_prj->get_date_prj())).'</li>';
if ($my_rel) { 
	$d_misc .= '<li><strong> License: </strong>'.$my_rel->get_id_lic().'</li>';
	$d_misc .= '<li><strong> Status: </strong>'.$release_status[$my_rel->get_status()].'</li>';
	$d_misc .= '<li><strong> Version: </strong>'.$my_rel->get_name_rel().'</li>';
}
$d_misc .= '</ul></div>';

// LINKS DIV
$homepage = $my_prj->get_url_prj();
$download = $my_rel ? $my_rel->get_download() : '';
$d_links = (!empty($homepage) || !empty($download))
	? '<div class="links"><ul>'.
		($homepage
			? '<li><a href="'.$homepage.'">homepage</a></li>'
			: '').
		($download
			? '<li><a href="'.$download.'">download</a></li>'
			: '').
		'</ul></div>'
	: '';

// ADMINISTRATION
$d_admin = '';
if ($isadmin || $ismaint) { # FIXME: enlever links
	$d_admin = '<div class="admin links"><ul>';
	if ($isadmin) {
		$d_admin .= '<li> <a href="/project/new_branch.php?id_prj='.$my_prj->get_id_prj().'"> Add a branch </a> </li>';
		$d_admin .= '<li> <a href="/project/add_user.php?id_prj='.$my_prj->get_id_prj().'"> Add an admin </a> </li>';
		$d_admin .= '<li> <a href="/project/add_user.php?id_branch='.$my_branch->get_id_branch().'"> Add a maintainer </a> </li>';
	}
	/*
	if ($isadmin && $ismaint) {
		$d_admin .= '<hr/>';
	}
	*/
	if ($ismaint) {
		$d_admin .= '<li> <a href="/project/new_release.php?id_branch='.$my_branch->get_id_branch().'"> Add a release </a> </li>';
		if ($my_rel)
			$d_admin .= '<li> <a href="/project/add_user.php?id_rel='.$my_rel->get_id_rel().'"> Add an author </a> </li>';
	}
	$d_admin .= '</ul></div>';
}

// AUTHORS
$d_authors = '';
$list = $request->list_authors();
if ($list) {
	foreach ($list as $author_id) {
		$author = user_get_by_id($author_id);
		if ($author) $d_authors .= ', <a href="/user/view.php?id='.$author->get_id_user().'">'.$author->get_name_user().'</a>';
	}
	$d_authors = '<dl><dt> Author(s): </dt><dd>'.substr($d_authors, 2).'.</dd></dl>';
}

// ADMINS LIST (project admins + maintainers)
$d_admins = '';
$list = $my_branch->list_admins();
if ($list) {
	foreach ($list as $tmpid) {
		$tmp = user_get_by_id($tmpid);
		if ($tmp) $d_admins .= ', <a href="/user/view.php?id='.$tmp->get_id_user().'">'.$tmp->get_name_user().'</a>';
	}
	$d_admins = '<dl><dt> Admin(s): </dt><dd>'.substr($d_admins, 2).'.</dd></dl>';
}


// PLATFORMS
$d_pfs = '';
$list = $request->list_platforms();
if ($list) {
	foreach ($list as $pf_id) {
		$pf = platform_get_by_id($pf_id);
		if ($pf) $d_pfs .= ', '.$pf->get_name_pf();
	}
	$d_pfs = '<dl><dt> Platform(s): </dt><dd>'.substr($d_pfs, 2).'.</dd></dl>';
}

// LANGUAGES
$d_langs = '';
$list = $request->list_languages();
if ($list) {
	foreach ($list as $lang_id) {
		$lang = language_get_by_id($lang_id);
		if ($lang) $d_langs .= ', '.$lang->get_name_lang();
	}
	$d_langs = '<dl><dt> Programmation languages: </dt><dd>'.substr($d_langs, 2).'.</dd></dl>';
}

// CATEGORIES
$d_cats = '';
$list = $request->list_categories();
if ($list) {
	foreach ($list as $cat_id) {
		$cat = category_get_by_id($cat_id);
		if ($cat) $d_cats .= ', '.$cat->get_name_cat();
	}
	$d_cats = '<dl><dt> Categories: </dt><dd>'.substr($d_cats, 2).'.</dd></dl>';
}





// show the data (NO PROCESSING HERE PLEASE, ONLY ECHOs)
header_box('Igoan :: View Project :: '.$my_prj->get_name_prj());
flush_errors(); ?>
<div id="main"><?php
// these are the "stuff" section
login_box($me);
categories_box();

#echo $my_prj.' '.$my_branch.' '.$my_rel;
?>
	<div class="item soft">
		<h4> <?php echo $d_full_title; ?> </h4>
		<div class="infos">
			<?php
			echo $d_screenshot;
			echo $d_misc; 
			echo $d_links;
			echo $d_admin;
			?>
		</div>
		<div class="description">
			<dl>
				<dt> Project summary: </dt>
				<dd>
					<?php echo nl2br($my_prj->get_desc_prj()); ?>
				</dd>
			</dl>
			<?php
			echo $d_authors;
			echo $d_pfs;
			echo $d_langs;
			echo $d_cats;
			echo $d_admins;
			?>
		</div><?php
		if ($releases) { ?>
			<div class="historique">
			<dl>
				<dt> All releases <?php if ($branches || $isadmin) echo 'for this branch ('.$my_branch->get_name_branch().')'; ?>: </dt>
				<dd>
					<table>
						<thead>
							<tr>
								<th class="short"> Version </th>
								<th class="short"> Release&nbsp;date </th>
								<th> Changes summary </th>
							</tr>
						</thead>
						<tbody><?php
						foreach ($releases as $rel_id) {
							$rel = release_get_by_id($rel_id);
							if (!$rel) continue;
							?>
							<tr>
								<td> <a href="/project/view.php?id_rel=<?php echo $rel->get_id_rel(); ?>"><?php echo $rel->get_name_rel(); ?></a> </td>
								<td> <?php echo $rel->get_date_rel(); ?> </td>
								<td> <?php echo $rel->get_changes(); ?> </td>
							</tr><?php
						} ?>
						</tbody>
					</table>
				</dd>
			</dl>
			</div><?php
		}
		if ($branches) { ?>
			<div class="historique">
			<dl>
				<dt> The branches: </dt>
				<dd>
					<table>
						<thead>
							<tr>
								<th class="short"> Branch </th>
								<th class="short"> Last version </th>
								<th> Release date </th><?php
							?></tr>
						</thead>
						<tbody><?php
						foreach ($branches as $branch_id) {
							$branch = branch_get_by_id($branch_id);
							if (!$branch) continue;
							$last_id = $branch->get_last_release();
							$last = $last_id
								? release_get_by_id($last_id)
								: 0;
							?>
							<tr>
								<td> <a href="/project/view.php?id_branch=<?php echo $branch->get_id_branch(); ?>"><?php echo $branch->get_name_branch(); ?></a> </td>
								<td> <?php echo $last ? $last->get_name_rel() : '-'; ?> </td>
								<td> <?php echo $last ? $last->get_date_rel() : '-'; ?> </td><?php
							?></tr><?php
						} ?>
						</tbody>
					</table>
				</dd>
			</dl>
		</div><?php
		} // end if(my_branch) ?>
		<br style="clear: both" />
	</div>
	<br style="clear: both" />
</div>
<?php
footer_box();
?>
