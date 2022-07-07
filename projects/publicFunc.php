<?php

/**
 * @param $count
 * @return mixed|string
 */
function reformatBIgInts($count) {
    if ($count > 1100000000) {
        $count = round($count / 1000000000, 2) . "G";
    } elseif ($count > 1250000) {
        $count = round($count / 1000000, 2) . "M";
    } elseif ($count > 1500) {
        $count = round($count / 1000, 2) . "K";
    }
    return $count;
}

function rngString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/*
function getYtIdPossible() {
    return "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_1234567890";
}
*/

function dateFromMySQL($mysql) {
    $datesplits = explode("-", explode(" ", $mysql)[0]);
    $timesplits = explode(":", explode(" ", $mysql)[1]);

    $year = $datesplits[0];
    $month = $datesplits[1];
    $day = $datesplits[2];

    $hour = $timesplits[0];
    $min = $timesplits[1];
    $sec = $timesplits[2];

    return mktime($hour, $min, $sec, $month, $day, $year);
}

function randomLetter() {
    $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    try {
        return $letters[random_int(0, strlen($letters) - 1)];
    } catch (Exception $e) {
    }
    return " - NULL - ";
}

function yt_exists($videoID) {
    try {
        $theURL = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=$videoID&format=json";
        $headers = get_headers($theURL);
    } catch (Exception $e) {
        $headers = get_headers("https://example.com");
    }

    #$info = file_get_contents($theURL);

    return !((substr($headers[0], 9, 3) === "400") || (substr($headers[0], 9, 3) === "404"));
    #return strpos($info, "Bad Request") === false;
}

function yt_info($videoID) {
    $theURL = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=$videoID&format=json";
    return json_decode(file_get_contents($theURL), true);
}

function untilNow($date) {
    $dateTimeObject1 = date_create($date);
    $dateTimeObject2 = date_create(gmdate("Y-m-d H:i:s"));
    return date_diff($dateTimeObject1, $dateTimeObject2);
}

function rngByPerCent($percent) {
    try {
        return random_int(1, 10000000000) <= $percent*100000000;
    } catch (Exception $e) {
        return false;
    }
}

function rngArray($array) {
    try {
        return $array[random_int(0, count($array)-1)];
    } catch (Exception $e) {
        return false;
    }
}

function rngTime() {
    try {
        $hour = random_int(0, 23);
        $min = random_int(0, 59);
        if ($hour < 10) {
            $hour = "0" . $hour;
        }
        if ($min < 10) {
            $min = "0" . $min;
        }
        return $hour . ":" . $min;
    } catch (Exception $e) {
        return "25:61";
    }
}

function getStats($key) {
    $con = con();
    $sql = "SELECT * FROM stats WHERE `key` = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $key);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function setStat($key, $val) {
    $con = con();
    $sql = "UPDATE stats SET value=? WHERE `key` = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $val, $key);
    mysqli_stmt_execute($stmt);
}

function isEven($number) {
    return $number % 2 == 0;
}

function allProjects() {
    $con = con();
    $sql = "SELECT * FROM projects ORDER BY `name` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1&part=allProjects");
        exit();
    }

    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $array = array();
    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            $array[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
    return $array;
}

function projectData($dir) {
    $con = con();
    $sql = "SELECT * FROM projects WHERE dir = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=projectData");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $dir);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return array("name" => "Nicht zugeordnet", "dir" => ".", "user" => "0", "des" => "Keine weiteren Informationen");
    }
}

function accountData($id) {
    $con = con();
    $sql = "SELECT * FROM users WHERE id = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=accountData");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function accountDataByToken($token) {
    $con = con();
    $sql = "SELECT * FROM users WHERE token = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=accountDataByToken");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function accountDataByName($acc) {
    $con = con();
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=accountDataById");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $acc, $acc);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function login($id) {
    $path = "../";
    if (isset($_GET["path"])) {
        $path = $_GET["path"];
    }
    $data = accountData($id);
    $_SESSION["id"] = $data["id"];
    #setcookie("login", $data["token"], time()+60*60*24*30, "/");
    header("location: ".$path."?error=loggedin");
    exit();
}

function roleData($id) {
    $con = con();
    $sql = "SELECT * FROM roles WHERE id = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=roleData");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function roleDataByName($name) {
    $con = con();
    $sql = "SELECT * FROM roles WHERE name = ?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=roleDataByName");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        return false;
    }
}

function rolesArray() {
    $con = con();
    $sql = "SELECT * FROM roles ORDER BY `name` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=rolesArray");
        exit();
    }

    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $array = array();
    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            $array[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
    return $array;
}

function usersArray() {
    $con = con();
    $sql = "SELECT * FROM users ORDER BY `username` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=usersArray");
        exit();
    }

    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $array = array();
    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            $array[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
    return $array;
}

