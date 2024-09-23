/**
 * @file Un menu simple, responsive bâti en amélioration progressive.
 * @author @evefevrier <eve.fevrier@cegep-ste-foy.qc.ca>
 * @version 1.2.3
 *
 */

//*******************
// Déclaration d'objet(s)
//*******************

var menu = {
  lblMenuFerme: 'Menu',
  lblMenuOuvert: 'Fermer',
  refBouton: null,
  refLibelle: null,
  refMenu: null,

  configurerNav: function ()
  {
    console.log("allo")
    //********** Création du bouton du menu mobile
    document.body.classList.add('js');
    // On sélectionne le menu dans le HTML
    this.refMenu = document.querySelector(".menu");

    // Création du bouton et du libellé
    this.refBouton = document.createElement("button");
    this.refLibelle = document.createElement("span");

    // On ajoute le libellé dans le bouton
    this.refBouton.appendChild(this.refLibelle);

    // On ajoute les classes au bouton et au libellé
    this.refBouton.className = 'menu__controle';
    this.refLibelle.className = 'menu__libelle';

    // On associe le texte du libellé à l'élément html
    this.refLibelle.innerHTML = this.lblMenuFerme;

    // Ajout du bouton dans l'entête de la page html
    this.refMenu.prepend(this.refBouton);

    // Ajout de l'écouteur d'événement sur le bouton du menu
    this.refBouton.addEventListener('click', function () {
      menu.ouvrirFermerNav();
    });
    //this.refBouton.addEventListener('click', this.ouvrirFermerNav.bind(this));
  },

  ouvrirFermerNav: function ()
  {
    // On bascule la classe de fermeture du menu
    this.refMenu.classList.toggle("menu--ferme");

    // On change le libellé du bouton selon l'état du menu
    if (this.refMenu.classList.contains("menu--ferme"))
    {
      this.refLibelle.innerHTML = this.lblMenuFerme;
    }
    else
    {
      this.refLibelle.innerHTML = this.lblMenuOuvert;
    }
  }
};


//*******************
// Écouteurs d'événements
//*******************

window.addEventListener('load', function () { menu.configurerNav(); });