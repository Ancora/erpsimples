<?php

class TipoDocumento extends TRecord
{
    const TABLENAME  = 'tipo_documento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const nfe = '1';
    const nfse = '2';
    const cf = '3';
    const cv = '4';
    const re = '5';
    const fa = '6';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('sigla');
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
        $criteria->add(new TFilter('tipo_documento_id', '=', $this->id));
        return Movimento::getObjects( $criteria );
    }

    
}

