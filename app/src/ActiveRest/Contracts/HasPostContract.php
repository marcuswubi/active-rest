<?php

namespace ActiveRest\Contracts;

/**
 * Interface HasPostContract
 *
 * @package ActiveRest\Contracts
 */
interface HasPostContract
{
    /**
     * Método que é executado antes de Inserir um Registro
     *
     * @param $param
     *
     * @return array
     */
    public function beforePost(array $param): array;

    /**
     * Método executado depois de Inserir um Registro
     * @param $param
     * @return array
     */
    public function afterPost($param): array;

    /**
     * Método Responsável por inserir 1 Registro
     * @param $params
     * @return array
     */
    public function postOne(array $params);

    /**
     * Método Responsável por inserir 1 ou N Registros
     * @param $params
     * @return array
     */
    public function post(array $params): array;
}