/*function mailsArray() {
    $con = con();
    $sql = "SELECT * FROM mails ORDER BY `id` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=usersArray");
        exit();
    }

    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $array = array();
    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            $array[] = $row["mail"];
        }
    }
    mysqli_stmt_close($stmt);
    return $array;
}*/

function rolesUsersArray($role) {
    $con = con();
    $sql = "SELECT * FROM users WHERE `role`=? ORDER BY `username` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=rolesUsersArray");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $role);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);

    $array = array();
    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            $array[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
    return $array;
}

function createUser($name, $pw, $mail) {
    $con = con();
    $sql = "INSERT INTO users (username, pw, email) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1&part=createUser");
        exit();
    }

    $pw = password_hash($pw, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $name, $pw, $mail);
    mysqli_stmt_execute($stmt);

    generateToken(accountDataByName($name)["id"]);
}

function setUserStat($usr, $stat, $value) {
    $con = con();
    $qry = "UPDATE users SET ".$stat."=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setUserStat");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $value, $usr);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function setUserPw($usr, $value) {
    $con = con();
    $qry = "UPDATE users SET pw=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setUserPw");
        exit();
    }

    $value = password_hash($value, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ss", $value, $usr);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function setUserName($usr, $value) {
    $con = con();
    $qry = "UPDATE users SET username=?,chName=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setUserName");
        exit();
    }

    $date = date("Y-m-d h:i:s");

    mysqli_stmt_bind_param($stmt, "sss", $value, $date, $usr);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function setUserSeen($usr) {
    $con = con();
    $qry = "UPDATE users SET lastseen=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setUserSeen");
        exit();
    }

    $date = date("Y-m-d H:i:s");

    mysqli_stmt_bind_param($stmt, "ss", $date, $usr);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function setUserRole($usr, $role) {
    $con = con();
    $qry = "UPDATE users SET `role`=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setUserRole");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $role, $usr);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function mirrorRolePerm($role, $perm) {
    $con = con();
    $qry = "UPDATE roles SET `".$perm."`=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=mirrorRolePerm");
        exit();
    }

    $current = roleData($role)[$perm];
    $should = mirrorBoolInInts($current);

    mysqli_stmt_bind_param($stmt, "ss", $should, $role);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function setRoleName($role, $name) {
    $con = con();
    $qry = "UPDATE roles SET `name`=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setRoleName");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $name, $role);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function setRoleColor($role, $color) {
    $con = con();
    $qry = "UPDATE roles SET `color`=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=setRoleColor");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $color, $role);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function userHasPerm($user, $perm) {
    if ($user === 0 || $user === 1) {
        return true;
    }
    return roleData(accountData($user)["role"])[$perm] === 1;
}

function userList() {
    $con = con();
    $sql = "SELECT * FROM users ORDER BY `username` ASC, `role` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: index.php?error=1");
        exit();
    }

    mysqli_stmt_execute($stmt);

    $rs = mysqli_stmt_get_result($stmt);

    if ($rs->num_rows > 0) {
        echo '
    <table class="profile" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
    <thead>
      <tr>
        <th style="padding-left: 10px; padding-right: 10px;">Account</th>
        <th style="padding-left: 10px; padding-right: 10px;">E-Mail</th>
        <th style="padding-left: 10px; padding-right: 10px;">Rolle</th>
      </tr>
    </thead>
    <tbody>
    ';
        while ($row = $rs->fetch_assoc()) {
            echo "

    <tr>
      <td style='border: 2px solid black;'><a class='user' href='admin.php?page=users&usr=".$row["id"]."'>".$row["username"]."</a></td>
      <td style='border: 2px solid black;'>".$row['email']."</td>
      <td style='border: 2px solid black; color: ".roleData($row['role'])['color']."'>".roleData($row['role'])['name']."</td>
    </tr>

    ";
        }
        echo '
    </tbody>
    </table>
    ';
    } else {
        echo "<p style='color: red;'>Es gibt keine Benutzer! Warte mal, wie bist du hier hergekommen?</p>";
    }

    mysqli_stmt_close($stmt);

}

