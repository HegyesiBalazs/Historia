








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
