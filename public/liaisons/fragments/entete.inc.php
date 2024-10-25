<?php $niveau = "../"; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="../public/liaisons/css/styles.css">
    <link rel="stylesheet" href="../ressources/liaisons/scss/styles.scss">
</head>

<header class="entete">
    <img class="logo" src="../public/liaisons/images/logo.svg" alt="Logo">
    <nav class="menu menu--ferme">
        <ul class="menu__liste">
            <li class="menu__listeItem"><a href="<?php echo $niveau; ?>index.php" class="menu__lien">Le OFF</a></li>
            <li class="menu__listeItem"><a href="<?php echo $niveau; ?>public/programmation/index.php"
                    class="menu__lien">Programmation</a></li>
            <li class="menu__listeItem"><a href="<?php echo $niveau; ?>public/artistes/index.php"
                    class="menu__lien">Artistes</a></li>
            <li class="menu__listeItem"><a href="<?php echo $niveau; ?>public/lieux/index.php"
                    class="menu__lien">Partenaires</a></li>
        </ul>
    </nav>

    <a class="sac"><img src="../public/liaisons/images/sac.svg" alt=""></a>
    <a class="loupe"><img src="../public/liaisons/images/loupe.svg" alt=""></a>
    <button type="bouton" style="text-shadow: 1px 1px 2px #000, 0 0 25em #000;">BILLETS</button>
</header>

</html>