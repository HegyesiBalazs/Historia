<?php
session_start();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>História</title>
</head>
<body>
    <header>
        <div class="main">
            <ul>
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#">Elérhetőség</a></li>
                <li><a href="#">Rólunk</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">Kijelentkezés</a></li>
                    <li><span>Üdv, <?= htmlspecialchars($_SESSION['keresztnev']) ?>!</span></li>
                <?php else: ?>
                    <li><a href="#" id="regisztracio">Bejelentkezés</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>
    
    <div id="div1" class="div hidden">
        <div class="kontent">
            <span id="bezarGomb" class="close">&times;</span>
            <h2 id="h2b">Regisztráció</h2>
            <form method="POST" action="register.php" onsubmit="return validatePassword()">
                <input type="text" class="input" placeholder="Vezetéknév" name="vezeteknev" required>
                <input type="text" class="input" placeholder="Keresztnév" name="keresztnev" required>
                <input type="email" class="input" placeholder="Email" name="email" required>
                <input type="password" class="input" placeholder="Jelszó" id="jelszo" name="jelszo" required>
                <p id="message">A jelszó <span id="strenght"></span></p>
                <input type="password" class="input" placeholder="Jelszó mégegyszer" id="jelszo2" required>
                <p id="passwordError" style="color: red; display: none;">A jelszavak nem egyeznek!</p>
                <button class="gomb" type="submit">Regisztráció</button>
                <p>Van már fiókod? <a href="#" id="bejelink">Jelentkezz be!</a></p>
            </form>
        </div>
    </div>

    <div id="div2" class="div hidden">
        <div class="kontent">
            <span class="close" id="bezarGomb2">&times;</span>
            <h2 id="h2b">Bejelentkezés</h2>
            <form method="POST" action="login.php">
                <input type="email" class="input" placeholder="Email" name="email" required>
                <input type="password" class="input" placeholder="Jelszó" name="jelszo" required>
                <div class="emlekezzram">
                    <input type="checkbox" id="emlekezzRam" name="emlekezzRam">
                    <label for="emlekezzRam">Emlékezz rám</label>
                </div>
                <button class="gomb" type="submit">Bejelentkezés</button>
                <p>Nincs még fiókod? <a href="#" id="regilink">Regisztrálj!</a></p>
            </form>
        </div>
    </div>

    <script>
        function validatePassword() {
            var pass1 = document.getElementById("jelszo").value;
            var pass2 = document.getElementById("jelszo2").value;
            var error = document.getElementById("passwordError");
            
            if (pass1 !== pass2) {
                error.style.display = "block";
                return false;
            } else {
                error.style.display = "none";
                return true;
            }
        }
    </script>
    
    <script src="script.js" defer></script>
</body>
</html>
