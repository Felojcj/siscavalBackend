<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <style type="text/css">
            .poli{color: #186844;}
            .nav {background-color: #186844;justify-content: center;}
            .nav-auth {text-align: center;}
            .logo {width: 35%;}
        </style>
    </head>
    <body>
        <header class="navbar navbar-dark nav">
            <div class="nav-auth">
                <a href="https://www.politecnicojic.edu.co/"><img class="logo" src="https://www.politecnicojic.edu.co/images/logo/logo.png" alt="Logo del Politécnico Colombiano Jaime Isaza Cadavid"></a>
            </div>
        </header>
        <h2 class="poli"><strong>Contraseña Siscaval Poli</strong></h2>
        <p>Señor usuario</p>
        <p>Para una mejor experiencia en nuestro aplicativo, recuerde cambiar su contraseña</p>
        <p>Contraseña provisional: <strong>{{$msg}}</strong></p>
    </body>
</html>
