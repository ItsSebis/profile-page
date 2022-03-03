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
<h1 style="font-size: 3rem; margin-top: 60px">Management</h1>
<div class="main">

</div>