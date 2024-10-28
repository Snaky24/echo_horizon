<?php 
$niveau = '../../';
?>
<?php include ($niveau . "liaisons/php/config.inc.php");?>

<?php

	$id_artiste=$_GET['id_artiste']; 
	
	$strRequete =  'SELECT artistes.nom, artistes.id, description, provenance, pays, site_web
					FROM artistes INNER JOIN styles_artistes ON styles_artistes.artiste_id = artistes.id
					WHERE styles_artistes.artiste_id='.$id_artiste;


	// INFOS DE L'ARTISTE
	$pdosResultat = $objPdo->query($strRequete);
    $pdosResultat->execute();

    $arrArtistes=array(); 
    $ligne=$pdosResultat->fetch();
            $arrArtistes['id'] = $ligne['id'];
            $arrArtistes['nom'] = $ligne['nom'];
			$arrArtistes['description']=$ligne['description'];
			$arrArtistes['provenance']=$ligne['provenance'];
            $arrArtistes['pays']=$ligne['pays'];
			$arrArtistes['site_web']=$ligne['site_web'];
    
                 //On établi une deuxième requête pour afficher les sports du participants
                 $strRequete =  'SELECT nom FROM styles
                 INNER JOIN styles_artistes ON styles_artistes.style_id=styles.id
                 WHERE artiste_id='. $ligne['id'];
    
                 //Initialisation de l'objet PDOStatement et exécution de la requête
                 $pdosSousResultat = $objPdo->prepare($strRequete);
                 $pdosSousResultat->execute();
    
                 $ligneStyle = $pdosSousResultat->fetch();
                 $strStyles="";
                 //Extraction des noms de Sports de la sous requête
                 for($intCptSport=0;$intCptSport<$pdosSousResultat->rowCount();$intCptSport++){
                     if($strStyles != ""){
                         $strStyles = $strStyles . ", ";    //ajout d'une virgule lorsque nécessaire
                     }
                     $strStyles = $strStyles . $ligneStyle['nom'];
                     $ligneStyle = $pdosSousResultat->fetch();
                 }
                
                 //On libère la sous requête
                 $pdosSousResultat->closeCursor();
                
            //ajout d'un propriété pour afficher les sports
            $arrArtistes['style'] = $strStyles;
    
            //On passe à l'autre participant
            $ligne=$pdosResultat->fetch();
        

    $pdosResultat ->closecursor();

    // ARTISTES SUGGERES
    $strRequeteArtistes =  'SELECT DISTINCT artistes.id, nom 
                            FROM artistes INNER JOIN styles_artistes ON artistes.id=styles_artistes.artiste_id 
                            WHERE style_id IN(SELECT style_id FROM styles_artistes WHERE artiste_id=' . $id_artiste . ') 
                            AND styles_artistes.artiste_id<>' . $id_artiste;
    
    $pdosResultatArtiste = $objPdo->query($strRequeteArtistes);
    $pdosResultatArtiste->execute();
	$arrArtistesStyle=array(); 

    for($intCptEnr=0;$intCptEnr<$pdosResultatArtiste->rowCount();$intCptEnr++){
    
    $ligne=$pdosResultatArtiste->fetch();
            $arrArtistesStyle[$intCptEnr]['id'] = $ligne['id'];
            $arrArtistesStyle[$intCptEnr]['nom'] = $ligne['nom'];
    }
    
    if($pdosResultatArtiste->rowCount() < 2){
        $nbArtistesSug = 1;
    }
    else if($pdosResultatArtiste->rowCount() < 3){
        $nbArtistesSug = 2;
    }
    else{
        $nbArtistesSug = rand(3,3);
    }
    
    $arrArtistesChoisi = []; 
    
    if($id_artiste != 24){ 
    for($intCptPart=0;$intCptPart<$nbArtistesSug;$intCptPart++){
        
        $artisteChoisi=rand(0,count($arrArtistesStyle)-1);
        array_push($arrArtistesChoisi,$arrArtistesStyle[$artisteChoisi]);
        array_splice($arrArtistesStyle,$artisteChoisi,1);
        }
    }
    $pdosResultatArtiste ->closecursor();


    // SPECTACLES
    $strRequeteSpectacle = 'SELECT artiste_id, lieu_id, nom, 
                            DAYOFMONTH(date_et_heure) AS jour, 
                            DAYOFWEEK(date_et_heure) AS jourSemaine,
                            MONTH(date_et_heure) AS mois, 
                            YEAR(date_et_heure) AS annee, 
                            HOUR(date_et_heure) AS heure, 
                            MINUTE(date_et_heure) AS minut 
                            FROM evenements
                            INNER JOIN lieux ON lieux.id=evenements.lieu_id
                            WHERE artiste_id=' . $id_artiste .
                            ' ORDER BY date_et_heure';


    $pdosResultatSpectacle = $objPdo->query($strRequeteSpectacle);
    $pdosResultatSpectacle->execute();
    $arrSpectacles=array(); 

    for($intCptEnr=0;$intCptEnr<$pdosResultatSpectacle->rowCount();$intCptEnr++){

    $ligne=$pdosResultatSpectacle->fetch();
        $arrSpectacles[$intCptEnr]['artiste_id'] = $ligne['artiste_id'];
        $arrSpectacles[$intCptEnr]['lieu_id'] = $ligne['lieu_id'];
        $arrSpectacles[$intCptEnr]['nom'] = $ligne['nom'];
        $arrSpectacles[$intCptEnr]['jourSemaine'] = $ligne['jourSemaine'];
        $arrSpectacles[$intCptEnr]['jour'] = $ligne['jour'];
        $arrSpectacles[$intCptEnr]['mois'] = $ligne['mois'];
        $arrSpectacles[$intCptEnr]['annee'] = $ligne['annee'];
        $arrSpectacles[$intCptEnr]['heure'] = $ligne['heure'];
        $arrSpectacles[$intCptEnr]['minut'] = $ligne['minut'];
        
    }
    
    $pdosResultatSpectacle ->closecursor();

    // DATES
    $arrMois = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
	$arrJour = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');


    // PHOTOS
    $arrPhoto = array('1', '2', '3', '4', '5');
    $nbPhotos = rand(3,5);
	$arrPhotosChoisies = []; 
	
	for($intCptPart=0;$intCptPart<$nbPhotos;$intCptPart++){
	
		$intIndexHazard=rand(0,count($arrPhoto)-1);
		array_push($arrPhotosChoisies,$arrPhoto[$intIndexHazard]);
		array_splice($arrPhoto,$intIndexHazard,1);
	};

