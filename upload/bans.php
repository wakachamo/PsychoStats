<?php

define("PSYCHOSTATS_PAGE", true);
include(dirname(__FILE__) . "/includes/common.php");
$cms->init_theme($ps->conf['main']['theme'], $ps->conf['theme']);
$ps->theme_setup($cms->theme);
$cms->theme->page_title('PsychoStats - Bans');

function get_plrid_by_uniqueid($db, $uniqueid)
{
  $cmd = "SELECT plrid FROM ps.ps_plr p WHERE p.uniqueid='".mysql_escape_string($uniqueid)."';";
  $result = $db->query($cmd);
  $plrid = mysql_fetch_array($result);

  return $plrid[0];
}

$db = &$ps->db;
$result = $db->query("SELECT * FROM ps.advanced_bans ORDER BY admin_name;");
$bancount = mysql_num_rows($result);
$downloads = array();
$i = 0;
while ($res = mysql_fetch_array($result))
{
  $downloads[$i] = $res;
  $i++;
}

$table = $cms->new_table($downloads);
$table->if_no_data($cms->trans("No Downloads"));
$table->attr('class', 'ps-table ps-ban-table');
$table->start_and_sort($start, $sort, $order);
$table->columns(array(

  'name' => array( 'label' => 'Playername' ),
  'steamid' => array( 'label' => 'IP Adress' ),
  'admin_name' => array( 'label' => 'Banned by' ), 
  'reason' => array( 'label' => 'Ban Reason' ),
  'unbantime' => array( 'label' => 'Banned till' ),

));
$table->column_attr('name', 'class' ,'left');
$cms->filter('ban_table_object', $table);

// assign variables to the theme

$cms->theme->assign(array(
  'ban_table' => $table->render(),
  'bancount' => $bancount,
));


//display the output

$basename = basename(__FILE__, '.php');
$cms->full_page($basename, $basename, $basename.'_header', $basename.'_footer');

?>
