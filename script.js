document.addEventListener("DOMContentLoaded", () => {

  /*Felugró div paraméterei*/

  const regisztracio1 = document.getElementById("regisztracio");
  const div_ = document.getElementById("div1");
  const bezar = document.getElementById("bezarGomb");
  const regisztracioGomb = document.getElementById("regilink")

  const bejelentkezes1 = document.getElementById("bejelink");
  const div_2 = document.getElementById("div2");
  const bezar2 = document.getElementById("bezarGomb2");
  const bejelentkezesGomb = document.getElementById("bejelink")

  /*felugró div kódja*/

  regisztracio1.addEventListener("click", (event) => {
      event.preventDefault();
      div_.classList.remove("hidden");
  });

  bezarGomb.addEventListener("click", () => {
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
  });

  bezarGomb2.addEventListener("click", (event) => {
      div_2.classList.add("hidden");
  })

  window.addEventListener("click", (event) => {
      if (event.target === div_2) {
          div_2.classList.add("hidden");
      }
  })

  bejelentkezesGomb.addEventListener("click", (event) => {
      event.preventDefault();
      div1.classList.add("hidden");  
      div2.classList.remove("hidden"); 
  });
  
  regisztracioGomb.addEventListener("click", (event) => {
      event.preventDefault();
      div2.classList.add("hidden");  
      div1.classList.remove("hidden"); 
  });

  //Jelszó erőssége

  var pass = document.getElementById("password");
  var msg = document.getElementById("message");
  var str = document.getElementById("strenght");

  pass.addEventListener("input", () => {
      if (pass.value.length > 0) 
      {
          msg.style.display = "block";
      } 
      
      else 
      {
          msg.style.display = "none";
      }

      let pont = 0;
      let regex = [/[0-9]/, /[a-z]/, /[A-Z]/, /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/];

      for (let i = 0; i < regex.length; i++) {
         
          if (regex[i].test(pass.value)) {
              pont++;
          }
      }

      if (pass.value.length < 8) 
      
      { 
          str.innerHTML = "gyenge";
          pass.style.borderColor = "red";
          msg.style.color = "red";
      } 
      
      else 
      {
          if (pont === 4) 
          {
              str.innerHTML = "erős";
              pass.style.borderColor = "#26d730";
              msg.style.color = "#26d730";
          } 
          
          else if (pont === 3) 
          {
              str.innerHTML = "közepes";
              pass.style.borderColor = "orange";
              msg.style.color = "orange";
          } 
          
          else 
          {
              str.innerHTML = "gyenge";
              pass.style.borderColor = "red";
              msg.style.color = "red";
          }
      }
  });

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

//navbar
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
      x.className += " responsive";
    } else {
      x.className = "topnav";
    }
  }