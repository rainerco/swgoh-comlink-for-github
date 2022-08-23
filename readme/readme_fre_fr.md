**Traduction:**
[中文(简体)](readme_chs_cn.md)
 | [English](/README.md)
 | Français
 | [Deutsch](readme_ger_de.md)
 | [Indonesia](readme_ind_id.md)
 | [Italiano](readme_ita_it.md)
 | [日本語](readme_jpn_jp.md)
 | [Português (Brasil)](reamde_por_br.md)
 | [Русский](readme_rus_ru.md)
 | [Español](readme_spa_xm.md)
 | [Türkçe](readme_tur_tr.md)

# swgoh-comlink-for-github
Ce repo est utilisé pour récupérer les données de SWGoH Comlink et les stocker sur GitHub afin qu'elles puissent être utilisées dans des projets personnels sans avoir à héberger Comlink sur un serveur. 
 
Pour commencer, suivez les étapes énumérées ci-dessous.

## Étape 1
Allez sur GitHub et [Créez un compte](https://github.com/signup). Après avoir créé votre nom d'utilisateur et votre mot de passe, vous devrez répondre à plusieurs questions après avoir vérifié votre adresse électronique. La première question demande combien de personnes font partie de votre équipe, sélectionnez juste vous et cliquez sur continuer. Sur l'écran de la question suivante, vous pouvez simplement cliquer sur continuer. Après cela, il vous donnera des options de plan, choisissez le plan gratuit en cliquant sur continuer avec gratuit.
 
## Étape 2
Une fois que vous êtes connecté, transférez ce dépôt en cliquant sur l'option "Fork" dans le coin supérieur droit de la fenêtre, entre les options "Watch" et "Star".

## Étape 3
Téléchargez et installez [GitHub Desktop] (https://desktop.github.com/) sur votre ordinateur. Après l'installation, ouvrez GitHub Desktop et connectez-vous.\
Choisissez ensuite de Cloner un référentiel et clonez la version bifurquée que vous avez faite de ce référentiel. Cela placera une copie de ce dépôt sur votre ordinateur.

## Étape 4
Téléchargez la dernière version du fichier binaire de [SWGoH Comlink] (https://github.com/swgoh-utils/swgoh-comlink/releases) sur votre ordinateur.

## Étape 5
Téléchargez la dernière version de [PHP](https://www.php.net/downloads) pour votre système d'exploitation si vous ne l'avez pas déjà installée.
### Étapes de l'installation de PHP
1. Pour voir si vous avez installé php, vous pouvez soit aller à la ligne de commande en utilisant cmd.exe ou Terminal et entrer `php -v`. Si vous l'avez installé, il vous montrera les informations de version et vous pourrez passer à la version 3.
2. Si vous n'avez pas installé php, téléchargez le fichier approprié à partir du lien ci-dessus. Pour Windows, vous devez prendre la version stable la plus récente qui dit Thread Safe. Si vous avez un système d'exploitation 32 bits, vous prendrez le fichier avec `x86 Thread Safe` dans le titre, pour les systèmes d'exploitation 64 bits, vous prendrez le fichier avec `x64 Thread Safe` dans le titre.
3. Après avoir installé/dézippé le fichier dans le répertoire souhaité, vous devrez peut-être définir la variable d'environnement pour le reconnaître. Dans la barre de recherche de Windows, tapez Variable d'environnement et sélectionnez l'option qui dit Modifier les variables d'environnement du système. Cliquez sur Variables d'environnement en bas de la fenêtre qui s'ouvre. Vous devez sélectionner la variable `Path` et ensuite choisir Editer. Ajoutez un ; suivi du chemin d'accès complet au fichier php.exe. Faites-le à la fois dans la boîte du haut et dans celle du bas.
4. Allez dans l'outil de ligne de commande et entrez `php -v` pour vérifier que php a été installé correctement et qu'il fonctionne.
5. 
## Étape 6
Ouvrez votre outil de ligne de commande en utilisant cmd.exe dans la boîte de recherche pour windows ou terminal pour linux/mac. Lancez Comlink en entrant le chemin complet du fichier suivi de `--name "Comlink for GitHub"`. Si vous le souhaitez, vous pouvez utiliser n'importe quel nom.\
***Exemple:***\
`C:\Users\Kidori\Desktop\Swgoh Comlink\swgoh-comlink-1.3.0.exe --name "Comlink for GitHub"`

## Étape 7
Ouvrez une nouvelle fenêtre d'outil en ligne de commande. Vous utiliserez cette fenêtre pour exécuter les scripts php pour créer/mettre à jour les données dans votre repo. Pour ce faire, saisissez `php -r "require 'File Path' ; function(arguments) ;"`. Vous devrez utiliser le chemin d'accès au fichier de votre copie bifurquée de ce dépôt, puis saisir la fonction que vous souhaitez exécuter.\
**Exemple**\
`php -r "require 'C:\Users\Kidori\Desktop\GitHub\swgoh-comlink-for-github\run.php'; getPlayerData(596966614);"`

### Fonctions disponibles pour run.php
#### getPlayerData(allyCode, host, port, username, password)
Récupère le profil du joueur appartenant au code d'alliage fourni.\
**Paramètres:**
```
 allyCode {chaîne de caractères/entier} - le code d'alliage du joueur
 host {chaîne de caractères} | En option - l'adresse web sur laquelle le Comlink de SWGOH fonctionne. La valeur par défaut est http://localhost :
 port {entier} | En option - le port sur lequel SWGOH Comlink fonctionne. La valeur par défaut est 3000
 username {chaîne de caractères} | En option - la clé d'accès ou le nom d'utilisateur requis par SWGOH Comlink, le cas échéant
 password {chaîne de caractères} | En option - la clé secrète ou le mot de passe dont a besoin SWGOH Comlink, le cas échéant.
```

#### getGuildData(allyCodes, host, port, username, password)
Retrieves multiple player profiles belonging to each specified ally code provided.\
**Paramètres:**
```
 allyCodes {tableau} - chaque code d'allié dans un maximum de deux guildes
 host {chaîne de caractères} | En option - l'adresse web sur laquelle le Comlink de SWGOH fonctionne. La valeur par défaut est http://localhost :
 port {entier} | En option - le port sur lequel SWGOH Comlink fonctionne. La valeur par défaut est 3000
 username {chaîne de caractères} | En option - la clé d'accès ou le nom d'utilisateur requis par SWGOH Comlink, le cas échéant
 password {chaîne de caractères} | En option - la clé secrète ou le mot de passe dont a besoin SWGOH Comlink, le cas échéant.
 ```

#### getGameData(filtered, allCollections, host, port, username, password)
Récupère les collections de données relatives à différents aspects du jeu.\
**Paramètres:**
```
 filtered {bool} | En option -ne renvoie que les données les plus utiles de chaque collection afin de réduire la taille des fichiers. La valeur par défaut est true.
 allCollections {bool} | En option - renvoie toutes les collections de données possibles que le jeu utilise. La valeur par défaut est false.
 host {chaîne de caractères} | En option - l'adresse web sur laquelle le Comlink de SWGOH fonctionne. La valeur par défaut est http://localhost :
 port {entier} | En option - le port sur lequel SWGOH Comlink fonctionne. La valeur par défaut est 3000
 username {chaîne de caractères} | En option - la clé d'accès ou le nom d'utilisateur requis par SWGOH Comlink, le cas échéant
 password {chaîne de caractères} | En option - la clé secrète ou le mot de passe dont a besoin SWGOH Comlink, le cas échéant.
```

#### buildTerritoryBattles(lang)
Crée le fichier des batailles de territoire.\
**Paramètres:**
```
lang {chaîne de caractères} | En option - la langue que vous souhaitez utiliser, toutes les données ne peuvent pas être traduites. La valeur par défaut est ENG_US
  OPTIONS: CHS_CN, CHT_CN, ENG_US, FRE_FR, GER_DE, IND_ID, ITA_IT,
               JPN_JP, KOR_KR, POR_BR, RUS_RU, SPA_XM, THA_TH, TUR_TR
```

## Étape 8
Après avoir exécuté les fonctions, vous devrez publier les modifications apportées à la version de votre repo cloné en utilisant GitHub Desktop. Allez sur GitHub Desktop et sélectionnez le repo swgoh-comlink-for-github s'il n'est pas déjà sélectionné. Une liste de tous les fichiers avec des modifications en attente devrait s'afficher. Ajoutez un résumé des modifications, puis cliquez sur Commit to main. Vous devez maintenant cliquer sur Push origin pour pousser les commits vers GitHub.

## Étape 9
Vous pouvez maintenant récupérer les informations mises à jour dans Google Sheets en récupérant l'adresse web du fichier brut. Vous pouvez obtenir cette adresse web en cliquant sur le fichier json dans votre repo dans un navigateur internet et en cliquant sur le bouton Raw en haut de la fenêtre de l'éditeur de fichier.\
**Exemple**\
`https://raw.githubusercontent.com/Kidori78/swgoh-comlink-for-github/data/platoons.json`
