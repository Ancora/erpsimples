<?php

class TipoProduto extends TRecord
{
    const TABLENAME  = 'tipo_produto';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const mer = '1';
    const esc = '2';
    const lim = '3';
    const ser = '4';

    

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
     * Method getProdutos
     */
    public function getProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipo_produto_id', '=', $this->id));
        return Produto::getObjects( $criteria );
    }

    
}

