# Configuration de SDG Market avec Docker

SDG Market est prévu pour fonctionner avec Docker. Un fichier docker-compose.json est présent afin de simplifier
la tâche.

La première étape sera de simplement configurer le fichier .env présent dans le dossier how-to-contribute (attention, un autre fichier .env différent est présent dans la racine du projet, ne pas l'utiliser pour Docker).

* MYSQL_DB : représente le login de votre choix pour la base de données mysql.
* MYSQL_USER : représente le compte user de votre choix pour mysql.
* MYSQL_PASSWORD : représente le mot de passe de votre choix pour la base de données mysql.
* GITHUB_PARSER_LOGIN : compte github utilisé pour se connecter à l'API github.
* GITHUB_PARSER_PASSWORD : mot de passe github utilisé pour se connecter à l'API github.
* GITHUB_OAUTH_CLIENT_ID : ID utilisé pour l'authentification des utilisateur de SDG Market via Github.
* GITHUB_OAUTH_CLIENT_SECRET : Secret utilisé conjoitement au Client ID.
* GITHUB_CLIENT_ID : Client ID lié au OAuth Client ID.
* GITHUB_REDIRECT_URI : URL de callback du SDG market (e.g. http://url/callback)
* BASE_URL : URL de base où est installé SDG Market (e.g. http://url)
* BASE_API_URL : URL de l'API ou est installé SDG Market (e.g. http://url/api/v1)
* DB_VOLUME_PATH : chemin local où sera stocké la base de donnée.

Une fois modifié, il suffira d'avoir Docker et Docker composer d'activé, puis de lancer la commande suivante toujours depuis le dossier how-to-contribute :

     docker-composer up