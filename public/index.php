<?php
    session_start();
    require_once("../src/functions.php");
    require_once("../src/db.php");
    $uname = "";
    $nameErr = "";
    $dbErr = false;
    $res = "";

    if (!isset($db))
        $db = new DB();

    //modes 
    $modes = $db->run("Select * from difficulty")->fetchAll();
    $rev = reverseMode($modes);
    $res = makeModeOption($modes);

    //validation
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_POST["uname"])) {
          $nameErr = 'Username is required';
        } else {
            if(!validateRegex($_POST["uname"],"/^[a-zA-Z0-9_]+$/") || strlen($_POST["uname"]) > 20)
                $nameErr = 'Username doesn\'t meet requirements';
            else 
                $uname = htmlspecialchars($_POST["uname"]);
        }

        if(empty($nameErr)){
            $tabid = generateRandHex();
            $insert = $db->run("INSERT INTO game (tabid, diffid, answer) Values (:tab, :diff, :answer)", array(':tab' => $tabid, ':diff' => $rev[$_POST["mode"]], ':answer'=> random_int('1111', '9999')));

            if ($insert->rowCount() > 0){
                $_SESSION["tab"]=$tabid;
                $_SESSION["mode_txt"] = $_POST["mode"];
                $_SESSION["mode_num"] = $rev[$_POST["mode"]];
                $_SESSION["uname"] = $uname;
                header('Location: public/game.php');
                exit();
            }else{
                $dbErr = true;
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
            <?php
                if($dbErr)
                    echo '<p> Database Error. Try again later. </p>';
            ?>
            <form id="gcreate" method="post" action = "">
                <div>
                    <p>Enter a username (max 20 characters, alphanumeric) </p>
                    <p id="uhint"><?php echo $nameErr;?></p>
                    <label for="uname">
                        Username
                    </label>
                    <!-- need an on change listener for validation-->
                    <input type = "text" id="uname" name="uname" value="Username" size = "40" req>
                    </input>
                </div>
                <div>
                    <label for="mode">
                        Mode
                    </label>
                    <select id="mode" name="mode">
                        <?=$res?>
                    </select>
                </div>
                <div>
                    <button id="submit" name="submit" type="submit">Start Game </button>
                </div>
            </form>
        </div>
        <script type="text/javascript" src="public/js/auth.js"></script>
	</body>
</html>