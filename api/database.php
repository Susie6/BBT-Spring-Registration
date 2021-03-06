<?php
require_once("../config.php");

function query($info) {
	$con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$con->set_charset('utf8mb4');

	if ($con->connect_error) {
		return -2;
	}

	$sql = "select name, sex, tel, grade, college, dorm, department, alternative, adjustment, introduction, information from application where name = ? && tel = ?";

	$ret = new StdClass();
	
	$stmt = $con->prepare($sql);
	$stmt->bind_param("ss", $info["name"], $info["tel"]);
	$stmt->execute();
	$stmt->bind_result($ret->name, $ret->sex, $ret->tel, $ret->grade, $ret->college, $ret->dorm, $ret->department, $ret->alternative, $ret->adjustment, $ret->introduction, $ret->information);
	$stmt->fetch();
	$stmt->close();
	$con->close();
	
	return $ret;
}

function signup($info, $cover) {
	$con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$con->set_charset('utf8mb4');
	if ($con->connect_error) {
		return -2;
	}
	
	$data = query($info);

	if (isset($info["query_name"]) && isset($info["query_tel"]) && $info["query_name"] && $info["query_tel"]) {
		if ($cover) {
			$sql = "update application set name = ?, tel = ?, sex = ?, grade = ?, college = ?, dorm = ?, department = ?, alternative = ?, adjustment = ?, introduction = ? where name = ? && tel = ?";
			$stmt = $con->prepare($sql);
			$stmt->bind_param("ssssssssisss", $info["name"], $info["tel"], $info["sex"], $info["grade"], $info["college"], $info["dorm"], $info["department"], $info["alternative"], $info["adjustment"], $info["introduction"], $info["query_name"], $info["query_tel"]);
			$stmt->execute();
			$stmt->close();
		} else {
			$con->close();
			return -1;
		}
	}elseif($data->name && $data->tel){
		$con->close();
		return -1;
	} else {
		$sql = "insert into application (name, sex, tel, grade, college, dorm, department, alternative, adjustment, introduction) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $con->prepare($sql);
		$stmt->bind_param("ssssssssis", $info["name"], $info["sex"], $info["tel"], $info["grade"], $info["college"], $info["dorm"], $info["department"], $info["alternative"], $info["adjustment"], $info["introduction"]);
		if (!$stmt->execute()) {
			$err_msg = $stmt->error;
			$stmt->close();
			return $err_msg;
		}
		$stmt->close();
	}

	$con->close();
	// TODO: check if success

	return 0;
}

function admin_login($username, $passwd) {
	$con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$con->set_charset('utf8mb4');
	if ($con->connect_error) {
		return -2;
	} else {
		//$enc_pwd = md5($passwd);
		$stmt = $con->prepare("select permission from admin where username=? and password=?");
		$stmt->bind_param("ss", $username, $passwd);
		$stmt->execute();
		$stmt->bind_result($ret);
		$stmt->fetch();
		$stmt->close();
		$con->close();

		return isset($ret) ? $ret : -1;
	}
}
//???????????????


function admin_query($permission) {
	//var_dump($permission);
	$con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$con->set_charset('utf8mb4');
	if ($con->connect_error) {
		return -2;
	} else {
		if ($permission == 0) {
			$dat = $con->query("select * from application");
			//$stmt = $con->prepare("select * from application");
		} else {
			$dat = $con->query("select * from application where department = " . $permission);
			//$stmt = $con->prepare("select * from application where department = ?");
			//$stmt->bind_param("s",$permission);
		}
		//$stmt->execute();
		$array = [];
		//$stmt->bind_result($ret->name, $ret->sex, $ret->tel, $ret->grade, $ret->college, $ret->dorm, $ret->department, $ret->alternative, $ret->adjustment, $ret->introduction,$ret->timestamp, $ret->information,$ret->note);
		while ($row = $dat->fetch_assoc()) {
			$array[] = [
				"name" => $row["name"],
				"sex" => StrToSex($row["sex"]),
				"tel" => $row["tel"],
				"grade" => $row["grade"],
				"college" => NumToCollege($row["college"]),
				"dorm" => $row["dorm"],
				"department" => NumToDep($row["department"]),
				"alternative" => NumToDep($row["alternative"]),
				"adjustment" => Ajustment($row["adjustment"]),
				"introduction" => $row["introduction"],
			];
		}
		//$stmt->close();	 
		$con->close();
	}

	return $array;
}
function change_department($value) {

	$con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$con->set_charset('utf8mb4');
	if ($con->connect_error) {
		return -2;
	} else {

		$dat = $con->query("select * from application where department = " . $value);
		//	$stmt = $con->prepare("select * from application where department = ?");
		//	$stmt->bind_param("i",$value);
		$array = [];
		//$stmt->bind_result($ret->name, $ret->sex, $ret->tel, $ret->grade, $ret->college, $ret->dorm, $ret->department, $ret->alternative, $ret->adjustment, $ret->introduction,$ret->timestamp, $ret->information,$ret->note);
		while ($row = $dat->fetch_assoc()) {
			$array[] = [
				"name" => $row["name"],
				"sex" => StrToSex($row["sex"]),
				"tel" => $row["tel"],
				"grade" => $row["grade"],
				"college" => NumToCollege($row["college"]),
				"dorm" => $row["dorm"],
				"department" => NumToDep($row["department"]),
				"alternative" => NumToDep($row["alternative"]),
				"adjustment" => Ajustment($row["adjustment"]),
				"introduction" => $row["introduction"],
			];
		}
	}
	return $array;
}

