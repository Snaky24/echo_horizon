<?php $niveau = "../"; ?>
<?php include($niveau . "public/liaisons/php/config.inc.php"); ?>

<?php
$arrJour = array("lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi", "dimanche");
$arrMois = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");

$strRequete = 'SELECT titre, DAYOFWEEK(date_actualite) AS jourSemaine, MONTH(date_actualite) AS mois, DAYOFWEEK(date_actualite) AS jour, YEAR(date_actualite) AS annee, auteurs, article FROM actualites ORDER BY date_actualite DESC;';

$pdosResultatActualites = $objPdo->prepare($strRequete);
$pdosResultatActualites->execute();

$arrActualites = array();
for ($cptEnr = 0; $ligneActualite = $pdosResultatActualites->fetch(); $cptEnr++) {
	$arrActualites[$cptEnr]["titre"] = $ligneActualite["titre"];
	$arrActualites[$cptEnr]["jour"] = $ligneActualite["jour"];
	$arrActualites[$cptEnr]["jourSemaine"] = $ligneActualite["jourSemaine"];
	$arrActualites[$cptEnr]["mois"] = $ligneActualite["mois"];
	$arrActualites[$cptEnr]["annee"] = $ligneActualite["annee"];
	$arrActualites[$cptEnr]["auteurs"] = $ligneActualite["auteurs"];

	$arrArticle = explode(" ", $ligneActualite["article"]);

	if (count($arrArticle) > 45) {
		array_splice($arrArticle, 45, count($arrArticle));
	}

	$arrActualites[$cptEnr]["article"] = implode(" ", $arrArticle);
}
$pdosResultatActualites->closeCursor();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="keyword" content="">
	<meta name="author" content="">
	<meta charset="utf-8">
	<link rel="stylesheet" href="../public/liaisons/css/styles.css">
	<link rel="stylesheet" href="../ressources/liaisons/scss/styles.scss">
	<link rel="stylesheet" href="../ressources/liaisons/scss/layout/_accueil.scss">
	<title>Un beau titre ici!</title>
	<?php include($niveau . "public/liaisons/fragments/headlinks.inc.php"); ?>
</head>

<body>
	<?php include($niveau . "public/liaisons/fragments/entete.inc.php"); ?>

	<>
		<link rel="stylesheet" href="../public/liaisons/fragments/entete.inc.php">
		<span class="centered" style="text-shadow: 1px 1px 2px #000, 0 0 40em #000;">Festival OFF de Québec</span>
		<span class="centered_dates" style="text-shadow: 1px 1px 2px #000, 0 0 40em #000;">DU 8 AU 11 JUILLET</span>
		<img src="../public/liaisons/images/Rectangle 61.png" srcset="../public/liaisons/images/Rectangle 61.png 768w, 
			 ../public/liaisons/images/Rectangle 61.png 1200w" sizes="(max-width: 768px) 100vw, 50vw"
			alt="Description de l'image">
		<nav class="nav_sec">
			<ul class="nav-sec__liste">
				<li class="nav-sec__listeItem"><a href="<?php echo $niveau; ?>#" class="nav-sec__lien">Lieux</a></li>
				<li class="nav-sec__listeItem"><a href="<?php echo $niveau; ?>#" class="nav-sec__lien">Tarifs</a></li>
				<li class="nav-sec__listeItem"><a href="<?php echo $niveau; ?>#" class="nav-sec__lien">Contact</a></li>
			</ul>
		</nav>
		<hr class="separator">
		<div id="contenu" class="conteneur">
			<section class="conteneur_actu">
				<?php for ($cpt = 0; $cpt < 3; $cpt++) { ?>
					<a>
						<header class="titre">
							<h3 class="titre_texte"><b><?php echo $arrActualites[$cpt]["titre"]; ?></b></h3>
						</header>
						<hr class="hr_article">
						<p class="auteurs"><?php echo $arrActualites[$cpt]["auteurs"]; ?></p>
						<article>
							<?php echo $arrActualites[$cpt]["article"];
							if (count(explode(" ", $arrActualites[$cpt]["article"])) >= 45) { ?>
								<a class="a_points" href="#">...</a>
							<?php } ?>
						</article>
						<footer>
							<h4>Par <?php echo $arrActualites[$cpt]["auteurs"]; ?>, le
								<?php echo $arrJour[$arrActualites[$cpt]["jourSemaine"] - 1]; ?>
								<?php echo $arrActualites[$cpt]["jour"] . " " . $arrMois[$arrActualites[$cpt]["mois"]] . " " . $arrActualites[$cpt]["annee"]; ?>
							</h4>
						</footer>
					<?php } ?>
			</section>
		</div>


		<p><a href="#" class="bouton">Bouton</a></p>
		<p><a href="#" class="bouton--inverse">Bouton</a></p>
		<a href="#" class="hyperlien">lien test!</a>
		</main>

		<hr class="separator">

		<?php include($niveau . "public/liaisons/fragments/piedDePage.inc.php"); ?>

</body>

</html>