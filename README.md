<h1>One Step Checkout Brasil 6 Pro</h1>
**by Inovarti**

**Versão 6.0 é a mais recente do projeto One Step Checkout 2014**

[Site Oficial do projeto](http://onestepcheckout.com.br)

<h2>Developers Mantenedores</h2>

* Deivison Arthur
* Isaac Lopes
* Michel Brito
* Denis Spalenza

<h2>Novidades</h2>
* Com validação do CPF/CNPJ;
* Validação de e-mail já registrado;
* Validação de CFP/CNPJ já registrado;
* CPF/CNPJ usando o Taxvat;
* Seleção do tipo pessoa;
* Uso do campo firstname como Razão Social para seleção de PJ;
* Uso do campo lastname como Nome Fantansia para seleção de PJ;
* Opção de autenticação antes de ir para o checkout;
* Responsivo com Bootstrap;
* Uso do CSS padrão da theme Default do Magento;
* Padronização de todo o código fonte;
* Homologado em projetos com grande volume de vendas;
* Suporte as versões acima da 1.6+;
* Busca do CEP para auto complete;
* Bloqueio da edição do e-mail, CPF, CNPJ na tela de edição de endereços;

<h2>Visões de demostração do OSC 6</h2>

**1 - Registro(Cadastro)**
<img src="http://www.inovarti.com.br/osc/OSC6-Cadastro.png" alt="One Step Checkout estilizado" title="One Step Checkout estilizado" />

**Verificação se o e-mail, CPF e CNPJ são únicos, ou seja, se já possui registro**
<img src="http://www.inovarti.com.br/osc/OSC6-Cadastro-Validacao.png" alt="Verificação se o e-mail, CPF e CNPJ são únicos, ou seja, se já possui registro" title="Verificação se o e-mail, CPF e CNPJ são únicos, ou seja, se já possui registro" />

**2 - Tela do OSC Estilizada 1**
<img src="http://www.inovarti.com.br/osc/OSC6-Estilizado-Responsivo-1.png" alt="One Step Checkout estilizado 1" title="One Step Checkout estilizado 1" />

**3 - Tela do OSC Estilizada 2**
<img src="http://www.inovarti.com.br/osc/OSC6-Estilizado-Responsivo-2.png" alt="One Step Checkout estilizado 2" title="One Step Checkout estilizado 2" />

**4 - Tela do OSC Estilizada 3**
<img src="http://www.inovarti.com.br/osc/OSC6-Estilizado-Responsivo-3.png" alt="One Step Checkout estilizado 3" title="One Step Checkout estilizado 3" />

**5 - Edição do Endereço**
<img src="http://www.inovarti.com.br/osc/OSC6-Editar-Enderecos.png" alt="One Step Checkout Edição do Endereço" title="One Step Checkout Edição do Endereço" />

**5 - Informações da Conta**
<img src="http://www.inovarti.com.br/osc/OSC6-Informacoes-da-conta.png" alt="One Step Checkout Informações da Conta" title="One Step Checkout Informações da Conta" />

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
