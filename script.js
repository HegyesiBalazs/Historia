document.addEventListener("DOMContentLoaded", () => {
    /* Modális ablakok elemei */
    const regisztracio1 = document.getElementById("regisztracio");
    const div1 = document.getElementById("div1");
    const bezarGomb = document.getElementById("bezarGomb");
    const regisztracioGomb = document.getElementById("regilink");

    const bejelentkezes1 = document.getElementById("bejelink");
    const div2 = document.getElementById("div2");
    const bezarGomb2 = document.getElementById("bezarGomb2");

    const forgotPasswordLink = document.getElementById("forgot-password-link");
    const forgotPasswordModal = document.getElementById("forgot-password-modal");
    const forgotClose = document.getElementById("forgot-close");

    const hitelesitesModal = document.getElementById("email-hitelesites-modal");
    const hitelesitesClose = document.getElementById("hitelesites-close");

    // Üzenetkezeléshez szükséges elemek 
    const regMessageContainer = document.getElementById("reg-message-container") || document.getElementById("messageContainer");
    const loginMessageContainer = document.getElementById("login-message-container") || document.getElementById("messageContainer");
    const forgotMessageContainer = document.getElementById("forgot-message-container") || document.getElementById("messageContainer");
    const changeEmailMessageContainer = document.getElementById("change-email-message-container") || document.getElementById("messageContainer");
    const hitelesitesError = document.getElementById("hitelesites-error");

    // Helyettesítő "session" adat a localStorage-ból
    const sessionData = {
        reg_error: localStorage.getItem('reg_error'),
        reg_success: localStorage.getItem('reg_success')
    };

    // Üzenet megjelenítése regisztrációhoz
    if (sessionData.reg_error) {
        regMessageContainer.innerHTML = `<p style="color: red;">${sessionData.reg_error}</p>`;
        localStorage.removeItem('reg_error');
    } else if (sessionData.reg_success) {
        regMessageContainer.innerHTML = `<p style="color: green;">${sessionData.reg_success}</p>`;
        localStorage.removeItem('reg_success');
    }

    // Üzenet beállítására szolgáló függvények
    function setErrorMessage(container, message) {
        container.innerHTML = `<p style="color: red;">${message}</p>`;
        localStorage.setItem('reg_error', message);
    }

    function setSuccessMessage(container, message) {
        container.innerHTML = `<p style="color: green;">${message}</p>`;
        localStorage.setItem('reg_success', message);
    }

    /* Modális ablakok eseménykezelői */
    regisztracio1.addEventListener("click", (event) => {
        event.preventDefault();
        div1.classList.remove("hidden");
    });

    bezarGomb.addEventListener("click", () => {
        div1.classList.add("hidden");
    });

    window.addEventListener("click", (event) => {
        if (event.target === div1) {
            div1.classList.add("hidden");
        }
    });

    bejelentkezes1.addEventListener("click", (event) => {
        event.preventDefault();
        div1.classList.add("hidden");
        div2.classList.remove("hidden");
    });

    bezarGomb2.addEventListener("click", () => {
        div2.classList.add("hidden");
    });

    window.addEventListener("click", (event) => {
        if (event.target === div2) {
            div2.classList.add("hidden");
        }
    });

    regisztracioGomb.addEventListener("click", (event) => {
        event.preventDefault();
        div2.classList.add("hidden");
        div1.classList.remove("hidden");
    });

    /* Elfelejtett jelszó modal kezelése */
    forgotPasswordLink.addEventListener("click", (event) => {
        event.preventDefault();
        div2.classList.add("hidden");
        forgotPasswordModal.classList.remove("hidden");
    });

    forgotClose.addEventListener("click", () => {
        forgotPasswordModal.classList.add("hidden");
    });

    window.addEventListener("click", (event) => {
        if (event.target === forgotPasswordModal) {
            forgotPasswordModal.classList.add("hidden");
        }
    });

    /* Regisztrációs űrlap kezelése */
    document.getElementById("regisztracio-form").addEventListener("submit", (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
    
        fetch("regisztral.php", {
            method: "POST",
            body: formData
        })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP hiba! Státusz: ${response.status} (${response.statusText})`);
            }
            return response.json().catch(() => {
                throw new Error('A szerver válasza nem érvényes JSON formátumú!');
            });
        })
        .then((data) => {
            if (data.success) {
                setSuccessMessage(regMessageContainer, data.message || "Sikeres regisztráció! Kérjük, hitelesítsd az email címed.");
                localStorage.setItem("reg_email", formData.get("email"));
                div1.classList.add("hidden");
                hitelesitesModal.classList.remove("hidden");
            } else {
                setErrorMessage(regMessageContainer, data.message || "Hiba történt a regisztráció során!");
            }
        })
        .catch((error) => {
            console.error('Hiba:', error);
            setErrorMessage(regMessageContainer, `Hiba történt a kérés során: ${error.message}`);
        });
    });

    /* Bejelentkezési űrlap kezelése */
    document.getElementById("bejelentkezes-form").addEventListener("submit", (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        fetch("bejelentkezik.php", {
            method: "POST",
            body: formData
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                setSuccessMessage(loginMessageContainer, data.message || "Sikeres bejelentkezés!");
                window.location.reload(); // Frissítjük az oldalt
            } else {
                setErrorMessage(loginMessageContainer, data.message || "Hibás email vagy jelszó!");
            }
        })
        .catch(() => {
            setErrorMessage(loginMessageContainer, "Hiba történt a kérés során!");
        });
    });

    /* Kód küldése az emailre (elfelejtett jelszó) */
    document.getElementById("forgot-password-form").addEventListener("submit", (e) => {
        e.preventDefault();
        const email = document.getElementById("forgot-email").value;

        fetch("ujkod.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "email=" + encodeURIComponent(email)
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.sikeres) {
                setSuccessMessage(forgotMessageContainer, "A kód elküldve az email címedre!");
                document.getElementById("forgot-password-form").style.display = "none";
                document.getElementById("reset-password-form").style.display = "block";
            } else {
                setErrorMessage(forgotMessageContainer, data.uzenet || "Hiba történt!");
            }
        })
        .catch(() => {
            setErrorMessage(forgotMessageContainer, "Hiba történt a kérés során!");
        });
    });

    /* Jelszó módosítása */
    document.getElementById("reset-password-form").addEventListener("submit", (e) => {
        e.preventDefault();
        const code = document.getElementById("reset-code").value;
        const newPassword = document.getElementById("new-password").value;

        fetch("uj_jelszo.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "code=" + encodeURIComponent(code) + "&new_password=" + encodeURIComponent(newPassword)
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.sikeres) {
                setSuccessMessage(forgotMessageContainer, data.message || "Jelszó sikeresen módosítva!");
                forgotPasswordModal.classList.add("hidden");
                div2.classList.remove("hidden");
            } else {
                setErrorMessage(forgotMessageContainer, data.uzenet || "Érvénytelen vagy lejárt kód!");
            }
        })
        .catch(() => {
            setErrorMessage(forgotMessageContainer, "Hiba történt a kérés során!");
        });
    });

    /* Email módosítás modal megnyitása */
    document.getElementById("change-email-link").addEventListener("click", (e) => {
        e.preventDefault();
        div2.classList.add("hidden");
        document.getElementById("change-email-modal").classList.remove("hidden");
    });

    /* Email módosítás modal bezárása */
    document.getElementById("change-email-close").addEventListener("click", () => {
        document.getElementById("change-email-modal").classList.add("hidden");
    });

    window.addEventListener("click", (event) => {
        if (event.target === document.getElementById("change-email-modal")) {
            document.getElementById("change-email-modal").classList.add("hidden");
        }
    });

    /* Kód küldése (email módosítás) */
    document.getElementById("change-email-form").addEventListener("submit", (e) => {
        e.preventDefault();
        const newEmail = document.getElementById("new-email").value;

        fetch("emailmodosit.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `action=request_code&new_email=${encodeURIComponent(newEmail)}`
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.sikeres) {
                setSuccessMessage(changeEmailMessageContainer, "A kód elküldve az új email címedre!");
                document.getElementById("change-email-form").style.display = "none";
                document.getElementById("verify-email-form").style.display = "block";
                document.getElementById("verify-new-email").value = newEmail;
            } else {
                setErrorMessage(changeEmailMessageContainer, data.uzenet || "Hiba történt!");
            }
        })
        .catch(() => {
            setErrorMessage(changeEmailMessageContainer, "Hiba történt a kérés során!");
        });
    });

    /* Kód ellenőrzése és email módosítása */
    document.getElementById("verify-email-form").addEventListener("submit", (e) => {
        e.preventDefault();
        const code = document.getElementById("verify-code").value;
        const newEmail = document.getElementById("verify-new-email").value;

        fetch("emailmodosit.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `action=verify_code&code=${encodeURIComponent(code)}&new_email=${encodeURIComponent(newEmail)}`
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.sikeres) {
                setSuccessMessage(changeEmailMessageContainer, "Email cím sikeresen módosítva!");
                document.getElementById("change-email-modal").classList.add("hidden");
            } else {
                setErrorMessage(changeEmailMessageContainer, data.uzenet || "Érvénytelen vagy lejárt kód!");
            }
        })
        .catch(() => {
            setErrorMessage(changeEmailMessageContainer, "Hiba történt a kérés során!");
        });
    });

    /* Email hitelesítési modal bezárása */
    hitelesitesClose.addEventListener("click", () => {
        hitelesitesModal.classList.add("hidden");
    });

    window.addEventListener("click", (event) => {
        if (event.target === hitelesitesModal) {
            hitelesitesModal.classList.add("hidden");
        }
    });

    /* Email hitelesítési űrlap kezelése */
    document.getElementById("hitelesites-form").addEventListener("submit", (e) => {
        e.preventDefault();
        const kod = document.getElementById("kod").value;
        const email = localStorage.getItem("reg_email");

        if (!email) {
            hitelesitesError.style.display = "block";
            hitelesitesError.textContent = "Hiba: Email cím nem található!";
            return;
        }

        fetch("hitelesites_api.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `kod=${encodeURIComponent(kod)}&email=${encodeURIComponent(email)}`
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                hitelesitesError.style.display = "none";
                setSuccessMessage(regMessageContainer, "Sikeres hitelesítés! Jelentkezz be!");
                localStorage.removeItem("reg_email");
                hitelesitesModal.classList.add("hidden");
                div2.classList.remove("hidden");
            } else {
                hitelesitesError.style.display = "block";
                hitelesitesError.textContent = data.message || "Érvénytelen vagy lejárt kód!";
            }
        })
        .catch(() => {
            hitelesitesError.style.display = "block";
            hitelesitesError.textContent = "Hiba történt a kérés során!";
        });
    });

    /* Új hitelesítő kód kérése */
    document.getElementById("uj-kod-link").addEventListener("click", (e) => {
        e.preventDefault();
        const email = localStorage.getItem("reg_email");
    
        if (!email) {
            hitelesitesError.style.display = "block";
            hitelesitesError.textContent = "Hiba: Email cím nem található!";
            return;
        }
    
        fetch(`uj_kod_emailhez.php?email=${encodeURIComponent(email)}`, {
            method: "GET"
        })
        .then((response) => response.json())
        .then((data) => {
            hitelesitesError.style.display = "block";
            if (data.success) {
                hitelesitesError.style.color = "green";
                hitelesitesError.textContent = data.message;
            } else {
                hitelesitesError.style.color = "red";
                hitelesitesError.textContent = data.message || "Hiba történt!";
            }
        })
        .catch(() => {
            hitelesitesError.style.display = "block";
            hitelesitesError.textContent = "Hiba történt a kérés során!";
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
    const pass = document.getElementById("password");
    const msg = document.getElementById("message");
    const str = document.getElementById("strenght");
    if (pass && msg && str) {
        checkPasswordStrength(pass, msg, str);
    }

    // Új jelszó ellenőrzése
    const newPass = document.getElementById("new-password");
    const newMsg = document.getElementById("new-message");
    const newStr = document.getElementById("new-strength");
    if (newPass && newMsg && newStr) {
        checkPasswordStrength(newPass, newMsg, newStr);
    }

    /* Kijelentkezés */
    const logoutBtn = document.getElementById("logout");
    const logoutModal = document.getElementById("logout-modal");
    const closeLogoutModal = document.getElementById("close-logout-modal");
    const logoutConfirm = document.getElementById("logout-confirm");
    const logoutCancel = document.getElementById("logout-cancel");

    if (logoutBtn) {
        logoutBtn.addEventListener("click", () => {
            logoutModal.classList.remove("hidden");
        });
    }

    if (closeLogoutModal) {
        closeLogoutModal.addEventListener("click", () => {
            logoutModal.classList.add("hidden");
        });
    }

    if (logoutCancel) {
        logoutCancel.addEventListener("click", () => {
            logoutModal.classList.add("hidden");
        });
    }

    if (logoutConfirm) {
        logoutConfirm.addEventListener("click", () => {
            fetch("kijelentkezik.php", {
                method: "POST"
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    setSuccessMessage(loginMessageContainer, "Sikeresen kijelentkeztél!");
                    logoutModal.classList.add("hidden");
                    logoutBtn.textContent = "Bejelentkezés";
                    logoutBtn.removeEventListener("click", arguments.callee);
                    logoutBtn.addEventListener("click", () => {
                        div2.classList.remove("hidden");
                    });
                } else {
                    setErrorMessage(loginMessageContainer, data.message || "Hiba történt a kijelentkezés során!");
                }
            })
            .catch(() => {
                setErrorMessage(loginMessageContainer, "Hiba történt a kérés során!");
            });
        });
    }
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

//navbar
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
      x.className += " responsive";
    } else {
      x.className = "topnav";
    }
  }