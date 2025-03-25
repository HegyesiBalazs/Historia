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

    /* Kijelentkezés */
    const logoutBtn = document.getElementById("logout");
    const logoutModal = document.getElementById("logout-modal");
    const closeLogoutModal = document.getElementById("close-logout-modal");
    const logoutConfirm = document.getElementById("logout-confirm");
    const logoutCancel = document.getElementById("logout-cancel");

    logoutBtn.addEventListener("click", function () {
        logoutModal.classList.remove("hidden");
    });

    closeLogoutModal.addEventListener("click", function () {
        logoutModal.classList.add("hidden");
    });

    logoutCancel.addEventListener("click", function () {
        logoutModal.classList.add("hidden");
    });

    logoutConfirm.addEventListener("click", function () {
        alert("Sikeresen kijelentkeztél!");
        logoutModal.classList.add("hidden");
        logoutBtn.textContent = "Bejelentkezés";
        logoutBtn.removeEventListener("click", arguments.callee);
        logoutBtn.addEventListener("click", function () {
            div_2.classList.remove("hidden"); // Visszaállítjuk a bejelentkezés modalra
        });
    });
});