'use strict';

import $ from "jquery";


import CrosierMasks from './crosier/CrosierMasks';

$(document).ready(function () {

    let $cargosPretendidos = $('#cv_cargosPretendidos');
    let $temFilhos = $('#cv_temFilhos');
    let $divQtdeFilhos = $('#divQtdeFilhos');
    let $qtdeFilhos = $('#qtdeFilhos');
    let $dadosFilhos = $('#dadosFilhos');

    $cargosPretendidos.select2({
            placeholder: "Selecione...",
            width: '100%'
        }
    );

    $temFilhos.on('change', function () {
        let display = $temFilhos.val() === 'S' ? '' : 'none';
        $divQtdeFilhos.css('display', display);
        $dadosFilhos.css('display', display);
    });


    $qtdeFilhos.keyup(function () {
        // Adiciona dinamicamente os campos conforme a qtde de filhos
        $dadosFilhos.html('');

        let qtdeFilhos = parseInt($qtdeFilhos.val());

        if (qtdeFilhos > 1) {

            for (let i = 1; i <= $qtdeFilhos.val(); i++) {
                $dadosFilhos.append(
                            `
                        <div class="card">
                            <h5 class="card-header">Filho (` + i + `)</h5>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="filho` + i + `_nome">Nome</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="filho` + i + `_nome" name="filho[` + i + `][nome]" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="filho` + i + `_dtNascimento">Dt Nascimento</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="filho` + i + `_dtNascimento" name="filho[` + i + `][dtNascimento]" class="crsr-date form-control" maxlength="10">
                                    </div>
                                </div>
                            </div>
                        </div>`
                );

            }
        }

        CrosierMasks.maskAll();

    });

});