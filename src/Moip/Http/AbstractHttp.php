<?php

namespace Moip\Http;

abstract class AbstractHttp
{
    /**
     * Adiciona um campo de cabeçalho para ser enviado com a requisição.
     *
     * @param string $name     Nome do campo de cabeçalho.
     * @param string $value    Valor do campo de cabeçalho.
     * @param bool   $override Indica se o campo deverá ser sobrescrito caso já tenha sido definido.
     *
     * @return bool
     *
     * @throws \InvalidArgumentException Se o nome ou o valor do campo não forem valores scalar.
     */
    public function addHeaderRequest($name, $value, $override = true)
    {
        if (is_scalar($name) && is_scalar($value)) {
            $key = strtolower($name);

            if ($override === true || !isset($this->requestHeader[$key])) {
                $this->requestHeader[$key] = array('name' => $name,
                    'value' => $value,
                );

                return true;
            }

            return false;
        }

        throw new InvalidArgumentException('Name and value MUST be scalar');
    }
}