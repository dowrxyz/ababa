<img src="logo.png" width="60%" style="margin-left: 10%" alt="">
<table style="width: 100%; margin-top: -90px;">
    <tr style="width: 100%">
        <td style="width: 80%"></td>
        <td style="width: 10%">
            <img src="{{public_path('documentos_imagenes/').$socio->foto}}" width="100%" alt="">
        </td>
    </tr>
</table>
<table style="width: 100%;font-size: 15px;">
    <tr style="width: 100%">
        <td style="width: 100%">
        Registro: {{$socio->id}} <br>
        Fecha: {{ date_format($socio->created_at,"Y/m/d H:i:s") }} <br><br>
        <b> Sr. Presidente </b> de la Asociación de Militares Nueva Visión F.A.
        </td>
    </tr>
</table>

<p style="text-align: justify;font-size: 15px;">
Presente. <br><br>
Yo  <b>{{$socio->nombres}}</b> portador (a), del NUI. <b>{{$socio->documento}}</b>, libre y voluntariamente solicito, muy comedidamente a usted señor
Presidente se digne autorizarme ingresar en calidad de socio de la Asociación de Militares Nueva Visión F.A., por la gentil acogida expreso mi agradecimiento.<br>
</p>

<table style="width: 100%;font-size: 15px;">
    <tr style="width: 100%">
        <td style="width: 50%"> <b> Email: </b> </td>
        <td style="width: 100%"> <b> {{$socio->correo}} </b> </td>
        <td style="width: 50%">  </td>
    </tr>
    <tr style="width: 100%">
        <td style="width: 50%"> <b> Celular: </b> </td>
        <td style="width: 50%"> <b> {{$socio->movil}} </b> </td>
        <td style="width: 50%">  </td>
    </tr>
    <!-- <tr style="width: 100%">
        <td style="width: 50%"> <b> Convencional: </b> </td>
        <td style="width: 50%"> <b>  </b> </td>
        <td style="width: 50%">  </td>
    </tr> -->
    <tr style="width: 100%">
        <td style="width: 50%"> <b> Nombre Referencia: </b> </td>
        <td style="width: 100%"> <b> {{$socio->nombres_referencia}} </b> </td>
        <td style="width: 50%">  </td>
    </tr>
    <tr style="width: 100%">
        <td style="width: 50%"> <b> Numero Referencia: </b> </td>
        <td style="width: 50%"> <b> {{$socio->movil_referencia}} </b> </td>
        <td style="width: 50%; text-align: center"> Firma. </td>
    </tr>
    <tr style="width: 100%">
        <td style="width: 50%"> <b> Estado / Fuerza: </b> </td>
        <td style="width: 50%"> <b> {{$socio->estado['value']}} / {{$socio->fuerza['value']}} </b> </td>
        <td style="width: 50%; text-align: center">  </td>
    </tr>
</table>
<table style="width: 100%;font-size: 15px;">
    <tr style="width: 100%">
        <td style="width: 33%"> <b> Dirección: </b> </td>
        <td style="width: 100%"> <b> {{$socio->direccion}}</b> </td>
    </tr>
</table>
<br>
<div style="width: 100%; text-align: center">
<b style="text-align: center; font-size: 20px">AUTORIZACION DE DEBITO</b> <br>
<b style="text-align: center; font-size: 20px">Banco. {{$socio->banco['nombre']}}</b> <br>
</div>
<table style="width: 100%">
    <tr style="width: 100%">
        <td style="width: 100%">
            Registro: {{$socio->id}} <br>
            Fecha: {{date('Y/m/d H:i:s')}} <br>
        </td>
    </tr>
</table>

<p style="text-align: justify;font-size: 15px;">
<br>Yo <b>{{$socio->nombres}}</b> portador (a), del NUI. <b>{{$socio->documento}}</b>, autorizo a la Asociación de Militares Nueva Visión F.A., se debite de mi
cuenta de ahorros No. <b>{{$socio->cuenta}}</b> del banco <b>{{$socio->banco['nombre']}}</b> .la cantidad de 5 dólares mensuales, 10 dólares
bimensuales, 30 dólares semestrales, y / o 60 dólares anuales, más costos bancarios. De igual forma me comprometo a mantener los fondos suficientes en mi cuenta de ahorros
a fin de cubrir los valores cuyo debito autorizo a través de este instrumento, valores que me obligoa pagar y autorizo debitar de mi cuenta ahorros antes
referida, durante el tiempo que subsista la Asociación de Militares Nueva Visión F.A. Además, EXIMO al Banco y/o Cooperativa de toda responsabilidad por los descuentos que efectúe
la Asociación de Militares Nueva Visión F.A. en virtud de la presente autorización de debito, por lo que renuncio a presentar por este concepto, cualquier
acción judicial o extrajudicial en contra del Banco y/o Cooperativa. En todo caso voluntariamente puedo realizar abonos proporcionales mensuales, bimensuales,
semestrales o anuales, valores que depositaré a la cuenta de ahorros de la Asociación de Militares Nueva Visión F.A.
</p>

<table style="width: 100%;font-size: 15px;">
    <tr style="width: 100%">
        <td style="width: 50%"> <b> Email: </b> </td>
        <td style="width: 100%"> <b> {{$socio->correo}} </b> </td>
        <td style="width: 50%">  </td>
    </tr>
    <tr style="width: 100%">
        <td style="width: 50%"> <b> Celular: </b> </td>
        <td style="width: 50%"> <b> {{$socio->movil}} </b> </td>
        <td style="width: 50%">  </td>
    </tr>
    <!-- <tr style="width: 100%">
        <td style="width: 50%"> <b> Convencional: </b> </td>
        <td style="width: 50%"> <b>  </b> </td>
        <td style="width: 50%">  </td>
    </tr> -->
    <tr style="width: 100%">
        <td style="width: 50%"> <b> Nombre Referencia: </b> </td>
        <td style="width: 100%"> <b> {{$socio->nombres_referencia}} </b> </td>
        <td style="width: 50%">  </td>
    </tr>
    <tr style="width: 100%">
        <td style="width: 50%"> <b> Numero Referencia: </b> </td>
        <td style="width: 50%"> <b> {{$socio->movil_referencia}} </b> </td>
        <td style="width: 50%; text-align: center"> Firma. </td>
    </tr>
    <tr style="width: 100%">
        <td style="width: 50%"> <b> Estado / Fuerza: </b> </td>
        <td style="width: 50%"> <b> {{$socio->estado['value']}} / {{$socio->fuerza['value']}} </b> </td>
        <td style="width: 50%; text-align: center">  </td>
    </tr>
</table>
<table style="width: 100%;font-size: 15px;">
    <tr style="width: 100%">
        <td style="width: 33%"> <b> Dirección: </b> </td>
        <td style="width: 100%"> <b> {{$socio->direccion}}</b> </td>
    </tr>
</table>
