<?php

class Forma extends TRecord
{
    const TABLENAME  = 'forma';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
            
    }

    /**
     * Method getFinanceiros
     */
    public function getFinanceiros()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('forma_id', '=', $this->id));
        return Financeiro::getObjects( $criteria );
    }

    
}

