<?php
namespace ActiveRest\Helpers;

/**
 * Class JsonExtract
 * @package ActiveRest\Helpers
 */
class JsonExtract
{

    /**
     * Método que retorna o array de Primary Keys do Schema, com o alias no lugar da property
     * @param $schema Schema
     * @param $alias Se definido como false retorna o nome da propriedade
     * @return array
     */
    public static function getPrimaryKeys(
        $schema,
        $alias = true
    ) {
        $aPkAux = [];
        $aPk = $schema->getKeys();
        foreach ($aPk as $property) {
            //Verifica se é ALIAS ou o NOME
            if ($alias === true) {
                $aPkAux[] = $property->getAlias();
            } else {
                $aPkAux[] = $property->getProperty();
            }
        }

        return $aPkAux;
    }

    /**
     * Retorna um Array com apenas as Primary Keys preenchidas
     *
     * @param $aParam
     * @param $aPk
     *
     * @return mixed
     */
    public static function getArrayPrimaryKeys(
        $aParam,
        $aPk
    ) {
        $aPkReturn = $aParam;
        foreach ($aParam as $key => $property) {
            if (!in_array(
                $key,
                $aPk
            )
            ) {
                unset($aPkReturn[$key]);
            }
        }

        return $aPkReturn;
    }

    /**
     * Retorna um Array com os Parâmetros preenchidos, sem as Primary Keys
     *
     * @param $aParam JSON enviado para atualizar o registro
     * @param $aPk    Array que contem apenas as PK do registro, preenchidas
     *
     * @return array
     */
    public static function getDataWithoutPrimaryKeys(
        $aParam,
        $aPk
    ) {
        return array_diff(
            $aParam,
            $aPk
        );
    }

}