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

                //funkcja drukujaca formularz logowania administratora
                //w zwiazku z tym ze potrzeba drukowania formularza moze zajsc w kilku miejscach,
                //oplaca sie wpakowac ten formularz w funkcje
                function drukuj_form() {
                    ?>
                    <div id="panel">
                        <form method="post">
                            Login:<br>
                            <input type="text" name="username" required><br>
                            Hasło:<br>
                            <input type="password" name="psw" required><br/><br/>

                            <input type="submit" name="zaloguj"><br/>


                        </form>
                        <?php
                    }

                    //sprawdzanie czy sesja jest wlaczona
                    //jesli jeszcze nie zostala wlaczona to wlacz ja
                    //to sprawdzanie jest wazne poniewaz wielokrotne wlaczanie sesji powoduje trudne do znalezienia bledy
                    //nalezy wlaczyc sesje w KAZDYM PLIKU gdzie mamy z nia do czynienia
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    //jesli uzytkownik juz jest zalogowany jako zwykly uzytkownik
                    //to przed zalogowaiem sie jako admin musi wylogowac sie
                    if (isset($_SESSION["user"])) {
                        //uzytkownik moze sie zalogowac tylko gdy jest niezalogowany :D
                        ?>
                        <span class="bigtitle"> Błąd dostępu </span>
                        <div class="dottedline"></div>
                        <div id="panel">
                            Jesteś zalogowany jako użytkownik! Proszę wylogować się.
                        </div>
                        <?php
                    } else if (isset($_SESSION["admin"])) {
                        //gdy jest zalogowany jako administrator to moze przegladac wszystkie zamowienia
                        //oraz je usuwac
                        //gdy zostal wybrany przycisk do usuniecia zamowienia
                        if (isset($_POST["usun"])) {
                            //to polacz sie z baza danych
                            $conn = new mysqli("localhost", "root", "", "wyporzyczalnia");
                            //ustaw kodowanie UTF-8 - polskie znaki
                            $conn->set_charset("utf8");
                            //w przypadku bledu wypisz komunikat
                            if ($conn->connect_error) {
                                die("Błąd połączenia z bazą danych: " . $conn->connect_error);
                            }

                            //zapytanie umozliwiajace usuniecie wybranego rekordu z bazy
                            //parametr $_POST["usun"] jest przesylany jako wartosc przysicku do usuwania poszczegolnego zamowienia
                            //przechowuje id tego zamowienia wiec latwo jest go usunac
                            $sql = "delete from wypozyczenia where id_wypozyczenia = " . $_POST["usun"];
                            if ($conn->query($sql) === TRUE) {
                                //jesli dane zostaly usuniete
                                //zamykanie polaczenia z baza
                                $conn->close();
                                //odswiez strone zeby wyswietlic reszte zamowien
                                header("Refresh:0");
                            }
                        }
                        //wyswietlanie zamowien
                        echo '<h2>Statystyka zamówień:</h2>';
                        echo '<div class="dottedline"></div>';
                        
                        //laczenie sie z baza
                        $conn = new mysqli("localhost", "root", "", "wyporzyczalnia");
                        $conn->set_charset("utf8");
                        if ($conn->connect_error) {
                            //gdy polaczenie nie powiodlo sie
                            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
                        }
                        
                        //zapytanie wybierajace dane to tabeli
                        //wykorzystywane jest zlaczenie naturalne (natural join)
                        //jest to mozliwe tylko wtedy gdy nazwy kluczy sa identyczne oraz typy danych przechowujace klucze sa takie same
                        $sql = "select login, email, id_wypozyczenia, "
                                . "data_startu_wypoz, "
                                . "data_konca_wyporzyczenia, "
                                . "tytul, "
                                . "rodzaj "
                                . "from wypozyczenia "
                                . "natural join filmy "
                                . "natural join klienci ";
                        //wykonanie zapytania sql na bazie
                        //zmienna $result1 przechowuje zbior wynikowy zapytania
                        $result1 = $conn->query($sql);
                        //gdy liczba zwroconych wierszy jest wieksza od 0 czyli znaleziono zamowienia
                        if ($result1->num_rows > 0) {
                            //tworzona jest tabela
                            //echo umozliwia wyswietlenie dowolnego tekstu (w tym znacznikow html)
                            //w tym samym miejscu jest definiowany naglowek tabeli
                            echo '<table id="tab">'
                            . '<tr><th>Login</th><th>Email</th><th>Nr zam.</th><th>Od</th><th>Do</th><th>Tytuł</th><th>Rodzaj</th><th>Usuń</th></tr><tbody>';

                            //dopoki sa rekordy w zbiorze wynikowym
                            while ($row = $result1->fetch_assoc()) {
                                //dodawanie poszczegolnych wierszy do tabeli na podstawie wynikow otrzymanych z bazy
                                echo '<tr>'
                                . '<td>' . $row["login"] . '</td>'
                                        . '<td>' . $row["email"] . '</td>'
                                . '<td>' . $row["id_wypozyczenia"] . '</td>'
                                . '<td>' . $row["data_startu_wypoz"] . '</td>'
                                . '<td>' . $row["data_konca_wyporzyczenia"] . '</td>'
                                . '<td>' . $row["tytul"] . '</td>'
                                . '<td>' . $row["rodzaj"] . '</td>'
                                . '<td><form method="post"><button type="submit" value="' . $row["id_wypozyczenia"] . '" name="usun" ><strong>Usuń</strong></button></form></td>'
                                . '</tr>';
                                //ostatnia komorka tabeli zawiera formularz, ktory zawiera tylko jeden przycisk umozliwiajacy uzuniecie zamowienia.
                                //jego wartoscia (atrybut value) jest ID wypozyczenia dzieki czemu jest mozliwe usuniecie zamowienia
                            }
                            //koniec tabeli
                            echo '</tbody></table>';
                        } else {
                            //gdy zapytanie nie zwrocilo wynikow
                            echo "<h3>Na razie nie ma zamówień</h3>";
                        }
                    } else {
                        //w tym miejscu obslugiwane jest logowanie administratora
                        if (isset($_POST["zaloguj"])) {
                            $conn = new mysqli("localhost", "root", "", "wyporzyczalnia");
                            $conn->set_charset("utf8");
                            if ($conn->connect_error) {
                                die("Błąd połączenia z bazą danych: " . $conn->connect_error);
                            }

                            //sprawdzenie czy wszystkie pola formularza zostaly wypelnione
                            if (!isset($_POST["username"]) || !isset($_POST["psw"])) {
                                echo '<h2>Nie wszystkie pola formularza zostały wypełnione!</h2>';
                                //ponownie wydrukuj formularz
                                    drukuj_form();
                            } else {
                                //sprawdzenie czy uzytkownik o podanym loginie oraz hasle istnieje w bazie 
                                $sql = "select * from administratorzy where login = '" . $_POST["username"] . "' and haslo = '" . hash('sha256', $_POST["psw"]) . "'";
                                $result = $conn->query($sql);
                                //jesli taki uzytkownik istnieje to ustaw zmienna sesji przechowujaca login administratora
                                if ($result->num_rows > 0) {
                                    //gdy istnieje to ustaw zmienne w sesji
                                    $_SESSION["admin"] = $_POST["username"];
                                    //wyswietl komunikat
                                    echo '<h2>Zalogowano administratora: ' . $_POST["username"] . '</h2>';
                                    echo '<div class="dottedline"></div>';
                                    //odswiez strone zeby pokazac wszystkie wypozyczenia
                                    header("Refresh:0");
                                } else {
                                    //bledny login i/lub haslo
                                    echo '<h2>Błąd logowania. Spróbuj jeszcze raz</h2>';
                                    echo '<div class="dottedline"></div>';
                                    //ponownie wydrukuj formularz
                                    drukuj_form();
                                }
                            }
                            //zamykanie polaczenia z baza
                            $conn->close();
                        } else {
                            // gdy uzytkownik nie jest zalogowany jako uzytkownik lub jako admin to wyswietl formularz do logowania
                            ?>

                            <span class="bigtitle"> Logowanie administratora </span>
                            <div class="dottedline"></div>

                            <?php
                            drukuj_form();
                        }
                    }
                    ?>
                </div>


            </div>

            <div id="apletpr"> 

            </div>      


            <div id="footer">
                Zapoznaj sie z oferta naszego serwisu i baw sie dobrze.Strona w sieci od 2017. Wszelkie prawa zastrzeżone &copy;
            </div>



        </div>
    </body>
</html>