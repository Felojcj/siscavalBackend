<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <style type="text/css">
            li{list-style:none;}
            .poli{color: #186844;}
            .nav {background-color: #186844;justify-content: center;}
            .nav-auth {text-align: center;}
            .logo {width: 35%;}
            .aprobado {color:#186844}
            .rechazado {color:#e72727}
        </style>
    </head>  
    <body>
        <header class="navbar navbar-dark nav">
            <div class="nav-auth">
                <a href="https://www.politecnicojic.edu.co/"><img class="logo" src="https://www.politecnicojic.edu.co/images/logo/logo.png" alt="Logo del Politécnico Colombiano Jaime Isaza Cadavid"></a>
            </div>
        </header>
        <h2 class="poli"><strong>Correo estado reserva</strong></h2>
        <p>Señor usuario {{$msg[0]->email}}</p>
        <p>Su Reserva se encuentra en estado {{$status}}</p>
        <ul>
            <li><strong>Solicitud: </strong> {{$msg[0]->id}}</li>
            <li><strong>Escenario: </strong> {{$msg[0]->escenario}}</li>
            <li><strong>Ubicación: </strong> {{$msg[0]->codigo}}</li>
            <li><strong>Fecha inicial: </strong> {{$msg[0]->fecha_inicial}}</li>
            <li><strong>Fecha final: </strong> {{$msg[0]->fecha_final}}</li>
            @if($status =='Rechazado')
                <li class="rechazado"><strong>Estado: {{$status}}</strong></li>
            @else 
                <li class="aprobado"><strong>Estado:  {{$status}}</strong></li>
            @endif
        </ul>
    </body>
</html>
