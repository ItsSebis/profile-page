<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo("Schulprojekt | ".basename(__DIR__)); ?></title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="../../../img/title-bar.png">
    <link rel="stylesheet" href="../../style.css">
</head>
<style>
    h2 {
        margin: 15px;
    }
</style>
<body onload="load()">
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>
<h1 id="h1" style="top: 50%; transform: translate(0, 50%);">Mexico - Gateway to the US?</h1>
<div id="main" class="main" style="display: flex; align-items: center; flex-wrap: wrap; position: absolute; left: 50%; top: 50%;
transform: translate(-50%, -50%); transition: 350ms; opacity: 0">
    <div class="sub" style="width: 45%">
        <h2>Human Development</h2>
        <p>Mexico is rank 74 of the Global Human Development Index with a score of 0.779, a life expectancy at birth at
            75.1 years and a GNI score of $19,160</p>
    </div>
    <div class="sub" style="width: 45%">
        <h2>The Way</h2>
        <p>Many people from El Salvador, Honduras and Guatemala immigrate to the US and pass through Mexico on their ways.</p>
    </div>
    <div class="sub" style="width: available">
        <h2>US Immigrant Programms/Politics</h2>
        <p>The US won´t be able to hold so many immigrants more, because of that they try to slow down the people coming from Mexico.
        Trump planned to build a wall on the southern side of the US to hold the immigrants in Mexico, but as soon as Trump was not
            the President anymore, the next, Biden stopped the building of the wall and invested the saved money in the military.<br>
            The new migration law limits the number of Asyl-Applications this causes that many immigrants have to stay at dangerous places
            where much crime takes place.
        </p>
    </div>
    <div class="sub" style="width: 45%">

    </div>
    <div class="sub" style="width: 45%">

    </div>
</div>
<script>
    function load() {
        fadeIn(document.getElementById("main"));
        fadeIn(document.getElementById("h1"));
    }

    function fadeIn(element) {
        let op = 0.3;  // initial opacity
        let timer = setInterval(function () {
            if (op >= 1) {
                clearInterval(timer);
            }
            element.style.opacity = op;
            element.style.filter = 'alpha(opacity=' + op * 100 + ")";
            op += op * 0.01;
        }, 5);
    }
</script>
</body>
</html>