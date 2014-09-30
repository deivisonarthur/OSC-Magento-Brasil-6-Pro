<h1>One Step Checkout Brasil 6 Pro</h1>
**by Inovarti**

**Versão 6.0 é a mais recente do projeto One Step Checkout 2014**

[Site Oficial do projeto](http://onestepcheckout.com.br)

<h2>Developers Mantenedores</h2>

* Deivison Arthur
* Isaac Lopes
* Michel Brito
* Denis Spalenza

<h2>Visões de demostração do OSC 6</h2>

**Registro(Cadastro)**
* Com validação do CPF/CNPJ;
* Validação de e-mail já registrado;
* Validação de CFP/CNPJ já registrado;
* CPF/CNPJ usando o Taxvat;
* Seleção do tipo pessoa;

<img src="http://www.inovarti.com.br/osc/OSC6-Cadastro.png" alt="One Step Checkout estilizado" title="One Step Checkout estilizado" />
**Verificação se o e-mail, CPF e CNPJ são únicos, ou seja, se já possui registro**
<img src="http://www.inovarti.com.br/osc/OSC6-Cadastro-Validacao.png" alt="Verificação se o e-mail, CPF e CNPJ são únicos, ou seja, se já possui registro" title="Verificação se o e-mail, CPF e CNPJ são únicos, ou seja, se já possui registro" />

<h2>Tutorial e Observações</h2>

<blockquote>
street1 = endereço
street2 = numero
street3 = complemento
street4 = bairro
</blockquote>
**********************************************************************************************
Primeiro passo


Habilitar o Estado como obrigatorio

Admin : Sistema > Configuração "Aba Geral " > Opção de estado "Estado é necessário para" > seleciona Brasil

==============================
Campo Fax estou usando como Celular

**********************************************************************************************
Segundo passo

Habilitar o taxvat em clientes em:

Sistema > Configuração > Clientes > Configuração > "Opções ao Criar Nova Conta" > Exibir CPF/CNPJ no Frontend:Sim
Sistema > Configuração > Clientes > Configuração > "Opções de Nome e Endereço" > 
    Número de Linhas p/ Endereço:4
    Exibir Data de Nascimento:Opcional
    Exibir CPF/CNPJ:Obrigatorio
    Exibir Sexo:Opcional

**********************************************************************************************
OBSERVAÇÕES



**********************************************************************************************
CRIDO UM RADIO E OCULTADO O TIPO DE PESSOAS, O QUE CADASTRA É O TIPO DE PESSOA O RADIO É APENAS VISUAL
**********************************************************************************************
PARA BILLING OU SHIPPING

<blockquote>
<?php $_tipopessoa = $this->getLayout()->createBlock('customer/widget_tipopessoa') ?>
<?php if ($_tipopessoa->isEnabled()): ?>
<li class="control"><?php echo $_tipopessoa->setTipopessoa($this->getDataFromSession('tipopessoa'))->setFieldIdFormat('billing:%s')->setFieldNameFormat('billing[%s]')->toHtml() ?></li>
<?php endif ?>
</blockquote>


OU SIMPLESMENTE

<?php $_tipopessoa = $this->getLayout()->createBlock('customer/widget_tipopessoa') ?>

<?php if ($_tipopessoa->isEnabled()): ?><li><?php echo $_tipopessoa->setTipopessoa($this->getCustomer()->getTipopessoa())->toHtml() ?></li><?php endif ?>
**********************************************************************************************
Campo celular

<?php $_celular = $this->getLayout()->createBlock('customer/widget_celular') ?>

<?php if ($_celular->isEnabled()): ?>

    <div class="field">
        <?php echo $_celular->setDate($this->getQuote()->getCustomerCelular())->setFieldIdFormat('billing:%s')->setFieldNameFormat('billing[%s]')->toHtml() ?>
    </div>

<?php endif ?>

**********************************************************************************************
CNPJ

firstname= Razão social

lastname = Nome Fantansia

**********************************************************************************************
