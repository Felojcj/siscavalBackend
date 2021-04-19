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
            .entregado {color: #E8C64D}
            .devuelto {color:#186844}
            .perdido {color:#e72727}
        </style>
    </head>  
    <body>
        <header class="navbar navbar-dark nav">
            <div class="nav-auth">
                <a href="https://www.politecnicojic.edu.co/"><img class="logo" src="https://www.politecnicojic.edu.co/images/logo/logo.png" alt="Logo del Politécnico Colombiano Jaime Isaza Cadavid"></a>
            </div>
        </header>
        <h2 class="poli"><strong>Correo estado prestamo</strong></h2>
        <p>Señor usuario {{$msg[0]->email}}</p>
        <p>Su Prestamo se encuentra en estado {{$msg[0]->estado_prestamo}}</p>
        <p>El implemento del Prestamo se encuentra en estado {{$status}}</p>
        <ul>
            <li><strong>Solicitud: </strong> {{$msg[0]->id}}</li>
            <li><strong>Implemento: </strong> {{$msg[0]->implemento}}</li>
            <li><strong>Cantidad Solicitada: </strong> {{$msg[0]->cantidad_implemento_solicitado}}</li>
            <li><strong>Cantidad Entregada: </strong> {{$msg[0]->cantidad_implemento_entregado}}</li>
            <li><strong>Cantidad Devuelta: </strong> {{$msg[0]->cantidad_implemento_devuelto}}</li>
            <li><strong>Cantidad Perdida: </strong> {{$msg[0]->cantidad_implemento_perdido}}</li>
            <li><strong>Cantidad Defectuoso: </strong> {{$msg[0]->cantidad_implemento_defectuoso}}</li>
            <li><strong>Valor Penalidad: </strong> ${{$msg[0]->valor}}</li>
            <li><strong>Ubicación: </strong> {{$msg[0]->placa}}</li>
            <li><strong>Fecha inicial: </strong> {{$msg[0]->fecha_inicial}}</li>
            <li><strong>Fecha final: </strong> {{$msg[0]->fecha_final}}</li>
            @if($status =='Entregado')
                <li class='entregado'><strong>Estado entrega implemento: </strong> {{$status}}</li>
            @elseif($status == 'Devuelto')
                <li class='devuelto'><strong>Estado entrega implemento: </strong> {{$status}}</li>
            @elseif($status == 'Perdido')
                <li class='perdido'><strong>Estado entrega implemento: </strong> {{$status}}</li>
            @endif
        </ul>
        @if($msg[0]->cantidad_implemento_perdido > 0)
            <p>Tienes Mora de  <span class="perdido">${{$msg[0]->cantidad_implemento_perdido*$msg[0]->valor}}</span></p>
        @endif
        <p>Recuerde Devolver los implementos al lugar donde los tomó</p>
    </body>
</html>
