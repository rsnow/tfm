<?
/* echo "test<br/>";

require_once("functions.php");
echo "test<br/>";
echo "http://" . $addy . "";
exit;
$start = GetDNA(1);
echo "ritems = [" . $start . "]<br/>";
echo "3651=" . GetCount(1,3651) . "<br/>";
echo "How many of 3 in 1=" . HowManyOfEffect(1,4) . "<br/>";
exit;
*/


//$start = "Veto Garsk > <url=fitting:588:3651;1:3636;1::>Veto Garsk's Reaper</url>";
$start = "[06:51:58] Deimos Ovaert > <url=fitting:11400:32772;1:519;2:2889;3:5839;1:3888;1:5137;1:31668;1:5973;1:31177;1:4025;1:5439;1:264;20:12625;571:21898;228::>pony</url>";
//print("dna=[".$dna."]<br/>");

$dna = preg_replace("/>/","&gt",preg_replace("/</","&lt",$start));
print("dna=[".$dna."]<br/>");
$dna = preg_replace("/\\\'/","",$dna);
print("dna=[".$dna."]<br/>");
$dna = preg_replace("/'/","",$dna);
print("dna=[".$dna."]<br/>");
$dna = preg_replace("/[^:]*$/","",$dna);
print("dna=[".$dna."]<br/>");
//$dna = preg_replace("/^[^:]*/","",$dna);
$dna = preg_replace("/.*?fitting:/","",$dna);
print("dna=[".$dna."]<br/>");
$dna = preg_replace("/^:/","",$dna);
print("dna=[".$dna."]<br/>");

$ship = preg_replace("/^:/","",$start);
$ship = preg_replace("/^[^>]*. /","",$ship);
$ship = preg_replace("/\'/","",$ship);
print("ship=[".$ship."]<br/>");

$data = explode(":",$dna);
echo "$data[0]<br/>";
echo "$data[1]<br/>";
echo "$data[2]<br/>";
echo "$data[3]<br/>";
print("data[0]=[".$data[0]."]<br/>");
//print('<img src="http://image.eveonline.com/Render/'.$data[0].'_32.png">');


?>
