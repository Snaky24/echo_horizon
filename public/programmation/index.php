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
        $strRequeteLieu = 'SELECT lieu_id, artiste_id, lieux.nom AS lieux_nom, artistes.nom AS artistes_nom, date_et_heure, 
        DAYOFMONTH(evenements.date_et_heure) AS jour, 
        MONTH(evenements.date_et_heure) AS mois, 
        HOUR (evenements.date_et_heure) AS heure, 
        MINUTE (evenements.date_et_heure) AS minute 
        FROM evenements 
        INNER JOIN artistes ON artistes.id  = evenements.artiste_id 
        INNER JOIN lieux ON lieux.id  = evenements.lieu_id 
        WHERE DAYOFMONTH(evenements.date_et_heure) = ' . $_GET['id_date'] . ' ORDER BY lieux_nom';
    } else {
        $strRequeteLieu = 'SELECT lieu_id, artiste_id, lieux.nom AS lieux_nom, artistes.nom AS artistes_nom, date_et_heure, 
         DAYOFMONTH(evenements.date_et_heure) AS jour, 
        MONTH(evenements.date_et_heure) AS mois, 
        HOUR (evenements.date_et_heure) AS heure, 
        MINUTE (evenements.date_et_heure) AS minute 
        FROM evenements 
        INNER JOIN artistes ON artistes.id  = evenements.artiste_id
        INNER JOIN lieux ON lieux.id  = evenements.lieu_id
        WHERE lieux.id = evenements.lieu_id
        ORDER BY lieux_nom';
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
        $arrEvenements[$intCptEvent]['date_et_heure'] = $ligneResulatLieu['date_et_heure'];
        $arrEvenements[$intCptEvent]['jour'] = $ligneResulatLieu['jour'];
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="author" content="">
    <meta charset="utf-8">
    <title>Programmation</title>
    <?php include($niveau . "liaisons/fragments/headlinks.inc.php"); ?>
</head>

<body>
    <?php include($niveau . "liaisons/fragments/entete.inc.php"); ?>

    <h1 class="titrePrincipal">Programmation</h1>
    <main class= contenuPrincipal>

        <?php
        if (isset($_GET['id_date']) == true) { ?>

            <h2 class="titreNiveau2"><?php echo afficherJour($arrJour[0]['jourdesemaine']) . " " . $_GET['id_date'] . " " . afficherMois($arrJour[0]['mois']) ?>
            </h2>

        <?php } else { ?>
            <h2 class="titreNiveau2">Toutes les dates</h2>
        <?php } ?>


        <?php
        for ($intCpt = 0; $intCpt < count($arrJour); $intCpt++) { ?>

            <a href='index.php?id_date=<?php echo $arrJour[$intCpt]['jour'] ?>'>
                <?php echo $arrJour[$intCpt]['jour'] . " " . afficherMois($arrJour[0]['mois']) ?>
            </a>
        <?php } ?>
        
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
                    <img class="evenementsDesArtistes__image" src="<?php echo $niveau; ?>liaisons/images/artistes/carre/id_<?php echo $arrEvenement["artiste_id"] ?>_artiste_<?php echo rand(1, 5) ?>_w812-carre.jpg"
                        alt="<?php echo $arrEvenement["artiste_id"] ?>" width="50" height="50">
                    <li class="evenementsDesArtistes__item">
                        <a class="evenementsDesArtistes__lien"
                            href='<?php echo $niveau; ?>artistes/fiches/index.php?id_artiste=<?php echo $arrEvenement['artiste_id']; ?>&id_style=<?php echo $arrEvenement['artiste_id']; ?>'>
                            <?php echo $arrEvenement['artistes_nom']; ?></a>
                        <?php echo trouverStylesArtiste($arrEvenement['artiste_id']); ?>
                        <time datetime="<?php echo $arrEvenement['date_et_heure'] ?>">
                            <?php echo ajouterZero($arrEvenement['heure']) ?>h<?php echo ajouterZero($arrEvenement['minute']) ?>
                        </time>

                    </li>

                <?php } ?>

            </ul>
        </li>
        </ul>


    </main>
    <footer>
        <?php include($niveau . "liaisons/fragments/pieddePage.inc.php"); ?>
    </footer>
</body>

</html>