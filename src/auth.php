<?php
    include_once("functions.php");
    if (isset($_GET["uname"])){
        //^[a-zA-Z0-9_]*$
        if (!validateRegex($_GET["uname"], "/^[a-zA-Z0-9_]+$/") || strlen($_GET["uname"]) > 20){
            echo 'Username doesn\'t meet requirements' ;
        }
    }
    
    if (isset($_GET["guess"])){
        if (!validateRegex($_GET["guess"], "/^[1-9]+$/") || strlen($_GET["guess"]) > 4 || strlen($_GET["guess"]) < 4 ){
            echo 'Guess doesn\'t meet requirements' ;
        }
    }

?>