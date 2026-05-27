<?php
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'root';
$db_password = getenv('DB_PASSWORD') ?: '';
$db_database = getenv('DB_DATABASE') ?: 'infocar';
$db_port = getenv('DB_PORT') ?: '3306';

$id_zalogowanego = 1; 


if (isset($_POST['przycisk_zapisz'])) {
    $id_t = $_POST['id_terminu'];
    $sql = "INSERT INTO zapisy (id_uzytkownika, id_terminu, wynik) VALUES ($id_zalogowanego, $id_t, 'oczekuje')";
    mysqli_query($connection, $sql);
}

if (isset($_POST['przycisk_wynik'])) {
    $id_z = $_POST['id_zapisu'];
    $nowy_wynik = $_POST['ocena'];
    $sql = "UPDATE zapisy SET wynik = '$nowy_wynik' WHERE id_zapisu = $id_z";
    mysqli_query($connection, $sql);
}

if (isset($_POST['przycisk_dodaj_termin'])) {
    $data = $_POST['nowa_data'];
    $miejsce = $_POST['nowe_miejsce'];
    $id_egz = $_POST['id_egzaminatora'];
    $sql = "INSERT INTO terminy_egzaminow (data_godzina, miejsce, id_egzaminatora) VALUES ('$data', '$miejsce', $id_egz)";
    mysqli_query($connection, $sql);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>System Egzaminacyjny</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 8px; }
        #sekcja_egzaminator, #sekcja_sekretarz { display: none; }
        nav button { padding: 10px; cursor: pointer; background-color: crimson; color: white; border: none; }
        .form-box { background: #f9f9f9; padding: 15px; border: 1px solid #ccc; margin-bottom: 20px; }
        table {
    background-color: rgba(255, 255, 255, 0.8); 
    
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

th {
    background-color: rgba(220, 20, 60, 0.9);
    color: white;
}

td {
    color: #333;
    font-weight: 500;
}
    </style>
    <style>
    body {
        background-image: url('zdj.jpg'); 
        background-repeat: no-repeat;    
        background-attachment: fixed;   
        background-size: cover;         
        background-position: center;   
    }
    </style>
</head>
<body>

    <nav>
        <button id="btn1" onclick="pokazUzytkownika()" style="background-color: salmon;">Użytkownik</button>
        <button id="btn2" onclick="pokazEgzaminatora()">Egzaminator</button>
        <button id="btn3" onclick="pokazSekretarza()">Sekretarz</button>
    </nav>

    <div id="sekcja_uzytkownik">
        <h2>Twoje wyniki</h2>
        <table>
            <tr><th>Data</th><th>Miejsce</th><th>Wynik</th></tr>
            <?php
            $wynik_h = mysqli_query($connection, "SELECT t.data_godzina, t.miejsce, z.wynik FROM zapisy z JOIN terminy_egzaminow t ON z.id_terminu = t.id_terminu WHERE z.id_uzytkownika = $id_zalogowanego");
            while($wiersz = mysqli_fetch_assoc($wynik_h)) {
                echo "<tr><td>".$wiersz['data_godzina']."</td><td>".$wiersz['miejsce']."</td><td>".$wiersz['wynik']."</td></tr>";
            }
            ?>
        </table>

        <h2>Dostępne terminy</h2>
        <table>
            <tr><th>Data</th><th>Miejsce</th><th>Akcja</th></tr>
            <?php
            $wynik_w = mysqli_query($connection, "SELECT id_terminu, data_godzina, miejsce FROM terminy_egzaminow WHERE id_terminu NOT IN (SELECT id_terminu FROM zapisy)");
            while($wiersz = mysqli_fetch_assoc($wynik_w)) {
                echo "<tr><td>".$wiersz['data_godzina']."</td><td>".$wiersz['miejsce']."</td>
                <td><form method='POST'><input type='hidden' name='id_terminu' value='".$wiersz['id_terminu']."'><button type='submit' name='przycisk_zapisz'>Zapisz się</button></form></td></tr>";
            }
            ?>
        </table>
    </div>

    <div id="sekcja_egzaminator">
        <h2>Egzaminy do ocenienia</h2>
        <table>
            <tr><th>Kandydat</th><th>Data</th><th>Wynik</th></tr>
            <?php
            $zapytanie_e = "SELECT z.id_zapisu, u.imie, u.nazwisko, t.data_godzina FROM zapisy z 
                            JOIN uzytkownicy u ON z.id_uzytkownika = u.id_uzytkownika 
                            JOIN terminy_egzaminow t ON z.id_terminu = t.id_terminu WHERE z.wynik = 'oczekuje'";
            $wynik_e = mysqli_query($connection, $zapytanie_e);
            while($wiersz = mysqli_fetch_assoc($wynik_e)) {
                echo "<tr>";
                echo "<td>".$wiersz['imie']." ".$wiersz['nazwisko']."</td>";
                echo "<td>".$wiersz['data_godzina']."</td>";
                echo "<td>
                        <form method='POST'>
                            <input type='hidden' name='id_zapisu' value='".$wiersz['id_zapisu']."'>
                            <select name='ocena'>
                                <option value='pozytywny'>Pozytywny</option>
                                <option value='negatywny'>Negatywny</option>
                            </select>
                            <button type='submit' name='przycisk_wynik'>Zatwierdź</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <div id="sekcja_sekretarz">
        <h2>Dodaj nowy termin egzaminu</h2>
        <div class="form-box">
            <form method="POST">
                Data i godzina: <input type="datetime-local" name="nowa_data" required><br><br>
                Miejsce: <input type="text" name="nowe_miejsce" required><br><br>
                Egzaminator: 
                <select name="id_egzaminatora">
                    <?php
                    $egzaminatorzy = mysqli_query($connection, "SELECT id_uzytkownika, imie, nazwisko FROM uzytkownicy WHERE rola = 'egzaminator'");
                    while($e = mysqli_fetch_assoc($egzaminatorzy)) {
                        echo "<option value='".$e['id_uzytkownika']."'>".$e['imie']." ".$e['nazwisko']."</option>";
                    }
                    ?>
                </select><br><br>
                <button type="submit" name="przycisk_dodaj_termin">Dodaj termin do bazy</button>
            </form>
        </div>
    </div>

    <script>
        function pokazUzytkownika() {
            zmienSekcje('sekcja_uzytkownik', 'btn1');
        }
        function pokazEgzaminatora() {
            zmienSekcje('sekcja_egzaminator', 'btn2');
        }
        function pokazSekretarza() {
            zmienSekcje('sekcja_sekretarz', 'btn3');
        }

        function zmienSekcje(idSekcji, idButtona) {
            document.getElementById('sekcja_uzytkownik').style.display = 'none';
            document.getElementById('sekcja_egzaminator').style.display = 'none';
            document.getElementById('sekcja_sekretarz').style.display = 'none';
            document.getElementById(idSekcji).style.display = 'block';

            document.getElementById('btn1').style.backgroundColor = 'crimson';
            document.getElementById('btn2').style.backgroundColor = 'crimson';
            document.getElementById('btn3').style.backgroundColor = 'crimson';
            document.getElementById(idButtona).style.backgroundColor = 'salmon';
        }
    </script>

</body>
</html>
<?php mysqli_close($connection); ?>