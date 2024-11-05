<?php $niveau = "./"; ?>
<?php include($niveau . "liaisons/php/config.inc.php"); ?>

<?php

$lieux = [
	[
		'image' => 'liaisons/images/lieux/lieux_meduse4.png',
		'nom' => 'Méduse',
		'adresse' => '591, rue de Saint-Vallier Est',
		'adresse2' => 'Québec'
	],
	[
		'image' => 'liaisons/images/lieux/lieux_sacrilege2.jpg',
		'nom' => 'Le Sacrilège',
		'adresse' => '447, rue Saint-Jean',
		'adresse2' => 'Québec'
	],
	[
		'image' => 'liaisons/images/lieux/lieux_sceneDesjardins.jpg',
		'nom' => 'Scène Desjardins',
		'adresse' => '480 rue Saint-Jean',
		'adresse2' => 'Québec'
	],
	[
		'image' => 'liaisons/images/lieux/lieux_foubar_interieur.jpg',
		'nom' => 'Le Fou-Bar',
		'adresse' => '525, Rue St-Jean',
		'adresse2' => 'Québec'
	],
	[
		'image' => 'liaisons/images/lieux/lieux_Ninkasi2.jpg',
		'nom' => 'La Ninkasi du Faubourg',
		'adresse' => '811, Rue Saint-Jean',
		'adresse2' => 'Québec'
	]
];

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

$nbArticles = rand(3, 5);
$arrArticlesChoisis = [];
for ($intCptPart = 0; $intCptPart < $nbArticles; $intCptPart++) {
	$artisteChoisi = rand(0, count($arrActualites) - 1);
	array_push($arrArticlesChoisis, $arrActualites[$artisteChoisi]);
	array_splice($arrActualites, $artisteChoisi, 1);

}
$pdosResultatActualites->closeCursor();

$strRequeteArtiste = 'SELECT id, nom FROM artistes';
$pdosResultatArtistesSug = $objPdo->query($strRequeteArtiste);
$pdosResultatArtistesSug->execute();

$arrArtistesSug = array();
for ($intCptEnr = 0; $ligne = $pdosResultatArtistesSug->fetch(); $intCptEnr++) {
	$arrArtistesSug[$intCptEnr]['id'] = $ligne['id'];
	$arrArtistesSug[$intCptEnr]['nom'] = $ligne['nom'];
}

$nbArtistesSug = rand(3, 5);
//Établie une liste de choix
$arrArtistesChoisi = []; //ou $arrParticipantsChoisi = array();
//Tant que le nombre de suggestions n'est pas atteintsx
for ($intCptPart = 0; $intCptPart < $nbArtistesSug; $intCptPart++) {
	//Trouve un index au hazard selon le nombre de sugestions
	$artisteChoisi = rand(0, count($arrArtistesSug) - 1);
	//Prendre la suggestion et la mettre dans les participants choisis
	array_push($arrArtistesChoisi, $arrArtistesSug[$artisteChoisi]);
	//Enlever la suggestion des suggestions disponibles (évite les suggestions en doublons)
	array_splice($arrArtistesSug, $artisteChoisi, 1);

	$pdosResultatArtistesSug->closecursor();

}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="keyword" content="">
	<meta name="author" content="">
	<meta charset="utf-8">
	<link rel="icon" type="image/x-icon" href="<?php echo $niveau; ?>liaisons/images/logo.svg">
	<title>Un beau titre ici!</title>
	<?php include($niveau . "liaisons/fragments/headlinks.inc.php"); ?>
</head>



