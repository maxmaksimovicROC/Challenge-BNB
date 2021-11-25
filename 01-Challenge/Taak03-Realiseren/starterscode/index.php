<?php
// Je hebt een database nodig om dit bestand te gebruiken....
require "database.php";
if (!isset($conn)) { //deze if-statement checked of er een database-object aanwezig is. Kun je laten staan.
    return;
}

$totaalprijs = 0;
$database_gegevens = null;
$poolIsChecked = false;
$bathIsChecked = false;
$bbqIsChecked = false;
$wifiIsChecked = false;
$fireplaceIsChecked = false;
$dishwasherIsChecked = false;
$bikerentalIsChecked  = false;
$fietsprijs = 0;
$bedprijs = 0;


$sql = "SELECT * FROM `homes`"; //Selecteer alle huisjes uit de database

if (isset($_GET['filter_submit'])) {

    if ($_GET['faciliteiten'] == "ligbad") { // Als ligbad is geselecteerd filter dan de zoekresultaten
        $bathIsChecked = true;
        $sql = "SELECT * FROM `homes` WHERE bath_present = 1"; // query die zoekt of er een BAD aanwezig is.
    }

    if ($_GET['faciliteiten'] == "zwembad") {
        $poolIsChecked = true;
        $sql = "SELECT * FROM `homes` WHERE pool_present = 1"; // query die zoekt of er een ZWEMBAD aanwezig is.
    }

    if ($_GET['faciliteiten'] == "bbq") {
        $bbqIsChecked = true;
        $sql = "SELECT * FROM `homes` WHERE bbq_present = 1"; // query die zoekt of er een BBQ aanwezig is.
    }

    if ($_GET['faciliteiten'] == "wifi") {
        $wifiIsChecked = true;
        $sql = "SELECT * FROM `homes` WHERE wifi_present = 1"; // query die zoekt of er een BBQ aanwezig is.
    }

    if ($_GET['faciliteiten'] == "fireplace") {
        $fireplaceIsChecked = true;
        $sql = "SELECT * FROM `homes` WHERE fireplace_present = 1"; // query die zoekt of er een BBQ aanwezig is.
    }

    if ($_GET['faciliteiten'] == "dishwasher") {
        $dishwasherIsChecked = true;
        $sql = "SELECT * FROM `homes` WHERE dishwasher_present = 1"; // query die zoekt of er een BBQ aanwezig is.
    }

    if ($_GET['faciliteiten'] == "bike_rental") {
        $bikerentalIsChecked = true;
        $sql = "SELECT * FROM `homes` WHERE bike_rental = 1"; // query die zoekt of er een BBQ aanwezig is.
    }
}


if (is_object($conn->query($sql))) { //deze if-statement controleert of een sql-query correct geschreven is en dus data ophaalt uit de DB
    $database_gegevens = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC); //deze code laten staan
}

