# Cahier des charges - Application pour une coiffeuse indépendante

## 1. Contexte de l’application

Le site comportera une partie destinée au public et une autre pour la coiffeuse elle même.

La partie publique sera une vitrine avec la possibilité de prendre des rendez-vous.


## 2. Personas et scénarios

### Joana - **Administratrice du salon**

🧩 **Scénario 1 - Consultation des rendez-vous :** 
Tous les matins, Joana se rend sur son dashboard afin de consulter son planning pour la journée. Elle y trouve donc les informations essentielles comme les informations du client, l’heure du rendez-vous ainsi que la coupe qu’elle devra réaliser. 

Joana pourra alors contacter les clients si le besoin est la. Elle pourra aussi annuler le(s) rendez-vous en cas d’imprévu. Un message sera alors automatiquement transmis aux personnes concernées pour les prévenir. 


🧩 Scénario 2 - Bilan de fin de mois : 

Joana vient donc de se lancer en tant qu’indépendante et elle aimerait bien avoir un bilan de statistiques afin de savoir si tout fonctionne comme elle le souhaite. 
Elle pourra donc consulter son bilan qui reprendra plusieurs informations utiles pour elle. Elle aura son nombre de rednez-vous, le nombre de client total sur le mois, les revenus qu’elle a réalisé ainsi que la prestation la plus demandée. 

Afin de démarrer sa stratégie marketing au mieux, Joana teste plusieurs approches telles qu’une distribution de flyers, des annonces sur les réseaux sociaux, etc. Avec son bilan mensuel, elle aura également accès au nombre de nouveaux clients sur le mois. Elle pourra alors savoir laquelle de ses approches attire les plus de nouvelles personnes.

🧩 Scénario 3 - Informations sur un client :

Joana vient de recevoir une cliente et elle a découverte que celle-ci était allergique à un de ses produits. Elle va donc ajouter cette information sur le profil de la cliente. 

Le mois prochain, la cliente en question reprend rendez-vous. Joana voit alors, dans son planning, le rendez-vous de cette cliente. Elle pourra alors aller voir l’information personnelle sur la fiche de la cliente afin de préparer au mieux la séance.

Ces notes sont privées et visibles uniquement par Joana dans son interface administratrice.

---

### Sabrine - Nouvellement installé à Orp-Jauche

🧩 **Scénario 1 - Recherche d’un(e) coiffeur(se) :**

Sabrine vient d’emménager dans la commune d’Orp-Jauche et cherche un endroit pour se coiffer. Elle se rend alors sur Internet avec son smartphone et tape les mots-clés : coiffeur, Orp-Jauche.

Elle tombe alors sur le site de Joana et le consulte. Elle trouve l’endroit très professionnel et accueillant. Elle consulte ensuite les différentes propositions de coiffure et leur tarif. Elle trouve le tout très raisonnable. Elle décide alors de prendre rendez-vous. Elle va dans un premier temps choisir la ou les prestations qu'elle désire. À la suite de cela, elle pourra choisir un jour et sélectionner un créneau parmis ceux disponibles. En dernière étape, elle n'aura plus qu'à rentrer ses données personnelles. Elle reçoit ensuite un mail avec le détail de son rendez-vous. 

Joana reçoit alors l’information d’une nouvelle prise de rendez-vous.

🧩 **Scénario 2 - Reprise de rendez-vous :**

Joana vient de finir la coupe de Sabrine et celle-ci demande à prendre rendez-vous pour le mois prochain. Joana accède alors à son interface d’administratrice et ajoute elle-même le nouveau rendez-vous qu’elle convînt avec Sabrine. Elle n'aura qu'à choisir Sabrine parmis les clients et convenir le crénau ainsi que la prestation souhaitée. Sabrine prend également rendez-vous pour son fils Mathieu qui n’est encore jamais venu et donc n’est pas dans la base de données, Joana pourra l’y ajouter avec les informations transmises via Sabrine.

## 3. Fonctionnalités
**Partie publique**
- Présentaion de Joana ;
- Présentaion des prestations proposées ;
- Galerie dynamique ;
- Formulaire de contact
- Prise de rendez-vous (prestations, date & heure, coordonnées).

**Partie administrative**
- Dashboard
  - Liste des rendez-vous de la journée ;
  - Affichage détaillé d'un rendez-vous ;
  - Annulation d'un rendez-vous avec ou sans mail au client.
 
- Agenda
  - Affichage des évènements (rendez-vous et périodes d'indisponibilité) ;
  - Affichage détaillé d'un rendez-vous ;
  - Annulation d'un rendez-vous avec ou sans mail au client ;
  - Ajout d'un rendez-vous avec possibilité d'ajouter un client et une prestation directement ;
  - Gestion des indisponibilités (ajoout, modification et suppression).

 - Grstion des clients
   - Liste des clients ;
   - Champ de recherche ;
   - Ajout d'un client ;
   - Modification d'un client ;
   - Fiche d'un client avec
     - Coordonnées ;
     - Gestion de notes personnelles ;
     - Historique des rendez-vous.

- Statistiques
  - Affichage du mois en cours par défaut ;
  - Filtre sur mois et année ;
  - Total des rendez-vous ;
  - Total des clients ;
  - Total des clients récurrents ;
  - Total des nouveaux clients ;
  - Total des revenus ;
  - Revenu moyen par client ;
  - Prestation la plus demandée.

- Données
  - Gestion des prestations (ajout, modification, suppression) ;
  - Gestion des images de la galerie (ajout, supression, changement d'ordre).

- Congés récurents
  - Ajout ;
  - Modification ;
  - Suppression.

- Profil
  Modification des informations personnelles.

## 4. Installation
```bash
# Mise en place des dépendances
composer install
npm install

# Mise en place du fichier d'environnement
cp .env.example .env
php artisan key:generate

# Mise en place de la base de données
## initialiser la base de donnée avec le fichier 'database.sqlite'
php artisan migrate --seed

# Compilation
npm run dev
