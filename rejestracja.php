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
                function drukuj_form(){
                    ?>
                <div id="panel">

                            <form method="post">
                                Login:<br>
                                <input type="text" name="username" required><br>
                                Hasło:<br>
                                <input type="password" name="psw" required><br/>
                                Potwierdź Hasło:<br>
                                <input type="password" name="psw1" required><br/>
                                Data urodzenia:<br/>
                                <input type="date" name="bdate" required ><br/>
                                Adres  email:<br/>
                                <input type="email" name="mail" required><br/>
                                <input type="submit" name="zaloguj"><br/>


                            </form>
                        <?php
                }
                
                //blablabla
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                //w przypadku rejesracji tylko niezalogowani moga zalozyc nowe konto
                //wiec gdy uzytkownik jest zalogowany, to wyswietlany jest komunikat, ze najpierw musi sie wylogowac
                if (isset($_SESSION["user"])) {
                    ?>

                    <span class="bigtitle"> Błąd dostępu </span>
                    <div class="dottedline"></div>
                    <div id="panel">
                        Musisz wylogować się przez zakładaniem nowego konta!
                    </div>
                    <?php
                } else {
                    //gdy zostal wybrany przycisk do zatwierdzania formularza
                    if (isset($_POST["zaloguj"])) {
                        //tworzenie polaczenia z baza danych (parametry: serwer, uzytkownik, haslo, nazwa bazy)
                        $conn = new mysqli("localhost", "root", "", "wyporzyczalnia");
                        $conn->set_charset("utf8");
                        if ($conn->connect_error) {
                            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
                        }

                        //sprawdzenie czy wszystkie pola formularza zostaly wypelnione
                        //wszystkie przeslane parametry żądania przechowywane są w tablicy globalnej o nazwie $_POST (w przypadku metody wysyłania żądania post)
                        //w przypadku metody get - tablica $_GET
                        //isset pozwala sprawdzic czy zmienna o takiej nazwie jest ustawiona (posiada wartosc inna niz null)
                        if (!isset($_POST["username"]) || !isset($_POST["psw"]) || !isset($_POST["psw1"]) || !isset($_POST["bdate"]) || !isset($_POST["mail"])) {
                            echo '<h2>Nie wszystkie pola formularza zostały wypełnione!</h2>';
                            echo '<div class="dottedline"></div>';
                            drukuj_form();
                        } else {
                            //zapytanie do sprawdzenia czy uzytkownik o podanym nicku juz istnieje w bazie
                            $sql = "select * from klienci where login = '" . $_POST["username"] . "'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                //gdy zapytanie zwrocilo wyniki to znaczy ze uzytkownik o takim loginie juz istnieje wiec nie mozna zakladac konta
                                echo '<h2>Login musi być unikalny!</h2>';
                                echo '<div class="dottedline"></div>';
                                drukuj_form();
                            } else {
                                //operator porownania w PHP to === (rowna sie), oraz !== (rozne)
                                //tutaj porownywane sa wprowadzone hasla
                                if ($_POST["psw"] !== $_POST["psw1"]) {
                                    echo '<h2>Wprowadzone hasła są różne!</h2>';
                                    echo '<div class="dottedline"></div>';
                                    drukuj_form();
                                } else {

                                    //zapytanie umowliwiajace dodanie nowego rekordu do bazy danych
                                    //hash() to funkcja haszujaca, jej parametrami sa nazwa algorytmu haszowania (tutaj wykorzystalem SHA-256) oraz napis wejsciowy. Funkcja zwraca 64-bajtowy hash
                                    $sql = "insert into klienci values ("
                                            . "NULL, '"
                                            . $_POST["username"] . "','"
                                            . hash('sha256', $_POST["psw"]) . "','"
                                            . $_POST["mail"] . "','"
                                            . $_POST["bdate"] . "')";
                                    if ($conn->query($sql) === TRUE) {
                                        echo "<h2>Użytkownik został zarejestrowany. Proszę zalogować się </h2>";
                                        echo '<div class="dottedline"></div>';
                                    } else {
                                        echo "Error: " . $sql . "<br>" . $conn->error;
                                    }
                                }
                            }
                        }

                        $conn->close();
                    } else {
                        ?>

                        <span class="bigtitle"> Zalogować się możesz tylko po wcześniejszej rejestracji. </span>
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