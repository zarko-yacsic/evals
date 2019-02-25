<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>

</head>
<body onload="cargar();">
    <div class="tgaPreCarga" style="display:table; position:fixed; z-index:100000; width:100%; height:100%; background-color:#fff;">
        <div class="images" style="width:140px; display:table; clear:both; margin:120px auto 0px auto;">
            <img src="/images/logo tga-azul-600px.png" width="100%" alt="">
            <p style="margin:0px; text-align:center; font-size:12px; color:#ccc;">Cargando...</p>
        </div>
    </div>

    <script type="text/javascript">
        $("head").append('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">');
        $("head").append('<link rel="stylesheet" href="/css/style.css" crossorigin="anonymous">');
        $("head").append('<meta name="author" content="Víctor Paredes">');
        $("head").append('<link rel="icon" href="/images/favicon.ico">');
        $("head").append('<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"><'+ "/" +'script>');
        $("head").append('<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"><'+ "/" +'script>');
        $("head").append('<script src="/include/jquery.form.min.js"><'+ "/" +'script>');
    </script>

    <div class="cargadorLoader cargadorLoader-1">
        <div class="cubo">
            <div class="images">
                <img src="/images/ball-triangle.svg">
            </div>
            <p>Cargando</p>
        </div>
    </div>
    <div class="cargadorLoader cargadorLoader-2">.</div>

    <!-- Modal -->
    <div class="modal fade"  id="tgaSleModal2"  tabindex="-1" role="dialog" aria-labelledby="tgaSleModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div>
        </div>
    </div>

    <div id="tgaSleModal">
        <div class="modal_content">
            <div class="modal_header">
                <h5>Ocurrio un problema</h5>
                <div class="close_alert">
                    <img src="/images/close_black_18dp.png" width="22px;">
                </div>
            </div>
            <div class="modal_body">
                <div class="icono">
                    <img src="/images/peligro.png">
                </div>
                <table cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td class="texto">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal_footer">
                <p>* Puede hacer clic fuera del modal para salir</p>
            </div>
        </div>
    </div>
    <div class="tgaSleModalC">.</div>


