<?php

class ProdutosMovimento extends TRecord
{
    const TABLENAME  = 'produtos_movimento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $produto;
    private $movimento;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('movimento_id');
        parent::addAttribute('produto_id');
        parent::addAttribute('lote');
        parent::addAttribute('data_validade');
        parent::addAttribute('qtd');
        parent::addAttribute('vlr_unitario');
        parent::addAttribute('vlr_icms');
        parent::addAttribute('vlr_ipi');
            
    }

    /**
     * Method set_produto
     * Sample of usage: $var->produto = $object;
     * @param $object Instance of Produto
     */
    public function set_produto(Produto $object)
    {
        $this->produto = $object;
        $this->produto_id = $object->id;
    }

    /**
     * Method get_produto
     * Sample of usage: $var->produto->attribute;
     * @returns Produto instance
     */
    public function get_produto()
    {
    
        // loads the associated object
        if (empty($this->produto))
            $this->produto = new Produto($this->produto_id);
    
        // returns the associated object
        return $this->produto;
    }
    /**
     * Method set_movimento
     * Sample of usage: $var->movimento = $object;
     * @param $object Instance of Movimento
     */
    public function set_movimento(Movimento $object)
    {
        $this->movimento = $object;
        $this->movimento_id = $object->id;
    }

    /**
     * Method get_movimento
     * Sample of usage: $var->movimento->attribute;
     * @returns Movimento instance
     */
    public function get_movimento()
    {
    
        // loads the associated object
        if (empty($this->movimento))
            $this->movimento = new Movimento($this->movimento_id);
    
        // returns the associated object
        return $this->movimento;
    }

    /**
     * Method getKardexs
     */
    public function getKardexs()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produtos_movimento_id', '=', $this->id));
        return Kardex::getObjects( $criteria );
    }

    
}

