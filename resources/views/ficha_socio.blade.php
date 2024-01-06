<img src="logo.png" width="60%" style="margin-left: 10%" alt="">
<table style="width: 100%; margin-top: -90px;">
    <tr style="width: 100%">
        <td style="width: 80%"></td>
        <td style="width: 10%">
            <img src="{{ public_path("documentos_imagenes/") . $socio->foto }}"
                width="100%" alt="">
        </td>
    </tr>
</table>
<table style="width: 100%;font-size: 15px;">
    <tr style="width: 100%">
        <td style="width: 100%">
            Registro: {{ $socio->id }} <br>
            Fecha: {{ date_format($socio->created_at, "Y/m/d H:i:s") }} <br><br>
            <b> Sr. Presidente </b> de la Asociación de Militares Nueva Visión
            F.A.
        </td>
    </tr>
</table>

<p style="text-align: justify;font-size: 15px;">
    Presente. <br><br>
    Yo <b>{{ $socio->nombres }}</b> portador (a), del NUI.
    <b>{{ $socio->documento }}</b>, libre y voluntariamente solicito, muy
    comedidamente a usted señor
    Presidente se digne autorizarme ingresar en calidad de socio de la
    Asociación de Militares Nueva Visión F.A., por la gentil acogida expreso mi
    agradecimiento, para lo cual consigno los siguientes datos: .<br>
</p>

<table class='table' style="width: 100%;font-size: 15px;">
    <tr style="width: 100%">
        <td style="width: 50%;border: 1px solid"> <b> Email: </b> </td>
        <td style="width: 100%;border: 1px solid"> <b> {{ $socio->correo }} </b>
        </td>
        <td style="width: 50%"> </td>
    </tr>
    <tr style="width: 100%">
        <td style="width: 50%;border: 1px solid"> <b> Celular: </b> </td>
        <td style="width: 50%;border: 1px solid"> <b> {{ $socio->movil }} </b>
        </td>
        <td style="width: 50%"> </td>
    </tr>
    <!-- <tr style="width: 100%">
        <td style="width: 50%"> <b> Convencional: </b> </td>
        <td style="width: 50%"> <b>  </b> </td>
        <td style="width: 50%">  </td>
    </tr> -->
    <tr style="width: 100%">
        <td style="width: 50%;border: 1px solid"> <b> Nombre Referencia: </b>
        </td>
        <td style="width: 100%;border: 1px solid"> <b>
                {{ $socio->nombres_referencia }} </b> </td>
        <td style="width: 50%"> </td>
    </tr>
    <tr style="width: 100%">
        <td style="width: 50%;border: 1px solid"> <b> Numero Referencia: </b>
        </td>
        <td style="width: 50%;border: 1px solid"> <b>
                {{ $socio->movil_referencia }} </b> </td>
        <td style="width: 50%; text-align: center">
            <div
                style="border-top-style: solid;border-top-width: 1px;border-top-color: black;">
                firma
            </div>
        </td>
    </tr>
    <tr style="width: 100%">
        <td style="width: 50%;border: 1px solid"> <b> Estado / Fuerza: </b>
        </td>
        <td style="width: 50%;border: 1px solid"> <b>
                {{ $socio->estado["value"] }} /
                {{ $socio->fuerza["value"] }} </b> </td>
        <td style="width: 50%; text-align: center"> </td>
    </tr>
    <tr style="width: 100%">
        <td style="width: 33%;border: 1px solid"> <b> Dirección: </b> </td>
        <td style="width: 100%;border: 1px solid"> <b>
                {{ $socio->direccion }}</b> </td>
        <td style="width: 50%; text-align: center"> </td>
    </tr>
</table>
<br>
<div style="width: 100%; text-align: center">
    <b style="text-align: center; font-size: 20px">AUTORIZACION DE DEBITO</b>
    <br>
    <b style="text-align: center; font-size: 20px">Banco.
        {{ $socio->banco["nombre"] }}</b> <br>
</div>
<table style="width: 100%">
    <tr style="width: 100%">
        <td style="width: 100%">
            Registro: {{ $socio->id }} <br>
            Fecha: {{ date("Y/m/d H:i:s") }} <br>
        </td>
    </tr>
</table>

