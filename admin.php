<?php
$GLOBALS["site"] = "Management";
include_once "header.php";
if (!isset($_SESSION["id"]) || !roleData(accountData($_SESSION["id"])["role"])["admin"]) {
    header("location: ./?error=noperm");
    exit();
}
?>
<script type="text/javascript">
    document.getElementById("manage").setAttribute("style", "border-bottom: 1px solid #f44; color: #f44")
</script>
<div class="main" style="margin-top: 75px">
    <h1>Management</h1>
</div>