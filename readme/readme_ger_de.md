**Übersetzung:**
[中文(简体)](readme_chs_cn.md)
 | [English](/README.md)
 | [Français](readme_fre_fr.md)
 | Deutsch
 | [Indonesia](readme_ind_id.md)
 | [Italiano](readme_ita_it.md)
 | [日本語](readme_jpn_jp.md)
 | [Português (Brasil)](reamde_por_br.md)
 | [Русский](readme_rus_ru.md)
 | [Español](readme_spa_xm.md)
 | [Türkçe](readme_tur_tr.md)
 
 # swgoh-comlink-for-github
Dieses Repo wird verwendet, um Daten von SWGoH Comlink zu erhalten und auf GitHub zu speichern, so dass es mit persönlichen Projekten verwendet werden kann, ohne dass Comlink auf einem Server gehostet werden muss. 
 
Um loszulegen, folgen Sie den unten aufgeführten Schritten.

## Schritt 1
Gehen Sie zu GitHub und [Registrieren Sie sich für ein Konto](https://github.com/signup). Nachdem Sie Ihren Benutzernamen und Ihr Passwort erstellt haben, werden Ihnen nach der Verifizierung Ihrer E-Mail mehrere Fragen gestellt. Bei der ersten Frage wird gefragt, wie viele Personen zu Ihrem Team gehören. Wählen Sie nur Sie aus und klicken Sie auf "Weiter". Bei der nächsten Frage können Sie einfach auf "Weiter" klicken. Danach werden Ihnen Planoptionen angezeigt. Wählen Sie den kostenlosen Plan, indem Sie auf "Weiter mit kostenlos" klicken. 
 
## Schritt 2
Sobald Sie angemeldet sind, forken Sie dieses Repository, indem Sie auf die Option Fork in der oberen rechten Ecke des Fensters zwischen den Optionen Watch und Star klicken.

## Schritt 3
Laden Sie [GitHub Desktop] (https://desktop.github.com/) herunter und installieren Sie es auf Ihrem Computer. Öffnen Sie nach der Installation GitHub Desktop und melden Sie sich an.\
Wählen Sie als Nächstes Klonen eines Repositorys und klonen Sie die geforkete Version, die Sie von diesem Repository erstellt haben. Dadurch wird eine Kopie des Repositorys auf Ihrem Computer gespeichert.

## Schritt 4
Laden Sie die neueste Binärdateiversion von [SWGoH Comlink](https://github.com/sw-goh-tools/swgoh-comlink/releases) auf Ihren Computer herunter.

## Schritt 5
Laden Sie die neueste Version von [PHP](https://www.php.net/downloads) für Ihr Betriebssystem herunter, falls Sie sie noch nicht installiert haben.
### Schritte zur Installation von PHP
1. Um zu sehen, ob Sie php installiert haben, können Sie entweder die Befehlszeile mit cmd.exe oder Terminal aufrufen und `php -v` eingeben. Wenn Sie es installiert haben, werden Ihnen die Versionsinformationen angezeigt und Sie können zu 3. übergehen.
2. Wenn Sie php nicht installiert haben, laden Sie die entsprechende Datei von dem obigen Link herunter. Für Windows sollten Sie die aktuellste stabile Version mit dem Hinweis "Thread Safe" herunterladen. Wenn Sie ein 32-Bit-Betriebssystem haben, nehmen Sie die Datei mit "x86 Thread Safe" im Titel, für 64-Bit-Betriebssysteme nehmen Sie die Datei mit "x64 Thread Safe" im Titel.
3. Nachdem Sie die Datei in das gewünschte Verzeichnis installiert/entpackt haben, müssen Sie möglicherweise die Umgebungsvariable setzen, damit sie erkannt wird. Geben Sie in der Suchleiste von Windows Umgebungsvariable ein und wählen Sie die Option Systemumgebungsvariablen bearbeiten. Klicken Sie unten im Fenster, das sich öffnet, auf Umgebungsvariablen. Wählen Sie die Variable `Path` aus und wählen Sie dann Bearbeiten. Fügen Sie ein ; ein, gefolgt vom vollständigen Dateipfad zur php.exe-Datei. Dies gilt sowohl für das obere als auch für das untere Kästchen.
4. Rufen Sie das Befehlszeilentool auf und geben Sie `php -v` ein, um zu überprüfen, ob php korrekt installiert wurde und funktioniert.

## Schritt 6
Öffnen Sie Ihr Kommandozeilentool, indem Sie cmd.exe in das Suchfeld für Windows oder Terminal für Linux/Mac eingeben. Führen Sie Comlink aus, indem Sie den vollständigen Dateipfad gefolgt von `--Name "Comlink für GitHub"` eingeben. Wenn Sie möchten, können Sie für den Namen einen beliebigen Namen verwenden.\
***Beispiel:***\
`C:\Users\Kidori\Desktop\Swgoh Comlink\swgoh-comlink-1.3.0.exe --name "Comlink for GitHub"`

## Schritt 7
Öffnen Sie ein neues Befehlszeilen-Toolfenster. In diesem Fenster führen Sie die php-Scrpts aus, um die Daten in Ihrem Repository zu erstellen/zu aktualisieren. Dies geschieht durch die Eingabe von `php -r "require 'File Path'; function(arguments);"`. Sie müssen den Dateipfad für Ihre geforkte Kopie dieses Repos verwenden und dann die Funktion eingeben, die Sie ausführen möchten.\
**Beispiel**\
`php -r "require 'C:\Users\Kidori\Desktop\GitHub\swgoh-comlink-for-github\run.php'; getPlayerData(596966614);"`

### Verfügbare Funktionen für run.php
#### getPlayerData(allyCode, host, port, username, password)
Ruft das Spielerprofil ab, das zu dem angegebenen Verbündetencode gehört.\
**Parameter:**
```
 allyCode {Zeichenfolge/Ganzzahl} - den Verbündeten-Code des Spielers
 host {Zeichenfolge} | Wahlweise - die Webadresse, unter der SWGOH Comlink läuft. Die Voreinstellung ist http://localhost:
 port {Ganzzahl} | Wahlweise - der Port, auf dem SWGOH Comlink läuft. Standard ist 3000
 username {Zeichenfolge} | Wahlweise - den von SWGOH Comlink benötigten Zugangsschlüssel oder Benutzernamen, falls vorhanden
 password {Zeichenfolge} | Wahlweise - den geheimen Schlüssel oder das Passwort, das für SWGOH Comlink benötigt wird, falls vorhanden
```

#### getGuildData(allyCodes, host, port, username, password)
Ruft mehrere Spielerprofile ab, die zu jedem angegebenen Verbündetencode gehören.\
**Parameter:**
```
 allyCodes {array} - jeder Verbündeten-Code in bis zu zwei Gilden
 host {Zeichenfolge} | Wahlweise - die Webadresse, unter der SWGOH Comlink läuft. Die Voreinstellung ist http://localhost:
 port {Ganzzahl} | Wahlweise - der Port, auf dem SWGOH Comlink läuft. Standard ist 3000
 username {Zeichenfolge} | Wahlweise - den von SWGOH Comlink benötigten Zugangsschlüssel oder Benutzernamen, falls vorhanden
 password {Zeichenfolge} | Wahlweise - den geheimen Schlüssel oder das Passwort, das für SWGOH Comlink benötigt wird, falls vorhanden
 ```

#### getGameData(filtered, allCollections, host, port, username, password)
Ruft Datensammlungen ab, die sich auf verschiedene Aspekte des Spiels beziehen.\
**Parameter:**
```
 filtered {bool} | Wahlweise - gibt nur die nützlichsten Daten zurück, die in jeder Sammlung benötigt werden, um die Dateigröße zu verringern. Der Standardwert ist true.
 allCollections {bool} | Wahlweise - gibt alle möglichen Datensammlungen zurück, die das Spiel verwendet. Der Standardwert ist false.
 host {Zeichenfolge} | Wahlweise - die Webadresse, unter der SWGOH Comlink läuft. Die Voreinstellung ist http://localhost:
 port {Ganzzahl} | Wahlweise - der Port, auf dem SWGOH Comlink läuft. Standard ist 3000
 username {Zeichenfolge} | Wahlweise - den von SWGOH Comlink benötigten Zugangsschlüssel oder Benutzernamen, falls vorhanden
 password {Zeichenfolge} | Wahlweise - den geheimen Schlüssel oder das Passwort, das für SWGOH Comlink benötigt wird, falls vorhanden
```

#### buildTerritoryBattles(lang)
Erstellt die Datei Territoriale Kämpfe.\
**Parameter:**
```
lang {Zeichenfolge} | Wahlweise - die Sprache, die Sie verwenden möchten, da nicht alle Daten übersetzt werden können. Standard ist ENG_US
  Optionen: CHS_CN, CHT_CN, ENG_US, FRE_FR, GER_DE, IND_ID, ITA_IT,
               JPN_JP, KOR_KR, POR_BR, RUS_RU, SPA_XM, THA_TH, TUR_TR
```

## Schritt 8
Nachdem Sie die Funktionen ausgeführt haben, müssen Sie die Änderungen, die Sie an Ihrer geklonten Repo-Version vorgenommen haben, mit GitHub Desktop veröffentlichen. Gehen Sie zu GitHub Desktop und wählen Sie das swgoh-comlink-for-github Repo aus, wenn es nicht bereits ausgewählt ist. Es sollte eine Liste aller Dateien mit ausstehenden Änderungen angezeigt werden. Fügen Sie eine Zusammenfassung für die Änderungen hinzu und klicken Sie dann auf Commit to main. Nun müssen Sie auf Push origin klicken, um die Commits an GitHub zu übertragen.

## Schritt 9
Sie können nun die aktualisierten Informationen in Google Sheets abrufen, indem Sie die Webadresse für die Rohdatei abrufen. Sie erhalten diese Webadresse, indem Sie in einem Internetbrowser auf die json-Datei in Ihrem Repo klicken und die Schaltfläche Raw am oberen Rand des Dateieditorfensters anklicken.\
**Beispiel**\
`https://raw.githubusercontent.com/Kidori78/swgoh-comlink-for-github/data/platoons.json`
