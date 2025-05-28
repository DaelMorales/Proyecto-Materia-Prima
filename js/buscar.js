document.addEventListener("DOMContentLoaded", function() { 
    document.getElementById("search").addEventListener("click", function(event) { 
        event.preventDefault(); 
        let searchTerm = document.querySelector(".form-control").value.toLowerCase(); 
         let pages = {
            "madera":"madera.html",
             "pino": "pino.html", 
             "picea": "picea.html", 
             "larice": "larice.html", 
             "enebro": "enebro.html", 
             "alamo": "alamo.html", 
             "carpe": "carpe.html", 
             "abedul": "abedul.html", 
             "aliso": "aliso.html",
              "haya": "haya.html",
               "roble": "maderaroble.html", 
               "olmo": "olmo.html", 
               "cerezo": "cerezo.html",
                "peral": "peral.html", 
                "arce": "arce.html", 
                "tilo": "tilo.html", 
                "fresno": "fresno.html", 
                "hierro": "hierro.html", 
                "bajo": "bajo.html", 
                "medio": "medio.html", 
                "alto": "alto.html", 
                "aleados": "aleados.html", 
                "verduras": "verduras.html",
                 "hoja": "hoja.html", 
                 "tallo": "tallo.html", 
                 "influrescencia": "influrescencia.html",
                  "fruto": "fruto.html", 
                  "bulbo": "bulbo.html", 
                  "semilla": "semilla.html",
                   "raiz": "raiz.html", 
                   "tuberculos": "tuberculos.html",
                    "cormo": "cormo.html", 
                    "rizoma": "rizoma.html",
                     "frutas": "frutas.html",
                      "hueso": "hueso.html", 
                      "pepita": "pepita.html", 
                      "grano": "grano.html",
                       "fruta fresca": "fresca.html", 
                       "seca": "seca.html", 
                       "climaterica": "climaterica.html", 
                       "noclimaterica": "noclimaterica.html", 
                       "citrica": "citrica.html", 
                       "tropical": "tropical.html", 
                       "bosque": "bosque.html", 
                       "secos": "secos.html" 
                    }; 
                    if (pages[searchTerm]) { 
                        window.location.href = pages[searchTerm]; 
                    } else { 
                        alert("No se encontró el contenido buscado.");
                     } 
                    }); 
                });