if (isset($_GET["actie"]))
    if ($_GET['actie'] == "totaal_prijs") {
        $gekozen_huisID = $_GET["gekozen_huis"];
        if ($gekozen_huisID > 0){
            $sql = "SELECT * FROM `homes` WHERE `id` = $gekozen_huisID "; // query die zoekt
            $database_gegevens = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC); //deze code laten staan+ 
            foreach ($database_gegevens as $huisje) : 
                $prijshuis = $huisje["price_p_p_p_n"];
                $prijspersonen = $prijshuis * $_GET["aantal_personen"];
                $prijsdagen = $prijshuis * $_GET["aantal_dagen"];
                $prijsbeddengoed = $huisje["price_bed_sheets"];
                $prijsfietsverhuur = $huisje["price_bike_rental"];
                if ($_GET["beddengoed"] == "ja"){
                    $bedprijs = $prijsbeddengoed;
                }
                if ($_GET["fietsverhuur"] == "ja"){
                    $fietsprijs = $prijsfietsverhuur;
                }
                $totaalprijs = $prijsdagen+$prijspersonen+$fietsprijs+$bedprijs-$prijshuis;
                if ($totaalprijs < 0){
                    $totaalprijs = 0;
                }

            endforeach;
        }
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <link href="css/style1.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Source+Sans+Pro:wght@200&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <h1>Quattro Cottage Rental</h1>
    </header>
    <main>
        <div class="top">
            <div class="top_left">
                <div class="filter-box">
                    <form class="filter-form">
                        <div class="form-control">
                            <a class="reset" id="reset" href="index.php">Reset Filters</a>
                        </div>
                        <div class="form-control"> 
                            <label for="ligbad">Ligbad</label>
                            <input type="checkbox" id="ligbad" name="faciliteiten" value="ligbad" <?php if ($bathIsChecked) echo 'checked' ?>>
                        </div>
                        <div class="form-control">
                            <label for="zwembad">Zwembad</label>
                            <input type="checkbox" id="zwembad" name="faciliteiten" value="zwembad" <?php if ($poolIsChecked) echo 'checked' ?>>
                        </div>
                        <div class="form-control">
                            <label for="bbq">bbq</label>
                            <input type="checkbox" id="bbq" name="faciliteiten" value="bbq" <?php if ($bbqIsChecked) echo 'checked' ?>>
                        </div>
                        <div class="form-control">
                            <label for="wifi">wifi</label>
                            <input type="checkbox" id="wifi" name="faciliteiten" value="wifi" <?php if ($wifiIsChecked) echo 'checked' ?>>
                        </div>
                        <div class="form-control">
                            <label for="fireplace">openhaard</label>
                            <input type="checkbox" id="fireplace" name="faciliteiten" value="fireplace" <?php if ($fireplaceIsChecked) echo 'checked' ?>>
                        </div>
                        <div class="form-control">
                            <label for="dishwasher">vaatwasser</label>
                            <input type="checkbox" id="dishwasher" name="faciliteiten" value="dishwasher" <?php if ($dishwasherIsChecked) echo 'checked' ?>>
                        </div>
                            <div class="form-control">
                            <label for="Fiets verhuur">Fiets verhuur</label>
                            <input type="checkbox" id="bike_rental" name="faciliteiten" value="bike_rental" <?php if ($bikerentalIsChecked) echo 'checked' ?>>
                        </div>
                        <button type="submit" id="submit" name="filter_submit">Filter</button>
                </div>
            </div>
            <div class="top_right">
                <div class="homes-box">
                    <?php if (isset($database_gegevens) && $database_gegevens != null) : ?>
                        <?php foreach ($database_gegevens as $huisje) : ?>
                            <div class="naam-kenmerk">
                            <h4>
                                <?php echo $huisje['name']; ?>
                            </h4>
                            
                            <p>
                                <?php echo $huisje['description'] ?>
                            </p>
                                <div class="kenmerken">
                                    <div>
                                        <h5>Kenmerken</h5>
                                        <ul>
                                            <?php // laat het kenmerk zien onder de informatie van huisjes
                                                if ($huisje['bath_present'] ==  1) {
                                                    echo "<li>Er is een ligbad!</li>";
                                                }

                                                if ($huisje['pool_present'] ==  1) {
                                                    echo "<li>Er is een zwembad!</li>";
                                                }

                                                if ($huisje['bbq_present'] ==  1) {
                                                    echo "<li>Er is een bbq!</li>";
                                                }

                                                if ($huisje['wifi_present'] ==  1) {
                                                    echo "<li>Er is wifi!</li>";
                                                }

                                                if ($huisje['fireplace_present'] ==  1) {
                                                    echo "<li>Er is een open haard!</li>";
                                                }

                                                if ($huisje['dishwasher_present'] ==  1) {
                                                    echo "<li>Er is een vaatwasser!</li>";
                                                }

                                                if ($huisje['bike_rental'] ==  1) {
                                                    echo "<li>Er is een fiets verhuur in de buurt!</li>";
                                                }

                                                if ($huisje['max_capacity'] ==  4) {
                                                    echo "<li>Er kunnen maximaal 4 personen in!</li>";
                                                }

                                                if ($huisje['max_capacity'] ==  6) {
                                                    echo "<li>Er kunnen maximaal 6 personen in!</li>";
                                                }

                                                if ($huisje['max_capacity'] ==  8) {
                                                    echo "<li>Er kunnen maximaal 8 personen in!</li>";
                                                }
                                            ?> 
                                        </ul>
                                    </div>
                                    <div>
                                        <h5>De prijs per persoon per nacht</h5>   
                                        Totale prijs &euro;
                                        <?php 
                                            echo $huisje['price_p_p_p_n'];
                                        ?>
                                    </div>
                                    <div class="prijs">
                                        <h5>De prijs voor beddengoed</h5>
                                        Totale prijs &euro;
                                        <?php 
                                            echo $huisje['price_bed_sheets'];
                                        ?>
                                    </div>
                                    <div class="prijs">
                                        <h5>De prijs voor de fiets verhuur</h5>
                                        Totale prijs &euro;
                                        <?php 
                                            echo $huisje['price_bike_rental'];
                                        ?>
                                    </div class="prijs">
                                </div>
                            </div>
                            <div class="foto">
                                <img src="images/<?php echo $huisje['image'] ?>">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>   
            </div>
        </div>
        <div class="bottom">
            <div class="bottom_left">
                <div id="mapid"></div>
            </div>
            <div class ="bottom_right">
                <div class="book">
                    <h3>Reservering maken</h3>
                        <div class="form-control">
                            <label for="aantal_personen">Vakantiehuis</label>
                            <select name="gekozen_huis" id="gekozen_huis">
                                <option value="1">IJmuiden Cottage</option>
                                <option value="2">Assen Bungalow</option>
                                <option value="3">Espelo Entree</option>
                                <option value="4">Weustenrade Woning</option>
                            </select>
                        </div>
                        <div class="form-control">
                            <label for="aantal_personen">Aantal personen</label>
                            <input type="number" name="aantal_personen" id="aantal_personen" value="1">
                        </div>
                        <div class="form-control">
                            <label for="aantal_dagen">Aantal dagen</label>
                            <input type="number" name="aantal_dagen" id="aantal_dagen" value="1">
                        </div>
                        <div class="form-control">
                            <h5>Beddengoed</h5>
                            <label for="beddengoed_ja">Ja</label>
                            <input type="radio" id="beddengoed_ja" name="beddengoed" value="ja">
                            <label for="beddengoed_nee">Nee</label>
                            <input type="radio" id="beddengoed_nee" name="beddengoed" value="nee" checked>
                        </div>
                        <div class="form-control">
                            <h5>Fiets verhuur</h5>
                            <label for="fietsverhuur_ja">Ja</label>
                            <input type="radio" id="fietsverhuur_ja" name="fietsverhuur" value="ja">
                            <label for="beddengoed_nee">Nee</label>
                            <input type="radio" id="fietsverhuur_nee" name="fietsverhuur" value="nee" checked>
                        </div>
                        <button type="submit" name="actie" value="totaal_prijs"> Bereken prijs</button>
                        <button type="submit" name="actie" value="reserveer"  onclick="reserveer()"> Reserveer huis</button>
                    </form>
                </div> 
                <div class="currentBooking">
                    <div class="bookedHome"></div>
                    <div class="totalPriceBlock">Totale prijs &euro;<span class="totalPrice"><?php echo $totaalprijs; ?></span></div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <div></div>
        <div>copyright Quattro Rentals BV.</div>
        <div></div>
    </footer>
    <script src="js/map_init.js"></script>
    <script>
        // De verschillende markers moeten geplaatst worden. Vul de longitudes en latitudes uit de database hierin
        var coordinates = [
            [52.44902, 4.61001],[52.99864,6.64928],[52.30340,6.36800],[50.89720,5.90979]
        ];

        var bubbleTexts = [
            "Ijmuiden cottage", "Assen bungalo", "Espolo entree", "Weustenrade woning"
        ];
    </script>
    <script src="js/betaal.js"></script>
    <script src="js/place_markers.js"></script>
</body>
</html>