<?php

class Pessoa extends TRecord
{
    const TABLENAME  = 'pessoa';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $cidade;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo_pessoa');
        parent::addAttribute('ativo');
        parent::addAttribute('nome');
        parent::addAttribute('nome_reduzido');
        parent::addAttribute('sexo');
        parent::addAttribute('data_aniversario');
        parent::addAttribute('rg');
        parent::addAttribute('orgao_emissor');
        parent::addAttribute('cpf_cnpj');
        parent::addAttribute('insc_municipal');
        parent::addAttribute('insc_estadual');
        parent::addAttribute('logradouro');
        parent::addAttribute('numero');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('cidade_id');
        parent::addAttribute('uf');
        parent::addAttribute('cep');
        parent::addAttribute('email');
        parent::addAttribute('tel_celular');
        parent::addAttribute('tel_fixo');
        parent::addAttribute('obs');
        parent::addAttribute('data_registro');
        parent::addAttribute('usuario_registro');
        parent::addAttribute('data_atualizacao');
        parent::addAttribute('usuario_atualizacao');
            
    }

    /**
     * Method set_cidade
     * Sample of usage: $var->cidade = $object;
     * @param $object Instance of Cidade
     */
    public function set_cidade(Cidade $object)
    {
        $this->cidade = $object;
        $this->cidade_id = $object->id;
    }

    /**
     * Method get_cidade
     * Sample of usage: $var->cidade->attribute;
     * @returns Cidade instance
     */
    public function get_cidade()
    {
    
        // loads the associated object
        if (empty($this->cidade))
            $this->cidade = new Cidade($this->cidade_id);
    
        // returns the associated object
        return $this->cidade;
    }

    /**
     * Method getFinanceiros
     */
    public function getFinanceiros()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pessoa_id', '=', $this->id));
        return Financeiro::getObjects( $criteria );
    }
    /**
     * Method getProdutos
     */
    public function getProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pessoa_id', '=', $this->id));
        return Produto::getObjects( $criteria );
    }
    /**
     * Method getPessoaGrupos
     */
    public function getPessoaGrupos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pessoa_id', '=', $this->id));
        return PessoaGrupo::getObjects( $criteria );
    }
    /**
     * Method getContatos
     */
    public function getContatos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pessoa_id', '=', $this->id));
        return Contato::getObjects( $criteria );
    }
    /**
     * Method getMovimentos
     */
    public function getMovimentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pessoa_id', '=', $this->id));
        return Movimento::getObjects( $criteria );
    }

    
}

