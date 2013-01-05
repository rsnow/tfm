<?
   require_once('functions.php');
?>

<html>

  <head>
  <title>Trivial Fleet Manager</title>
  <link rel="stylesheet" href="style.css" type="text/css">
  </head>

  <body>
	<?
		$ingame = strpos($_SERVER['HTTP_USER_AGENT'], 'EVE-IGB');
		$trust =	$_SERVER['HTTP_EVE_TRUSTED'];

		if ($ingame != false || $dev) {

			if ($_SERVER['HTTP_EVE_TRUSTED']=='No') {

			echo "You'll have to trust the website for this to work.<br/><br/><strong>Reload the Window once you Trust the Site</strong><br/>";
			echo '<script type="text/javascript">CCPEVE.requestTrust("http://' . $addy . '/*");</script>';

			} else {

				$trusted = true;
				$pilotname = $_SERVER['HTTP_EVE_CHARNAME'];
				$pilotcorp = $_SERVER['HTTP_EVE_CORPNAME'];
				$pilotloc = $_SERVER['HTTP_EVE_SOLARSYSTEMNAME'];
				if ( $dev == 1 ) {
					$pilotname = "Veto Garsk";
					$pilotcorp = "Variable Intentions";
					$pilotloc = "Osmeden";
				}

				$query = "SELECT ship_id FROM ships WHERE name='".$pilotname."';";
				$result = RunQuery($query);
				$item = sqlite_fetch_array($result, SQLITE_ASSOC);
				if ( $item[ship_id] ) {

					ShowFleet();

				} else {
?>
					<form method="post" action="formproc.php" >
					<input type='hidden' name='form[action]' value='addship'>
	
					Role:<br/>
					<select name="role">
					<option>Member</option>
					<option>Scout</option>
					<option>FC</option>
					</select>
					<br/><br/>
					Fitting:<br/> 
					<textarea name='dna' id='dna' rows='4' cols='80'"  maxlength="1024" /></textarea><br/>
					<br/>
					<input type='submit' name='Submit' value='Submit' /><br/>
					</form>
<?
				echo "Name: $pilotname<br/>Corp: $pilotcorp<br/>System: $pilotloc<br/><br/>";
					?><strong>Usage:</strong><br/>Drag the ship name from the fitting window to a chat window and press enter.  Then, right click on the line you pasted in chat, select copy and then paste it into the "Fitting:" box above.  You should see a bunch of numbers and such if you did it right.<?
				}



			}
		} else {
			// Out of game
			echo "You are out of the game, nothing to see here dude.";
		}

//		$pilotname = $_SERVER['HTTP_EVE_CHARNAME'];


	?>
  </body>

</html>
