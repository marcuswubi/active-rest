<?php

namespace ActiveRest\Concerns;

//dependencias
use LazyFramework\Core\Helpers\Request\Find;
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
            // Inicia o retorno
            $count = 0;
            $aRetorno = [
                'status' => true,
                'message' => '',
                'count' => $count,
                'data' => []
            ];

            // Realiza a consulta com base nos filtros informados
            $params = $this->beforeFind($params);
            $params = Find::params(empty($params['param']) ? [] : $params['param']);
            if (!empty($params)) {
                $aModels = $this->getModel()->select(
                    $params['arguments'],
                    $params['options']
                );

                if (!empty($aModels)) {
                    $aModels = !is_array($aModels) ? [$aModels] : $aModels;

                    // Contagem dos dados retornados
                    $aRetorno['count'] = $this->getModel()->count($params['arguments']);

                    // Converte os objetos em array para retornar
                    $aRetorno['data'] = [];
                    foreach ($aModels as $model) {
                        $aRetorno['data'][] = $model->toArray($withAlias);
                    }
                }
            }


            // Valida execução
            $aRetorno['status'] = !empty($aRetorno['data']) ? true : false;
            $aRetorno['message'] = !empty($aRetorno['data']) ? self::$MSG_CONSULTA_SUCCESS : self::$MSG_CONSULTA_FAIL;


            // Retorno Geral
            $retorno = $this->afterFind($aRetorno);;
            return $retorno['param'];
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }
}