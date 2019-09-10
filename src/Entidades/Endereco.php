<?php
namespace Unicred\Entidades\Endereco;

use \Unicred\Entidades\EntidadeAbstrata;

/**
 * Entidade Endereco
 *
 * Contém os dados genéricos de endereço
 *
 * @author Gildonei Mendes Anacleto Junior <junior@sitecomarte.com.br>
 */
class Endereco extends EntidadeAbstrata
{
    /**
     * @var string
     */
    private $logradouro;

    /**
     * @var string
     */
    private $bairro;

    /**
     * @var string
     */
    private $cidade;

    /**
	 * Sigla do estado com 2 caracteres
	 *
     * @var string
     */
    private $uf;

	/**
	 * Array com as siglas dos estados
	 *
	 * @var array
	 */
	private $estados = array('AC', 'AL', 'AM', 'AP', 'BA', 'CE', 'DF', 
		'ES', 'GO', 'MA', 'MG', 'MT', 'MS', 'PA', 'PB', 'PE', 'PI', 
		'PR', 'RJ', 'RN', 'RO', 'RR', 'RS', 'SC', 'SP', 'SE', 'TO');

    /**
	 * Número do cep com 8 dígitos
	 *
     * @var int
     */
    public $cep;

	/**
	 * Define a sigla do nome do estado com 2 caracteres
	 *
	 * @param string $uf Sigla 
	 * @return Endereco
	 */
	public function setUf($uf)
	{
		if (!in_array(strtoupper($uf), $estados)) {
			throw new \InvalidArgumentException("Sigla do estado inválida!");
		}
		
		$this->uf = $uf;
		
		return $this;
	}
}
