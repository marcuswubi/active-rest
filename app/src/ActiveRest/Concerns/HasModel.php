<?php
namespace ActiveRest\Concerns;

use Solis\Expressive\Contracts\ExpressiveContract;

/**
 * Trait HasModel
 *
 * @package ActiveRest\Concerns
 */
trait HasModel
{
    //traits
    use HasRetorno;

    /**
     * @var ExpressiveContract
     */
    protected $Model;

    /**
     * @param ExpressiveContract $Model
     */
    public function setModel($Model)
    {
        $this->Model = $Model;
    }

    /**
     * @return ExpressiveContract
     */
    public function getModel()
    {
        return $this->Model;
    }

    /**
     * Método abstrato com o retorno padrao de BEFORE
     * @param array $param
     * @return array
     */
    public function before(
        array $param
    ): array {
        return [
            'success' => true,
            'param' => $param
        ];
    }

    /**
     * Método abstrato com o retorno padrao de AFTER
     * @param array $param
     * @return array
     */
    public function after(
        $param
    ): array {
        return [
            'success' => true,
            'param' => $param
        ];
    }

}