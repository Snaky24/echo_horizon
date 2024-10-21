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
    <h1 class="artiste__titre">Fiche de l'artiste</h1>
	
    <?php 
		//Affichage du participant et de son identifiant
		echo "<h2>Nom:</h2>" . $arrArtistes['nom'];
		echo "<h2>Description:</h2>" . $arrArtistes['description'];
		echo "<h2>Provenance:</h2> " . $arrArtistes['provenance'];
		echo "<h2>Site Web:</h2> " . $arrArtistes['site_web'];
		echo "<h2>Style:</h2> " . $arrArtistes['style'];
		
	?>


<p>
	<h3><a href="<?php echo $niveau ?>artistes/index.php">Retour à la liste des artistes</a></h3>
</p>
</body>
</html>