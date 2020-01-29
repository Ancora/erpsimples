<?php

class Grupo extends TRecord
{
    const TABLENAME  = 'grupo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const corporativo = '1';
    const cliente = '2';
    const fornecedor = '3';
    const fabricante = '4';
    const vendedor = '5';
    const funcionario = '6';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('data_registro');
        parent::addAttribute('usuario_registro');
        parent::addAttribute('data_atualizacao');
        parent::addAttribute('usuario_atualizacao');
            
    }

    /**
     * Method getPessoaGrupos
     */
    public function getPessoaGrupos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('grupo_id', '=', $this->id));
        return PessoaGrupo::getObjects( $criteria );
    }

    
}

