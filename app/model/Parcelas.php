<?php

class Parcelas extends TRecord
{
    const TABLENAME  = 'parcelas';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $financeiro;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('financeiro_id');
        parent::addAttribute('parcela');
        parent::addAttribute('vlr_parcela');
        parent::addAttribute('data_vencimento');
            
    }

    /**
     * Method set_financeiro
     * Sample of usage: $var->financeiro = $object;
     * @param $object Instance of Financeiro
     */
    public function set_financeiro(Financeiro $object)
    {
        $this->financeiro = $object;
        $this->financeiro_id = $object->id;
    }

    /**
     * Method get_financeiro
     * Sample of usage: $var->financeiro->attribute;
     * @returns Financeiro instance
     */
    public function get_financeiro()
    {
    
        // loads the associated object
        if (empty($this->financeiro))
            $this->financeiro = new Financeiro($this->financeiro_id);
    
        // returns the associated object
        return $this->financeiro;
    }

    
}

