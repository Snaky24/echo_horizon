Programmation!
<?php $niveau = "../";
include($niveau . 'liaisons/php/config.inc.php') ?>;
<a href="<?php echo $niveau; ?>index.php">Retour</a>

<?php
// REQUETE DE SELECTION DE TOUS LES LIEUX
$strRequeteTousLesLieux = 'SELECT lieux.id, nom
                            FROM lieux';
$pdoResultatTousLesLieux = $objPdo->prepare($strRequeteTousLesLieux);
$pdoResultatTousLesLieux->execute();
$arrTousLesLieux = array();
$ligneTousLesLieux = $pdoResultatTousLesLieux->fetch();
// SELECTION DE TOUS LES LIEUX PRÈSENTS DANS LE TABLEAU
for ($intCpt = 0; $intCpt < $pdoResultatTousLesLieux->rowCount(); $intCpt++) {
    $arrTousLesLieux[$intCpt]['nom'] = $ligneTousLesLieux['nom'];

    // REQUETE DE SELECTION DE TOUS LES LIEUX ET DES ARTISTES PRÉSENTS DANS LES LIEUX
    if(isset($_GET ['id_date'])==true){
        $strRequeteLieu = 'SELECT lieu_id, artiste_id, lieux.nom AS lieux_nom, artistes.nom AS artistes_nom, date_et_heure, 
        DAYOFMONTH(evenements.date_et_heure) AS mois, 
        HOUR (evenements.date_et_heure) AS heure, 
        MINUTE (evenements.date_et_heure) AS minute 
        FROM evenements 
        INNER JOIN artistes ON artistes.id  = evenements.artiste_id
        INNER JOIN lieux ON lieux.id  = evenements.lieu_id
        WHERE DAYOFMONTH(evenements.date_et_heure) = '.$_GET ['id_date']. 
        ' ORDER BY evenements.date_et_heure 
         AND lieu_id';
    }
    else{
        $strRequeteLieu = 'SELECT lieu_id, artiste_id, lieux.nom AS lieux_nom, artistes.nom AS artistes_nom, date_et_heure, 
        DAYOFMONTH(evenements.date_et_heure) AS mois, 
        HOUR (evenements.date_et_heure) AS heure, 
        MINUTE (evenements.date_et_heure) AS minute 
        FROM evenements 
        INNER JOIN artistes ON artistes.id  = evenements.artiste_id
        INNER JOIN lieux ON lieux.id  = evenements.lieu_id
        WHERE lieux.id = evenements.lieu_id';
    }
    $pdoResulatLieu = $objPdo->prepare($strRequeteLieu);
    $pdoResulatLieu->execute();
    $arrEvenements = array();
    $ligneResulatLieu = $pdoResulatLieu->fetch();

    for ($intCpt = 0; $intCpt < $pdoResulatLieu->rowCount(); $intCpt++) {
        // var_dump($arrResultatLieu);
        $arrEvenements[$intCpt]['lieu_id'] = $ligneResulatLieu['lieu_id'];
        $arrEvenements[$intCpt]['artiste_id'] = $ligneResulatLieu['artiste_id'];
        $arrEvenements[$intCpt]['lieux_nom'] = $ligneResulatLieu['lieux_nom'];
        $arrEvenements[$intCpt]['artistes_nom'] = $ligneResulatLieu['artistes_nom'];
        $arrEvenements[$intCpt]['date_et_heure'] = $ligneResulatLieu['date_et_heure'];
        $arrEvenements[$intCpt]['mois'] = $ligneResulatLieu['mois'];
        $arrEvenements[$intCpt]['heure'] = $ligneResulatLieu['heure'];
        $arrEvenements[$intCpt]['minute'] = $ligneResulatLieu['minute'];
        $ligneResulatLieu = $pdoResulatLieu->fetch();
    }

    $ligneTousLesLieux = $pdoResultatTousLesLieux->fetch();
    $pdoResulatLieu->closeCursor();
    $pdoResultatTousLesLieux->closeCursor();
}

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

$strRequeteDates = 'SELECT DISTINCT DAYOFMONTH(evenements.date_et_heure) AS jour, 
                    MONTH(evenements.date_et_heure) AS mois
                    FROM evenements';
$pdoResultatDates = $objPdo->prepare($strRequeteDates);
$pdoResultatDates->execute();

$arrDates = array();

for ($cptDate = 0; $ligneResultatDates = $pdoResultatDates->fetch(); $cptDate++) {
    $arrDates[$cptDate]['id_date'] = $cptDate+8;
    $arrDates[$cptDate]['jour'] = $ligneResultatDates['jour'];
    $arrDates[$cptDate]['mois'] = $ligneResultatDates['mois'];
}
// $ligneResultatDates = $pdoResultatDates->fetch();
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

    <h1>Programmation</h1>
    <main>

        <?php
        for ($intCpt = 0; $intCpt < count($arrDates); $intCpt++) { ?>
           <?php  var_dump( value: $arrDates[$intCpt]['id_date']) ?>


            <a href='index.php?id_date=<?php echo $arrDates[$intCpt]['id_date'] ?>'>
                <?php echo $arrDates[$intCpt]['jour'] ?>
            </a>
        <?php } ?>

        <ul>
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

                <li>
                    <h3>

                        <?php echo $arrEvenement['lieux_nom'];
                        $lieuActuel = $arrEvenement['lieux_nom']; ?>

                    </h3>
                    <ul>

                    <?php } ?>

                    <li>
                        <time datetime="<?php echo $arrEvenement['date_et_heure'] ?>">
                            <?php echo ajouterZero($arrEvenement['heure']) ?>h<?php echo ajouterZero($arrEvenement['minute']) ?>
                        </time>,
                        <a
                            href='<?php echo $niveau; ?>artistes/fiches/index.php?artiste_id=<?php echo $arrEvenement['artiste_id']; ?>'>
                            <?php echo $arrEvenement['artistes_nom']; ?></a>,
                        <?php echo trouverStylesArtiste($arrEvenement['artiste_id']); ?>
                        <img src="<?php echo $niveau; ?>liaisons/images/artistes/<?php echo $arrEvenement["artiste_id"] ?>_0_carre_w150.jpg"
                            alt="<?php echo $arrEvenement["artiste_id"] ?>" width="50" height="50">

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