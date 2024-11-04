<?php $niveau = ""; ?>
<?php include($niveau . "liaisons/php/config.inc.php"); ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="author" content="">
    <meta charset="utf-8">
    <title>Un beau titre ici!</title>
    <?php include($niveau . "liaisons/fragments/headlinks.inc.php"); ?>
</head>

<footer class="piedDePage">
    <hr class="separator">
    <div class="section__logo">
        <img class="logo__footer" src="<?php echo $niveau ?>liaisons/images/logo.svg" alt="Logo">
    </div>
    <div class="section__nav">
        <nav class="nav__footer">
            <ul class="nav__principal">
                <li class="nav__principal-items"><a href="#">LE OFF</a></li>
                <li class="nav__principal-items"><a href="#">PROGRAMMATION</a></li>
                <li class="nav__principal-items"><a href="#">ARTISTES</a></li>
                <li class="nav__principal-items"><a href="#">PARTENAIRES</a></li>
            </ul>
            <ul class="nav__secondaire">
                <li class="nav__secondaire-items"><a href="#">LIEUX</a></li>
                <li class="nav__secondaire-items"><a href="#">TARIFS</a></li>
                <li class="nav__secondaire-items"><a href="#">CONTACT</a></li>
            </ul>
        </nav>
    </div>
    <div class="footer">
        <div class="contact">
            <h2>COORDONNÉES</h2>
            <p>110 boulevard René-Lévesque Ouest<br>
                C.P. 48036<br>
                Québec, Québec<br>
                G1R 5R5</p>
            <p><a href="mailto:info@quebecoff.org">info@quebecoff.org</a><br>
                <a href="mailto:media@quebecoff.org">media@quebecoff.org</a>
            </p>
        </div>

        <div class="newsletter">
            <h2>S'ABONNER À L'INFOLETTRE</h2>
            <form>
                <input type="email" placeholder="Votre email">
                <button type="submit">S'ABONNER</button>
            </form>
        </div>
    </div>

    <div class="socials">
        <a href="#"><img src="facebook-icon.png" alt="Facebook"></a>
        <a href="#"><img src="x-icon.png" alt="X"></a>
        <a href="#"><img src="youtube-icon.png" alt="YouTube"></a>
    </div>
    </div>
    <h5 class="piedDePage__copyRights">@Copyrights Michel Rouleau</h5>
</footer>

<script src="public/liaisons/js/_menu.js"></script>