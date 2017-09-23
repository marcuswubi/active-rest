<?php

namespace ActiveRest\Concerns;

//texception
use Solis\Breaker\TException;
use Solis\Breaker\Abstractions\TExceptionAbstract;
//models
use MsNFSe\Modules\NFSe\Models\NFSe as ModelNFSe;

/**
 * Trait HasReplicate
 *
 * @package ActiveRest\Concerns
 */
trait HasReplicate
{
    // Mensagens
    protected static $MSG_REPLICATE_ALL = ' registros foram replicados e ';
    protected static $MSG_REPLICATE_SUCCESS = 'Registro replicado com sucesso';
    protected static $MSG_REPLICATE_FAIL = 'Falha ao replicar o registro';
    protected static $MSG_BEFORE_REPLICATE_FAIL = 'Falha ao executar as operacoes pre replicacao';
    protected static $MSG_AFTER_REPLICATE_FAIL = 'Falha ao executar as operacoes pos replicacao';


    /**
     * Método que é executado antes de Replicar um Registro
     * @param $param
     * @return array
     */
    public function beforeReplicate(
        array $param
    ): array {
        try {
            return $this->before($param);
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }

    /**
     * Método executado depois de Replicar um Registro
     * @param $param
     * @return array
     */
    public function afterReplicate(
        $param
    ): array {
        try {
            return $this->after($param);
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }


    /**
     * Método responsável por Replicar 1 Registro na Database
     * @param array $param
     */
    public function replicateOne(
        array $param
    ) {
        // Chamada o método de interceptação beforeReplicate e valida o insert
        $beforeReplicate = $this->beforeReplicate($param);

        // Valida se o beforeReplicate retornou sucesso e os parâmetros
        if ($beforeReplicate['success'] === true && is_array($beforeReplicate['param'])) {
            $instance = call_user_func_array(
                [get_class($this->getModel()), 'make'],
                [$beforeReplicate['param']]
            );

            $this->setModel($instance);
            $replicate = $this->getModel()->replicate();

            // Status
            if (empty($replicate)) {
                // ADICIONA FALHA via HasPrepareRetorno
                $this->newFail(self::$MSG_REPLICATE_FAIL);
                return;
            }

            // Depois de fazer a Replicacao
            $afterReplicate = $this->afterReplicate($replicate);
            if ($afterReplicate['success'] === false || empty($afterReplicate['param'])) {
                // ADICIONA FALHA via HasPrepareRetorno
                $this->newFail(self::$MSG_AFTER_REPLICATE_FAIL);
                return;
            }

            // Retorno
            $this->newSuccess(self::$MSG_REPLICATE_SUCCESS);
        } elseif ($beforeReplicate['success'] === false || empty($beforeReplicate['param'])) {
            // Mensagem
            $message = self::$MSG_REPLICATE_FAIL . ' - ' . self::$MSG_BEFORE_REPLICATE_FAIL;
            // ADICIONA FALHA via HasPrepareRetorno
            $this->newFail($message);
        }
    }

    /**
     * @param $params
     * @return array|string
     */
    public function replicate(
        array $params
    ): array {
        try {
            // Se os parâmetros não estão vazios carrega o Model
            if (empty($params)) {
                throw new TException(
                    __CLASS__,
                    __METHOD__,
                    'Metodo: [ ' . __CLASS__ . ' ], da Classe: [ ' . __METHOD__ . ' ], nao pode ser vazio.',
                    400
                );
            }

            // Retorna sempre um array de parametros, independente de fornecer 1 ou N
            $this->simpleArraytoMulti($params);

            // Inicia o retorno a partir do Trait HasPrepareRetorno
            $this->prepareRetorno(
                count($params)
            );

            // Itera sobre os parâmetros inserindo os registros um por um
            foreach ($params as $param) {
                $this->replicateOne($param);
            }

            return $this->getRetornoProcessamento();
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }

}