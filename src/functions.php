<?php 

function generateRandHex(){
    return bin2hex(random_bytes(10));
}

function validateRegex($target, $regex){
    return filter_var($target, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$regex)));
}

function reverseMode ($array){
    $res = array(); 
    for ($i = 0; $i < sizeof($array); $i++) {
        $res[$array[$i]["diffname"]] =  $array[$i]["diffid"];
    }
    return $res;
}

function makeModeOption($array){
    $res = "";
    for ($i = 0; $i < sizeof($array); $i++) {
        $res .= "<option id='" . $array[$i]["diffid"] . "'>" . $array[$i]["diffname"]. "</option>";
    }

    return $res;
}

function printTurns($array){
    $res = "";
    if(!empty($array)){
        for ($i = 0; $i < sizeof($array); $i++) {
            $res .= "<tr> ";
            $res .= "<td>" . $array[$i]["num"] . "</td>";
            $res .=  "<td>" . $array[$i]["guess"] . "</td>";
            $res .= "<td>" . $array[$i]["correct"] . "</td>";
            $res .= "<td>" . $array[$i]["pos"] . "</td>";
            $res .= "</tr>";
        }
    }
    return $res;
}

function checkGuess($guess, $solution){
    $correct = 0;
    $pos = 0;
    $checked = "";
    for ($i = 0; $i <= 3; $i++){
        if($solution[$i] == $guess[$i])
            $correct++;
        else {
            if(strpos($solution, $guess[$i]) !== false && strpos($checked, $guess[$i]) === false){
                $checked .= $guess[$i];
                $pos++;
            }

        }
    }

    return array($correct, $pos);

}

?>