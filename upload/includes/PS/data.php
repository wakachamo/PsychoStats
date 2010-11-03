<?

if (defined("DATA_PHP")) return 1;
define("DATA_PHP", 1);

function select_string($info)
{
  $info_arr = array();
  foreach ($info as $i) { array_push($info_arr, "SUM($i) $i"); }
  return $info_string = implode(",", $info_arr);

}

function retrieve_countries($max = 10)
{
  // TODO: add rest

  global $ps;

  $list = $ps->db->fetch_rows(1,
    "SELECT pp.cc code,cn name,COUNT(*) count " .
    "FROM $ps->t_plr_profile pp, $ps->t_geoip_cc cc " .
    "WHERE pp.cc IS NOT NULL AND cc.cc=pp.cc " .
    "GROUP BY pp.cc " .
    "ORDER BY 3 DESC LIMIT $max"
  );

  return $list;

}

function retrieve_hours($max = 24)
{
  global $ps;
  $info = array('games', 'rounds', 'kills', 'connections');

  $info_string = select_string($info);
  $list = $ps->db->fetch_rows(1,
    "SELECT statdate,hour,$info_string " .
    "FROM $ps->t_map_hourly " .
    "GROUP BY statdate,hour " .
    "ORDER BY statdate DESC,hour DESC LIMIT $max"
  );

  return $list;
}

function retrieve_days($max = 31)
{
  global $ps;
  $data = array();
  $info = array('games', 'rounds', 'kills', 'suicides', 'ffkills', 'connections');
  
  $info_string = select_string($info);
  $list = $ps->db->fetch_rows(1,
    "SELECT statdate,$info_string " .
    "FROM $ps->t_map_data " .
    "GROUP BY statdate " .
    "ORDER BY statdate DESC LIMIT $max"
  );

  
  $now = $list ? ymd2time($list[0]['statdate']) : time();
  while (count($data) < $max) {
    $arr = array();
    $arr['statdate'] = date('Y-m-d',$now);
    foreach ($info as $i) { $arr[$i] = 0; }

    foreach ($list as $d) {
      if ($d['statdate'] == $arr['statdate']) {
        foreach ($info as $i) { $arr[$i] = $d[$i]; }
      }
    }
    array_push($data, $arr);
    $now -= 60*60*24;
  }

  return array_reverse($data);
}

function retrieve_weapons()
{
  global $ps;
  $weapons = $ps->get_weapon_list(array(
    'sort'    => 'dataid',
    'order'   => 'asc',
    'start'   => 0,
    'limit'   => 100    // there's never more than about 25-30 weapons
  ));
  return $weapons;
}

function retrieve_maps()
{
  global $ps;
  $maps = $ps->get_map_list(array(
    'sort'    => $sort,
    'order'   => $order,
    'start'   => $start,
    'limit'   => $limit,
  ));
  return $maps;
}

function xml_serialize_data($data, $root_name, $child_name)
{
  $doc = new DOMDocument();
  $doc->formatOutput = true;
  $root = $doc->createElement($root_name);

  foreach ($data as $item) {
    $keys = array_keys($item);
    $values = array_values($item);
    $child = $doc->createElement($child_name);
    for ($i = 0; $i < count($item); $i++) { 
      $child->setAttribute($keys[$i], $values[$i]);
    }
    $root->appendChild($child);
  }
  $doc->appendChild($root);
  return $doc->saveXML();
}

?>
