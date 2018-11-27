'use strict';

import $ from "jquery";

import Dropzone from 'dropzone';
import 'dropzone/dist/dropzone.css'

import CrosierMasks from './crosier/CrosierMasks';

import toastr from 'toastr';

$(document).ready(function () {

    let $form = $('[name="cv"]');

    let $cargosPretendidos = $('#cv_cargosPretendidos');

    let $temFilhos = $('#cv_temFilhos');
    let $divQtdeFilhos = $('#divQtdeFilhos');
    let $qtdeFilhos = $('#cv_qtdeFilhos');
    let $dadosFilhos = $('#dadosFilhos');
    let $dadosFilhosJSON = $('#dadosFilhosJSON');

    let $jaTrabalhou = $('#cv_jaTrabalhou');
    let $divQtdeEmpregos = $('#divQtdeEmpregos');
    let $qtdeEmpregos = $('#cv_qtdeEmpregos');
    let $dadosEmpregos = $('#dadosEmpregos');
    let $dadosEmpregosJSON = $('#dadosEmpregosJSON');


    $cargosPretendidos.select2({
            placeholder: "Selecione...",
            width: '100%'
        }
    );


    // ------ FILHOS

    function showCamposFilhos() {
        let display = $temFilhos.val() === 'S' ? '' : 'none';
        $divQtdeFilhos.css('display', display);
        $dadosFilhos.css('display', display);
    }

    $temFilhos.on('change', function () {
        showCamposFilhos();
    });

    function buildCamposFilhos() {
        let qtdeFilhos = parseInt($qtdeFilhos.val());

        let dadosFilhosJSON = JSON.parse($dadosFilhosJSON.html());

        if (qtdeFilhos > 0) {

            for (let i = 1; i <= qtdeFilhos; i++) {
                let nome = dadosFilhosJSON[i - 1] ? dadosFilhosJSON[i - 1].nome : '';
                let dtNascimento = dadosFilhosJSON[i - 1] ? dadosFilhosJSON[i - 1].dtNascimento : '';
                let ocupacao = dadosFilhosJSON[i - 1] ? dadosFilhosJSON[i - 1].ocupacao : '';
                let obs = dadosFilhosJSON[i - 1] ? dadosFilhosJSON[i - 1].obs : '';
                $dadosFilhos.append(
                    `
                        <div class="card">
                            <h5 class="card-header">Filho (` + i + `)</h5>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="filho` + i + `_nome">Nome</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="filho` + i + `_nome" name="filho[` + i + `][nome]" class="form-control" value="` + nome + `">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="filho` + i + `_dtNascimento">Dt Nascimento</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="filho` + i + `_dtNascimento" name="filho[` + i + `][dtNascimento]" 
                                            class="crsr-date form-control" maxlength="10" value="` + dtNascimento + `">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="filho` + i + `_ocupacao">Ocupação</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="filho` + i + `_ocupacao" name="filho[` + i + `][ocupacao]" 
                                            class="form-control" value="` + ocupacao + `">
                                        <small class="form-text text-muted">Informe se estuda, e em qual horário.</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="filho` + i + `_obs">Cuidados</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="filho` + i + `_obs" name="filho[` + i + `][obs]" 
                                            class="form-control" value="` + obs + `">
                                        <small class="form-text text-muted">Nos horários em que não estuda, quem cuida.</small>
                                    </div>
                                </div>
                            </div>
                        </div>`
                );

            }
        }

        CrosierMasks.maskAll();

    }

    $qtdeFilhos.keyup(function () {
        // Adiciona dinamicamente os campos conforme a qtde de filhos
        $dadosFilhos.html('');
        buildCamposFilhos();
    });


    // ------ EMPREGOS

    function showCamposEmpregos() {
        let display = $jaTrabalhou.val() === 'S' ? '' : 'none';
        $divQtdeEmpregos.css('display', display);
        $dadosEmpregos.css('display', display);
    }

    $jaTrabalhou.on('change', function () {
        showCamposEmpregos();
    });

    function buildCamposEmpregos() {
        let qtdeEmpregos = parseInt($qtdeEmpregos.val());

        let dadosEmpregosJSON = JSON.parse($dadosEmpregosJSON.html());

        if (qtdeEmpregos > 0) {

            for (let i = 1; i <= qtdeEmpregos; i++) {
                let nomeEmpresa = dadosEmpregosJSON[i - 1] ? dadosEmpregosJSON[i - 1].nomeEmpresa : '';
                let localEmpresa = dadosEmpregosJSON[i - 1] ? dadosEmpregosJSON[i - 1].localEmpresa : '';
                let nomeSuperior = dadosEmpregosJSON[i - 1] ? dadosEmpregosJSON[i - 1].nomeSuperior : '';
                let cargo = dadosEmpregosJSON[i - 1] ? dadosEmpregosJSON[i - 1].cargo : '';
                let horario = dadosEmpregosJSON[i - 1] ? dadosEmpregosJSON[i - 1].horario : '';
                let admissao = dadosEmpregosJSON[i - 1] ? dadosEmpregosJSON[i - 1].admissao : '';
                let demissao = dadosEmpregosJSON[i - 1] ? dadosEmpregosJSON[i - 1].demissao : '';
                let ultimoSalario = dadosEmpregosJSON[i - 1] ? dadosEmpregosJSON[i - 1].ultimoSalario : '';
                let beneficios = dadosEmpregosJSON[i - 1] ? dadosEmpregosJSON[i - 1].beneficios : '';
                let motivoDesligamento = dadosEmpregosJSON[i - 1] ? dadosEmpregosJSON[i - 1].motivoDesligamento : '';

                $dadosEmpregos.append(
                    `
                        <div class="card">
                            <h5 class="card-header">Emprego (` + i + `)</h5>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="emprego` + i + `_nomeEmpresa">Empresa</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="emprego` + i + `_nomeEmpresa" name="emprego[` + i + `][nomeEmpresa]" class="form-control" value="` + nomeEmpresa + `">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="emprego` + i + `_localEmpresa">Onde fica</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="emprego` + i + `_localEmpresa" name="emprego[` + i + `][localEmpresa]" class="form-control" value="` + localEmpresa + `">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="emprego` + i + `_nomeSuperior">Nome do superior imediato</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="emprego` + i + `_nomeSuperior" name="emprego[` + i + `][nomeSuperior]" class="form-control" value="` + nomeSuperior + `">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="emprego` + i + `_cargo">Cargo</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="emprego` + i + `_cargo" name="emprego[` + i + `][cargo]" class="form-control" value="` + cargo + `">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="emprego` + i + `_horario">Horário</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="emprego` + i + `_horario" name="emprego[` + i + `][horario]" class="form-control" value="` + horario + `">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="emprego` + i + `_admissao">Dt Admissão</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="emprego` + i + `_admissao" name="emprego[` + i + `][admissao]" 
                                            class="crsr-date form-control" maxlength="10" value="` + admissao + `">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="emprego` + i + `_demissao">Dt Demissão</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="emprego` + i + `_demissao" name="emprego[` + i + `][demissao]" 
                                            class="crsr-date form-control" maxlength="10" value="` + demissao + `">
                                        <small class="form-text text-muted">Não informar caso ainda esteja trabalhando</small>
                                    </div>
                                </div>
                                
                                <div class="form-group row" style="">
                                    <label class="col-form-label col-sm-2" for="emprego` + i + `_ultimoSalario">Último Salário</label>
                                    <div class="col-sm-10">
                                        <div class="input-group"><div class="input-group-prepend"><span class="input-group-text">R$ </span></div>
                                        <input type="text" id="emprego` + i + `_ultimoSalario" name="emprego[` + i + `][ultimoSalario]" 
                                            class="crsr-money form-control" value="` + ultimoSalario + `">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="emprego` + i + `_beneficios">Benefícios</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="emprego` + i + `_beneficios" name="emprego[` + i + `][beneficios]" class="form-control" value="` + beneficios + `">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2" for="emprego` + i + `_motivoDesligamento">Motivo do Desligamento</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="emprego` + i + `_motivoDesligamento" name="emprego[` + i + `][motivoDesligamento]" class="form-control" value="` + motivoDesligamento + `">
                                    </div>
                                </div>
                                
                            </div>
                        </div>`
                );

            }
        }

        CrosierMasks.maskAll();

    }

    $qtdeEmpregos.change(function () {
        // Adiciona dinamicamente os campos conforme a qtde de empregos
        $dadosEmpregos.html('');
        buildCamposEmpregos();
    });

    $('.btnSalvarAnchored').click(function () {
        let div = $(this).data('div');
        let formActionOrig = $form.attr('action') ? $form.attr('action') : '';
        if (div !== '') {
            $form.attr('action', formActionOrig + '#' + div);
        }
        $form.submit();
    });



    function buildDropZone() {

        if ($('#divDropzone').length) {

            let dropzone = new Dropzone("#divDropzone",
                {
                    previewTemplate: $('#dzTemplate').html(),
                    clickable: '#dz-clickable',
                    url: "/uploadFoto",
                    maxFilesize: 6,
                    maxFiles: 1,
                    acceptedFiles: 'image/*',
                    resizeWidth: 800,
                    dictDefaultMessage: 'dictDefaultMessage',
                    dictFallbackMessage: 'dictFallbackMessage',
                    dictFallbackText: 'dictFallbackText',
                    dictFileTooBig: 'dictFileTooBig',
                    dictInvalidFileType: 'dictInvalidFileType',
                    dictResponseError: 'dictResponseError',
                    dictCancelUpload: 'dictCancelUpload',
                    dictUploadCanceled: 'dictUploadCanceled',
                    dictCancelUploadConfirmation: 'dictCancelUploadConfirmation',
                    dictRemoveFile: 'dictRemoveFile',
                    dictRemoveFileConfirmation: 'dictRemoveFileConfirmation',
                    dictMaxFilesExceeded: 'dictMaxFilesExceeded',
                    dictFileSizeUnits: 'dictFileSizeUnits'
                });

            dropzone.on("addedfile", function (file) {
                if (file.size > (2 * 1024 * 1024)) {
                    console.log('muito grande');
                    toastr.warning('Arquivo muito grande. Máximo: 6MB!');
                    this.removeFile(file);
                } else {
                    $('#dz-clickable').css('display', 'none');
                }
            });
            dropzone.on("removedfile", function (file) {
                $('#dz-clickable').css('display', '');
            });
            dropzone.on("maxfilesexceeded", function (file) {
                this.removeFile(file);
            });


            window.dzRemoveAll = function () {
                dropzone.removeAllFiles(true);
                $.post('/deleteFoto');
            }
        }

    }

    function checkStatus() {
        // pega o status do <span>
        let status = $('#cvStatus').html();
        if (status === 'F') {
            // escondo todos os botões de ação
            $('.CV_BTN_ACTION').css('display','none');
            // desabilito todos os inputs (isso por causa dos gerados dinamicamente, pois os fixos são desabilitados no CVType)
            $('input[type=text]').attr('disabled', 'true');
        }
    }

    // --------

    showCamposFilhos();
    buildCamposFilhos();

    showCamposEmpregos();
    buildCamposEmpregos();

    buildDropZone();

    checkStatus();


});