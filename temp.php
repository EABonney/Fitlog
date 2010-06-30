SELECT SUM( flmain.seconds ) AS seconds_main, SUM( flstrength.seconds ) AS seconds_str
FROM flmain, flstrength
WHERE flmain.workout_date >= '2010-01-10'
AND flmain.workout_date <= '2010-01-17'
AND flmain.user_id =18
AND flmain.plan_type = 'a'
GROUP BY flmain.user_id

SELECT SUM(flmain.seconds) AS seconds_main, SUM(flstrength.seconds) AS seconds_str FROM flmain, flstrength WHERE (flmain.workout_date>='2010-01-18' AND flmain.workout_date<='2010-01-24' AND flmain.user_id=21 AND flmain.plan_type='a') OR (flstrength.workout_date='2010-01-18' AND flstrength.workout_date='2010-01-24' AND flstrength.user_id=21 AND flstrength.plan_type ='a') GROUP BY flmain.user_id

< ?php
/* ------------------------------------------
** Code is released into the public domain.
** Enjoy.
** Chui Tey teyc@cognoware.com
*/

require_once("Image/Graph.php");
require_once("Image/Graph/Line/Solid.php");
require_once("Image/Canvas/ImageMap.php");

function &bargraph($datasets)
{
  $width  =  800;
  $height =  600;

  /* This emits an imagemap or image depending on the URL */
  if ($_SERVER["QUERY_STRING"] == "image.png")
  {
    $canvasType = "png";
  }
  else
  {
    echo '<img src="' . $_SERVER['REQUEST_URI'] .'?image.png" usemap="#fruits"
        width="' . $width .'"
        height="' . $height .'"
 />';
    $canvasType = "imagemap";
  }

  $hgradient = Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_HORIZONTAL, '#336699', '#003366'));
  $vgradient = Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, '#336699', '#003366'));

  $graph =& Image_Graph::factory("graph", array(
    array(
      "canvas" => $canvasType,
      "width"  => $width,
      "height" => $height)));

  $graph->setBackground($vgradient);
  $title = Image_Graph::factory('title', array('  Monthly Fruit Production', 14));
  $title->setAlignment(IMAGE_GRAPH_ALIGN_LEFT);
  $title->setBackground($hgradient);

  $arial =& $graph->addNew('ttf_font', '/nfsn/content/precis/htdocs/verdana.ttf');
  $arial->setColor('#ffffff');
  $graph->setFont($arial);
  $arial->setSize(8);
  $graph->add(
      Image_Graph::vertical(
          $title,
          Image_Graph::vertical(
              $plotarea = Image_Graph::factory('plotarea'),
              $legend   = Image_Graph::factory('legend'),
              90),
          15));

  foreach ($datasets as $name => $dataset)
  {
    $data =& Image_Graph::factory('dataset');
    $data->setName($name);
    foreach ($dataset as $x => $y)
    {
        $data->addPoint($x,$y, array("url"=>"http://www.yahoo.com"));
    }
    $datas[] = $data;
  }

  $linestyle =& new Image_Graph_Line_Solid("#ffffff");
  $linestyle->setThickness(2);

  $fillstyle=Image_Graph::factory('Image_Graph_Fill_Array');
  $fillstyle->add(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, '#ff8205', '#ffe016')));
  $fillstyle->add(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, '#009100', '#00df00')));
  $fillstyle->add(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, '#860000', '#fc0000')));

  $legend->setPlotArea($plotarea);
  $plot =& $plotarea->addNew('bar', array($datas));
  $plot->setLineStyle($linestyle);
  $plot->setFillStyle($fillstyle);

  $plotarea->setPadding(20);
  return $graph;
}

/* =================================
   Example
*/

$datas = array(
  "oranges" =>
    array(
      "Jan" => 17,
      "Feb" => 18,
      "Mar" => 19,
      "Apr" => 10,
      "May" => 12,
   ),
  "pears" =>
    array(
      "Jan" => 11,
      "Feb" => 13,
      "Mar" => 15,
      "Apr" => 18,
      "May" => 22,
   ),
  "apples" =>
    array(
      "Jan" => 15,
      "Feb" => 16,
      "Mar" => 17,
      "Apr" => 18,
      "May" => 18,
   ),

);

$graph =& bargraph($datas);
$graph->done(array('name'=>'fruits'));
?>
