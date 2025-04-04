document.addEventListener("DOMContentLoaded", () => {
    /* Felugró div paraméterei */
    const regisztracio1 = document.getElementById("regisztracio");
    const div_ = document.getElementById("div1");
    const bezar = document.getElementById("bezarGomb");
    const regisztracioGomb = document.getElementById("regilink");

    const bejelentkezes1 = document.getElementById("bejelink");
    const div_2 = document.getElementById("div2");
    const bezar2 = document.getElementById("bezarGomb2");

    const forgotPasswordLink = document.getElementById("forgot-password-link");
    const forgotPasswordModal = document.getElementById("forgot-password-modal");
    const forgotClose = document.getElementById("forgot-close");

    // Üzenetkezeléshez szükséges elem
    const messageContainer = document.getElementById("messageContainer");

    // Helyettesítő "session" adat a localStorage-ból
    const sessionData = {
        reg_error: localStorage.getItem('reg_error'),
        reg_success: localStorage.getItem('reg_success')
    };

    // Üzenet megjelenítése
    if (sessionData.reg_error) {
        messageContainer.innerHTML = `<p style="color: red;">${sessionData.reg_error}</p>`;
        localStorage.removeItem('reg_error'); // Egyszeri megjelenítés után törlés
    } else if (sessionData.reg_success) {
        messageContainer.innerHTML = `<p style="color: green;">${sessionData.reg_success}</p>`;
        localStorage.removeItem('reg_success'); // Egyszeri megjelenítés után törlés
    }

    // Üzenet beállítására szolgáló függvények
    function setErrorMessage(message) {
        localStorage.setItem('reg_error', message);
        location.reload(); // Frissítés az oldal újratöltés szimulálására
    }

    function setSuccessMessage(message) {
        localStorage.setItem('reg_success', message);
        location.reload(); // Frissítés az oldal újratöltés szimulálására
    }

    /* Felugró div kódja */
    regisztracio1.addEventListener("click", (event) => {
        event.preventDefault();
        div_.classList.remove("hidden");
    });

    bezar.addEventListener("click", () => {
        div_.classList.add("hidden");
    });

    window.addEventListener("click", (event) => {
        if (event.target === div_) {
            div_.classList.add("hidden");
        }
    });

    bejelentkezes1.addEventListener("click", (event) => {
        event.preventDefault();
        div_2.classList.remove("hidden");
        div_.classList.add("hidden");
    });

    bezar2.addEventListener("click", () => {
        div_2.classList.add("hidden");
    });

    window.addEventListener("click", (event) => {
        if (event.target === div_2) {
            div_2.classList.add("hidden");
        }
    });

    regisztracioGomb.addEventListener("click", (event) => {
        event.preventDefault();
        div_2.classList.add("hidden");
        div_.classList.remove("hidden");
    });

    /* Elfelejtett jelszó modal kezelése */
    forgotPasswordLink.addEventListener("click", (event) => {
        event.preventDefault();
        div_2.classList.add("hidden"); // Bejelentkezés modal bezárása
        forgotPasswordModal.classList.remove("hidden"); // Elfelejtett jelszó modal megnyitása
    });

    forgotClose.addEventListener("click", () => {
        forgotPasswordModal.classList.add("hidden");
    });

    window.addEventListener("click", (event) => {
        if (event.target === forgotPasswordModal) {
            forgotPasswordModal.classList.add("hidden");
        }
    });

    /* Kód küldése az emailre */
    document.getElementById("forgot-password-form").addEventListener("submit", (e) => {
        e.preventDefault();
        const email = document.getElementById("forgot-email").value;

        fetch("ujkod.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "email=" + encodeURIComponent(email),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("A kód elküldve az email címedre!");
                    document.getElementById("forgot-password-form").style.display = "none";
                    document.getElementById("reset-password-form").style.display = "block";
                } else {
                    alert(data.message || "Hiba történt!");
                }
            })
            .catch(() => alert("Hiba történt a kérés során!"));
    });

    /* Jelszó módosítása */
    document.getElementById("reset-password-form").addEventListener("submit", (e) => {
        e.preventDefault();
        const code = document.getElementById("reset-code").value;
        const newPassword = document.getElementById("new-password").value;

        fetch("uj_jelszo.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "code=" + encodeURIComponent(code) + "&new_password=" + encodeURIComponent(newPassword),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("Jelszó sikeresen módosítva!");
                    forgotPasswordModal.classList.add("hidden");
                } else {
                    alert(data.message || "Hiba történt!");
                }
            })
            .catch(() => alert("Hiba történt a kérés során!"));
    });

    // Email módosítás modal megnyitása
document.getElementById('change-email-link').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('change-email-modal').classList.remove('hidden');
});

// Email módosítás modal bezárása
document.getElementById('change-email-close').addEventListener('click', function() {
    document.getElementById('change-email-modal').classList.add('hidden');
});

// Kód küldése
document.getElementById('change-email-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const newEmail = document.getElementById('new-email').value;
    fetch('emailmodosit.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=request_code&new_email=${encodeURIComponent(newEmail)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.sikeres) {
            document.getElementById('change-email-form').style.display = 'none';
            document.getElementById('verify-email-form').style.display = 'block';
            document.getElementById('verify-new-email').value = newEmail;
        } else {
            alert(data.uzenet);
        }
    });
});

