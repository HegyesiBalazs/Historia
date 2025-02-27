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

    var pass = document.getElementById("jelszo");
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

        if (pass.value.length < 4)
        {
            str.innerHTML = "gyenge";
            pass.style.borderColor = "red";
            msg.style.color = "red";
        }

        else if (pass.value.length >= 4 && pass.value.length < 8) 
        {
            str.innerHTML = "közepes";
            pass.style.borderColor = "orange"; 
            msg.style.color = "orange"; 
        }

        else if (pass.value.length >= 8)
        {
            str.innerHTML = "erős";
            pass.style.borderColor = "#26d730";
            msg.style.color = "#26d730";
        }
        
    });

    
});