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

        $builder->add('id', HiddenType::class, array(
            'required' => false
        ));

        $builder->add('updated', DateType::class, array(
            'label' => 'Data do currículo',
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'attr' => array(
                'class' => 'crsr-date',
                'readonly' => true
            ),
            'required' => true
        ));

        $builder->add('cargosPretendidos', EntityType::class, array(
            'label' => 'Cargos pretendidos',
            'class' => Cargo::class,
            'choices' => $this->doctrine->getRepository(Cargo::class)->findAll(WhereBuilder::buildOrderBy('cargo')),
            'multiple' => true,
            'choice_label' => 'cargo',
            'expanded' => false,
            'help' => 'Selecione cada um dos cargos pretendidos (caso mais de um)'
        ));

        $builder->add('cpf', TextType::class, array(
            'label' => 'CPF',
            'attr' => array(
                'class' => 'cpf',
                'readonly' => true
            ),
            'required' => true
        ));

        $builder->get('cpf')
            ->addModelTransformer(new CallbackTransformer(
                function ($obj) {
                    // transform the array to a string
                    return $obj;
                },
                function ($str) {
                    // transform the string back to an array
                    // return explode(', ', $str);
                    return preg_replace('/[^\d]/', '', $str);
                }
            ))
        ;

        $builder->add('email', EmailType::class, array(
            'label' => 'E-mail',
            'attr' => array(
                'class' => 'email',
                'readonly' => true
            ),
            'required' => true
        ));

        $builder->add('nome', TextType::class, array(
            'label' => 'Nome',
            'required' => true
        ));

        $builder->add('dtNascimento', DateType::class, array(
            'widget' => 'single_text',
            'required' => false,
            'format' => 'dd/MM/yyyy',
            'label' => 'Dt Nascimento',
            'attr' => array(
                'class' => 'crsr-date'
            )
        ));

        $builder->add('naturalidade', TextType::class, array(
            'label' => 'Em que cidade nasceu'
        ));

        for ($i = 1; $i <= 5; $i++) {
            $builder->add('telefone' . $i, TextType::class, array(
                'label' => 'Fone (' . $i . ')',
                'attr' => array(
                    'class' => 'telefone'
                ),
                'required' => false
            ));
            $builder->add('telefone' . $i . 'Tipo', ChoiceType::class, array(
                'label' => 'Fone (' . $i . ') Tipo',
                'choices' => array(
                    'CELULAR COM WHATSAPP' => 'CELULAR COM WHATSAPP',
                    'CELULAR SEM WHATSAPP' => 'CELULAR SEM WHATSAPP',
                    'RESIDENCIAL' => 'RESIDENCIAL',
                    'COMERCIAL' => 'COMERCIAL',
                ),
                'required' => false
            ));
        }

        $builder->add('enderecoAtualLogr', TextType::class, array(
            'label' => 'Logradouro',
            'required' => false
        ));
        $builder->add('enderecoAtualNumero', TextType::class, array(
            'label' => 'Número',
            'required' => false
        ));
        $builder->add('enderecoAtualCompl', TextType::class, array(
            'label' => 'Complemento',
            'required' => false
        ));
        $builder->add('enderecoAtualBairro', TextType::class, array(
            'label' => 'Bairro',
            'required' => false
        ));
        $builder->add('enderecoAtualCidade', TextType::class, array(
            'label' => 'Cidade',
            'required' => false
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
            'required' => false
        ));
        $builder->add('enderecoAtualTempoResid', TextType::class, array(
            'label' => 'Tempo em que reside',
            'required' => false
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
            'required' => false
        ));

        $builder->add('conjugeNome', TextType::class, array(
            'label' => 'Nome do cônjuge',
            'required' => false
        ));
        $builder->add('conjugeProfissao', TextType::class, array(
            'label' => 'Profissão do cônjuge',
            'required' => false
        ));

        $builder->add('temFilhos', ChoiceType::class, array(
            'label' => 'Filhos',
            'choices' => array(
                'Sim' => 'S',
                'Não' => 'N'
            ),
            'required' => false
        ));

        $builder->add('qtdeFilhos', IntegerType::class, array(
            'label' => 'Qtde Filhos',
            'required' => false
        ));

        $builder->add('paiNome', TextType::class, array(
            'label' => 'Nome do pai',
            'required' => false,
            'help' => 'Caso desconhecido, não informar.'
        ));
        $builder->add('paiProfissao', TextType::class, array(
            'label' => 'Profissão do pai',
            'required' => false,
            'help' => 'Caso aposentado ou falecido, informe.'
        ));
        $builder->add('maeNome', TextType::class, array(
            'label' => 'Nome da mãe',
            'required' => false,
            'help' => 'Caso desconhecida, não informar.'
        ));
        $builder->add('maeProfissao', TextType::class, array(
            'label' => 'Profissão da mãe',
            'required' => false,
            'help' => 'Caso aposentada ou falecida, informe.'
        ));


        $builder->add('referencia1Nome', TextType::class, array(
            'label' => 'Nome',
            'required' => false
        ));
        $builder->add('referencia1Relacao', TextType::class, array(
            'label' => 'Relação',
            'required' => false,
            'help' => 'Informar o tipo de relação: amigo, vizinho, familiar, colega de trabalho, etc.'
        ));
        $builder->add('referencia1Telefone1', TextType::class, array(
            'label' => 'Fone (1)',
            'attr' => array(
                'class' => 'telefone'
            ),
            'required' => false
        ));
        $builder->add('referencia1Telefone2', TextType::class, array(
            'label' => 'Fone (2)',
            'attr' => array(
                'class' => 'telefone'
            ),
            'required' => false
        ));
        $builder->add('referencia2Nome', TextType::class, array(
            'label' => 'Nome',
            'required' => false
        ));
        $builder->add('referencia2Relacao', TextType::class, array(
            'label' => 'Relação',
            'required' => false,
            'help' => 'Informar o tipo de relação: amigo, vizinho, familiar, colega de trabalho, etc.'
        ));
        $builder->add('referencia2Telefone1', TextType::class, array(
            'label' => 'Fone (1)',
            'attr' => array(
                'class' => 'telefone'
            ),
            'required' => false
        ));
        $builder->add('referencia2Telefone2', TextType::class, array(
            'label' => 'Fone (2)',
            'attr' => array(
                'class' => 'telefone'
            ),
            'required' => false
        ));


        $builder->add('ensinoFundamentalStatus', ChoiceType::class, array(
            'label' => 'Ensino Fundamental',
            'choices' => array(
                'Não cursado' => 'NC',
                'Cursando' => 'CR',
                'Incompleto' => 'I',
                'Concluído' => 'C'
            ),
            'required' => false
        ));
        $builder->add('ensinoFundamentalLocal', TextType::class, array(
            'label' => 'Escola',
            'required' => false
        ));

        $builder->add('ensinoMedioStatus', ChoiceType::class, array(
            'label' => 'Ensino Médio',
            'choices' => array(
                'Não cursado' => 'NC',
                'Cursando' => 'CR',
                'Incompleto' => 'I',
                'Concluído' => 'C'
            ),
            'required' => false
        ));
        $builder->add('ensinoMedioLocal', TextType::class, array(
            'label' => 'Escola',
            'required' => false
        ));

        $builder->add('ensinoSuperiorStatus', ChoiceType::class, array(
            'label' => 'Ensino Superior',
            'choices' => array(
                'Não cursado' => 'NC',
                'Cursando' => 'CR',
                'Incompleto' => 'I',
                'Concluído' => 'C'
            ),
            'required' => false
        ));
        $builder->add('ensinoSuperiorLocal', TextType::class, array(
            'label' => 'Faculdade/Curso',
            'required' => false,
            'required' => 'Informar a faculdade e o curso'
        ));

        $builder->add('ensinoDemaisObs', TextareaType::class, array(
            'label' => 'Outros',
            'required' => false,
            'help' => 'Caso tenha cursado, informe aqui sobre cursos técnicos, pós-graduações, mestrados, etc'
        ));

        $builder->add('conheceAEmpresaTempo', TextType::class, array(
            'label' => 'Há quanto tempo conhece nossa empresa?',
            'required' => false,
            'help' => 'Informe também caso ainda não conheça'
        ));

        $builder->add('ehNossoCliente', ChoiceType::class, array(
            'label' => 'É nosso cliente?',
            'choices' => array(
                'Sim' => 'S',
                'Não' => 'N'
            ),
            'required' => false
        ));

        $builder->add('parente1FichaCrediarioNome', TextType::class, array(
            'label' => 'Nome (1)',
            'required' => false
        ));
        $builder->add('parente2FichaCrediarioNome', TextType::class, array(
            'label' => 'Nome (2)',
            'required' => false
        ));

        $builder->add('conhecido1TrabalhouNaEmpresa', TextType::class, array(
            'label' => 'Nome (1)',
            'required' => false
        ));

        $builder->add('conhecido2TrabalhouNaEmpresa', TextType::class, array(
            'label' => 'Nome (2)',
            'required' => false
        ));

        $builder->add('motivosQuerTrabalharAqui', TextareaType::class, array(
            'label' => 'Por quais motivos deseja trabalhar em nossa empresa?',
            'required' => false
        ));


        $builder->add('jaTrabalhou', ChoiceType::class, array(
            'label' => 'Já trabalhou?',
            'choices' => array(
                'Sim' => 'S',
                'Não' => 'N'
            ),
            'required' => false
        ));

        $builder->add('qtdeEmpregos', IntegerType::class, array(
            'label' => 'Qtde Empregos',
            'required' => false
        ));



    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CV::class
        ));
    }
}