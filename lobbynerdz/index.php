<?php
$GLOBALS["site"] = "Start";
require_once "../lobbynerdz/header.php";
?>

<div class="main" style="text-align: center">
    <form action="../posts/post.post.php" method="post">
        <textarea maxlength="73" style="background: none; outline: none; border: 1px solid grey; padding: 10px;
            min-width: 50%; width: 65%; min-height: 250px; height: 250px; resize: none"
            placeholder="Was hast du jetzt wieder angestellt..." name="content"></textarea><br>
        <button <?php if (!isset($_SESSION["id"]) || accountData($_SESSION["id"]) === false)
        {echo("disabled");} ?> type="submit" name="post"><i class='bx bxs-send'></i> Post! <i class='bx bxs-send'></i></button>
    </form>

    <div class="posts">
        <?php
        foreach (postArray(0, 20) as $post) {
            echo "
<div class='post'>
".$post['content']."<br>
<a style='color: grey; position: relative; bottom: -15px'>by ".accountData($post['usr'])['username']."</a>
</div>";
        }
        ?>
    </div>

</div>
