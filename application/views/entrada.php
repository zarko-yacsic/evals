
<section class="entrada-tga">
    <form class="form-signin" id="miForm" action="<?php print(base_url());?>entrada/login" method="post">
        <img class="mb-4" src="/images/logo tga-azul-600px.png" alt="" width="120px" >
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" value="" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" value="" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <div class="checkbox mb-3">
        <label>
        <input type="checkbox" value="remember-me"> Remember me
        </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Ingresar</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2018-2019</p>
    </form>
</section>

<script type="text/javascript">
$(document).ready(function() {
    var options = {
        target:        '#oculto',
        beforeSubmit:  showRequest,
        success:       showResponse
    };
    $('#miForm').submit(function() {
        loaderTgaSolutions(1);
        $(this).ajaxSubmit(options);
        return false;
    });
});
</script>


<script type="text/javascript">
$("head").append('<link rel="stylesheet" href="/css/entrada.css" crossorigin="anonymous">');
</script>
