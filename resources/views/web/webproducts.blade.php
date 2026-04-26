@extends('layouts.web')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="">
                <div class="card">
                    <div class="card-header">
                        <div>&nbsp;</div>
                        <div>&nbsp;</div>
                        <div class="text-center">
                            <h4>Paquetes</h4>
                            <h5></h5>

                        </div>
                    </div>
                    <div class="card-body">
                        <a href="/web/products/simple" class="h5">Paquete Simple</a>
                        <h6>El cliente mediante la carta digital puede hacer su pedido, y recogerlo en su local. </h6>

                        <a href="/web/products/standard" class="h5">Paquete Estandard</a>
                        <h6>El cliente escanea el codigo QR inteligente, que le abre directamente la carta digital con su mesa configurada. Hace una seleccion desde la carta digital y cursa el pedido. En la barra y/o cocina salen los tickets de los pedidos para que los camareros lo sirven.</h6>

                        <a href="/web/products/premium" class="h5">Paquete Premium</a>
                        <h6>100% integrado en nuestro POS, con sus modulos de stock, reservas, informes. Haz que los clientes piden que se integra con el POS a 100%. Haz que sus camaeras pueden usar cualquier dispositivo con interet para tomar nota, corregir pedidos de los clientes.</h6>
                        <h6>Permite ofrecer a sus clientes que piden desde la mesa, en su casa para recoger o para que se lo llevan.</h6>

                        <h6>Haz que cada persona en la mesa puede añadir sus propias gustas, comentarias y ver y pedir la cuenta todo desde su movil.</h6>

                    </div>
                    <style type="text/css">
                        .table {
                            display: table;
                        }

                        .row {
                            display: table-row;
                        }

                        .column {
                            display: table-cell;
                            vertical-align: top;
                        }
                    </style>

                    <div>
                        <div>
                            <div class="h5 strong">BENEFICIOS para el ESTABLECIMIENTO</div>

                        </div>
                        <div>
                            <div>
                                <b>AUMENTA LA SEGURIDAD DE TU EQUIPO Y DE TUS CLIENTES<br></b>
                                Ayuda a mantener la distancia de seguridad y las condiciones de higiene. Minimiza el
                                contacto y optimiza tu servicio gracias a los pedidos y los avisos desde el
                                móvil<br>
                                <br>
                                <b>100% PERSONALIZADA<br></b>
                                Con imágenes, idiomas, dirección, web, teléfono, email, horario y ordenando los
                                platos y secciones de tu carta como te interese.
                                <br><br>
                                <b>AUMENTE LA VENTA<br></b>
                                Muchas veces los clientes por no esperar no repiten o piden postre. Con el movil en la mano los clientes siguen viendo su oferta y comentado con sus acompañantes.
                                <br><br>
                                <b>REDUCCIÓN DE TIEMPOS Y COSTES<br></b>
                                Optimiza tus recursos e impulsa tu negocio. Gestiona toda tu carta, pedidos y avisos
                                con los pedidos online al momento y aumenta tus márgenes.
                            </div>
                            <div>&nbsp;</div>
                            <div>&nbsp;</div>
                            <div class="h5 strong">BENEFICIOS para los CLIENTES</div>
                            <div>
                                <b>REDUCE LOS TIEMPOS DE ESPERA<br></b>
                                Consulta el menú digital con los productos, imágenes e ingredientes al llegar al
                                establecimiento y avisa al camarero en un clic si tienes cualquier duda.
                                <br><br>
                                <b>PIDE CUANDO QUIERAS<br></b>
                                Pide platos, menús y bebidas al momento desde la Carta Digital manteniendo la
                                distancia de seguridad
                                <br><br>
                                <b>UNA IMÁGEN VALE MÁS QUE MIL PALABRAS<br></b>
                                Una buena comida comienza con una gran presentación, abre el apetito de tus clientes
                                con las fotos de tus productos.
                                <br><br>
                                <b>MENÚ DIGITAL MULTIDIOMA<br></b>
                                Lee el menú digital del restaurante en diferentes idiomas.
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
