<?php $niveau = "../"; ?>
<?php include($niveau . "liaisons/php/config.inc.php"); ?>

<?php
// REQUETE DE SELECTION DE TOUS LES LIEUX
$strRequeteTousLesLieux = 'SELECT id, nom 
                            FROM lieux';

$pdoResultatTousLesLieux = $objPdo->prepare($strRequeteTousLesLieux);
$pdoResultatTousLesLieux->execute();

$arrTousLesLieux = array();
$ligneTousLesLieux = $pdoResultatTousLesLieux->fetchAll();
// SELECTION DE TOUS LES LIEUX PRÈSENTS DANS LE TABLEAU

foreach ($ligneTousLesLieux as $ligneTousLesLieu) {
    $arrTousLesLieux['nom'] = $ligneTousLesLieu['nom'];

    // REQUETE DE SELECTION DE TOUS LES LIEUX ET DES ARTISTES PRÉSENTS DANS LES LIEUX
    if (isset($_GET['id_date']) == true) {

        $strRequeteLieu = 'SELECT lieu_id, artiste_id, lieux.nom AS lieux_nom, artistes.nom AS artistes_nom, date_et_heure AS dates_et_heures, 
        DAYOFWEEK(evenements.date_et_heure) AS jourSemaine,
        DAYOFMONTH(evenements.date_et_heure) AS jour, 
        MONTH(evenements.date_et_heure) AS mois, 
        HOUR (evenements.date_et_heure) AS heure, 
        MINUTE (evenements.date_et_heure) AS minute 
        FROM evenements 
        INNER JOIN artistes ON artistes.id  = evenements.artiste_id 
        INNER JOIN lieux ON lieux.id  = evenements.lieu_id 
        WHERE DAYOFMONTH(evenements.date_et_heure) = ' . $_GET['id_date'] . ' ORDER BY lieux_nom';

        if(isset($_GET['tri']) == 1){
        $strRequeteLieu = 'SELECT lieu_id, artiste_id, lieux.nom AS lieux_nom, artistes.nom AS artistes_nom, date_et_heure AS dates_et_heures, 
             DAYOFWEEK(evenements.date_et_heure) AS jourSemaine,
        DAYOFMONTH(evenements.date_et_heure) AS jour, 
        MONTH(evenements.date_et_heure) AS mois, 
        HOUR (evenements.date_et_heure) AS heure, 
        MINUTE (evenements.date_et_heure) AS minute 
        FROM evenements 
        INNER JOIN artistes ON artistes.id  = evenements.artiste_id 
        INNER JOIN lieux ON lieux.id  = evenements.lieu_id 
        WHERE DAYOFMONTH(evenements.date_et_heure) = ' . $_GET['id_date'] . ' ORDER BY dates_et_heures';
        }
    
    } else {
        $strRequeteLieu = 'SELECT lieu_id, artiste_id, lieux.nom AS lieux_nom, artistes.nom AS artistes_nom, date_et_heure AS dates_et_heures, 
              DAYOFWEEK(evenements.date_et_heure) AS jourSemaine,
         DAYOFMONTH(evenements.date_et_heure) AS jour, 
        MONTH(evenements.date_et_heure) AS mois, 
        HOUR (evenements.date_et_heure) AS heure, 
        MINUTE (evenements.date_et_heure) AS minute 
        FROM evenements 
        INNER JOIN artistes ON artistes.id  = evenements.artiste_id
        INNER JOIN lieux ON lieux.id  = evenements.lieu_id
        WHERE lieux.id = evenements.lieu_id
        ORDER BY lieux_nom';

        if(isset($_GET['tri']) == 1){
            $strRequeteLieu = 'SELECT lieu_id, artiste_id, lieux.nom AS lieux_nom, artistes.nom AS artistes_nom, date_et_heure AS dates_et_heures, 
                DAYOFWEEK(evenements.date_et_heure) AS jourSemaine,
            DAYOFMONTH(evenements.date_et_heure) AS jour, 
           MONTH(evenements.date_et_heure) AS mois, 
           HOUR (evenements.date_et_heure) AS heure, 
           MINUTE (evenements.date_et_heure) AS minute 
           FROM evenements 
           INNER JOIN artistes ON artistes.id  = evenements.artiste_id
           INNER JOIN lieux ON lieux.id  = evenements.lieu_id
           WHERE lieux.id = evenements.lieu_id
           ORDER BY dates_et_heures';
        }
        
    }
    $pdoResulatLieu = $objPdo->prepare($strRequeteLieu);
    $pdoResulatLieu->execute();
    $arrEvenements = array();
    $ligneResulatLieu = $pdoResulatLieu->fetch();

    for ($intCptEvent = 0; $intCptEvent < $pdoResulatLieu->rowCount(); $intCptEvent++) {
        $arrEvenements[$intCptEvent]['lieu_id'] = $ligneResulatLieu['lieu_id'];
        $arrEvenements[$intCptEvent]['artiste_id'] = $ligneResulatLieu['artiste_id'];
        $arrEvenements[$intCptEvent]['lieux_nom'] = $ligneResulatLieu['lieux_nom'];
        $arrEvenements[$intCptEvent]['artistes_nom'] = $ligneResulatLieu['artistes_nom'];
        $arrEvenements[$intCptEvent]['dates_et_heures'] = $ligneResulatLieu['dates_et_heures'];
        $arrEvenements[$intCptEvent]['jour'] = $ligneResulatLieu['jour'];
        $arrEvenements[$intCptEvent]['jourSemaine'] = $ligneResulatLieu['jourSemaine'];
        $arrEvenements[$intCptEvent]['mois'] = $ligneResulatLieu['mois'];
        $arrEvenements[$intCptEvent]['heure'] = $ligneResulatLieu['heure'];
        $arrEvenements[$intCptEvent]['minute'] = $ligneResulatLieu['minute'];
        $ligneResulatLieu = $pdoResulatLieu->fetch();
    }
    $pdoResulatLieu->closeCursor();
    $ligneTousLesLieux = $pdoResultatTousLesLieux->fetch();
}

