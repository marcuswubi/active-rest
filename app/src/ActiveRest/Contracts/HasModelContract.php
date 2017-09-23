<?php

namespace ActiveRest\Contracts;

use Solis\Expressive\Contracts\ExpressiveContract;

/**
 * Interface HasModelContract
 *
 * @package ActiveRest\Contracts
 */
interface HasModelContract
{
    /**
     * @param ExpressiveContract $Model
     */
    public function setModel($Model);

    /**
     * @return ExpressiveContract
     */
    public function getModel();

    /**
     * Método abstrato com o retorno padrao de BEFORE
     * @param $param
     * @return array
     */
    public function before(array $param): array;

    /**
     * Método abstrato com o retorno padrao de AFTER
     * @param $param
     * @return array
     */
    public function after($param): array;

}