<p style="text-align: justify;font-size: 15px;">
    <br>Yo <b>{{ $socio->nombres }}</b> portador (a), del NUI.
    <b>{{ $socio->documento }}</b>, autorizo a la Asociación de Militares Nueva
    Visión F.A., se debite de mi
    cuenta de ahorros No. <b>{{ $socio->cuenta }}</b> del banco
    <b>{{ $socio->banco["nombre"] }}</b> .la cantidad de 84 dólares por derecho
    de inscripción; y, 7 dólares mensuales como cuota y/o aportación, sumado los
    costos bancarios.

    Dejo constancia que, en el caso que la Asociación de Militares Nueva Visión
    F.A, no pueda realizar el débito por
    la cantidad 84 dólares por derecho de inscripción; y, 7 dólares mensuales
    como cuota y/o aportación, tienen la opción de realizarme descuentos
    acumulados de 24 dólares en el mes de agosto y 60 dólares en el mes de
    diciembre de cada año, sumado los costos bancarios.

    De igual forma me comprometo a mantener los fondos suficientes en mi cuenta
    de ahorros a fin de cubrir los valores cuyo debito autorizo a través de este
    instrumento, valores que me obligo a pagar y autorizo debitar de mi cuenta
    ahorros antes referida, durante el tiempo que subsista la Asociación de
    Militares Nueva Visión F.A.

    Además, EXIMO al Banco y/o Cooperativa de toda responsabilidad por los
    descuentos que efectúe la Asociación de Militares Nueva Visión F.A. en
    virtud de la presente autorización de débito, por lo que renuncio a
    presentar por este concepto, cualquier acción judicial o extrajudicial en
    contra del Banco y/o Cooperativa.

    En todo caso voluntariamente puedo realizar abonos proporcionales mensuales,
    bimensuales, semestrales o anuales, valores que depositaré a la cuenta de
    ahorros de la Asociación de Militares Nueva Visión F.A.
</p>

<table class='table' style="width: 100%;font-size: 15px;">
    <tr style="width: 100%">
        <td style="width: 50%;border: 1px solid"> <b> Email: </b> </td>
        <td style="width: 100%;border: 1px solid"> <b> {{ $socio->correo }}
            </b> </td>
        <td style="width: 50%"> </td>
    </tr>
    <tr style="width: 100%">
        <td style="width: 50%;border: 1px solid"> <b> Celular: </b> </td>
        <td style="width: 50%;border: 1px solid"> <b> {{ $socio->movil }} </b>
        </td>
        <td style="width: 50%"> </td>
    </tr>
    <tr style="width: 100%">
        <td style="width: 50%;border: 1px solid"> <b> Nombre Referencia: </b>
        </td>
        <td style="width: 100%;border: 1px solid"> <b>
                {{ $socio->nombres_referencia }} </b>
        </td>
        <td style="width: 50%"> </td>
    </tr>
    <tr style="width: 100%">
        <td style="width: 50%;border: 1px solid"> <b> Numero Referencia: </b>
        </td>
        <td style="width: 50%;border: 1px solid"> <b>
                {{ $socio->movil_referencia }} </b> </td>
        <td style="width: 50%; text-align: center">
            <div
                style="border-top-style: solid;border-top-width: 1px;border-top-color: black;">
                firma
            </div>
        </td>
    </tr>
    <tr style="width: 100%">
        <td style="width: 50%;border: 1px solid"> <b> Estado / Fuerza: </b>
        </td>
        <td style="width: 50%;border: 1px solid"> <b>
                {{ $socio->estado["value"] }} /
                {{ $socio->fuerza["value"] }} </b> </td>
        <td style="width: 50%; text-align: center"> </td>
    </tr>

    <tr style="width: 100%">
        <td style="width: 33%;border: 1px solid"> <b> Dirección: </b> </td>
        <td style="width: 100%;border: 1px solid"> <b>
                {{ $socio->direccion }}</b> </td>
        <td style="width: 50%; text-align: center"> </td>
    </tr>
</table>

<style>
    .table {
        width: 100%;
        border-collapse: collapse;
    }

    /* #table1 td, */
    /* #table1 th { */
    /*     border: 1px solid; */
    /* } */
</style>
