<?php
namespace ActiveRest\Contracts;

/**
 * Interface HasReplicateContract
 * @package ActiveRest\Contracts
 */
interface HasReplicateContract
{
    /**
     * Método executado antes de Replicar um Registro
     * @param $param
     * @return array
     */
    public function beforeReplicate(array $param): array;

    /**
     * Método executado depois de Replicar um Registro
     * @param $param
     * @return array
     */
    public function afterReplicate($param): array;

    /**
     * Método Responsável por inserir 1 Registro
     * @param $aParams
     * @return array
     */
    public function replicateOne(array $aParams);

    /**
     * Método Responsável por inserir 1 ou N Registros
     * @param $aParams
     * @return array
     */
    public function replicate(array $aParams): array;
}