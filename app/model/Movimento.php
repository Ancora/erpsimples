<?php

class Movimento extends TRecord
{
    const TABLENAME  = 'movimento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $situacao;
    private $tipo_documento;
    private $pessoa;
    private $tipo_movimento;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo_estoque');
        parent::addAttribute('tipo_movimento_id');
        parent::addAttribute('pessoa_id');
        parent::addAttribute('tipo_documento_id');
        parent::addAttribute('situacao_id');
        parent::addAttribute('situacao_id_ant');
        parent::addAttribute('numero_documento');
        parent::addAttribute('data_documento');
        parent::addAttribute('data_abertura');
        parent::addAttribute('data_entrega');
        parent::addAttribute('vlr_frete');
        parent::addAttribute('vlr_icms');
        parent::addAttribute('vlr_ipi');
        parent::addAttribute('vlr_total');
        parent::addAttribute('obs');
        parent::addAttribute('data_registro');
        parent::addAttribute('usuario_registro');
        parent::addAttribute('data_atualizacao');
        parent::addAttribute('usuario_atualizacao');
            
    }

    /**
     * Method set_situacao
     * Sample of usage: $var->situacao = $object;
     * @param $object Instance of Situacao
     */
    public function set_situacao(Situacao $object)
    {
        $this->situacao = $object;
        $this->situacao_id = $object->id;
    }

    /**
     * Method get_situacao
     * Sample of usage: $var->situacao->attribute;
     * @returns Situacao instance
     */
    public function get_situacao()
    {
    
        // loads the associated object
        if (empty($this->situacao))
            $this->situacao = new Situacao($this->situacao_id);
    
        // returns the associated object
        return $this->situacao;
    }
    /**
     * Method set_tipo_documento
     * Sample of usage: $var->tipo_documento = $object;
     * @param $object Instance of TipoDocumento
     */
    public function set_tipo_documento(TipoDocumento $object)
    {
        $this->tipo_documento = $object;
        $this->tipo_documento_id = $object->id;
    }

    /**
     * Method get_tipo_documento
     * Sample of usage: $var->tipo_documento->attribute;
     * @returns TipoDocumento instance
     */
    public function get_tipo_documento()
    {
    
        // loads the associated object
        if (empty($this->tipo_documento))
            $this->tipo_documento = new TipoDocumento($this->tipo_documento_id);
    
        // returns the associated object
        return $this->tipo_documento;
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
     * Method set_tipo_movimento
     * Sample of usage: $var->tipo_movimento = $object;
     * @param $object Instance of TipoMovimento
     */
    public function set_tipo_movimento(TipoMovimento $object)
    {
        $this->tipo_movimento = $object;
        $this->tipo_movimento_id = $object->id;
    }

    /**
     * Method get_tipo_movimento
     * Sample of usage: $var->tipo_movimento->attribute;
     * @returns TipoMovimento instance
     */
    public function get_tipo_movimento()
    {
    
        // loads the associated object
        if (empty($this->tipo_movimento))
            $this->tipo_movimento = new TipoMovimento($this->tipo_movimento_id);
    
        // returns the associated object
        return $this->tipo_movimento;
    }

    /**
     * Method getFinanceiros
     */
    public function getFinanceiros()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('movimento_id', '=', $this->id));
        return Financeiro::getObjects( $criteria );
    }
    /**
     * Method getProdutosMovimentos
     */
    public function getProdutosMovimentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('movimento_id', '=', $this->id));
        return ProdutosMovimento::getObjects( $criteria );
    }

    
}

