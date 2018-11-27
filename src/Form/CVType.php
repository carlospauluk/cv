<?php

namespace App\Form;

use App\Entity\Cargo;
use App\Entity\CV;
use App\Utils\Repository\WhereBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MovimentacaoType.
 *
 * Form para movimentações.
 *
 * @package App\Form\Financeiro
 */
class CVType extends AbstractType
{
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $cv = $event->getData();
            $builder = $event->getForm();

            $disabled = (($cv) and ($cv->getStatus() == 'F')) ? true : false;

            $builder->add('id', HiddenType::class, array(
                'required' => false,
                'disabled' => $disabled
            ));

            $builder->add('updated', DateType::class, array(
                'label' => 'Data do currículo',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy HH:mm',
                'attr' => array(
                    'class' => 'crsr-datetime',
                    'readonly' => true
                ),
                'required' => true,
                'disabled' => $disabled
            ));

            $builder->add('cargosPretendidos', EntityType::class, array(
                'label' => 'Cargos pretendidos',
                'class' => Cargo::class,
                'choices' => $this->doctrine->getRepository(Cargo::class)->findAll(WhereBuilder::buildOrderBy('cargo')),
                'multiple' => true,
                'choice_label' => 'cargo',
                'expanded' => false,
                'help' => 'Selecione cada um dos cargos pretendidos (caso mais de um)',
                'disabled' => $disabled
            ));

            $builder->add('cpf', TextType::class, array(
                        'label' => 'CPF',
                        'attr' => array(
                            'class' => 'cpf',
                            'readonly' => true
                        ),
                        'required' => true,
                        'disabled' => $disabled
                    )
            );

            $builder->add('email', EmailType::class, array(
                'label' => 'E-mail',
                'attr' => array(
                    'class' => 'email',
                    'readonly' => true
                ),
                'required' => true,
                'disabled' => $disabled
            ));

            $builder->add('nome', TextType::class, array(
                'label' => 'Nome',
                'required' => true,
                'disabled' => $disabled
            ));

            $builder->add('dtNascimento', DateType::class, array(
                'widget' => 'single_text',
                'required' => false,
                'format' => 'dd/MM/yyyy',
                'label' => 'Dt Nascimento',
                'attr' => array(
                    'class' => 'crsr-date'
                ),
                'disabled' => $disabled
            ));

            $builder->add('naturalidade', TextType::class, array(
                'label' => 'Em que cidade nasceu',
                'disabled' => $disabled
            ));

            for ($i = 1; $i <= 5; $i++) {
                $builder->add('telefone' . $i, TextType::class, array(
                    'label' => 'Fone (' . $i . ')',
                    'attr' => array(
                        'class' => 'telefone'
                    ),
                    'required' => false,
                    'disabled' => $disabled
                ));
                $builder->add('telefone' . $i . 'Tipo', ChoiceType::class, array(
                    'label' => 'Fone (' . $i . ') Tipo',
                    'choices' => array(
                        'CELULAR COM WHATSAPP' => 'CELULAR COM WHATSAPP',
                        'CELULAR SEM WHATSAPP' => 'CELULAR SEM WHATSAPP',
                        'RESIDENCIAL' => 'RESIDENCIAL',
                        'COMERCIAL' => 'COMERCIAL',
                    ),
                    'required' => false,
                    'disabled' => $disabled
                ));
            }

