<?php
session_start();

require_once("../src/functions.php");
require_once("../src/db.php");
$guessErr = "";
$win = null;
$dbErr = false;
$turns = null;
$turn_num = 0;
if (!isset($db))
        $db = new DB();

$gid = $db->run("SELECT * FROM game WHERE tabid = :tid", array(':tid' => $_SESSION["tab"]));

if($gid->rowCount() > 0){
	$gamedetails = $gid->fetch();
}else {
	$dbErr = true;
}

if (!$dbErr){
	$stmt = $db->run("SELECT * FROM turns WHERE gid = :gameid", array(':gameid' => $gamedetails['gid']));
	$turn_num = $stmt->rowCount()+1;
	$turns =$stmt->fetchAll();
	$turn_table = printTurns($turns);
	$turn_limit = $db->run("SELECT tries FROM difficulty WHERE diffid = :did", array(':did' => $_SESSION["mode_num"]))->fetch()["tries"];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (!isset($_POST["guess"])) {
	  $guessErr = 'Guess is required';
	} else {
		if(!validateRegex($_POST["guess"],"/^[1-9]+$/") || strlen($_POST["guess"]) > 4 || strlen($_POST["guess"]) < 4 )
			$guessErr = 'Guess doesn\'t meet requirements';
		else 
			$guess = htmlspecialchars($_POST["guess"]);
	}

	if(empty($guessErr)){
		$res = checkGuess($guess, $gamedetails["answer"]);

		if ($turn_limit  === $turn_num){
			if ($res[0] !== 4) //correct
				$win = false;
			else{
				$win = true;
			}
		}else{
			if($res[0] === 4)
				$win = true;
		}



		$insert = $db->run("INSERT INTO turns (gid, guess, num, correct, pos) Values (:gid, :g, :num, :correct, :pos)", array(':gid' =>$gamedetails["gid"], ':g' => $guess,
		 ':num' => $turn_num, ':correct' => $res[0], ':pos' => $res[1]));

		if ($insert->rowCount() < 0){
			$dbErr = true;
		}else{
			$turn_table = printTurns($db->run("SELECT * FROM turns WHERE gid = :gameid", array(':gameid' => $gamedetails['gid']))->fetchAll());
		}
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Numeric Mastermind</title>
	</head>
	<body>
        <div>
			<div>
				Username: <?= $_SESSION["uname"] ?>
				Difficulty: <?=$_SESSION["mode_txt"]?>
				Turn <?=$turn_num?> of <?=$turn_limit?>
			</div>
			<?php 
			if(isset($win)){
				if ($win === true)
					echo '<h1> You Win! </h1>';
				else if ($win === false)
					echo '<h1> You Lose! </h1>';
			}
			?>
			<form id="game" method="post" action = "">
				<div>
					<p>Enter a guess (max 4 characters, numeric) </p>
					<p id="ghint"><?php echo $guessErr;?></p>
					<label for="guess"> Guess: </label>
					<input type = "text" id="guess" name="guess"  size = "6" req <?=!isset($win) ? "" : 'disabled'?>> </input>
				</div>
				<div>
					<button id="submit" name="submit" type="submit" <?=!isset($win) ? "" : 'disabled'?>>Guess!</button>
				</div>
			</form>
		</div>
		<?php 
		if (!empty($turn_table)){
		?>
			<table>
				<tr>
				<th>Guess Number</th>
				<th>Guess </th>
				<th>Correct Numbers</th>
				<th>Correct Positions</th>
				</tr>
				<?= $turn_table ?>
			</table>
		<?php } else {
			echo 'No turns recorded yet.';
		}
		?>
		<script type="text/javascript" src="js/game.js"></script>
	</body>
</html>