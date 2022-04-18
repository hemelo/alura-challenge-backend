<?php

namespace App\Contracts;

class Transferencia
{
    public function __invoke()
    {
        return array(
            'banco_origem',
            'agencia_origem',
            'conta_origem',
            'banco_destino',
            'agencia_destino',
            'conta_destino',
            'valor_transferido',
            'created_at'
        );
    }

    private function rules()
    {
        return array(
            'required',
            'required|digits:4',
            'required|regex:/^(\d{1,5})?(\-\d{1})$/',
            'required',
            'required|digits:4',
            'required|regex:/^(\d{1,5})?(\-\d{1})$/',
            'required|regex:/^(\d+)+(\.\d{1,2})?$/',
            'required|date_format:Y-m-d\TH:i:s',
        );
    }

    public function validation()
    {
        return array_combine($this->__invoke(), $this->rules());
    }

    public function jsonKeys() 
    {
        $jsonKeys = $this->__invoke();

        foreach (array_keys($jsonKeys, 'created_at', true) as $key) {
            unset($jsonKeys[$key]);
        }

        array_push($jsonKeys, 'id');
        return $jsonKeys;
    }
}