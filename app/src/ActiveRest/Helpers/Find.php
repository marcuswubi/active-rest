<?php
namespace ActiveRest\Helpers;

/**
 * Class Find
 * @package ActiveRest\Helpers
 */
final class Find
{

    private static function getRestParam(
        $params
    ): array {
        error_log(json_encode($params));
        if (!is_array($params)) {
            error_log($params);
            $params = json_decode(
                $params,
                true
            );
        }

        //LIMIT
        $limit = ($params['queryParams']['_end'] - $params['queryParams']['_start']);
        //OFFSET
        $offset = $params['queryParams']['_start'];

        //OPTIONS
        $options = [
            'orderBy' => [
                'column'    => $params['queryParams']['_sort'],
                'direction' => $params['queryParams']['_order'],
            ],
            'limit'   => [
                'number' => $limit,
                'offset' => $offset
            ],
        ];

        //REMOVE PAGINATION ARGS
        unset($params['queryParams']['_sort']);
        unset($params['queryParams']['_order']);
        unset($params['queryParams']['_start']);
        unset($params['queryParams']['_end']);

        //ARGUMENTS
        $arguments = [];
        foreach ($params['queryParams'] as $field => $value) {
            if (
                !empty($field) && is_string($field) &&
                !empty($value) && is_string($value)
            ) {
                //@TODO ADICIONAR PHP SMART PARA FORNECER MESMO TRATAMENTO QUE FAZ ANTES DE INSERIR
                $arguments[] = [
                    'column' => $field,
                    'value' => '%'.strtoupper($value).'%',
                    'operator' => 'LIKE',
                    'chainType' => 'or'
                ];
            }
        }

        //RETURN
        $return = [
            'options'   => $options,
            'arguments' => $arguments
        ];

        error_log(
            json_encode(
                $return
            )
        );

        return $return;
    }

    /**
     * @param array $params
     * @return array
     */
    public static function params(
        $params = []
    ): array {
        if (empty($params)) {
            $arguments = [];
            $options = [
                'orderBy' => [
                    'column'    => 'id',
                    'direction' => 'DESC',
                ],
                'limit'   => [
                    'number' => 10,
                ],
            ];

            return [
                'options'   => $options,
                'arguments' => $arguments,
            ];
        }

        return self::getRestParam($params);
    }
}
