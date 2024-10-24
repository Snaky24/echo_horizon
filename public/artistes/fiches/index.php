<?php 
$niveau = '../../';
?>
<?php include ($niveau . "liaisons/php/config.inc.php");?>
<?php include ($niveau . "liaisons/fragments/headlinks.inc.php");?>

<?php

	$id_artiste=$_GET['id_artiste']; 
	//Établissement de la chaine de requête
	$strRequete =  'SELECT artistes.nom, artistes.id, description, provenance, site_web
					FROM artistes INNER JOIN styles_artistes ON styles_artistes.artiste_id = artistes.id
					WHERE styles_artistes.artiste_id='.$id_artiste;


	//Exécution de la requête
	$pdosResultat = $objPdo->query($strRequete);
    $pdosResultat->execute();
	//Extraction de l'enregistrements de la BD

    $arrArtistes=array(); 
    $ligne=$pdosResultat->fetch();
            $arrArtistes['id'] = $ligne['id'];
            $arrArtistes['nom'] = $ligne['nom'];
			$arrArtistes['description']=$ligne['description'];
			$arrArtistes['provenance']=$ligne['provenance'];
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
	
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>Fiche de l'artiste</title>
    <?php include ($niveau . "liaisons/fragments/headlinks.inc.php");?>
	
</head>

<body>
    <h1 class="artiste__titre"><?php echo $arrArtistes['nom'] ?></h1>
    <h2 class="artiste__titre">Description</h2>
    <p><?php echo $arrArtistes['description']?></p>
    <h3 class="artiste__titre">Site Web :</h3>
	<p class="hyperlien" ><?php echo $arrArtistes['site_web']?></p>
    <h3 class="artiste__titre">Provenance</h3>
	<p><?php echo $arrArtistes['provenance']?></p>
    <h3 class="artiste__titre">Style musical</h3>
	<p><?php echo $arrArtistes['style']?></p>


<p>
	<p><a class="hyperlien" href="<?php echo $niveau ?>artistes/index.php">Retour à la liste des artistes</a></p>
</p>
</body>
</html>