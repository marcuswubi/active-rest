<?php

namespace ActiveRest\Contracts;

/**
 * Interface HasPatchContract
 *
 * @package ActiveRest\Contracts
 */
interface HasPatchContract
{
    /**
     * Método executado antes de Atualizar via Patch um Registro
     * @param $param
     * @return array
     */
    public function beforePatch(array $param): array;

    /**
     * Método executado depois de Atualizar via Patch um Registro
     * @param $param
     * @return array
     */
    public function afterPatch($param): array;

    /**
     * Método Responsável por Atualizar via Patch 1 Registro
     * @param $params
     * @return array
     */
    public function patchOne(array $params);

    /**
     * Método Responsável por Atualizar via Patch 1 ou N Registros
     * @param $params
     * @return array
     */
    public function patch(array $params): array;
}