<?php

class Produto extends TRecord
{
    const TABLENAME  = 'produto';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $pessoa;
    private $tipo_produto;
    private $medida;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo_produto_id');
        parent::addAttribute('pessoa_id');
        parent::addAttribute('ativo');
        parent::addAttribute('descricao');
        parent::addAttribute('codigo_barras');
        parent::addAttribute('estoque_minimo');
        parent::addAttribute('estoque_maximo');
        parent::addAttribute('medida_id');
        parent::addAttribute('obs');
        parent::addAttribute('data_registro');
        parent::addAttribute('usuario_registro');
        parent::addAttribute('data_atualizacao');
        parent::addAttribute('usuario_atualizacao');
            
    }

    /**
     * Method set_pessoa
     * Sample of usage: $var->pessoa = $object;
     * @param $object Instance of Pessoa
     */
    public function set_pessoa(Pessoa $object)
    {
        $this->pessoa = $object;
        $this->pessoa_id = $object->id;
    }

    /**
     * Method get_pessoa
     * Sample of usage: $var->pessoa->attribute;
     * @returns Pessoa instance
     */
    public function get_pessoa()
    {
    
        // loads the associated object
        if (empty($this->pessoa))
            $this->pessoa = new Pessoa($this->pessoa_id);
    
        // returns the associated object
        return $this->pessoa;
    }
    /**
     * Method set_tipo_produto
     * Sample of usage: $var->tipo_produto = $object;
     * @param $object Instance of TipoProduto
     */
    public function set_tipo_produto(TipoProduto $object)
    {
        $this->tipo_produto = $object;
        $this->tipo_produto_id = $object->id;
    }

    /**
     * Method get_tipo_produto
     * Sample of usage: $var->tipo_produto->attribute;
     * @returns TipoProduto instance
     */
    public function get_tipo_produto()
    {
    
        // loads the associated object
        if (empty($this->tipo_produto))
            $this->tipo_produto = new TipoProduto($this->tipo_produto_id);
    
        // returns the associated object
        return $this->tipo_produto;
    }
    /**
     * Method set_medida
     * Sample of usage: $var->medida = $object;
     * @param $object Instance of Medida
     */
    public function set_medida(Medida $object)
    {
        $this->medida = $object;
        $this->medida_id = $object->id;
    }

    /**
     * Method get_medida
     * Sample of usage: $var->medida->attribute;
     * @returns Medida instance
     */
    public function get_medida()
    {
    
        // loads the associated object
        if (empty($this->medida))
            $this->medida = new Medida($this->medida_id);
    
        // returns the associated object
        return $this->medida;
    }

    /**
     * Method getSaldoss
     */
    public function getSaldoss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return Saldos::getObjects( $criteria );
    }
    /**
     * Method getProdutosMovimentos
     */
    public function getProdutosMovimentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return ProdutosMovimento::getObjects( $criteria );
    }

    
}