// Kód ellenőrzése és email módosítása
document.getElementById('verify-email-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const code = document.getElementById('verify-code').value;
    const newEmail = document.getElementById('verify-new-email').value;
    fetch('emailmodosit.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=verify_code&code=${encodeURIComponent(code)}&new_email=${encodeURIComponent(newEmail)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.sikeres) {
            alert('Email cím sikeresen módosítva!');
            document.getElementById('change-email-modal').classList.add('hidden');
        } else {
            alert(data.uzenet);
        }
    });
});

    /* Jelszó erőssége */
    function checkPasswordStrength(pass, msg, str) {
        pass.addEventListener("input", () => {
            if (pass.value.length > 0) {
                msg.style.display = "block";
            } else {
                msg.style.display = "none";
            }

            let pont = 0;
            let regex = [/[0-9]/, /[a-z]/, /[A-Z]/, /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/];

            for (let i = 0; i < regex.length; i++) {
                if (regex[i].test(pass.value)) {
                    pont++;
                }
            }

            if (pass.value.length < 8) {
                str.innerHTML = "gyenge";
                pass.style.borderColor = "red";
                msg.style.color = "red";
            } else {
                if (pont === 4) {
                    str.innerHTML = "erős";
                    pass.style.borderColor = "#26d730";
                    msg.style.color = "#26d730";
                } else if (pont === 3) {
                    str.innerHTML = "közepes";
                    pass.style.borderColor = "orange";
                    msg.style.color = "orange";
                } else {
                    str.innerHTML = "gyenge";
                    pass.style.borderColor = "red";
                    msg.style.color = "red";
                }
            }
        });
    }

    // Regisztrációs jelszó ellenőrzése
    var pass = document.getElementById("password");
    var msg = document.getElementById("message");
    var str = document.getElementById("strenght");
    if (pass && msg && str) {
        checkPasswordStrength(pass, msg, str);
    }

    // Új jelszó ellenőrzése
    var newPass = document.getElementById("new-password");
    var newMsg = document.getElementById("new-message");
    var newStr = document.getElementById("new-strength");
    if (newPass && newMsg && newStr) {
        checkPasswordStrength(newPass, newMsg, newStr);
    }

  // kijelentkezés

  const logoutBtn = document.getElementById("logout");
  const logoutModal = document.getElementById("logout-modal");
  const closeLogoutModal = document.getElementById("close-logout-modal");
  const logoutConfirm = document.getElementById("logout-confirm");
  const logoutCancel = document.getElementById("logout-cancel");

  // Kijelentkezés gomb megnyomása -> modal megjelenik
  logoutBtn.addEventListener("click", function() {
      logoutModal.classList.remove("hidden");
  });

  // Bezárás (X gomb)
  closeLogoutModal.addEventListener("click", function() {
      logoutModal.classList.add("hidden");
  });

  // "Nem" gombra kattintás -> modal bezárása
  logoutCancel.addEventListener("click", function() {
      logoutModal.classList.add("hidden");
  });

  // "Igen" gombra kattintás -> kijelentkezteti a felhasználót (pl. törölheti a bejelentkezési státuszt localStorage-ból)
  logoutConfirm.addEventListener("click", function() {
      alert("Sikeresen kijelentkeztél!"); // Ide lehetne egy tényleges kijelentkeztetési funkció
      logoutModal.classList.add("hidden");

      // Gomb visszaváltása bejelentkezésre
      logoutBtn.textContent = "Bejelentkezés";
      logoutBtn.removeEventListener("click", arguments.callee);
      logoutBtn.addEventListener("click", function() {
          window.location.href = "bejelentkezes.html"; // Irány a bejelentkezési oldal
      });
  });

  
});



//megjelenítés magyar töri
document.getElementById("valasztogomb").onclick = function () {
    var section = document.getElementById("idovonal");
    var bezar = document.getElementById("bezar");
    var gomb = document.getElementById("valasztogomb");

    gomb.style.display = "none";
    section.style.display = "block";
    bezar.style.display = "block";
}

//magyar eltüntetés
document.getElementById("bezar").onclick = function() {
    var gomb = document.getElementById("valasztogomb");
    var section = document.getElementById("idovonal");
    var bezar = document.getElementById("bezar");

    gomb.style.display = "block";
    section.style.display = "none";
    bezar.style.display = "none";
}



// //megjelenítés egyetemes töri
document.getElementById("valasztogombe").onclick = function () {
    var section2 = document.getElementById("eidovonal");
    var bezar2 = document.getElementById("bezar2");
    var gomb2 = document.getElementById("valasztogombe");

    gomb2.style.display = "none";
    section2.style.display = "block";
    bezar2.style.display = "block";
}

//egyetemes eltüntetés
document.getElementById("bezar2").onclick = function() {
    var gomb2 = document.getElementById("valasztogombe");
    var section2 = document.getElementById("eidovonal");
    var bezar2 = document.getElementById("bezar2");

    gomb2.style.display = "block";
    section2.style.display = "none";
    bezar2.style.display = "none";
}

//menj vissza gomb
let mybutton = document.getElementById("myBtn");

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}

function topFunction() {
  document.body.scrollTop = 0; // Safarihoz
  document.documentElement.scrollTop = 0; // Chrome-hoz, Firefox-hoz, Opera-hoz stb...
}