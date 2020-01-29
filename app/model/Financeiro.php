<?php

class Financeiro extends TRecord
{
    const TABLENAME  = 'financeiro';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $pessoa;
    private $movimento;
    private $forma;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('movimento_id');
        parent::addAttribute('pessoa_id');
        parent::addAttribute('forma_id');
        parent::addAttribute('vlr_total');
        parent::addAttribute('qtd_parcelas');
        parent::addAttribute('usuario_registro');
        parent::addAttribute('data_registro');
        parent::addAttribute('usuario_atualizacao');
        parent::addAttribute('data_atualizacao');
            
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
     * Method set_forma
     * Sample of usage: $var->forma = $object;
     * @param $object Instance of Forma
     */
    public function set_forma(Forma $object)
    {
        $this->forma = $object;
        $this->forma_id = $object->id;
    }

    /**
     * Method get_forma
     * Sample of usage: $var->forma->attribute;
     * @returns Forma instance
     */
    public function get_forma()
    {
    
        // loads the associated object
        if (empty($this->forma))
            $this->forma = new Forma($this->forma_id);
    
        // returns the associated object
        return $this->forma;
    }

    /**
     * Method getParcelass
     */
    public function getParcelass()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('financeiro_id', '=', $this->id));
        return Parcelas::getObjects( $criteria );
    }

    
}