function NumToCollege($val) {
	switch ($val) {
		case 0:
			$val = "???????????????????????????";
			break;
		case 1:
			$val = "????????????";
			break;
		case 2:
			$val = "?????????????????????";
			break;
		case 3:
			$val = "?????????????????????";
			break;
		case 4:
			$val = "???????????????????????????";
			break;
		case 5:
			$val = "?????????????????????";
			break;
		case 6:
			$val = "???????????????????????????";
			break;
		case 7:
			$val = "???????????????????????????";
			break;
		case 8:
			$val = "????????????";
			break;
		case 9:
			$val = "?????????????????????";
			break;
		case 10:
			$val = "?????????????????????";
			break;
		case 11:
			$val = "??????????????????????????????";
			break;
		case 12:
			$val = "??????????????????????????????";
			break;
		case 13:
			$val = "????????????";
			break;
		case 14:
			$val = "???????????????????????????";
			break;
		case 15:
			$val = "?????????????????????";
			break;
		case 16:
			$val = "????????????";
			break;
		case 17;
			$val = "??????????????????";
			break;
		case 18:
			$val = "??????????????????";
			break;
		case 19:
			$val = "?????????????????????";
			break;
		case 20:
			$val = "???????????????";
			break;
		case 21:
			$val = "?????????";
			break;
		case 22:
			$val = "?????????????????????";
			break;
		case 23:
			$val = "????????????";
			break;
		case 24:
			$val = "????????????";
			break;
		case 25:
			$val = "????????????";
			break;
		case 26:
			$val = "?????????";
			break;
		case 27:
			$val = "??????????????????";
			break;
		default:
			$val = "???????????????";
	}
	return $val;
}

function StrToSex($val)
{
	if ($val == 'M') {
		$val = "???";
	} elseif ($val == 'F') {
		$val = "???";
	} else {
		$val = "??????";
	}
	return $val;
}

function NumToDep($val)
{
	switch ($val) {
		case 0:
			$val = "?????????-?????????";
			break;
		case 1:
			$val = "?????????-?????????";
			break;
		case 2:
			$val = "???????????????????????????";
			break;
		case 3:
			$val = "???????????????";
			break;
		case 4:
			$val = "?????????-????????????";
			break;
		case 5:
			$val = "?????????-??????";
			break;
		case 6:
			$val = "?????????-???????????????";
			break;
		case 7:
			$val = "???????????????";
			break;
		case 8:
			$val = "?????????-????????????";
			break;
		case 9:
			$val = "?????????-????????????";
			break;
		case 10:
			$val = "?????????-????????????";
			break;
		case 11:
			$val = "?????????";
			break;
		case 12:
			$val = "?????????-?????????";
			break;
		case 13:
			$val = "?????????-?????????";
			break;
		case 14:
			$val = "?????????-?????????";
			break;
		case 15:
			$val = "???????????????";
			break;
		case 16:
			$val = "???????????????-????????????";
			break;
		case 17:
			$val = "???????????????-????????????";
			break;
		case 18:
			$val = "???????????????-????????????";
			break;
		case 19:
			$val = "???????????????-????????????";
			break;
		case 20:
			$val = "?????????????????????????????????";
			break;
		default:
			$val = "?????????????????????";
	}
	return $val;
}

function Ajustment($val)
{
	if ($val == 0) {
		$val = "???";
	} elseif ($val == 1) {
		$val = "???";
	} else {
		$val = "?????????";
	}
	return $val;
}

