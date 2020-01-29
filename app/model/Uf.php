<?php

class Uf extends TRecord
{
    const TABLENAME  = 'uf';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const al = '1';
    const pe = '2';
    const ba = '3';

    private $pais;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('sigla');
        parent::addAttribute('pais_id');
            
    }

    /**
     * Method set_pais
     * Sample of usage: $var->pais = $object;
     * @param $object Instance of Pais
     */
    public function set_pais(Pais $object)
    {
        $this->pais = $object;
        $this->pais_id = $object->id;
    }

    /**
     * Method get_pais
     * Sample of usage: $var->pais->attribute;
     * @returns Pais instance
     */
    public function get_pais()
    {
    
        // loads the associated object
        if (empty($this->pais))
            $this->pais = new Pais($this->pais_id);
    
        // returns the associated object
        return $this->pais;
    }

    /**
     * Method getCidades
     */
    public function getCidades()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('uf_id', '=', $this->id));
        return Cidade::getObjects( $criteria );
    }

    
}

