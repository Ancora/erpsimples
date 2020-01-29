<?php

class Situacao extends TRecord
{
    const TABLENAME  = 'situacao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const abe = '1';
    const pno = '2';
    const eno = '3';
    const can = '4';
    const pnf = '5';
    const enf = '6';
    const est = '7';
    const enp = '8';
    const sap = '9';
    const dev = '10';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('sigla');
        parent::addAttribute('modulo');
            
    }

    /**
     * Method getMovimentos
     */
    public function getMovimentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('situacao_id', '=', $this->id));
        return Movimento::getObjects( $criteria );
    }

    
}

