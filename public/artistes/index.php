<?php $niveau="../";?>

<?php include ($niveau . "liaisons/php/config.inc.php");?>

<?php

if(isset($_GET['id_page'])){
    $id_page = $_GET['id_page'];
}else{
    $id_page = 0;
}

$intMaxParPage = 4;

$enregistrementDepart = $id_page * $intMaxParPage;

$strRequete = 'SELECT COUNT(*) AS nbEnregistrement FROM artistes';

//Initialisation de l'objet PDOStatement et exécution de la requête
$pdosResultat = $objPdo->prepare($strRequete);
$pdosResultat->execute();

//On récupère l'enregistrement dans une variable
$ligne = $pdosResultat->fetch();
//On extrait la réponse de l'enregistrement et on sauvegarde dans une variable $ligne
$intNbParticipant = $ligne['nbEnregistrement'];
//On libère la requête
$pdosResultat->closeCursor();
//On détermine le nombre de pages en divisant le nombre de participant par le nombre de participants par page
$nbPages = ceil($intNbParticipant / $intMaxParPage);

if (isset($_GET['style_id'])==true) {
    $id_style=$_GET['style_id']; 
    
        $strRequete =  'SELECT artistes.id, artistes.nom
                        FROM artistes
                        INNER JOIN styles_artistes ON styles_artistes.artiste_id = artistes.id 
                        WHERE styles_artistes.style_id='.$id_style.'
                        LIMIT '. $enregistrementDepart . ',' . $intMaxParPage;
    }

   

else{
    $id_style=0;
    $strRequete =  'SELECT artistes.id, artistes.nom  
                    FROM artistes 
                    ORDER BY nom
                    LIMIT '. $enregistrementDepart . ',' . $intMaxParPage;
 }
    

	//Exécution de la requête
	$pdosResultat = $objPdo->query($strRequete);
    $pdosResultat->execute();
	//Extraction de l'enregistrements de la BD

    $arrArtistes=array(); 
    $ligne=$pdosResultat->fetch();

        for($intCptEnr=0;$intCptEnr<$pdosResultat->rowCount();$intCptEnr++){
            $arrArtistes[$intCptEnr]['id'] = $ligne['id'];
            $arrArtistes[$intCptEnr]['nom'] = $ligne['nom'];
    
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
            $arrArtistes[$intCptEnr]['style'] = $strStyles;
    
            //On passe à l'autre participant
            $ligne=$pdosResultat->fetch();

        }

    $pdosResultat ->closecursor();



   
    $strRequeteStyle =  'SELECT id, nom
                        FROM styles';
	
	$pdosResultatStyle = $objPdo->prepare($strRequeteStyle);
	$pdosResultatStyle->execute();

	$arrStyles=array();
	$ligne=$pdosResultatStyle->fetch();
	for($cpt=0;$cpt<$pdosResultatStyle->rowCount();$cpt++){
		$arrStyles[$cpt]['id']=$ligne['id'];
		$arrStyles[$cpt]['nom']=$ligne['nom'];
		$ligne=$pdosResultatStyle->fetch();
	}
	
	$pdosResultatStyle->closeCursor();




    
    $strRequeteArtiste = 'SELECT id, nom FROM artistes';
    $pdosResultatArtistesSug = $objPdo->query($strRequeteArtiste);
    $pdosResultatArtistesSug->execute();

    $arrArtistesSug = array();
    for($intCptEnr=0;$ligne=$pdosResultatArtistesSug->fetch();$intCptEnr++){
        $arrArtistesSug[$intCptEnr]['id'] = $ligne['id'];
        $arrArtistesSug[$intCptEnr]['nom'] = $ligne['nom'];
    }
    
    $nbArtistesSug = rand(3,5);
    //Établie une liste de choix
    $arrArtistesChoisi = []; //ou $arrParticipantsChoisi = array();
    //Tant que le nombre de suggestions n'est pas atteintsx
    for($intCptPart=0;$intCptPart<$nbArtistesSug;$intCptPart++){
        //Trouve un index au hazard selon le nombre de sugestions
        $artisteChoisi=rand(0,count($arrArtistesSug)-1);
        //Prendre la suggestion et la mettre dans les participants choisis
        array_push($arrArtistesChoisi,$arrArtistesSug[$artisteChoisi]);
        //Enlever la suggestion des suggestions disponibles (évite les suggestions en doublons)
        array_splice($arrArtistesSug,$artisteChoisi,1);

        $pdosResultatArtistesSug ->closecursor();

    };    

?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Artistes</title>
    </head>

    <body>
    <h1>Liste des artistes</h1>

    <?php if($id_style != 0){
	echo '<h2>Artistes du style : '.$arrStyles[$id_style - 1]['nom'].'</h2>';
    }
    ?>
    <h2><a href='index.php'>Tous les artistes</a></h2>
    <ul>
        <?php

            for($intCpt=0;$intCpt<count($arrArtistes);$intCpt++){?>
                <li><a href="<?php echo $niveau ?>artistes/fiches/index.php?id_artiste=<?php echo $arrArtistes[$intCpt]["id"]; ?>"><?php echo $arrArtistes[$intCpt]['nom'];?></a>
                     -> 
                    <?php echo $arrArtistes[$intCpt]['style'];?>
                </li>
    <?php } ?>
    </ul>

    <p>
    <?php if($id_page>0){
			//Si la page courante n'est pas la première, afficher bouton précédent?>
			<a href='index.php?id_page=<?php echo $id_page-1;?>'>Précédent</a>
		<?php } ?>

		<?php
			echo ($id_page+1)?> de <?php echo $nbPages;?>
		
		<?php if($id_page<$nbPages-1){
			//Si la page courante n'est pas la dernière, afficher bouton suivant?>
			<a href='index.php?id_page=<?php echo $id_page+1;?>'>Suivant</a>
		<?php } ?>
        </p>
		

    <h2><a href='index.php'>Tous les styles</a></h2>
    <?php
    for($cpt=0;$cpt<count($arrStyles);$cpt++){?>

	    <li><a href='<?php echo $niveau ?>artistes/index.php?style_id=<?php echo $arrStyles[$cpt]["id"]; ?>'><?php echo $arrStyles[$cpt]["nom"];?></a></li>

    <?php } ?>     

    <h2>Vous connaissez peut-être:</h2>
		<ul>
		<?php
			for($intCpt=0;$intCpt<count($arrArtistesChoisi);$intCpt++){?>
				<li>
					<a href="<?php echo $niveau ?>artistes/fiches/index.php?id_artiste=<?php echo $arrArtistesChoisi[$intCpt]["id"]; ?>">
						<?php echo $arrArtistesChoisi[$intCpt]['nom'];?><a>
				</li>
			<?php } ?>
		</ul>


    <h3><a href="<?php echo $niveau;?>index.php">Retour à l'accueil</a></h3> 
        
    </body>
</html>