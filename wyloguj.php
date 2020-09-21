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
                //gdy uzytkownik jest zalogowany jako zwykly uzytkownik
                if (isset($_SESSION["user"])) {
                    //to wyswietl komunikat ze zostal on wylogowany
                    echo '<h2>Wylogowano użytkownika: ' . $_SESSION["user"] . '</h2>';  
                    //usun zmienna z sesji
                    unset($_SESSION["user"]);
                    //oraz zniszcz wszystkie dane zarejestrowane w sesji
                    session_destroy();
                    ?>
                    <div class="dottedline"></div>

                    <?php
                } else if(isset($_SESSION["admin"])) {
                    //to samo w przypadku administratora
                    echo '<h2>Wylogowano administratora: ' . $_SESSION["admin"] . '</h2>';  
                    unset($_SESSION["admin"]);
                    session_destroy();
                    ?>
                    <div class="dottedline"></div>

                    <?php
                } else {
                    //gdy uzytkownik jest niezalogowany to nic nie rob
                    ?>
                   <span class="bigtitle"> Użytkownik niezalogowany </span>
                    <div class="dottedline"></div>
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



        </div>
    </body>
</html>