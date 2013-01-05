<!-- Commented out so that header()  works, this makes the site URL always be at the root and doesn't expose ugly urls to the user
<html>
<body> 
-->
<?
require_once('functions.php');
header("Location: http://" . $addy. "");

{
	switch ($_POST['form']['action']) {

		case "addship":

//			header("Location: http://localhost/efm");
//			print "request=".$_REQUEST['dna']."<br/>";
//			print "dna=".$_POST['dna']."<br/>";
			$start = $_POST['dna'];

			// DNA extract = cat dna | sed "s/[^:]*.//" | sed "s/:[^:]*$//"
			// Ship name extract = cat dna | sed "s/[^:>]*.//" | sed "s/[^>]*.//" | sed "s/<[^<]*$//" | sed "s/'//"

			$dna = preg_replace("/>/","&gt",preg_replace("/</","&lt",$start));
			$dna = preg_replace("/\\\'/","",$dna);
			$dna = preg_replace("/'/","",$dna);
			$dna = preg_replace("/[^:]*$/","",$dna);
//			$dna = preg_replace("/^[^:]*/","",$dna);
			$dna = preg_replace("/.*?fitting:/","",$dna);
			$dna = preg_replace("/^:/","",$dna);
			$rdna = explode(":",$dna);
			print("dna=[".$dna."]<br/>");

			$ship = preg_replace('@<[\/\!]*?[^<>]*?>@si','',$start);
			$ship = preg_replace("/\\\'/","",$ship);
			$ship = preg_replace("/'/","",$ship);
			$ship = preg_replace('/^[^>]*. /','',$ship);
//			print("ship=[".$ship."]<br/>");

			$role = $_POST['role'];
			$pilotname = $_SERVER['HTTP_EVE_CHARNAME'];
//			$pilotname = 'Veto Garsk';
			$pilotcorp = $_SERVER['HTTP_EVE_CORPNAME'];
			$pilotloc = $_SERVER['HTTP_EVE_SOLARSYSTEMNAME'];

			$query = "INSERT INTO ships (name,role,system,dna,ship_id,shipname) VALUES('$pilotname','$role','$pilotloc','$dna','$rdna[0]','$ship')";
			RunQuery($query);
			header("Location: http://" . $addy . "");
			ShowFleet();
		break;

		case "modifyitems":

			$dnapilot = $_POST['pilotid'];
			echo "pilotid=[" . $dnapilot . "]<br/>";
			$ilist = GetItemIDs($dnapilot);
			$items = explode("," , $ilist );
			foreach ( $items as $item ) {

				$effectid = $_POST[$item];
				echo $item . " -> " . $effectid . "<br/>";
//				if ( $effectid ) {
//					$query = "INSERT OR REPLACE INTO lookup ( module_id, effect_id ) VALUES ( '" . $item . "' , '" . $effectid . "' )";
					$query = "INSERT OR REPLACE INTO lookup ( module_id, effect_id ) VALUES ( '" . $item . "' , '" . $effectid . "' )";
					RunQuery($query);
//				}

			}
			ShowFleet();
			
		break;
					
		default:
			echo"<pre>";
			print_r($_POST);
			echo "</pre>";
	}
}
?>
<!-- removed since the matching header isn't there for the header() to work </body></html> -->
