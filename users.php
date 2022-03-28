<?php
$GLOBALS["site"] = "Benutzer";
include_once "header.php";
if (!isset($_GET["user"]) || accountData($_GET["user"]) === false) {
?>
<h1 style="margin-top: 60px; font-size: 3rem">Alle Benutzer</h1>
<div class="main" style="text-align: center; padding-bottom: 17px">
    <?php
    foreach (rolesArray() as $role) {
        echo "<h2 style='font-size: 1.8rem; margin-bottom: -1px; color: ".$role['color']."; text-decoration: underline'>".$role['name']."</h2>";
        foreach (rolesUsersArray($role["id"]) as $user) {
            echo "<a href='users.php?user=".$user['id']."' style='font-size: 1.1rem'>".$user['username']."</a><br>";
        }
    }
    ?>
</div>
<?php
} else {
    $user = accountData($_GET["user"]);
    ?>
<div class="main" style="text-align: center; margin-top: 60px">
    <h1 style="font-size: 3rem; margin: 20px auto 0; padding: 5px; border: dotted #fff; border-radius: 15px; width: min-content"><?php echo($user["username"]) ?></h1>
    <p style="color: gray">Beigetreten: <?php echo($user["joindate"]);?></p>
    <h2 style="font-size: 2rem">Statistiken</h2>

    <h3 style="font-size: 1.5rem;">Werwolf</h3>
    <p>
        Gespielt: <?php echo($user["werplayed"]);?><br><br>

        Als Werwolf gespielt: <?php echo($user["werwer"]);?><br>
        Als Hexe gespielt: <?php echo($user["werhex"]);?><br>
        Als Amor gespielt: <?php echo($user["weramor"]);?><br>
        Als Urwolf gespielt: <?php echo($user["werur"]);?><br><br>

        Als Werwolf gewonnen: <?php echo($user["werwerwin"]);?><br>
        Als Dorf gewonnen: <?php echo($user["wervilwin"]);?><br>
        Als Verliebte gewonnen: <?php echo($user["werlovwin"]);?><br>
    </p>

    <h2 style="font-size: 2rem">Rechte</h2>
    <?php
    echo "<p>";
    foreach (getPerms() as $perm => $des) {
        if (userHasPerm($user["id"], $perm)) {
            echo $des."<br>";
        }
    }
    echo "</p>";
    ?>

</div>
<?php
}