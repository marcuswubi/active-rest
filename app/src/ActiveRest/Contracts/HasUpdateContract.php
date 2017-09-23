<?php

namespace ActiveRest\Contracts;

/**
 * Interface HasUpdateContract
 *
 * @package ActiveRest\Contracts
 */
interface HasUpdateContract
{
    /**
     * Método executado antes de Atualizar via Update um Registro
     * @param $param
     * @return array
     */
    public function beforeUpdate(array $param): array;

    /**
     * Método executado depois de Atualizar via Update um Registro
     * @param $param
     * @return array
     */
    public function afterUpdate($param): array;

    /**
     * Método Responsável por Atualizar via Update 1 Registro
     * @param $params
     * @return array
     */
    public function updateOne($params);

    /**
     * Método Responsável por Atualizar via Update 1 ou N Registros
     * @param $params
     * @return array
     */
    public function update(array $params): array;
}