function rolesList() {
    $con = con();
    $sql = "SELECT * FROM roles ORDER BY `name` ASC, `id` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ./?error=1");
        exit();
    }

    mysqli_stmt_execute($stmt);

    $rs = mysqli_stmt_get_result($stmt);

    if ($rs->num_rows > 0) {
        echo '
    <table class="profile" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
    <thead>
      <tr>
        <th style="padding-left: 10px; padding-right: 10px;">Name</th>
        <th style="padding-left: 10px; padding-right: 10px;">Farbe</th>
        <th style="padding-left: 10px; padding-right: 10px;">Erstellt von</th>
      </tr>
    </thead>
    <tbody>
    ';
        while ($row = $rs->fetch_assoc()) {
            echo "

    <tr>
      <td style='border: 2px solid black;'><a class='user' href='admin.php?page=roles&role=".$row["id"]."'>".$row["name"]."</a></td>
      <td style='border: 2px solid black; color: ".$row['color']."'>".$row['color']."</td>
      <td style='border: 2px solid black; color: ".roleData(accountData($row['creator'])['role'])['color']."'>".accountData($row['creator'])['username']."</td>
    </tr>

    ";
        }
        echo '
    </tbody>
    </table>
    ';
    } else {
        echo "<p style='color: red;'>Es gibt keine Rollen! Warte mal, wie bist du hier hergekommen?</p>";
    }

    mysqli_stmt_close($stmt);

}

function rngNumPw() {
    $pw = "";
    try {
        $pw = random_int(0, 9999999999);
    } catch (Exception $e) {
    }
    while (strlen($pw) < 10) {
        $pw = "0".$pw;
    }
    return $pw;
}

function roleSelector($user) {
    echo '<select name="role" id="roles" style="background-color: #303030; outline: none; color: white; border: solid #333333; border-radius: 24px; width: 350px; height: 70px; padding: 14px 10px; transition: 0.2s; font-size: larger;">';
    echo '<option value="null">'.roleData(accountData($user)["role"])["name"].'</option>';
    foreach (rolesArray() as $role) {
        if ($role["id"] != accountData($user)["role"]) {
            echo '
            <option value="'.$role["id"].'">'.$role["name"].'</option>
        ';
        }
    }
    echo '
    </select><br>';
}

/*function mailSelector() {
    echo '<select name="mail" id="mails" style="background-color: #303030; outline: none; color: white; border: solid #333333; border-radius: 24px; width: 350px; height: 70px; padding: 14px 10px; transition: 0.2s; font-size: larger;">';
    foreach (mailsArray() as $mail) {
        echo '<option value="'.$mail."@sebis.net".'">'.$mail.'</option>';
    }
    echo '
    </select><br>';
}*/

function generateToken($user) {
    $token = "";
    while (strlen($token) < 64) {
        $token .= randomLetter();
        #echo($token);
    }
    $con = con();
    $qry = "UPDATE users SET `token`=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ./?error=1&part=generateToken");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $token, $user);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function countUsersByRole($role) {
    $count = 0;
    foreach (usersArray() as $user) {
        if ($user["role"] == $role) {
            $count++;
        }
    }
    return $count;
}

/*function sendMail($via, $to, $subject, $text) {
    $mail = "<html lang='de'>
    <head>
        <meta charset='utf8'/>
        <title>Mail</title>
    </head>
    <body style='background-color: #252322; color: #FFFFFF;'>
    <style>
    
    a {
      text-decoration: underline;
      color: white;
    }
    a:hover {
      color: GRAY;
    }
    a:active {
      color: red;
    }
    
    </style>
    " .$text."</body></html>";
    $headers = array(
    'From' => $via,
    'Content-Type' => 'text/html; charset=utf-8'
    );

    mail($to, $subject, $mail, $headers);
}*/

function boolToYN($bool) {
    $yes = "<span style='color: lime'>Ja</span>";
    $no = "<span style='color: red'>Nein</span>";
    if ($bool) {
        return $yes;
    } else {
        return $no;
    }
}

function mirrorBoolInInts($intBool) {
    if ($intBool == 1) {
        return 0;
    } elseif ($intBool == 0) {
        return 1;
    } else {
        return false;
    }
}

function delUser($user) {
    $con = con();
    $qry = "DELETE FROM users WHERE id=?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ../?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

function delRole($role) {
    $con = con();
    $qry = "DELETE FROM roles WHERE id=?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ../?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $role);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

function createRole($name) {
    $con = con();
    $sql = "INSERT INTO roles (`name`, creator) VALUES (?, ?);";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=1");
        exit();
    }

    $me = $_SESSION["id"];

    mysqli_stmt_bind_param($stmt, "ss",$name, $me);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

function safeDeleteRole($role) {
    foreach (usersArray() as $user) {
        if ($user["role"] == $role) {
            setUserRole($user["id"], 2);
        }
    }
    delRole($role);
}

function getPerms() {
    return array("admin" => "Zugriff auf Management", "respw" => "Passwörter zurücksetzten", "delusrs" => "Benutzer löschen",
        "grantroles" => "Benutzer Rollen hinzufügen", "editroles" => "Rollen bearbeiten", "debugs" => "Debugs anzeigen",
        "wertests" => "Werwolf Testspiele", "chairs" => "Beichträume erstellen", "managechairs" => "Beichträume verwalten",
        "seesays" => "Immer alle Beichten sehen");
}