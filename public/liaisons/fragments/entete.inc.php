<?php $niveau = ""; ?>
<?php include($niveau . "liaisons/php/config.inc.php"); ?>

<header class="entete">
    <img class="logo" src="<?php echo $niveau; ?>liaisons/images/logo.svg" alt="Logo">
</header>
<nav class="menu menu--ferme">
    <ul class="menu__liste">
        <li class="menu__listeItem"><a href="<?php echo $niveau; ?>index.php" class="menu__lien">Le OFF</a></li>
        <li class="menu__listeItem"><a href="<?php echo $niveau; ?>programmation/index.php"
                class="menu__lien">Programmation</a></li>
        <li class="menu__listeItem"><a href="<?php echo $niveau; ?>artistes/index.php" class="menu__lien">Artistes</a>
        </li>
        <li class="menu__listeItem"><a href="<?php echo $niveau; ?>lieux/index.php" class="menu__lien">Partenaires</a>
        </li>
    </ul>
    <a class="sac"><img src="<?php echo $niveau; ?>liaisons/images/sac.svg" alt=""></a>
    <a class="loupe"><img src="<?php echo $niveau; ?>liaisons/images/loupe.svg" alt=""></a>
    <button type="bouton" style="text-shadow: 1px 1px 2px #000, 0 0 25em #000;">BILLETS</button>
</nav>

<script src="liaisons/js/_menu.js"></script>