<!DOCTYPE HTML>
<html lang="pl">

    <head>
        <meta charset="utf-8" />
        <title>Wypożyczalnia</title>
        <meta name="description" content="Witam cie na mojej stronie wypozyczalnia.pl. Wybierz Sobie interesuajcy cie film i ogladnij Go już Dziś !" />
        <meta name=keywords content= "filmy,online,wypożyczalnia" />


        <link href="https://fonts.googleapis.com/css?family=Roboto:400,900i&amp;subset=latin-ext" rel="stylesheet">

        <link rel="stylesheet" href="style.css" type="text/css" />

    </head>
    <body>


        <div id="container">

            <div id="logo">
                www.Wypożycz-film.pl

            </div>        


            <div id="menu">


                <div class="option"> 
                    <a href="Cennik.html" class="tilelink" target="_blank" title="Cennik"> Cennik </a>
                </div> 
                <div class="option"> 
                    <a href="login.php" class="tilelink" target="_blank" title="Zaloguj"> Zaloguj </a>
                </div> 
                <div class="option"> 
                    <a href="rejestracja.php" class="tilelink" target="_blank" title="Dołącz do nas "> Dołącz do nas </a>
                </div>  
                <div class="option"> 
                    <a href="oferta.php" class="tilelink" target="_blank" title="Oferta"> Oferta</a>
                </div>   
                <div class="option">  
                    <a href="kontakt.html" class="tilelink" target="_blank" title="Kontakt "> Kontakt </a>
                </div> 
                <div class="option">  
                    <a href="pomoc.php" class="tilelink" target="_blank" title="Pomoc"> Pomoc </a>
                </div>
                <div class="option">  
                    <a href="wyloguj.php" class="tilelink" target="_blank" title="Pomoc"> Wyloguj </a>
                </div>

                <div style="clear:both;"> </div>
            </div>




            <div id="sidebar">
                <div class="optionL">
                    <a href="glowna.html" class="tilelink" target="_blank" title="Strona Główna"> Strona główna </a>
                </div> 
                <div class="optionL">Top Miesiąca</div> 
                <div class="optionL"><a href="admin.php">Panel administratora</a></div> 
                <div class="optionL"> Zapowiedzi </div> 
                <div class="optionL"><a href="moje_rezerwacje.php">Moje Rezerwacje</a></div>  
            </div>



            <div id="content">   

                <?php

                function drukuj_form() {
                    //w tym pliku funkcja drukuj_form() jest bardziej skomplikowana
                    //wiec sugeruje zwrocic na nia uwage
                    //na poczatku jest wyswietlany naglowek formularza
                    //oraz poczatek listy rozwijanej
                    ?>
                    <div id="panel">

                        <form method="post">

                            Wybór filmu:<br/>
                            <select name="wybor" required>
                                <?php
                                //nastepnie do listy zostana wstawione dane dynamicznie pobrane z bazy
                                //przy czym zostana one rowniez podzielone wg rodzaju
                                //oraz posortowane alfabetycznie
                                
                                //wiec najpierw trzeba polaczyc sie z baza
                                $conn = new mysqli("localhost", "root", "", "wyporzyczalnia");
                                $conn->set_charset("utf8");
                                if ($conn->connect_error) {
                                    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
                                }
                                
                                //nastepnie wybierz wszystkie rodzaje filmow z tabeli filmy
                                //posortowane alfabetycznie
                                //distinct zapewnia usuniecie duplikowanych wartosci
                                $sql = "select distinct rodzaj from filmy order by rodzaj";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    //przetwarzanie zbioru wynikowego rekord po rekordzie
                                    while ($row = $result->fetch_assoc()) {
                                        //tworzenie grupy w liscie rozwijanej o nazwie rodzaju filmu
                                        echo '<optgroup label="' . $row["rodzaj"] . '">';
                                        //nastepnie tworzone jest zapytanie ktore szuka filmy tylko tego rodzaju, ktory obecnie jest wyswietlany
                                        //filmy posortowane sa alfabetycznie
                                        $sql1 = "select id_filmu, tytul from filmy where rodzaj = '" . $row["rodzaj"] . "' order by tytul";
                                        $result1 = $conn->query($sql1);
                                        //przetwarzanie zbioru wynikowego (czyli znalezionych filmow) rekord po rekordzie
                                        while ($row1 = $result1->fetch_assoc()) {
                                            //wyswietlenie pojedynczego elementu listy rozwijanej zawierajacego nazwe (tytul)
                                            //jako wartosc z formularza zostanie przeslany id filmu
                                            echo '<option value = "' . $row1["id_filmu"] . '">' . $row1["tytul"] . '</option>';
                                        }
                                        //koniec grupy filmow o takim samym gatunku
                                        echo '</optgroup>';
                                    }
                                }
                                $conn->close();
                                //pozostale pola formularza wyswietlane sa bez zmian
                                ?>
                            </select>
                            <br/>
                            Data od:<br/>
                            <input type="date" name="od" required><br/>
                            Data do:<br/>
                            <input type="date" name="do" required><br/>
                            <input type="submit" name="zaloguj">
                        </form>

                    </div>  
                    <?php
                }

                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION["user"])) {
                    //gdy zostal zatwierdzony formularz
                    if (isset($_POST["zaloguj"])) {
                        $conn = new mysqli("localhost", "root", "", "wyporzyczalnia");

                        if ($conn->connect_error) {
                            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
                        }

                        //sprawdzenie danych
                        if (!isset($_POST["wybor"]) || !isset($_POST["od"]) || !isset($_POST["do"])) {
                            echo '<h2>Nie wszystkie pola formularza zostały wypełnione!</h2>';
                            echo '<div class="dottedline"></div>';
                            drukuj_form();
                        } else {
                            //nalezy sprawdzic czy zostaly wybrane poprawne daty
                            //do porownania dat nalezy skorzystac z typu DateTime (tylko dla PHP > 5.2)
                            $dataOd = new DateTime($_POST["od"]);
                            $dataDo = new DateTime($_POST["do"]);
                            //data dzisiejsza, format w PHP jest inny niz w MySQL, ponizszy format reprezentuje date w postaci RRRR-MM-DD
                            $dzisiaj = new DateTime(date("Y-m-d"));
                            //sprawdzenie czy data zwrotu nie jest wczesniej niz dzisiaj
                            if ($dataDo < $dzisiaj) {
                                echo '<h2>Data zwrotu nie może być wcześniej niż dzisiaj! Spróbuj jeszcze raz</h2>';
                                echo '<div class="dottedline"></div>';
                                drukuj_form();
                            } else {
                                //sprawdzenie czy data wypozyczenia nie jest wczesniej niz dzisiaj
                                if ($dataOd < $dzisiaj) {
                                    echo '<h2>Data wypożyczenia nie może być wcześniej niż dzisiaj! Spróbuj jeszcze raz</h2>';
                                    echo '<div class="dottedline"></div>';
                                    drukuj_form();
                                } else {
                                    //sprawdzenie czy data zwrotu nie jest wczesniej niz data wypozyczenia
                                    if ($dataDo < $dataOd) {
                                        echo '<h2>Data zwrotu nie może być wcześniej niż data wypożyczenia! Spróbuj jeszcze raz</h2>';
                                        echo '<div class="dottedline"></div>';
                                        drukuj_form();
                                    } else {
                                        //pobranie id klienta na podstawie loginu, ktory mozna pobrac z sesji
                                        $sql = "select id_klienta from klienci where login = '" . $_SESSION["user"] . "'";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $id = $row["id_klienta"];
                                            }
                                            //wstawianie nowego rekordu do bazy
                                            //pierwszy atrybut to id_wypozyczenia
                                            //przy tworzeniu tabeli został nadany mu atrybut AUTO_INCREMENT
                                            //wiec przy dodawaniu nowego rekondu nie nalezy przejmowac sie tym jakie id dodac
                                            $sql1 = "insert into wypozyczenia "
                                                    . "values(NULL, '"
                                                    . $_POST["od"] . "', '"
                                                    . $_POST["do"] . "', "
                                                    . $id . ", "
                                                    . $_POST["wybor"] . ")";
                                            if ($conn->query($sql1) === TRUE) {
                                                //gdy wstawianie rekordu zakonczylo sie sukcesem
                                                echo "<h2>Zamówienie zostało złożone. Proszę sprawdzić na stronie Moje rezerwacje. </h2>";
                                                echo '<div class="dottedline"></div>';
                                            } else {
                                                echo "Error: " . $sql1 . "<br>" . $conn->error;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $conn->close();
                    } else {
                        ?>
                        <span class="bigtitle">Zamówienie</span>
                        <div class="dottedline"></div>
                        <?php
                        //gdy formularz nie zostal zatwierdzony to wyswietl go ponownie
                        drukuj_form();
                    }
                } else {
                    ?>
                    <span class="bigtitle">Musisz zalogować się przed składaniem zamówienia! </span>
                    <div class="dottedline"></div>
                    <?php
                }
                ?>






            </div>
            <div id="apletpr">  
            </div>              
            <div id="footer">
                Zapoznaj sie z oferta naszego serwisu i baw sie dobrze.Strona w sieci od 2017. Wszelkie prawa zastrzeżone &copy;
            </div>

        </div>
    </body>
</html>