<body>
	<?php include($niveau . "liaisons/fragments/entete.inc.php"); ?>
	<main class="accueil">
		<span class="centered" style="text-shadow: 1px 1px 2px #000, 0 0 40em #000;">Festival OFF de Québec</span>
		<span class="centered_dates" style="text-shadow: 1px 1px 2px #000, 0 0 40em #000;">DU 8 AU 11 JUILLET</span>
		<img class="img__entete" src="<?php echo $niveau; ?>liaisons/images/img_entete_w470px.jpg" alt="">
		<nav class="nav_sec">
			<ul class="nav-sec__liste">
				<li class="nav-sec__listeItem"><a href="<?php echo $niveau; ?>#" class="nav-sec__lien">Lieux</a></li>
				<li class="nav-sec__listeItem"><a href="<?php echo $niveau; ?>#" class="nav-sec__lien">Tarifs</a></li>
				<li class="nav-sec__listeItem"><a href="<?php echo $niveau; ?>#" class="nav-sec__lien">Contact</a></li>
			</ul>
		</nav>
		<hr class="separator">
		<h1 class="accueil__titre">Actualités brèves</h1>
		<section class="conteneur_actu">
			<?php for ($cpt = 0; $cpt < count($arrArticlesChoisis); $cpt++) { ?>
				<article class="articles">
					<header class="titre">
						<h3 class="titre_texte"><b><?php echo $arrArticlesChoisis[$cpt]["titre"]; ?></b></h3>
						<hr class="hr__actu">
						<br>
						<p class="auteurs"><?php echo $arrArticlesChoisis[$cpt]["auteurs"]; ?></p>
						<br>
						<p class="articles_texte">
							<?php echo $arrArticlesChoisis[$cpt]["article"];
							if (count(explode(" ", $arrArticlesChoisis[$cpt]["article"])) >= 45) { ?>
								<a class="a_points" href="#">...</a>
							<?php } ?>
						</p>
					</header>
					<footer class="articles__footer">
						<h4 class="date__article__footer">Le
							<?php echo $arrJour[$arrArticlesChoisis[$cpt]["jourSemaine"] - 1]; ?>
							<?php echo $arrArticlesChoisis[$cpt]["jour"] . " " . $arrMois[$arrArticlesChoisis[$cpt]["mois"]] . " " . $arrArticlesChoisis[$cpt]["annee"]; ?>
						</h4>
					</footer>
				</article>
			<?php } ?>
		</section>

		<h1 class="artistes__titre">Artistes à découvrir</h1>
		<section class="artistes__sect">
			<ul class="img_artistes__ul">
				<?php
				for ($intCpt = 0; $intCpt < count($arrArtistesChoisi); $intCpt++) { ?>
					<li class="img_artistes__li">
						<a
							href="<?php echo $niveau ?>artistes/fiches/index.php?id_artiste=<?php echo $arrArtistesChoisi[$intCpt]["id"]; ?>">
							<picture class="picture__artistes">
								<source
									srcset="<?php echo $niveau ?>liaisons/images/artistes/portrait/<?php echo $arrArtistesChoisi[$intCpt]["id"]; ?>_1_portrait__w318.jpg"
									media="(max-width:600px)">
								<source
									srcset="<?php echo $niveau ?>liaisons/images/artistes/portrait/<?php echo $arrArtistesChoisi[$intCpt]["id"]; ?>_1_portrait__w482.jpg"
									media="(min-width:601px)">
								<img class="picture__img"
									src="<?php echo $niveau ?>liaisons/images/artistes/portrait/<?php echo $arrArtistesChoisi[$intCpt]["id"]; ?>_1_portrait__w482.jpg"
									alt="<?php echo $arrArtistesChoisi[$intCpt]["nom"]; ?>">
							</picture>
							<section class="noms__artistes">
								<div class="artistes__noms">
									<p class="noms__artiste"><?php echo $arrArtistesChoisi[$intCpt]["nom"]; ?></p>
								</div>
							</section>
							<a>
					</li>
				<?php } ?>
			</ul>
		</section>


		<h1 class="tarifs__titre">Tarifs</h1>
		<section class="tarifs__sect">
			<div class="liste__tarifs-items">
				<h2 class="items_tarifs">Passeport</h2>
				<li class="items_tarifs-conditions">Toute la durée du festival</li>
				<div class="tarifs__prix">
					<p class="tarifs_items-prix">10$</p>
				</div>
			</div>
			<div class="liste__tarifs-items">
				<h2 class="items_tarifs">À la porte</h2>
				<li class="items_tarifs-conditions">Disponibles les soirs</li>
				<li class="items_tarifs-conditions">Spectacles à Méduse</li>
				<div class="tarifs__prix">
					<p class="tarifs_items-prix">5$</p>
				</div>
			</div>
			<div class="liste__tarifs-items">
				<h2 class="items_tarifs">Spectacles extérieurs</h2>
				<div class="tarifs__prix">
					<p class="tarifs_items-prix">Gratuit</p>
				</div>
			</div>
			<div class="liste__tarifs-items">
				<h2 class="items_tarifs">Spectacles</h2>
				<li class="items_tarifs-conditions">Parvis de l’Église Saint-Jean-Baptiste</li>
				<li class="items_tarifs-conditions">Bar le Sacrilège</li>
				<li class="items_tarifs-conditions">Bar Fou-Bar</li>
				<div class="tarifs__prix">
					<p class="tarifs_items-prix">Gratuit</p>
				</div>
			</div>
		</section>
		<div class="infos__tarifs">
			<div class="infos__textes__tarifs">
				<p class="textes__tarifs">*Procurez-vous un passeport en ligne à <a class="lien__tarif"
						href="#">lepointdevente.com</a> et
					profitez d’offres spéciales!</p>
				<p class="textes__tarifs">*Les passeports sont aussi disponibles en prévente chez nos partenaires.</p>
			</div>
			<div class="lieux__billets__tarifs">
				<div class="icone__lieux__tarifs">
					<svg class="icone__lieux" width="26" height="26" xmlns="http://www.w3.org/2000/svg"
						shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
						image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
						viewBox="0 0 384 511.49">
						<path fill="#fff"
							d="M285.17 442.38c-23.26 25.51-50.35 48.35-80.78 66.86-3.76 2.75-8.84 3.09-13.01.42-44.94-28.6-82.7-62.95-112.39-100.3C38.06 358 12.3 301.06 3.46 246.2c-9-55.61-.58-109.13 27.1-152.65C41.48 76.38 55.4 60.7 72.41 47.19 111.43 16.1 156.03-.33 200.47.01c42.77.33 85.04 16.25 121.56 49.6 12.83 11.67 23.59 25.01 32.43 39.52 29.76 49.02 36.18 111.55 23.09 174.91-12.92 62.62-44.94 126.23-92.38 178.25v.09zm-93.2-343.5c52.77 0 95.54 42.77 95.54 95.55 0 52.77-42.77 95.54-95.54 95.54-52.78 0-95.55-42.77-95.55-95.54 0-52.78 42.77-95.55 95.55-95.55z" />
					</svg>
					<br>
					<span>Érico<br>634 Rue Saint-Jean, Québec</span>
				</div>
				<div class="icone__lieux__tarifs">
					<svg class="icone__lieux" width="26" height="26" xmlns="http://www.w3.org/2000/svg"
						shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
						image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
						viewBox="0 0 384 511.49">
						<path fill="#fff"
							d="M285.17 442.38c-23.26 25.51-50.35 48.35-80.78 66.86-3.76 2.75-8.84 3.09-13.01.42-44.94-28.6-82.7-62.95-112.39-100.3C38.06 358 12.3 301.06 3.46 246.2c-9-55.61-.58-109.13 27.1-152.65C41.48 76.38 55.4 60.7 72.41 47.19 111.43 16.1 156.03-.33 200.47.01c42.77.33 85.04 16.25 121.56 49.6 12.83 11.67 23.59 25.01 32.43 39.52 29.76 49.02 36.18 111.55 23.09 174.91-12.92 62.62-44.94 126.23-92.38 178.25v.09zm-93.2-343.5c52.77 0 95.54 42.77 95.54 95.55 0 52.77-42.77 95.54-95.54 95.54-52.78 0-95.55-42.77-95.55-95.54 0-52.78 42.77-95.55 95.55-95.55z" />
					</svg>
					<br>
					<span>Le Sacrilège<br>447 Rue Saint-Jean, Québec</span>
				</div>
				<div class="icone__lieux__tarifs">
					<svg class="icone__lieux" width="26" height="26" xmlns="http://www.w3.org/2000/svg"
						shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
						image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
						viewBox="0 0 384 511.49">
						<path fill="#fff"
							d="M285.17 442.38c-23.26 25.51-50.35 48.35-80.78 66.86-3.76 2.75-8.84 3.09-13.01.42-44.94-28.6-82.7-62.95-112.39-100.3C38.06 358 12.3 301.06 3.46 246.2c-9-55.61-.58-109.13 27.1-152.65C41.48 76.38 55.4 60.7 72.41 47.19 111.43 16.1 156.03-.33 200.47.01c42.77.33 85.04 16.25 121.56 49.6 12.83 11.67 23.59 25.01 32.43 39.52 29.76 49.02 36.18 111.55 23.09 174.91-12.92 62.62-44.94 126.23-92.38 178.25v.09zm-93.2-343.5c52.77 0 95.54 42.77 95.54 95.55 0 52.77-42.77 95.54-95.54 95.54-52.78 0-95.55-42.77-95.55-95.54 0-52.78 42.77-95.55 95.55-95.55z" />
					</svg>
					<br>
					<span>Le Bonnet d'âne<br>298 Rue Saint-Jean, Québec</span>
				</div>
				<div class="icone__lieux__tarifs">
					<svg class="icone__lieux" width="26" height="26" xmlns="http://www.w3.org/2000/svg"
						shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
						image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
						viewBox="0 0 384 511.49">
						<path fill="#fff"
							d="M285.17 442.38c-23.26 25.51-50.35 48.35-80.78 66.86-3.76 2.75-8.84 3.09-13.01.42-44.94-28.6-82.7-62.95-112.39-100.3C38.06 358 12.3 301.06 3.46 246.2c-9-55.61-.58-109.13 27.1-152.65C41.48 76.38 55.4 60.7 72.41 47.19 111.43 16.1 156.03-.33 200.47.01c42.77.33 85.04 16.25 121.56 49.6 12.83 11.67 23.59 25.01 32.43 39.52 29.76 49.02 36.18 111.55 23.09 174.91-12.92 62.62-44.94 126.23-92.38 178.25v.09zm-93.2-343.5c52.77 0 95.54 42.77 95.54 95.55 0 52.77-42.77 95.54-95.54 95.54-52.78 0-95.55-42.77-95.55-95.54 0-52.78 42.77-95.55 95.55-95.55z" />
					</svg>
					<br>
					<span>Le Sacrilège<br>Disquaire CD Mélomane<br>248 rue Saint-Jean, Québec</span>
				</div>
				<div class="icone__lieux__tarifs">
					<svg class="icone__lieux" width="26" height="26" xmlns="http://www.w3.org/2000/svg"
						shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
						image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
						viewBox="0 0 384 511.49">
						<path fill="#fff"
							d="M285.17 442.38c-23.26 25.51-50.35 48.35-80.78 66.86-3.76 2.75-8.84 3.09-13.01.42-44.94-28.6-82.7-62.95-112.39-100.3C38.06 358 12.3 301.06 3.46 246.2c-9-55.61-.58-109.13 27.1-152.65C41.48 76.38 55.4 60.7 72.41 47.19 111.43 16.1 156.03-.33 200.47.01c42.77.33 85.04 16.25 121.56 49.6 12.83 11.67 23.59 25.01 32.43 39.52 29.76 49.02 36.18 111.55 23.09 174.91-12.92 62.62-44.94 126.23-92.38 178.25v.09zm-93.2-343.5c52.77 0 95.54 42.77 95.54 95.55 0 52.77-42.77 95.54-95.54 95.54-52.78 0-95.55-42.77-95.55-95.54 0-52.78 42.77-95.55 95.55-95.55z" />
					</svg>
					<br>
					<span>Le Knock-Out<br>832 St-Joseph Est, Québec</span>
				</div>
			</div>
		</div>

		<h1 class="lieux__titre">Lieux de spectacles</h1>

		<div class="lieux-container">
			<?php foreach ($lieux as $lieu): ?>
				<div class="lieu">
					<img class="img__lieu" src="<?php echo ($lieu['image']); ?>" alt="<?php echo ($lieu['nom']); ?>">
					<div class="lieu-details">
						<div class="lieu-nom"><?php echo ($lieu['nom']); ?></div>
						<div class="lieu-adresse"><svg class="icone__adresse" width="14" height="14"
								xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision"
								text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd"
								clip-rule="evenodd" viewBox="0 0 384 511.49">
								<path fill="#fff"
									d="M285.17 442.38c-23.26 25.51-50.35 48.35-80.78 66.86-3.76 2.75-8.84 3.09-13.01.42-44.94-28.6-82.7-62.95-112.39-100.3C38.06 358 12.3 301.06 3.46 246.2c-9-55.61-.58-109.13 27.1-152.65C41.48 76.38 55.4 60.7 72.41 47.19 111.43 16.1 156.03-.33 200.47.01c42.77.33 85.04 16.25 121.56 49.6 12.83 11.67 23.59 25.01 32.43 39.52 29.76 49.02 36.18 111.55 23.09 174.91-12.92 62.62-44.94 126.23-92.38 178.25v.09zm-93.2-343.5c52.77 0 95.54 42.77 95.54 95.55 0 52.77-42.77 95.54-95.54 95.54-52.78 0-95.55-42.77-95.55-95.54 0-52.78 42.77-95.55 95.55-95.55z" />
							</svg><?php echo ($lieu['adresse']); ?><br><?php echo ($lieu['adresse2']); ?></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</main>

	<?php include($niveau . "liaisons/fragments/piedDePage.inc.php"); ?>

</body>

</html>