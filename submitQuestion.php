<?php
	include 'HOST.php';
	$quesLbl = $_POST['qL'];
	$query = "SELECT * FROM questions WHERE `QuestionLevel` = ".$quesLbl.";";
	$response = mysqli_query($link, $query) -> fetch_assoc();
	$answer = $response['Solution'];
	$tN = $_POST['teamID'];
	if(strtoupper($_POST['answer']) == strtoupper($answer)){
		$msg = "Correct Answer!";
		$score = intval($response['Score']);


		$query = "SELECT * FROM users WHERE `TeamName` = '".$_POST['teamID']."'";
		$response = mysqli_query($link, $query) -> fetch_assoc();
		$query = "SELECT * FROM questions WHERE `QuestionLevel` = ".$_POST['qL'].";";
		$response = mysqli_query($link, $query) -> fetch_assoc();
		$DD = mysqli_query($link, "SELECT UNIX_TIMESTAMP() AS TST FROM DUAL;") -> fetch_assoc();
		$tStp = $DD['TST'];
		if($tStp < strtotime($response['StartTime'])){
			$score = -5;
		}else if($tStp > strtotime($response['EndTime'])){
			$score = -5;
		}else{
			echo $response['Question']."<br>";
			if($tStp > strtotime($response['Hint1Time'])){
				$score /= 2;
			}
			if($tStp > strtotime($response['Hint2Time'])){
				$score /= 2;
			}
			if($tStp > strtotime($response['Hint3Time'])){
				$score /= 2;
			}
		}

		$query1 = "UPDATE users SET Score = Score + ".$score." WHERE `TeamName` = '".$_POST['teamID']."';";
		if($score != -5){
			$query2 = "UPDATE users SET level = level + 1 WHERE `TeamName` = '".$_POST['teamID']."';";
		}else{
			$query2 = "SELECT * FROM USERS;";
		}
		if(mysqli_query($link, $query1) && mysqli_query($link, $query2)){
			echo "SUCCESS";
		}else{
			
		}
		include 'mainPage.php';
	}else{
		$msg = "Incorrect Answer... Try Again!";
		include 'mainPage.php';
	}
	
?>