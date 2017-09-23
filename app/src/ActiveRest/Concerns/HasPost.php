<?php

namespace ActiveRest\Concerns;

//texception
use LazyFramework\Core\Helpers\JsonExtract\JsonExtract;
use Solis\Breaker\TException;
use Solis\Breaker\Abstractions\TExceptionAbstract;

/**
 * Trait HasPost
 * @package ActiveRest\Concerns
 */
trait HasPost
{
    // Mensagens
    protected static $MSG_POST_ALL = ' registros foram criados e ';
    protected static $MSG_POST_SUCCESS = 'Registro criado com sucesso';
    protected static $MSG_POST_FAIL = 'Falha ao criar registro';
    protected static $MSG_BEFORE_POST_FAIL = 'Falha ao executar as operacoes pre insercao';
    protected static $MSG_AFTER_POST_FAIL = 'Falha ao executar as operacoes pos insercao';

    /**
     * Método que é executado antes de Inserir um Registro
     * @param $param
     * @return array
     */
    public function beforePost(
        array $param
    ): array {
        try {
            return $this->before($param);
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }

    /**
     * Método executado depois de Inserir um Registro
     * @param $param
     * @return array
     */
    public function afterPost(
        $param
    ): array {
        try {
            return $this->after($param);
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }

    /**
     * Método responsável por Inserir 1 Registro na Database
     * @param array $param
     */
    public function postOne(
        array $param
    ) {
        // Antes de fazer o INSERT
        $beforePost = $this->beforePost($param);

        // Valida se o beforePost retornou sucesso e os parâmetros
        if ($beforePost['success'] === true && is_array($beforePost['param'])) {
            //Carrega o Model com os parametros
            $this->setModel(
                call_user_func_array(
                    [get_class($this->getModel()), 'make'],
                    [$beforePost['param']]
                )
            );

            // Valdia a existencia de um metodo custom para criacao do registro
            if (method_exists(
                $this,
                'customCreate'
            )) {
                $create = $this->customCreate($param);
            } else {
                $create = $this->getModel()->create();
            }

            // Valida se criou o registro
            if (empty($create)) {
                $this->newFail(self::$MSG_POST_FAIL);
                return;
            }

            // Depois de fazer o INSERT
            $afterPost = $this->afterPost($create);
            if ($afterPost['success'] === false || empty($afterPost['param'])) {
                // ADICIONA FALHA via HasPrepareRetorno
                $this->newFail(self::$MSG_AFTER_POST_FAIL);
                return;
            }

            //Carrega a Chave do registro para retornar nos parametros
            $chave = JsonExtract::getArrayPrimaryKeys(
                $afterPost['param'],
                JsonExtract::getPrimaryKeys(
                    $this->getModel()::$schema,
                    false
                )
            );

            // Retorno
            $this->newSuccess(
                self::$MSG_POST_SUCCESS,
                $chave
            );
        } elseif ($beforePost['success'] === false || empty($beforePost['param'])) {
            // Mensagem
            $message = self::$MSG_POST_FAIL . ' - ' . self::$MSG_BEFORE_POST_FAIL;
            // ADICIONA FALHA via HasPrepareRetorno
            $this->newFail($message);
        }
    }

    /**
     * Método Responsável por Inserir 1 ou N Registros
     * @param $params
     * @return array
     */
    public function post(
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
                $this->postOne($param);
            }

            //Retorno
            return $this->getRetornoProcessamento();
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }

}