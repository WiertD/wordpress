=== GGKP Excel===
Contributors: GemeneGronden
Tags: excel, import, xlsx, json, excel import
Requires at least: 3.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin waarmee een specifiek Excelbestand van de Stichting Landschapsbeheer Gelderland wordt geimporteerd naar Wordpress, waarna de gegevens uit het werkblad worden gefilterd zodat relevante informatie op in het werkblad aangegeven pagina's wordt gepresenteerd.

De WP Excel CMS plugin van Vincent Schroeder heeft als basis gediend voor deze plugin.

== Installatie ==

1. Navigeer naar 'Nieuwe plugin' in het Plugins dashboard
2. Klik i op 'Plugin uoloaden'
3. Selecteer `ggkp-excel.zip` op je computer
4. Klik 'Nu installeren'
5. Activeer de plugin in het Plugins dashboard
6. Upload het Excelbestand van de klompenpaden (dashboard -> klompenpad werkblad)

== Eigenaardigheden ==

Wanneer het Klompenpad werkblad wordt geopend en vervolgens wordt opgeslagen met LibreOffice kloppen de werkbladnummers niet meer. Alle werkbladnummers worden dan met een opgehoogd. Daarom moet de Constante GGKP_SHEET dan de waarde 2 krijgen (ggkp-excel/ggkp-excel.php regel 35).
Wanneer het bestand met MSExcel wordt opgeslagen zijn de werkbladnummers oplopend vanaf 1 en moet GGKP_SHEET de waarde 1 toegekend krijgen.

== Conventies
Het filter dat het excelblad omzet naar paginainformatie gaat uit van de volgende structuur van het Excelbestand:

Categorie 
  Ondernemers zijn hier globaal aan of uit te zetten voor wat betreft hun presentatie op de website:
    kolom A folderverkoop: Alleen als deze 1 is wordt de ondernemer eventueel getoond onder Verkooppunten brochure
    kolom B koffie web: Alleen als deze 1 is wordt de ondernemer eventueel getoond onder Horeca onderweg

NAW gegevens beginnen in kolom Y
Wordt getoond als Verkooppunten brochure
De eerste rij met NAW gegevens is rij 4
- bedrijf kolom Y
- straat kolom AD
- postcode kolom AE
- woonplaats kolom AF
- website kolom AJ

Folders begint in rij AL
In de derde rij moet het WordPress pageId worden ingevoerd waarop de daaronder aangevinkte ondernemers moeten verschijnen
Aanvinken gebeurt door het plaatsen van een 1 

Relatie begint in rij FB
Wordt getoond als Horeca onderweg
In de derde rij moet het WordPress pageId worden ingevoerd waarop de daaronder aangevinkte ondernemers moeten verschijnen
Aanvinken gebeurt door het plaatsen van een 1

