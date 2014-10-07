<h1>One Step Checkout Brasil 6 Pro</h1>
**[powered by](http://www.inovarti.com.br)**

<img src="http://www.inovarti.com.br/osc/inovarti.png" alt="Inovarti One Step Checkout Brasil 6" title="Inovarti One Step Checkout Brasil 6" />

O projeto OCS-Magento-Brasil é uma iniciativa que visa o desenvolvimento de um módulo de OSC (One Step Checkout) para o Magento incluindo opções e configurações básicas para o funcionamento nas lojas brasileiras, tais como: validações, formatações, CPF, CNPJ, Celular, busca CEP,...

**Versão 6.0 é a mais recente do projeto One Step Checkout 2014**

<h2>Links Úteis</h2>
* [Blog www.deivison.com.br](http://www.deivison.com.br)
* [Startup www.inovarti.com.br](http://www.inovarti.com.br)
* [Site Oficial do projeto www.onestepcheckout.com.br](http://onestepcheckout.com.br)

<h2>Developers Mantenedores</h2>

* Deivison Arthur
* Isaac Lopes
* Michel Brito
* Denis Spalenza

<h2>Novidades</h2>

**Obs: O módulo foi totalmente rescrito visando a padronização, aumento das conversões e performance.**

* Validação do CPF/CNPJ cross browser;
* Validação de e-mail já registrado;
* Validação de CFP/CNPJ já registrado;
* CPF/CNPJ usando o Taxvat;
* Seleção do tipo pessoa;
* Adição de todos os estados do Brasil;
* Uso do campo Fax como Celular;
* Formatação dos campos do Telefone e Celular cross browser; 
* Uso do campo firstname como Razão Social para seleção de PJ;
* Uso do campo lastname como Nome Fantansia para seleção de PJ;
* Opção de autenticação antes de ir para o checkout;
* Homologado em projetos com grande volume de vendas;
* Suporte as versões acima da 1.6+;
* Busca do CEP para auto complete do endereço cross browser;
* Bloqueio da edição do e-mail, CPF, CNPJ na tela de edição de endereços;
* Uso do CSS padrão da theme Default do Magento;
* Padronização de todo o código fonte;
* Responsivo com Bootstrap;

<h2>Instalação</h2>

<img src="http://www.inovarti.com.br/osc/atencao2.png" alt="Atenção! Faça sempre backup antes de realizar qualquer modificação! E sempre teste em um ambiente de desenvolvimento!" title="Atenção! Faça sempre backup antes de realizar qualquer modificação! E sempre teste em um ambiente de desenvolvimento!" />

**Instalar usando o modgit:**

    $ cd /path/to/magento
    $ modgit init
    $ modgit add oscbrasil6 git@github.com:deivisonarthur/OSC-Magento-Brasil-6-Pro.git

**Instalação manual enviando os arquivos**

*Baixe a ultima versão aqui, descompacte o arquivo baixado e copie as pastas app, js e skin para dentro do diretório principal do Magento
*Limpe a cache em Sistema > Gerenciamento de Cache


<img src="http://www.inovarti.com.br/osc/atencao.png" alt="Atenção! Faça sempre backup antes de realizar qualquer modificação! E sempre teste em um ambiente de desenvolvimento!" title="Atenção! Faça sempre backup antes de realizar qualquer modificação! E sempre teste em um ambiente de desenvolvimento!" />

<h2>Visões de demostração do OSC 6</h2>

**1 - Registro(Cadastro)**
<img src="http://www.inovarti.com.br/osc/OSC6-Cadastro.png" alt="One Step Checkout estilizado" title="One Step Checkout estilizado" />

**2 - Verificação se o e-mail, CPF e CNPJ são únicos, ou seja, se já possui registro**
<img src="http://www.inovarti.com.br/osc/OSC6-Cadastro-Validacao.png" alt="Verificação se o e-mail, CPF e CNPJ são únicos, ou seja, se já possui registro" title="Verificação se o e-mail, CPF e CNPJ são únicos, ou seja, se já possui registro" />

**3 - Tela do OSC Estilizada 1**
<img src="http://www.inovarti.com.br/osc/OSC6-Estilizado-Responsivo-1.png" alt="One Step Checkout estilizado 1" title="One Step Checkout estilizado 1" />

**4 - Tela do OSC Estilizada 2**
<img src="http://www.inovarti.com.br/osc/OSC6-Estilizado-Responsivo-2.png" alt="One Step Checkout estilizado 2" title="One Step Checkout estilizado 2" />

**5 - Tela do OSC Estilizada 3**
<img src="http://www.inovarti.com.br/osc/OSC6-Estilizado-Responsivo-3.png" alt="One Step Checkout estilizado 3" title="One Step Checkout estilizado 3" />

**6 - Edição do Endereço**
<img src="http://www.inovarti.com.br/osc/OSC6-Editar-Enderecos.png" alt="One Step Checkout Edição do Endereço" title="One Step Checkout Edição do Endereço" />

**7 - Informações da Conta**
<img src="http://www.inovarti.com.br/osc/OSC6-Informacoes-da-conta.png" alt="One Step Checkout Informações da Conta" title="One Step Checkout Informações da Conta" />



<img src="http://www.inovarti.com.br/osc/atencao.png" alt="Atenção! Faça sempre backup antes de realizar qualquer modificação! E sempre teste em um ambiente de desenvolvimento!" title="Atenção! Faça sempre backup antes de realizar qualquer modificação! E sempre teste em um ambiente de desenvolvimento!" />


<h2>Tutoriais e Observações</h2>

**A - Habilitando os campos de endereços**
**********************************************************************************************
**A1 - Visite a página:**
Admin : Sistema > Configuração > Clientes > Configuração > "Opções de Nome e Endereço" > 

**A2 - Aletera para:**
* Número de Linhas p/ Endereço:4
* Exibir Data de Nascimento:Opcional
* Exibir CPF/CNPJ:Obrigatorio
* Exibir Sexo:Opcional

Ou seja, ficará assim:
- street1 = endereço
- street2 = numero
- street3 = complemento
- street4 = bairro


**B - Habilite o Estado como obrigatório:**
**********************************************************************************************
**B1 - Visite a página:**
Admin : Sistema > Configuração "Aba Geral " > Opção de estado "Estado é necessário para" >

**B2 - Selecione:**
Brasil


**C - Habilite o taxvat e coloque como obrigatório:**
**********************************************************************************************
**C1 - Visite a página:**
Admin : Sistema > Configuração > Clientes > Configuração > "Opções ao Criar Nova Conta"

**C2 - Selecione:**
Exibir CPF/CNPJ no Frontend: Sim

<img src="http://www.inovarti.com.br/osc/atencao2.png" alt="Atenção! Faça sempre backup antes de realizar qualquer modificação! E sempre teste em um ambiente de desenvolvimento!" title="Atenção! Faça sempre backup antes de realizar qualquer modificação! E sempre teste em um ambiente de desenvolvimento!" />


<img src="http://www.inovarti.com.br/gostou.png" alt="Faça uma doação" title="Faça uma doação" />
**********************************************************************************************
Se você gostou, se foi útil para você, se fez você economizar aquela grana pois estava prestes a pagar caro por aquele módulo pago, pois não achava um solução gratuita que te atendesse e queira prestigiar o trabalho feito efetuando uma doação de qualquer valor, não vou negar e vou ficar grato, você pode fazer isso utilizando o Pagseguro no site ofical do projeto: [Site Oficial do projeto](http://onestepcheckout.com.br)

