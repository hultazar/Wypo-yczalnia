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

                function drukuj_form() {
                    ?>
                    <div id="panel">

                        <form method="post">
                            Login:<br>
                            <input type="text" name="username" required><br>
                            Data urodzenia:<br/>
                            <input type="date" name="bday" required><br/>
                            Adres  email:<br/>
                            <input type="email" name="mail" required><br/>
                            Nowe hasło:<br>
                            <input type="password" name="psw" required><br>
                            Potwierdź nowe hasło:<br>
                            <input type="password" name="psw1" required><br>
                            <input type="submit" name="zaloguj"><br/>

                        </form>
                    </div>
                    <?php
                }

                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION["user"])) {
                    //uzytkownik moze sie zalogowac tylko gdy jest niezalogowany :D
                    ?>

                    <span class="bigtitle"> Musisz wylogować się przez zmianą hasła!</span>
                    <div class="dottedline"></div>

                    <?php
                } else {
                    //gdy zostal zatwierdzony formularz zmiany hasla
                    if (isset($_POST["zaloguj"])) {
                        //utworz polaczenie z baza
                        $conn = new mysqli("localhost", "root", "", "wyporzyczalnia");
                        $conn->set_charset("utf8");
                        if ($conn->connect_error) {
                            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
                        }

                        //sprawdzenie danych
                        if (!isset($_POST["username"]) || !isset($_POST["bday"]) || !isset($_POST["mail"]) || !isset($_POST["psw"]) || !isset($_POST["psw1"])) {
                            echo '<h2>Nie wszystkie pola formularza zostały wypełnione!</h2>';
                            drukuj_form();
                        } else {
                            //czy podane nowe hasla sa rozne?
                            if ($_POST["psw"] !== $_POST["psw1"]) {
                                echo '<h2>Wprowadzone hasła są różne!</h2>';
                            } else {

                                //zapytanie do aktualizacji hasla w bazie. Sprawdzane jest czy istnieje rekord zawierajace wszystkie wprowadzone dane
                                $sql = "update klienci set haslo = '" . hash('sha256', $_POST["psw"]) . "' "
                                        . "where login = '" . $_POST["username"] . "' "
                                        . "and email = '" . $_POST["mail"] . "' "
                                        . "and data_urodzenia = '" . $_POST["bday"] . "'";
                                if ($conn->query($sql) === TRUE) {
                                    //zmienna $conn->affected_rows przechowuje liczbe zmienionych rekordow
                                    if ($conn->affected_rows > 0) {
                                        echo "<h2>Hasło zostało zmienione. Proszę zalogować się przy pomocy nowego hasła </h2>";
                                        echo '<div class="dottedline"></div>';
                                    } else {
                                        echo "<h2>Wprowadzono niepoprawne dane. Hasło nie zostało zmienione! </h2>";
                                        echo '<div class="dottedline"></div>';
                                        drukuj_form();
                                    }
                                } else {
                                    echo "Error: " . $sql . "<br>" . $conn->error;
                                }
                            }
                        }
                        $conn->close();
                    } else {
                        ?>
                        <span class="bigtitle">Zmiana hasła</span>
                        <div class="dottedline"></div>
                        <?php
                        drukuj_form();
                    }
                }
                ?>
                <br/><br/>

            </div>

            <div id="apletpr"> 

            </div>      


            <div id="footer">
                Zapoznaj sie z oferta naszego serwisu i baw sie dobrze.Strona w sieci od 2017. Wszelkie prawa zastrzeżone &copy;
            </div>



        </div>
    </body>
</html>