<?php
$GLOBALS["site"] = "Settings";
include_once "header.php";
if (!isset($_SESSION["id"])) {
    header("location: ./?error=notSignedIn");
    exit();
}
?>
<script type="text/javascript">
    document.getElementById("account").setAttribute("style", "border-bottom: 1px solid #999; color: #999")
</script>