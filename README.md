[![Build Status](https://api.travis-ci.org/gildonei/unicred.png)](https://travis-ci.org/gildonei/unicred)
[![Latest Stable Version](https://poser.pugx.org/gildonei/unicred/v/stable.svg)](https://packagist.org/packages/gildonei/unicred)

# Unicred API 
Versão PHP para consumo da API de serviços do banco Unicred (Cobweb - da UBR - Unicred Brasil).
Utilizado para autenticação, emissão e consulta de títulos de cobrança.

Site oficial - https://portal-api.e-unicred.com.br/api-portal/



### Dependências

* PHP >= 5.6

### Instalando o SDK

Se já possui um arquivo `composer.json`, basta adicionar a seguinte dependência ao seu projeto:

```json
"require": {
    "gildonei/unicred": "dev-master"
}
```

Com a dependência adicionada ao `composer.json`, basta executar:

```
composer install
```

Alternativamente, você pode executar diretamente em seu terminal:

```
composer require gildonei/unicred:dev-master
```

### Exemplo de uso

#### Registro de Título

```php

<?php

use \Unicred\Environment;
use \Unicred\UnicredApi;
use \Unicred\Entity\Assignor;
use \Unicred\Entity\Payer;
use \Unicred\Entity\Address;
use \Unicred\Entity\BankSlip;
use \Unicred\Request\AuthenticationRequest;
use \Unicred\Exception\UnicredRequestException;

try {
    #Preparando o ambiente
    $ambiente = Environment::sandbox();

    #Dados do Cedente (Cliente Unicred)
    $cedente = new Assignor();
    $cedente->setBankAgency('BANK AGENCY NUMBER')
        ->setApiKey('SUA API KEY')
        ->setUser('SEU LOGIN')
        ->setPassword('SUA SENHA')
        ->setPayeeCode('SEU CÓDIGO DE BENEFICIÁRIO')
        ->setPortifolio('CÓDIGO VARIAÇÃO DA CARTEIRA');

    #Obter o Token de Acesso
    $autenticacao = new AuthenticationRequest($cedente, $ambiente);
    $autenticacao->execute();

    #Dados do boleto
    $parcelaId = 12304;
    $boleto = new BankSlip();
    $boleto->setDueDate(new DateTime())
        ->setValue(10.45)
        ->setYourNumber($parcelaId)
        ->setBankSlipNumber($parcelaId)
		->getPayer()
            ->setName('Fulano de Tal')
            ->setPayerType(PAYER::PERSON)
            ->setDocument('44675831141')
            ->setEmail('Test@test.com')
            ->setCorporateName('Fulano de Tal')
        ->getAddress()
            ->setState('SC')
            ->setDistrict('Bairro')
            ->setCity('Cidade')
            ->setAddress('Logradouro com Número')
            ->setZip('99999-999');
	$boleto->getDiscount()
		->setIndicator(0)	// Opções válidas no manual da Unicred
		->setDateLimit(new DateTime())
		->setValue(0.00);
	$boleto->getFine()
		->setIndicator(0)
		->setDateLimit(new DateTime())
		->setValue(0.00);
	$boleto->getInterest()
		->setIndicator(0)
		->setDateLimit(new DateTime())
		->setValue(0.00);

    #Instancia a API
    $unicred = new UnicredApi($cedente, $ambiente);

    #Obtém o ID do boleto gerado
    $boletoId = $unicred->createBankSlip($boleto);

    #Atribui o ID do boleto Unicred ao objeto boleto
    $boleto->setBankSlipId($boletoId);

    # Consulta o objeto boleto na API - obtém código de barras e linha digitável
    $boleto = $unicred->consultBankSlip($boleto);

    #Output do objeto boleto (BankSlip)
    print_r($boleto);

} catch (UnicredRequestException $e) {
    echo $e->getUnicredError()->getMessage();

} catch (Exception $e) {
    echo $e->getMessage();
}
```

#### Consulta de Título

```php

<?php

use \Unicred\Environment;
use \Unicred\UnicredApi;
use \Unicred\Entity\Assignor;
use \Unicred\Entity\BankSlip;
use \Unicred\Request\AuthenticationRequest;
use \Unicred\Exception\UnicredRequestException;

try {
    #Preparando o ambiente
    $ambiente = Environment::sandbox();

    #Dados do Cedente (Cliente Unicred)
    $cedente = new Assignor();
    $cedente->setBankAgency('BANK AGENCY NUMBER')
        ->setApiKey('SUA API KEY')
        ->setUser('SEU LOGIN')
        ->setPassword('SUA SENHA')
        ->setPayeeCode('SEU CÓDIGO DE BENEFICIÁRIO')
        ->setPortifolio('CÓDIGO VARIAÇÃO DA CARTEIRA');

    #Obter o Token de Acesso
    $autenticacao = new AuthenticationRequest($cedente, $ambiente);
    $autenticacao->execute();

    #Dados do boleto
    $boleto = new BankSlip();
    $boleto->setBankSlipId('TOKEN DO BOLETO');

    # Consulta o objeto boleto na API - obtém código de barras e linha digitável
    $boleto = $unicred->consultBankSlip($boleto);

    #Output do objeto boleto (BankSlip)
    print_r($boleto);

} catch (UnicredRequestException $e) {
    echo $e->getUnicredError()->getMessage();

} catch (Exception $e) {
    echo $e->getMessage();
}

```

### Agradecimentos e Referências

Parte do modelo de implementação e referência para o desenvolvimento desta API foi tirada de inspiração no projeto Cielo API-3.0 desenvolvido pelo @netojoaobatista - https://github.com/DeveloperCielo/API-3.0-PHP

Qualquer contribuilção adicional como testes, melhorias, correções e evoluções será muito bem vinda.
