<?php
require_once("database.php");

function onSignup($arg) {
	$ret = new stdClass();
	
	$sta = signup($arg, isset($arg["cover"]) ? $arg["cover"] : false);
	
	if ($sta === -1) {
		$ret->errcode = 300;
		$ret->errmsg = "Existed application";
	} elseif ($sta === -2) {
		$ret->errcode = 500;
		$ret->errmsg = "Database issue";
	} elseif ($sta !== 0) {
		$ret->errcode = 500;
		$ret->errmsg = $sta;
	}
	
	return $ret;
}

registerMethod("signup", "onSignup", array(
	"required" => array("name", "sex", "tel", "grade", "college", "department", "dorm", "adjustment"),
	"optional" => array("alternative", "introduction", "cover")
));

/*
} elseif ($_GET["method"] == "signup") {	
	if (empty($data["name"])) {
		$ret->errmsg = "Missing parameter: name";

	} elseif (empty($data["sex"])) {
		$ret->errmsg = "Missing parameter: sex";
		// check 

	}elseif (empty($data["tel"])) {
		$ret->errmsg = "Missing parameter: tel";
		// check 

	}elseif (empty($data["grade"])) {
		$ret->errmsg = "Missing parameter: grade";
		// check 

	}elseif (empty($data["college"])) {
		$ret->errmsg = "Missing parameter: college";
		// check 

	}elseif (empty($data["dorm"])) {
		$ret->errmsg = "Missing parameter: dorm";
		// check 

	}
	 elseif (empty($data["department"])) {
		$ret->errmsg = "Missing parameter: department";
		// check 

	} elseif (empty($data["adjustment"])) {
		$ret->errmsg = "Missing parameter: adjustment";
		// check 
	} elseif (preg_match("/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/", $data["name"])) {
		$ret->errmsg = "special characters in name";

	} elseif (!is_numeric($data["tel"]) || $data["tel"][0] != 1 || strlen($data["tel"]) != 11) {
		$ret->errmsg = "wrong telephone information";

	} elseif($data["department"] == $data["alternative"]){
		$ret->errmsg = "department can't be the same as alternative";

	}elseif (!empty($data["introduction"]) && mb_strlen($data["introduction"]) >= 50) {
		$ret->errmsg = "length of introduction limit exceeded";
	
	}else {
		if(isset($data["cover"])){
			$info = array(
				"query_name" => $_SESSION["name"],
				"query_tel" => $_SESSION["tel"],
				"name" => $data["name"],
				"sex" => $data["sex"],
				"tel" => $data["tel"],
				"grade" => $data["grade"],
				"college" => $data["college"],
				"dorm" => $data["dorm"],
				"department" => $data["department"],
				"alternative" => $data["alternative"],
				"adjustment" => $data["adjustment"],
				"introduction" => $data["introduction"],
			);
		}else{
			$info = array(
			"name" => $data["name"],
			"sex" => $data["sex"],
			"tel" => $data["tel"],
			"grade" => $data["grade"],
			"college" => $data["college"],
			"dorm" => $data["dorm"],
			"department" => $data["department"],
			"alternative" => $data["alternative"],
			"adjustment" => $data["adjustment"],
			"introduction" => $data["introduction"],
		);
		}
		$sta = signup($info, isset($data["cover"]) ? $data["cover"] : false);

		if ($sta == -1) {
			$ret->errmsg = "existed";
		} elseif ($sta == -2) {
			$ret->errmsg = "database issue";
		}
	}
} elseif ($_GET["method"] == "query") {
	if (empty($data["name"])) {
		$ret->errmsg = "Missing parameter: name";
	} elseif (empty($data["tel"])) {
		$ret->errmsg = "Missing parameter: tel";
		// check 
	} elseif (!is_numeric($data["tel"]) || $data["tel"][0] != 1 || strlen($data["tel"]) != 11) {
		$ret->errmsg = "wrong telephone information";
	} else {
		$info = array(
			"name" => $data["name"],
			"tel" => $data["tel"],
		);
		$_SESSION["name"] = $data["name"];
		$_SESSION["tel"] = $data["tel"];
		$sta = query($info);

		if ($sta === -2) {
			$ret->errmsg = "database issue";
		} else {
			$ret->exist = isset($sta->name);
			if ($ret->exist) {
				//????????????$sta->info,?????????????????????$sta?????????
				$ret->info = $sta;
			} else {
				$ret->errmsg = "no infomation";
			}
		}
	}
} 
$ret->status = isset($ret->errmsg) ? "failed" : "ok";

echo json_encode($ret);
*/
