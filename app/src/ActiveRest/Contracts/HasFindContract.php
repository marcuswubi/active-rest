<?php

namespace ActiveRest\Contracts;

/**
 * Interface HasFindContract
 *
 * @package ActiveRest\Contracts
 */
interface HasFindContract
{
    /**
     * Método executado antes de Pesquisar um Registro
     * @param $param
     * @return array
     */
    public function beforeFind(array $param): array;

    /**
     * Método executado depois de Pesquisar um Registro
     * @param $param
     * @return array
     */
    public function afterFind($param): array;

    /**
     * Método de Pesquisa
     * @param array $params
     * @param bool $withAlias
     * @return array
     */
    public function find(
        array $params = [],
        bool $withAlias = false
    ): array;
}