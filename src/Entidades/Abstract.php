<?php
namespace Unicred\Entidades\EntidadeAbstrata;

/**
 * Entidade abstrata
 *
 * Criada para popular as propriedades das demais entidades do projeto
 *
 * @author Gildonei Mendes Anacleto Junior <junior@sitecomarte.com.br>
 */
abstract class EntidadeAbstrata
{
    /**
     * Constructor
     *
     * @param  \stdClass|array|null  $parametros  (opcional) Parâmeteros existentes na entidade a sendo instanciada
     */
    public function __construct($parametros = null)
    {
        if(empty($parametros)) {
            return null;
        }

        if($parametros instanceof \stdClass) {
            $parametros = get_object_vars($parametros);
        }

        $this->popular($parametros);
    }

    /**
     * Popula a entidade instanciada com os valores definidos para os parâmetros
     *
     * @param array $parametros  Entity parameters
     */
    public function popular(array $parametros)
    {
        foreach ($parametros as $propriedade => $valor) {
            if (property_exists($this, $propriedade)) {
                $this->$propriedade = $valor;

                // Apply mutator
                $mutator = 'set' . ucfirst(static::convertToCamelCase($propriedade));
                if(method_exists($this, $mutator)) {
                    call_user_func_array(array($this, $mutator), [$valor]);
                }
            }
        }
    }

    /**
     * Converte uma string de data no formato d/m/Y para um objeto DateTime
     *
     * @param  string|null  $date  DateTime string
     * @return null|\DateTime
     */
    protected static function convertDateTime($date = null)
    {
        if(empty($date)) {
            return null;
        }

        $date = \DateTime::createFromFormat('d/m/Y', $date);
        if(!$date) {
            return null;
        }

        return $date;
    }

    /**
     * Converte para CamelCase
     *
     * @param string  $str  Snake case string
     * @return string  CamelCase string
     */
    protected static function convertToCamelCase($str)
    {
        $callback = function($match) {
            return strtoupper($match[2]);
        };

        return lcfirst(preg_replace_callback('/(^|_)([a-z])/', $callback, $str));
    }
}
