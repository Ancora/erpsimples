<?php

class TipoMovimento extends TRecord
{
    const TABLENAME  = 'tipo_movimento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const epc = '1';
    const ddv = '2';
    const cdc = '3';
    const edc = '4';
    const spv = '5';
    const ddc = '6';
    const cdv = '7';
    const edv = '8';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('sigla');
        parent::addAttribute('tipo_estoque');
        parent::addAttribute('data_registro');
        parent::addAttribute('usuario_registro');
        parent::addAttribute('data_atualizacao');
        parent::addAttribute('usuario_atualizacao');
            
    }

    /**
     * Method getMovimentos
     */
    public function getMovimentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipo_movimento_id', '=', $this->id));
        return Movimento::getObjects( $criteria );
    }

    
}

