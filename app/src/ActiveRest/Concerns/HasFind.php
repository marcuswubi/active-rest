<?php

namespace ActiveRest\Concerns;

//dependencias
use ActiveRest\Helpers\Find;
//texception
use Solis\Breaker\Abstractions\TExceptionAbstract;

/**
 * Trait HasFind
 *
 * @package ActiveRest\Concerns
 */
trait HasFind
{
    // Mensagens
    protected static $MSG_CONSULTA_SUCCESS = 'Consulta realizada com sucesso';
    protected static $MSG_CONSULTA_FAIL = 'Nenhum registro encontrado';

    /**
     * Método executado antes de Pesquisar um Registro
     * @param $param
     * @return array
     */
    public function beforeFind(
        array $param
    ): array {
        try {
            return $this->before($param);
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }

    /**
     * Método executado depois de Pesquisar um Registro
     * @param $param
     * @return array
     */
    public function afterFind(
        $param
    ): array {
        try {
            return $this->after($param);
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }

    /**
     * Método que implementa a pesquisa dos registro no banco de dados
     * @param array $params - Parametros de consulta (filtros,paginacao,ordenacao)
     * @param bool $withAlias - Retorna como de Alias se verdadeiro
     * @return array
     */
    public function find(
        array $params = [],
        bool $withAlias = false
    ): array {
        try {
            //PARAM VALIDATE
            $params = $this->beforeFind($params);
            $params = Find::params(empty($params['param']) ? [] : $params['param']);

            // DO QUERY
            $count = 0;
            $data = [];
            $aModels = $this->getModel()->select(
                $params['arguments'],
                $params['options']
            );

            //RESULTS VALIDATION
            if (!empty($aModels)) {
                $aModels = !is_array($aModels) ? [$aModels] : $aModels;

                //COUNT RETURN WITH SAME ARGUMENTS
                $count = $this->getModel()->count($params['arguments']);

                //CONVERT OBJECTS RETURNED TO ARRAY
                foreach ($aModels as $model) {
                    $data[] = $model->toArray($withAlias);
                }
            }

            //AFTER FIND
            $afterFind = $this->afterFind($data);
            if ($afterFind['status'] !== true) {
                return [
                    'data' => $afterFind['params'],
                    'headers' => [
                        'count' => $count,
                        'statusCode' => 400,
                        'dateTime' => Date('Y-m-d H:i:s'),
                    ]
                ];
            }
            $data = $afterFind['param'];

            //RETURN
            $page = $params['arguments']['limit']['number']
                ? $params['arguments']['limit']['number']
                : 0;
            return [
                'data' => $data,
                'headers' => [
                    'count' => $count,
                    'statusCode' => 200,
                    'dateTime' => Date('Y-m-d H:i:s'),
                ],
            ];
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }
}