<?php 
$niveau = '../../';
?>
<?php include ($niveau . "liaisons/php/config.inc.php");?>
<?php include ($niveau . "liaisons/fragments/headlinks.inc.php");?>

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
    <title>Fiche de l'artiste</title>
    
	
</head>

<body>
    <h1>Fiche de l'artiste</h1>
	
    <?php 
		echo "<h2>Nom:</h2>" . $arrArtistes['nom'];
		echo "<h2>Description:</h2>" . $arrArtistes['description'];
		echo "<h2>Provenance:</h2> " . $arrArtistes['provenance']. ', '.$arrArtistes['pays'];
        if($arrArtistes['site_web'] != NULL){ ?>
		<h2>Site Web:</h2><a href='<?php echo $arrArtistes['site_web']; ?>'><?php echo $arrArtistes['site_web']; ?></a>
        <?php }
		echo "<h2>Style:</h2> " . $arrArtistes['style'];
		
	?>

    <h2>Représentations</h2>
    <ul>
        <?php
            for($intCpt=0;$intCpt<count($arrSpectacles);$intCpt++){?>
                <li>
                    <?php echo $arrSpectacles[$intCpt]['nom']. ' '.$arrJour[$arrSpectacles[$intCpt]['jourSemaine']-1].' le ' .$arrSpectacles[$intCpt]['jour'].' '.$arrMois[$arrSpectacles[$intCpt]['mois']-1].' '.$arrSpectacles[$intCpt]['annee'].' à '.$arrSpectacles[$intCpt]['heure'].'h'.$arrSpectacles[$intCpt]['minut']?>
            
            
                </li>
    <?php } ?>
    </ul>

    <!-- AFFICHER 3-5 PHOTOS -->
    <?php for($intCpt=0;$intCpt<count($arrPhotosChoisies);$intCpt++){?>
    <img src="<?php echo $niveau ?>liaisons/images/artistes/artiste<?php echo $id_artiste ?>_photo<?php echo $arrPhotosChoisies[$intCpt] ?>" alt="photo artiste #<?php echo $intCpt +1 ?>">
    <!-- La photo se nomme artisteID_photoNUM -->
    <?php }?>



    <?php if($arrArtistesChoisi != NULL){
	?>
    <h2>Vous pourriez aimer:</h2>
    <ul>
        <?php

            for($intCpt=0;$intCpt<count($arrArtistesChoisi);$intCpt++){?>
                <li><a href="<?php echo $niveau ?>artistes/fiches/index.php?id_artiste=<?php echo $arrArtistesChoisi[$intCpt]["id"]; ?>"><?php echo $arrArtistesChoisi[$intCpt]['nom'];?></a>
                </li>
    <?php } ?>
    </ul>
    <?php 
        }
	?>          
    

	<h3><a href="<?php echo $niveau ?>artistes/index.php">Retour à la liste des artistes</a></h3>

</body>
</html>