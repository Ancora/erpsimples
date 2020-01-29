<?php

class Kardex extends TRecord
{
    const TABLENAME  = 'kardex';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $produtos_movimento;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_movimento');
        parent::addAttribute('produtos_movimento_id');
        parent::addAttribute('movimento_pessoa_id');
        parent::addAttribute('movimento_tipo_estoque');
        parent::addAttribute('qtd');
        parent::addAttribute('vlr_unit');
        parent::addAttribute('custo_medio');
        parent::addAttribute('saldo');
            
    }

    /**
     * Method set_produtos_movimento
     * Sample of usage: $var->produtos_movimento = $object;
     * @param $object Instance of ProdutosMovimento
     */
    public function set_produtos_movimento(ProdutosMovimento $object)
    {
        $this->produtos_movimento = $object;
        $this->produtos_movimento_id = $object->id;
    }

    /**
     * Method get_produtos_movimento
     * Sample of usage: $var->produtos_movimento->attribute;
     * @returns ProdutosMovimento instance
     */
    public function get_produtos_movimento()
    {
    
        // loads the associated object
        if (empty($this->produtos_movimento))
            $this->produtos_movimento = new ProdutosMovimento($this->produtos_movimento_id);
    
        // returns the associated object
        return $this->produtos_movimento;
    }

    
}

