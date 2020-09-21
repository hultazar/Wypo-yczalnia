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
                    <a href="pomoc.php" class="tilelink" target="_blank" title="Zmiana hasła"> Zmiana hasła </a>
                </div>
                <div class="option">  
                    <a href="wyloguj.php" class="tilelink" target="_blank" title="Wyloguj"> Wyloguj </a>
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
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION["user"])) {
                    //jesi uzytkownik jest zalogowany to wyswietl dane o zalogowanym uzytkowniku
                    echo '<h2>Jesteś zalogowany jako ' . $_SESSION["user"] . ' </h2>';
                    echo '<div class="dottedline"></div>';
                    //tworzenie polaczenia z baza danych (mysqli udostepnia wygodny interfejs bazy danych w PHP
                    $conn = new mysqli("localhost", "root", "", "wyporzyczalnia");
                    $conn->set_charset("utf8");

                    if ($conn->connect_error) {
                        //gdy polaczenie nie powiodlo sie
                        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
                    }
                    //kolejne zapytanie wybierajace dane o wszystkich zamowieniach dokonanych przez zalogowanego uzytkownika
                    //natural join pozwala zlaczac tabele tak samo jak inner join tylko bez koniecznosci wymieniania kluczy, na podstawie ktorych nastepuje zlaczenie
                    //natural join mozna stosowac tylko wtedy gdy nazwy kluczy podstawowych oraz kluczy obcych po obu stronach sa identyczne
                    $sql = "select id_wypozyczenia, "
                            . "data_startu_wypoz, "
                            . "data_konca_wyporzyczenia, "
                            . "tytul, "
                            . "rok_wydania, "
                            . "rodzaj "
                            . "from wypozyczenia "
                            . "natural join filmy "
                            . "natural join klienci "
                            . "where login = '" . $_SESSION["user"] . "'";
                    $result1 = $conn->query($sql);
                    if ($result1->num_rows > 0) {
                        //tworzona jest tabela
                        //echo umozliwia wyswietlenie dowolnego tekstu (w tym znacznikow html)
                        //w tym samym miejscu jest definiowany naglowek tabeli
                        echo '<table id="tab">'
                        . '<tr><th>ID wypożyczenia</th><th>Od(dni)</th><th>Do</th><th>Tytuł</th><th>Rok wydania</th><th>Rodzaj</th><th>Usunięcie zamówienia</th></tr><tbody>';

                        while ($row = $result1->fetch_assoc()) {
                            //dodawanie poszczegolnych wierszy do tabeli na podstawie wynikow otrzymanych z bazy
                            echo '<tr>'
                            . '<td>' . $row["id_wypozyczenia"] . '</td>'
                            . '<td>' . $row["data_startu_wypoz"] . '</td>'
                            . '<td>' . $row["data_konca_wyporzyczenia"] . '</td>'
                            . '<td>' . $row["tytul"] . '</td>'
                            . '<td>' . $row["rok_wydania"] . '</td>'
                            . '<td>' . $row["rodzaj"] . '</td>'
                            . '<td><form method="post"><button type="submit" value="' . $row["id_wypozyczenia"] . '" name="usun" ><strong>Usuń wypożyczenie</strong></button></form></td>'
                            . '</tr>';
                            //ostatnia komorka tabeli zawiera formularz, ktory zawiera tylko jeden przycisk umozliwiajacy uzuniecie zamowienia.
                            //jego wartoscia (atrybut value) jest ID rezerwacji dzieki czemu jest mozliwe usuniecie zamowienia
                        }
                        //koniec tabeli
                        echo '</tbody></table>';
                    } else {
                        //gdy zapytanie nie zwrocilo wynikow
                        echo "<h3>Na razie nie ma zamówień</h3>";
                    }
                    if (isset($_POST["usun"])) {
                        //zapytanie umozliwiajace usuniecie z bazy
                        $sql = "delete from wypozyczenia where id_wypozyczenia = " . $_POST["usun"];
                        if ($conn->query($sql) === TRUE) {
                            //jesli dane zostaly usuniete
                            //to odswiez strone
                            header("Refresh:0");
                        }
                    }
                    //naleza zawsze pamietac o zamykaniu polaczenia
                    $conn->close();
                } else {
                    ?>
                    <span class="bigtitle"> Błąd dostępu </span>
                    <div class="dottedline"></div>
                    <div id="panel">
                        Musisz zalogować się przez żeby przeglądać swoje rezerwacje!
                    </div>
                    <?php
                }
                ?>
            </div>


        </div>

        <div id="apletpr"> 

        </div>      


        <div id="footer">
            Zapoznaj sie z oferta naszego serwisu i baw sie dobrze.Strona w sieci od 2017. Wszelkie prawa zastrzeżone &copy;
        </div>

    </body>
</html>