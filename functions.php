<?

$dbname = "tfm.db";
$addy = "184.173.248.124/tfm";
$db = "";
$iconsize = 30;

Init();

function Init() {

	global $dbname;
	global $db;

//	$fi = file_exists($dbname);
//	echo "dbname=$dbname file_exists=$fi<br/>";
	if ( file_exists($dbname) ) {

//		echo "Opening DB<br/>";
		OpenDB();
	
	} else {

//		echo "Creating Database<br/>";
		CreateDB();
		OpenDB();

	}
}



function CreateDB() {

	global $dbname;

//	echo "In Create DB dbname=$dbname<br/>";
	if ($db = sqlite_open($dbname, 0666, $sqliteerror)) {
		sqlite_query($db, 'CREATE TABLE ships (
			id integer NOT NULL,
			name varchar(32), 
			dna varchar(128),
			system varchar(128),
			role varchar(16),
			ship_id integer,
			shipname	varchar(64),
			PRIMARY KEY (id))');
		sqlite_query($db, 'CREATE TABLE effects (
			id integer NOT NULL,
			name	varchar(32),
			image  varchar(256),
			PRIMARY KEY (id))');
		sqlite_query($db, 'CREATE TABLE lookup (
			module_id	integer,
			effect_id	integer,
			PRIMARY KEY (module_id))');
		sqlite_query($db, 'CREATE TABLE items (
			id integer NOT NULL,
			module_id	integer,
			name			varchar(128),
			PRIMARY KEY (id))');
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('Armor Reps','img/icon01_11.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('Shield Reps','img/icon01_16.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('Cap Transfer','img/icon01_02.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('Nosferatu','img/icon01_03.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('Neut','img/icon12_04.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('Point','img/icon03_07P.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('Scram','img/icon03_07S.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('Bubble','img/icon56_08.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('Web','img/icon12_06.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('Tracks','img/icon05_07.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('Painter','img/icon56_01.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('Damps','img/icon04_11.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('ECM Ladar(Minmitar)','img/icon63_15.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('ECM Radar(Amarr)','img/icon63_16.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('ECM Mag(Gallente)','img/icon63_14.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('ECM Grav(Caldari)','img/icon63_13.png')");
		sqlite_query($db,"INSERT INTO effects (name, image) VALUES ('ECM Multispec','img/icon63_16.png')");
	} else {
		die ($sqliteerror);
	}
	sqlite_close($db);
	OpenDB();
	Import();
}

function Import() {

	$fh = fopen( "typeids.csv", "r" );
	$i=0;
	echo "Importing Database, please wait for the page to load.<br/>";
	while ( !feof($fh) ) {
		$line = fgets($fh);
		$ld = explode( ",", $line );
		$iname = preg_replace("/\"/","", $ld[1]);
		$iname = preg_replace("/\\\/","", $iname);
		$iname = preg_replace("/'/","", $iname);
//		echo "Importing ".$iname." with id ".$ld[0]."<br/>";
		$insert = "INSERT INTO items (module_id, name) VALUES (".$ld[0].", '".$iname."')";
		$rval = RunQuery($insert);
	//	$i++;
	//	if ( $i == 10 ) exit;

	}
	echo "Done<br/>";
}

function OpenDB() {

	global $dbname;
	global $db;

	if ( $db ) sqlite_close($db);
	if ($db = sqlite_open($dbname, 0666, $sqliteerror)) {
	} else {
		die ($sqliteerror);
	}
}

function RunQuery($query) {

	global $db;

//	$ok = sqlite_exec($db,$query);
	OpenDB();

	$val = sqlite_query($db,$query);

	if (!$val) die("Cannot complete $query<br/>");
	return $val;

}

function DeleteShipRow($id) {

	echo "Would delete row [".$id."]<br/>";

}

function AmIFC($myname) {

//	$myname = $_SERVER[HTTP_EVE_CHARNAME];

	$qry = "SELECT role FROM ships WHERE name='" . $myname . "'";
//	echo "SELECT role FROM ships WHERE name='" . $myname . "'<br/>";
	$res = RunQuery($qry);
	$fce = sqlite_fetch_array($res, SQLITE_ASSOC);
//	echo "role=".$fce[role]."<br/>";
	if ( $fce[role] == "FC" ) {
		return 1;
	} else {
		return 0;
	}
}


function ShowFleet() {

	global $db;
	global $iconsize;

	$eff_tot	= array(32);

//	echo "We are in ShowFleet()<br/>";
	echo "<html><br/><strong>Fleet</strong>";
	echo "<table class='gridtable' border='0'>";
	//echo "<th>ID</th><th>Pilot</th><th>Role</th><th>Ship</th><th>DNA</th><th>System</th><th>Name</th>";
//	echo "<thead><th><img src='img/icon02_16.png' width='28' title='Pilot'></th><th>Role</th><th><img src='img/icon09_05.png' width='28' title='Ship'></th><th>System</th><th>Name</th>";
//	echo "<thead><th><img src='img/icon02_16.png' width='" . $iconsize . "' title='Pilot'></th><th>Role</th><th><img src='img/icon09_05.png' width='" . $iconsize . "' title='Ship (Ship name)'></th><th><img src='img/12_64_2.png' width ='" . $iconsize . "' height='" . $iconsize . "' title='System'></th>";
	echo "<thead><th><img src='img/icon02_16.png' width='" . $iconsize . "' title='Pilot'></th><th><img src='img/icon09_05.png' width='" . $iconsize . "' title='Ship (Ship name)'></th><th><img src='img/12_64_2.png' width ='" . $iconsize . "' title='System'></th>";
	$query = "SELECT * FROM effects";
	$results = RunQuery($query);
	$effnum = 0;
	while ($entry = sqlite_fetch_array($results, SQLITE_ASSOC)) {
//		echo "<th>".$entry[name]."</th>";
		echo "<th><img src='" . $entry[image] . "' title='" . $entry[name] . "' width='" . $iconsize . "'></th>";
		$effnum++;
	}
	echo "<th></th><th></th><th></th>";
	echo "</thead>";
	$query = "SELECT * from ships;";
	$results = RunQuery($query);
	$total_pilots = 0;
	echo "<tbody>";
	while ($entry = sqlite_fetch_array($results, SQLITE_ASSOC)) {
		$total_pilots++;
		echo "<tr>";
//		echo '<td>' . $entry[id] . '</td>';
		echo '<td>' . $entry[name] . '</td>';
//		echo '<td>' . $entry[role] . '</td>';
		echo '<td>' . GetItemName($entry[ship_id]) . ' (' . $entry[shipname] . ')</td>';
//		echo '<td>' . $entry[dna] . '</td>';
		echo '<td>' . $entry[system] . '</td>';
//		echo '<td>' . $entry[shipname] . '</td>';
		$query = "SELECT * FROM effects";
		$eff_res = RunQuery($query);
		while ($eff_ent = sqlite_fetch_array($eff_res, SQLITE_ASSOC)) {
			$neff = HowManyOfEffect($entry[id], $eff_ent[id]);
			echo "<td>" . ( $neff>0 ? $neff:"") . "</td>";
			$eff_tot[$eff_ent[id]] += $neff;
		}
		if ( ( $_SERVER[HTTP_EVE_CHARNAME] == $entry[name] )  || AmIFC($_SERVER[HTTP_EVE_CHARNAME]) ) {
//		if ( ( $_SERVER[HTTP_EVE_CHARNAME] == $entry[name] )  ) {
//		if ( 0 ) { 
			echo '<td><a href=delrow.php?id='.$entry[id].'><img src="img/Delete-icon.png" width="14"/></a></td>';
			echo '<td><a href=modify.php?id='.$entry[id].'><img src="img/Gear-icon.png" width="14"/></a></td>';
		} else {
			echo '<td> </td><td> </td>';
		}
		echo "<td><a href='javascript:CCPEVE.showFitting(\"" . $entry[dna] . "\");'><img src='img/icon09_05.png' width='16' title='Show fitting'></a></td>";
		echo "</tr>";
	}
	echo "<tr>";
	echo "<td><b>Total</b></td><td></td><td><b>".$total_pilots."</b></td>";
	for ($i=1;$i<=$effnum;$i++) {
		echo "<td><b>" . ( $eff_tot[$i]>0 ? $eff_tot[$i]:"" ) . "</b></td>";
	}
	echo "<td></td><td></td>";
	echo "</tr>";
	echo "</tbody></table>";
	echo "</html>";

}

function HowManyOfEffect($pid, $eid) {

//	echo "pid=$pid eid=$eid<br/>";
	$ilist = GetItemIDs($pid);
//	echo "ilist=$ilist<br/>";
	$sum_effect = 0;

	$items = explode( "," , $ilist );
//	echo "items=$items<br/>";
//	var_dump($items);
	foreach ($items as $ii) {

	//	echo "item=$ii eid=$eid<br/>";
		if ( GetItemEffect($ii) == $eid ) {

			// now go find out how many of this he has
			$sum_effect += GetCount($pid, $ii);
		}

	}
	return $sum_effect;
}

function GetCount($pid, $item) {

	$dna = GetDNA($pid); 

	$ilist = explode(":",$dna);
//	echo "ilist=" . $ilist . "<br/>";
	foreach ( $ilist as $ii ) {

//		echo "ii=" . $ii . "<br/>";
		$decomp = explode( ";" , $ii );
		if ( $decomp[0] == $item ) return $decomp[1];
	}
}

function GetItemName($id) {

	$query = "SELECT name FROM items WHERE module_id='".$id."'";
//	echo "query=".$query."<br/>";
	$results = RunQuery($query);
	$data = sqlite_fetch_array($results, SQLITE_ASSOC);
	return $data[name];

}

function GetItemEffect($id) {

	$query = "SELECT effect_id FROM lookup WHERE module_id='".$id."'";
//	echo "SELECT effect_id FROM lookup WHERE module_id='".$id."'";

	$results = RunQuery($query);
	$data = sqlite_fetch_array($results, SQLITE_ASSOC);
//	echo $data[effect_id] . "<br/>";
	return $data[effect_id];

}

function GetDNA($id) {

	$ritems = "";

	$query = "SELECT dna FROM ships WHERE id='".$id."'";
	$results = RunQuery($query);
	while ( $entry = sqlite_fetch_array($results) ) {

		$ritems = preg_replace("/^[^:]*./","",$entry[dna]);

	}
//	echo "ritems=[" . $ritems . "]<br/>";
	return $ritems;

}

function GetItemIDs($id) {

	$ritems = "";

	$query = "SELECT dna FROM ships WHERE id='".$id."'";
	$results = RunQuery($query);
	while ( $entry = sqlite_fetch_array($results) ) {

		$items = explode(":",$entry[dna]);
		// echo "entry[dna]=[".$entry[dna]."]<br/>";
	   $i=0;
	   foreach ( $items as $item ) {
		//    echo "item=[".$item."]<br/>";
					
		   if ( $i > 0 && $item ) {
			//    if ( $item ) {
				$itemid = explode(";",$item);
				$ritems = $ritems . ($i==1? "":",") . $itemid[0];
			}
			$i++;
		}
	}
//	echo "ritems=[" . $ritems . "]<br/>";
	return $ritems;

}




?>