            $builder->add('enderecoAtualLogr', TextType::class, array(
                'label' => 'Logradouro',
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('enderecoAtualNumero', TextType::class, array(
                'label' => 'Número',
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('enderecoAtualCompl', TextType::class, array(
                'label' => 'Complemento',
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('enderecoAtualBairro', TextType::class, array(
                'label' => 'Bairro',
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('enderecoAtualCidade', TextType::class, array(
                'label' => 'Cidade',
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('enderecoAtualUf', ChoiceType::class, array(
                'label' => 'Estado',
                'choices' => array(
                    'Selecione...' => '',
                    'Acre' => 'AC',
                    'Alagoas' => 'AL',
                    'Amapá' => 'AP',
                    'Amazonas' => 'AM',
                    'Bahia' => 'BA',
                    'Ceará' => 'CE',
                    'Distrito Federal' => 'DF',
                    'Espírito Santo' => 'ES',
                    'Goiás' => 'GO',
                    'Maranhão' => 'MA',
                    'Mato Grosso' => 'MT',
                    'Mato Grosso do Sul' => 'MS',
                    'Minas Gerais' => 'MG',
                    'Pará' => 'PA',
                    'Paraíba' => 'PB',
                    'Paraná' => 'PR',
                    'Pernambuco' => 'PE',
                    'Piauí' => 'PI',
                    'Rio de Janeiro' => 'RJ',
                    'Rio Grande do Norte' => 'RN',
                    'Rio Grande do Sul' => 'RS',
                    'Rondônia' => 'RO',
                    'Roraima' => 'RR',
                    'Santa Catarina' => 'SC',
                    'São Paulo' => 'SP',
                    'Sergipe' => 'SE',
                    'Tocantins' => 'TO'
                ),
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('enderecoAtualTempoResid', TextType::class, array(
                'label' => 'Tempo em que reside',
                'required' => false,
                'disabled' => $disabled
            ));


            $builder->add('estadoCivil', ChoiceType::class, array(
                'label' => 'Estado Civil',
                'choices' => array(
                    'Solteiro(a)' => 'SOLTEIRO',
                    'Casado(a)' => 'CASADO',
                    'Viúvo(a)' => 'VIUVO',
                    'Separado(a)' => 'SEPARADO',
                    'Divorciado(a)' => 'DIVORCIDADO'
                ),
                'required' => false,
                'disabled' => $disabled
            ));

            $builder->add('conjugeNome', TextType::class, array(
                'label' => 'Nome do cônjuge',
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('conjugeProfissao', TextType::class, array(
                'label' => 'Profissão do cônjuge',
                'required' => false,
                'disabled' => $disabled
            ));

            $builder->add('temFilhos', ChoiceType::class, array(
                'label' => 'Filhos',
                'choices' => array(
                    'Sim' => 'S',
                    'Não' => 'N'
                ),
                'required' => false,
                'disabled' => $disabled
            ));

            $builder->add('qtdeFilhos', IntegerType::class, array(
                'label' => 'Qtde Filhos',
                'required' => false,
                'disabled' => $disabled
            ));

            $builder->add('paiNome', TextType::class, array(
                'label' => 'Nome do pai',
                'required' => false,
                'help' => 'Caso desconhecido, não informar.',
                'disabled' => $disabled
            ));
            $builder->add('paiProfissao', TextType::class, array(
                'label' => 'Profissão do pai',
                'required' => false,
                'help' => 'Caso aposentado ou falecido, informe.'
            ));
            $builder->add('maeNome', TextType::class, array(
                'label' => 'Nome da mãe',
                'required' => false,
                'help' => 'Caso desconhecida, não informar.',
                'disabled' => $disabled
            ));
            $builder->add('maeProfissao', TextType::class, array(
                'label' => 'Profissão da mãe',
                'required' => false,
                'help' => 'Caso aposentada ou falecida, informe.',
                'disabled' => $disabled
            ));


            $builder->add('referencia1Nome', TextType::class, array(
                'label' => 'Nome',
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('referencia1Relacao', TextType::class, array(
                'label' => 'Relação',
                'required' => false,
                'help' => 'Informar o tipo de relação: amigo, vizinho, familiar, colega de trabalho, etc.',
                'disabled' => $disabled
            ));
            $builder->add('referencia1Telefone1', TextType::class, array(
                'label' => 'Fone (1)',
                'attr' => array(
                    'class' => 'telefone'
                ),
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('referencia1Telefone2', TextType::class, array(
                'label' => 'Fone (2)',
                'attr' => array(
                    'class' => 'telefone'
                ),
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('referencia2Nome', TextType::class, array(
                'label' => 'Nome',
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('referencia2Relacao', TextType::class, array(
                'label' => 'Relação',
                'required' => false,
                'help' => 'Informar o tipo de relação: amigo, vizinho, familiar, colega de trabalho, etc.',
                'disabled' => $disabled
            ));
            $builder->add('referencia2Telefone1', TextType::class, array(
                'label' => 'Fone (1)',
                'attr' => array(
                    'class' => 'telefone'
                ),
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('referencia2Telefone2', TextType::class, array(
                'label' => 'Fone (2)',
                'attr' => array(
                    'class' => 'telefone'
                ),
                'required' => false,
                'disabled' => $disabled
            ));


            $builder->add('ensinoFundamentalStatus', ChoiceType::class, array(
                'label' => 'Ensino Fundamental',
                'choices' => array(
                    'Não cursado' => 'NC',
                    'Cursando' => 'CR',
                    'Incompleto' => 'I',
                    'Concluído' => 'C'
                ),
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('ensinoFundamentalLocal', TextType::class, array(
                'label' => 'Escola',
                'required' => false,
                'disabled' => $disabled
            ));

            $builder->add('ensinoMedioStatus', ChoiceType::class, array(
                'label' => 'Ensino Médio',
                'choices' => array(
                    'Não cursado' => 'NC',
                    'Cursando' => 'CR',
                    'Incompleto' => 'I',
                    'Concluído' => 'C'
                ),
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('ensinoMedioLocal', TextType::class, array(
                'label' => 'Escola',
                'required' => false,
                'disabled' => $disabled
            ));

            $builder->add('ensinoSuperiorStatus', ChoiceType::class, array(
                'label' => 'Ensino Superior',
                'choices' => array(
                    'Não cursado' => 'NC',
                    'Cursando' => 'CR',
                    'Incompleto' => 'I',
                    'Concluído' => 'C'
                ),
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('ensinoSuperiorLocal', TextType::class, array(
                'label' => 'Faculdade/Curso',
                'required' => false,
                'help' => 'Informar a faculdade e o curso',
                'disabled' => $disabled
            ));

            $builder->add('ensinoDemaisObs', TextareaType::class, array(
                'label' => 'Outros',
                'required' => false,
                'help' => 'Caso tenha cursado, informe aqui sobre cursos técnicos, pós-graduações, mestrados, etc',
                'disabled' => $disabled
            ));

            $builder->add('conheceAEmpresaTempo', TextType::class, array(
                'label' => 'Há quanto tempo conhece nossa empresa?',
                'required' => false,
                'help' => 'Informe também caso ainda não conheça',
                'disabled' => $disabled
            ));

            $builder->add('ehNossoCliente', ChoiceType::class, array(
                'label' => 'É nosso cliente?',
                'choices' => array(
                    'Sim' => 'S',
                    'Não' => 'N'
                ),
                'required' => false,
                'disabled' => $disabled
            ));

            $builder->add('parente1FichaCrediarioNome', TextType::class, array(
                'label' => 'Nome (1)',
                'required' => false,
                'disabled' => $disabled
            ));
            $builder->add('parente2FichaCrediarioNome', TextType::class, array(
                'label' => 'Nome (2)',
                'required' => false,
                'disabled' => $disabled
            ));

            $builder->add('conhecido1TrabalhouNaEmpresa', TextType::class, array(
                'label' => 'Nome (1)',
                'required' => false,
                'disabled' => $disabled
            ));

            $builder->add('conhecido2TrabalhouNaEmpresa', TextType::class, array(
                'label' => 'Nome (2)',
                'required' => false,
                'disabled' => $disabled
            ));

            $builder->add('motivosQuerTrabalharAqui', TextareaType::class, array(
                'label' => 'Por quais motivos deseja trabalhar em nossa empresa?',
                'attr' => array(
                    'rows' => '5'
                ),
                'required' => false,
                'disabled' => $disabled
            ));


            $builder->add('jaTrabalhou', ChoiceType::class, array(
                'label' => 'Já trabalhou?',
                'choices' => array(
                    'Sim' => 'S',
                    'Não' => 'N'
                ),
                'required' => false,
                'disabled' => $disabled
            ));

            $builder->add('qtdeEmpregos', IntegerType::class, array(
                'label' => 'Qtde Empregos',
                'required' => false,
                'disabled' => $disabled
            ));

        });





    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CV::class
        ));
    }
}