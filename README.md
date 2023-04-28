Pour tester l'application à ce stade :

//-----------------------------//

INSTALLATION

--> Téléchargez les fichiers sur votre serveur 
--> Renseignez les identifiants de votre BDD dans le fichier "config.php" --> config/config.php 
--> Ouvrez l'application depuis votre serveur 
--> Ajoutez le controller login à l'URL --> + "?controller=login"

--> L'application vérifie si les tables nécessaires sont déjà créées dans votre BDD
   - Si non :
   --> Le formulaire de BDD s'ouvre --> Renseignez vos identifiants de BDD --> L'application créé les tables nécessaires à son fonctionnement (4 tables avec le préfixe "apm_")
   --> Le formulaire de logs Admin s'ouvre --> Créez des logs de connexion --> L'application insert vos logs de connexion dans la table "apm_admin_list"
   --> Connectez vous avec vos logs de connexion --> Vous êtes administrateur principal de l'application

 - Si oui :
   --> Connectez vous avec vos logs de connexion 

//-----------------------------//

UTILISATION

- L'onglet "Menu" vous permet de créer votre premier menu ;
- Naviguez dans la Sidebar pour créer/éditer/organiser/exporter le contenu de votre Menu ;
- L'onglet déroulant "Users" vous permet de gérer les admin secondaires si vous êtes admin principal
