<?

define("PSYCHOSTATS_PAGE", true);
include(dirname(__FILE__) . "/includes/common.php");
include(dirname(__FILE__) . "/includes/PS/data.php");
$cms->init_theme($ps->conf['main']['theme'], $ps->conf['theme']);
$ps->theme_setup($cms->theme);
$cms->theme->page_title('PsychoStats - Data');
//header("Content-Type: text/xml");


$functions = array(
  'days'      => array('retrieve_days',      'days',      'day'    ),
  'hours'     => array('retrieve_hours',     'hours',     'hour'   ),
  'countries' => array('retrieve_countries', 'countries', 'country'),
  'weapons'   => array('retrieve_weapons',   'weapons',   'weapon' ),
  'maps'      => array('retrieve_maps',      'maps',      'map'    )
);

header("Content-Type: text/xml");
$arr = $functions[$_GET['xml']];
if (!isset($arr)) exit();
print xml_serialize_data(call_user_func($arr[0]), $arr[1], $arr[2]);
?>
