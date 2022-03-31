<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo("School project | ".basename(__DIR__)); ?></title>
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
    p {
        line-height: 22px;
        font-size: 1.1rem;
    }
    .sources {
        cursor: help;
        color: #00cccc;
    }
    .sources:hover {
        color: #007777;
    }
</style>
<body onload="load()">
<a style="position: absolute; top: 10px; left: 10px;" href="..">← Back</a>
<h1 id="h1" style="top: 20%;">Mexico - Gateway to the US?</h1>
<div id="main" class="main" style="display: flex; align-items: center; flex-wrap: wrap; opacity: 0">
    <div class="sub" style="width: 45%">
        <h2>Human Development in Mexico</h2>
        <p>Mexico is rank 74 of the Global Human Development Index with a score of 0.779, a life expectancy at birth at
            75.1 years and a GNI score of $19,160.<br><br>This is the primary reason for people to go from Mexico to the US.</p>
    </div>
    <div class="sub" style="width: 45%">
        <h2>The Way</h2>
        <p>Many people from El Salvador, Honduras and Guatemala immigrate to the US and pass through Mexico on their ways.<br><br>
            The way for the immigrants to the US goes through a desert where 400 to 500 people die every year.</p>
    </div>
    <div class="sub" style="width: 90%">
        <h2>US Immigrant Programms/Politics</h2>
        <p>Since 1964 the US had a no politics policy until, twenty years later, Ronald Reagan made strickt immigration laws.
            But even with those laws the number of immigrants risen further up. The US won´t be able to hold so many
            immigrants more, because of that they try to slow down the people coming from Mexico. Trump planned to build
            a wall on the southern side of the US to hold the immigrants in Mexico, but as soon as Trump was not the
            President anymore, the next, Biden stopped the building of the wall and invested the saved money in the
            military. The new migration law limits the number of Asyl-Applications this causes that many immigrants have
            to stay at dangerous places where much crime takes place.<br>
            <br>
            In the "Operation Gatekeeper“ Bill Clinton had built in the 1990s a 72km long fence along the border to Mexico.
            After 9/11 the fence were built another 1400km (now 1472km long). Donald Trump wanted to build another 3200km
            long fence at the border between the US and Mexico. The fence which 4672km would have been along the whole
            Mexican-american border.
        </p>
    </div>
    <div class="sub" style="width: 90%">
        <h2>Stay in the US - The Greencard</h2>
        <p>
            The United States Permanent Resident Card, better known as "the Greencard" is a permanent residence allowance
            for the US there are two variants of this Greencard first the workplace-based Greencard and the family-based
            Greencard. For the workplace-based Greencard you need to have a job offer. But there are two exceptions are
            if you are a worker with "extraordinary ability" or you are a person who is in national interest to work.<br>
            <br>
            Research or do something for the US you don't need a job offer. The family-based Greencard available for
            people who have close relatives of US citizens like spouses, unmarried children under the age of 21 and
            parents of a US citizen who's at least 21 years old for example. But both of those Greencards are subjects
            of quota so only a certain number of Greencards can get confirmed per tax year. The "excess applications" are
            temporarily not processed, but get put on a waiting list.
        </p>
    </div>
    <div class="sub" style="width: 45%">
        <h2>Sources</h2>
        <p>
            <a href="img" class="sources" target="_self">-> Pictures <-</a><br><br>
            <a href="https://www.visum-usa.com/greencard.html" class="sources" target="_blank">-> Greencard <-</a><br>
            <a href="https://hdr.undp.org/en/content/latest-human-development-index-ranking" class="sources"
               target="_blank">-> HDI Infos <-</a><br>
            <a href="https://www.aerzte-ohne-grenzen.de/unsere-arbeit/aktuelles/mexiko-usa-migrationsgesetze"
               class="sources" target="_blank">-> Immigrationsgesetz 2019 <-</a><br>
        </p>
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