?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <?php include($niveau . "liaisons/fragments/headlinks.inc.php"); ?>
    <title>Fiche de l'artiste</title>
</head>



<body class="artiste">
<?php include($niveau . "liaisons/fragments/entete.inc.php"); ?>
<main>
    <h1 class="artiste__nom"><?php echo $arrArtistes['nom']?></h1>
	<h2 class="artiste__titre">Description</h2>
    <p class="artiste__description"><?php echo $arrArtistes['description']?></p>

    <h2 class="artiste__titre">Représentations</h2>
    <ul class="spectacle">
        <?php
            for($intCpt=0;$intCpt<count($arrSpectacles);$intCpt++){?>
                <li class="spectacle__liste">
                    <?php echo $arrSpectacles[$intCpt]['nom']?>
                    <br>
                    <?php echo $arrJour[$arrSpectacles[$intCpt]['jourSemaine']-1].' le ' .$arrSpectacles[$intCpt]['jour'].' '.$arrMois[$arrSpectacles[$intCpt]['mois']-1].' à '.$arrSpectacles[$intCpt]['heure'].'h'.$arrSpectacles[$intCpt]['minut']?>
            
            
                </li>
    <?php } ?>
    </ul>

    <section class="infos">
        <h3 class="infos__titre">Provenance</h3>
        <p class="infos__texte"><?php echo $arrArtistes['provenance']. ', '.$arrArtistes['pays']?></p>
        <h3 class="infos__titre">Style musical</h3>
        <p class="infos__texte"><?php echo $arrArtistes['style']?></p>
    </section>

    <?php if($arrArtistes['site_web'] != NULL){ ?>
		<h3 class="website">Site Web:</h3><a class="hyperlien" href='<?php echo $arrArtistes['site_web']; ?>'><?php echo $arrArtistes['site_web']; ?></a>
    <?php } ?>


    
 
    <img src="<?php echo $niveau ?>liaisons/images/artistes/artiste_<?php echo $id_artiste ?>_1__port_w908" alt="photo artiste #<?php echo $id_artiste ?>">
  



    <?php if($arrArtistesChoisi != NULL){
	?>
    <section class="suggestion">
        <h3 class="suggestion__titre">Vous pourriez aimer:</h3>
        <ul class="suggestion__liste">
            <?php

                for($intCpt=0;$intCpt<count($arrArtistesChoisi);$intCpt++){?>
                    <li><a class="hyperlien suggestion__artiste" href="<?php echo $niveau ?>artistes/fiches/index.php?id_artiste=<?php echo $arrArtistesChoisi[$intCpt]["id"]; ?>"><?php echo $arrArtistesChoisi[$intCpt]['nom'];?></a>
                    </li>
                <?php } ?>
        </ul>
    </section>
    <?php } ?>          
</main>
    <?php include($niveau . "liaisons/fragments/piedDePage.inc.php"); ?>
</body>
  
</html>