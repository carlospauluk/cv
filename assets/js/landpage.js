import $ from "jquery";

$(document).ready(function () {

    let $cpf = $('#cpf');
    let $password = $('#password');
    let $password2 = $('#password2');
    let $btnNovo = $('#btnNovo');
    let $alertSenhas = $('#alertSenhas');

    let $btnEsqueciMinhaSenha = $('#btnEsqueciMinhaSenha');
    let $form = $('#loginForm');


    $('#password,#password2').keyup(function() {
        console.log($password.val());
        console.log($password2.val());
        if ($password.val() !== $password2.val()) {
           $btnNovo.prop('disabled', true);
           $alertSenhas.removeClass('hide').addClass('show');
        } else {
            $btnNovo.prop('disabled', false);
            $alertSenhas.removeClass('show').addClass('hide');
        }
    });


    $btnEsqueciMinhaSenha.click(function() {
        let form = $('<form></form>').attr("method", "post");
        form.append($cpf);
        form.append($('<input></input>').attr("type", "hidden").attr(
            "name", "btnEsqueciMinhaSenha").val("true"));
        $(form).appendTo('body').submit();
    });





});