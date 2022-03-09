<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>pdf</title>
</head>
<style>
    /* body{
        height: 100vh;
        padding: 8px
    } */
    .content-titulo{
        display: flex;
        flex-direction: column;
        text-align: center;
    }
    .border{
        width: 100%;
        display: block;
        height: 80%;
        border: 2px solid #000;
        border-radius: 30px;
        padding: 10px
    }
    .seccion_supeior{
        display: flex;
        justify-content: space-between;
        width: 100%;
        margin-top: 15px;
        border-bottom: 2px solid #000;
    }
    .left{
        display: inline-block;
        /* flex-direction: column; */
    }
    .left span{
        display: block;
  
    }
    .right{
        display: inline-block;
        float: right;
    }
    .right span{
        display: block;
  
    }
    .detail{
        width: 100%;
    }
    .detail table th{
        text-align: left;
    }
    
</style>
<body>
    <div class="content-titulo">
        <h4>IMPORTACIONES CLIO NICARAGUA</h4>
        <h5>ALTAMIRA DE DONDE FUE EL BDF 1C A LAGO 1C ARRIBA CONTIGUO A ETIRROL</h5>
        <h5>81562409784214465</h5>
    </div>
    <div class="border">
        <div class="seccion_supeior">
            <div class="left">
                <span><b>Nombre Completo:</b>Cliente Prueba</span>
                <span><b>Nombre salon:</b>Salon de prueba</span>
                <span><b>Cedula:</b> 00012563950125</span>
                <span><b>Dirección:</b> Arenes 1917</span>
                <span><b>Dirección salon:</b> Arenes 1917</span>
                <span><b>Teléfono:</b> 1234567896</span>
                <span><b>Teléfono salon:</b> 4121212121</span>
            </div>
            <div class="right">
                <span><b>factura:</b> #3</span>
                <span><b>Fecha:</b> 08-03-2022</span>
                <span><b>Fecha vencimiento:</b> 31-05-2022</span>
                <span><b>Estado:</b> En proceso</span>
            </div>
        </div>

        <div class="detail">
            <table style="width: 100%">
                <thead>
                    <tr>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>plancha ultra 500</td>
                        <td>1 Ud</td>
                        <td>$155.00</td>
                    </tr>
                    <tr>
                        <td colspan="2">Total</td>
                        <td>$155.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>