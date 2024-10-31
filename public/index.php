<?php $niveau = ""; ?>
<?php include($niveau . "liaisons/php/config.inc.php"); ?>

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
	<title>Un beau titre ici!</title>
	<?php include($niveau . "liaisons/fragments/headlinks.inc.php"); ?>
</head>



<body>
<?php include($niveau . "liaisons/fragments/entete.inc.php"); ?>
	<main>
		<span class="centered" style="text-shadow: 1px 1px 2px #000, 0 0 40em #000;">Festival OFF de Québec</span>
		<span class="centered_dates" style="text-shadow: 1px 1px 2px #000, 0 0 40em #000;">DU 8 AU 11 JUILLET</span>
		<img src="<?php echo $niveau; ?>liaisons/images/img_entete_w940.jpg" alt="">
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
					<article class="articles">
						<header class="titre">
							<h3 class="titre_texte"><b><?php echo $arrActualites[$cpt]["titre"]; ?></b></h3>
						</header>
						<hr class="hr_article">
						<p class="auteurs"><?php echo $arrActualites[$cpt]["auteurs"]; ?></p>
						<p>
							<?php echo $arrActualites[$cpt]["article"];
							if (count(explode(" ", $arrActualites[$cpt]["article"])) >= 45) { ?>
								<a class="a_points" href="#">...</a>
							<?php } ?>
						</p>
						<footer>
							<h4>Par <?php echo $arrActualites[$cpt]["auteurs"]; ?>, le
								<?php echo $arrJour[$arrActualites[$cpt]["jourSemaine"] - 1]; ?>
								<?php echo $arrActualites[$cpt]["jour"] . " " . $arrMois[$arrActualites[$cpt]["mois"]] . " " . $arrActualites[$cpt]["annee"]; ?>
							</h4>
						</footer>
					</article>
				<?php } ?>
			</section>
		</div>

	</main>

	<?php include($niveau . "liaisons/fragments/piedDePage.inc.php"); ?>

</body>

</html>