$pdoResultatTousLesLieux->closeCursor();


function trouverStylesArtiste($id)
{
    //Faire une requête pour les styles de ce artiste
    $strSql = 'SELECT nom 
            FROM styles 
            INNER JOIN styles_artistes ON styles.id=styles_artistes.style_id
            WHERE artiste_id=' . $id;

    global $objPdo;
    //Exécution de la requête
    $objStmtStylesArtiste = $objPdo->prepare($strSql);
    $objStmtStylesArtiste->execute();
    $arrStyles = $objStmtStylesArtiste->fetchAll();
    $strStylesArtiste = "";
    //Extraction des informations sur les styles de cet artiste
    foreach ($arrStyles as $arrStyle) {
        //Si la liste de style n'est pas vide
        if ($strStylesArtiste != "") {
            //ajouter une virgule
            $strStylesArtiste = $strStylesArtiste . ", ";
        }
        //Ajouter ensuite l'indentifiant de la style
        $strStylesArtiste = $strStylesArtiste . $arrStyle["nom"];
    }
    return $strStylesArtiste;
}

function afficherMois($mois)
{
    $arrMois = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
    return $arrMois[$mois - 1];
}

function afficherJour($jour)
{
    $arrJours = array('dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
    return $arrJours[$jour - 1];
}
function ajouterZero($temps)
{
    if ($temps <= 9) {
        return "0" . $temps;
    } else {
        return $temps;
    }
}

// EXTRACTION DES DATES POUR L'AFFICHAGE SELON LES DATES

$strRequeteDates = 'SELECT DISTINCT DAYOFWEEK(evenements.date_et_heure) AS jourdesemaine, DAYOFMONTH(evenements.date_et_heure) AS jour, 
                    MONTH(evenements.date_et_heure) AS mois
                    FROM evenements';
$pdoResultatDates = $objPdo->prepare($strRequeteDates);
$pdoResultatDates->execute();
$arrJour = array();

for ($cptDate = 0; $ligneResultatDates = $pdoResultatDates->fetch(); $cptDate++) {
    $arrJour[$cptDate]['id_date'] = $cptDate;
    $arrJour[$cptDate]['jourdesemaine'] = $ligneResultatDates['jourdesemaine'];
    $arrJour[$cptDate]['jour'] = $ligneResultatDates['jour'];
    $arrJour[$cptDate]['mois'] = $ligneResultatDates['mois'];
}
$pdoResultatDates->closeCursor();
?>


<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Un nouveau festival à Québec. De sombreux artistes seront invités pour participer à ce grand évenement">
    <meta name="keyword" content="festival, artistes, fête, soirée, jeunes, enfants, musique, québec">
    <meta name="author" content="Atindehou Elma">
    <link rel="icon" type="image/x-icon" href="<?php echo $niveau; ?>liaisons/images/logo.svg">
    <title>Programmation</title>
    <?php include($niveau . "liaisons/fragments/headlinks.inc.php"); ?>
</head>

<body>
    <a class="screen-reader-only focusable" href="#main">Aller au contenu</a>
    <?php include($niveau . "liaisons/fragments/entete.inc.php"); ?>
    <nav class="nav_sec">
			<ul class="nav-sec__liste">
            <?php
        if (isset($_GET['id_date']) == true) { ?>
         <li class="nav-sec__listeItem"><a href="index.php?id_date=<?php echo $_GET['id_date']?>&tri=1" class="nav-sec__lien">Par date</a></li>
         <li class="nav-sec__listeItem"><a href="index.php?id_date=<?php echo $_GET['id_date']?>" class="nav-sec__lien">Par lieu</a></li>
        
         <?php } else { ?>
            <li class="nav-sec__listeItem"><a href="index.php?tri=1" class="nav-sec__lien">Par date</a></li>
            <li class="nav-sec__listeItem"><a href="index.php" class="nav-sec__lien">Par lieu</a></li>
        <?php } ?>

			</ul>
		</nav>
        <hr class="separator">

    <main class=contenuPrincipal>
        <h1 class="titrePrincipal">Programmation</h1>

        <?php
        if (isset($_GET['id_jour']) == true ) { ?>
   <?php 
   ?>
            <h2 class="titreNiveau2">
                <?php echo afficherJour($arrJour[$_GET['id_jour']-3]['jourdesemaine']) . " " . $_GET['id_date'] . " " . afficherMois($arrJour[0]['mois']) ?>
            </h2>

        <?php } else { ?>
            <h2 class="titreNiveau2">Toutes les dates</h2>
        <?php } ?>

        <ul class="lienDate">
            <?php
            for ($intCpt = 0; $intCpt < count($arrJour); $intCpt++) {  ?>
            <?php
             ?>
                <li class="lienDate__item">
                    <a class="lienDate__lien" href='index.php?id_date=<?php echo $arrJour[$intCpt]['jour'] ?>&id_jour=<?php echo $arrJour[$intCpt]['jourdesemaine'] ?>'>
                        <?php echo $arrJour[$intCpt]['jour'] . " " . afficherMois($arrJour[0]['mois']) ?>
                    </a>
                </li>

            <?php } ?>
        </ul>

        <!-- AFFICHAGE DES LIEUX  -->
        <ul class="conteneurEvenements">
            <?php
            //mémorise le lieu présentement traité
            $lieuActuel = "";
            //partout tout les événements
            foreach ($arrEvenements as $arrEvenement) {
                //si le lieu présentement traité
                if ($lieuActuel != $arrEvenement['lieux_nom']) {
                    if ($lieuActuel != "") { ?>
                    </ul>
                    </li>
                <?php } ?>
                <li class="conteneurEvenements__items">
                    <h3 class="titreNiveau3">
                        <?php echo $arrEvenement['lieux_nom'];
                        $lieuActuel = $arrEvenement['lieux_nom']; ?>
                    </h3>
                    <ul class="evenementsDesArtistes">
                    <?php } ?>
                    <li class="evenementsDesArtistes__item">
                        <picture >
                            <source srcset="<?php echo $niveau; ?>liaisons/images/artistes/carre/id_<?php echo $arrEvenement["artiste_id"] ?>_artiste_<?php echo rand(1, 5) ?>_w812-carre.jpg" media="(max-width:600px)">
                            <source srcset="<?php echo $niveau; ?>liaisons/images/artistes/portrait/<?php echo $arrEvenement["artiste_id"] ?>_<?php echo rand(1, 2) ?>_portrait__w636.jpg" media="(min-width:601px)">
                            <img class="evenementsDesArtistes__image"
                            src="<?php echo $niveau; ?>liaisons/images/artistes/portrait/<?php echo $arrEvenement["artiste_id"] ?>_<?php echo rand(1, 2) ?>_portrait__w636.jpg"
                            alt=" Artiste <?php echo $arrEvenement["artistes_nom"] ?>" width="50" height="50">
                        </picture>
                        <div class="infoArtiste">
                            <a class="evenementsDesArtistes__lien"
                                href='<?php echo $niveau; ?>artistes/fiches/index.php?id_artiste=<?php echo $arrEvenement['artiste_id']; ?>&id_style=<?php echo $arrEvenement['artiste_id']; ?>'>
                                <?php echo $arrEvenement['artistes_nom']; ?></a>
                           <p class="styleArtiste"><?php echo trouverStylesArtiste($arrEvenement['artiste_id']); ?></p>
                            <time datetime="<?php echo $arrEvenement['dates_et_heures'] ?>">
                                <?php echo ajouterZero($arrEvenement['heure']) ?>h<?php echo ajouterZero($arrEvenement['minute']) ?>
                            </time>
                        </div>
                    </li>

                <?php } ?>

            </ul>
        </li>
        </ul>

    </main>
    <footer>
        <?php include($niveau . "liaisons/fragments/piedDePage.inc.php"); ?>
    </footer>
</body>

</html>