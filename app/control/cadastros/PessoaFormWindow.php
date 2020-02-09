<?php

class PessoaFormWindow extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'ancorati_erpsimplesHS';
    private static $activeRecord = 'Pessoa';
    private static $primaryKey = 'id';
    private static $formName = 'form_Pessoa';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Cadastro de Pessoas");
        parent::setProperty('class', 'window_modal');

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de Pessoas");


        $tipo_pessoa = new TCombo('tipo_pessoa');
        $ativo = new TCombo('ativo');
        $grupo = new TDBCheckGroup('grupo', 'ancorati_erpsimplesHS', 'Grupo', 'id', '{descricao}','id asc'  );
        $nome = new TEntry('nome');
        $oculto = new THidden('oculto');
        $cpf_cnpj = new TEntry('cpf_cnpj');
        $cidade_uf_id = new TDBCombo('cidade_uf_id', 'ancorati_erpsimplesHS', 'Uf', 'id', '{entity_column_id:906250->entity_column_id:906505}','descricao asc'  );
        $cidade_id = new TCombo('cidade_id');

        $cidade_uf_id->setChangeAction(new TAction([$this,'onChangecidade_uf_id']));
        $tipo_pessoa->setChangeAction(new TAction([$this,'onChangeTipoPessoa']));

        $tipo_pessoa->addValidation("Tipo", new TRequiredValidator());
        $ativo->addValidation("Ativo", new TRequiredValidator());
        $grupo->addValidation("Grupo", new TRequiredValidator());
        $nome->addValidation("Nome/Razão Social", new TRequiredValidator());
        $cpf_cnpj->addValidation("CPF/CNPJ", new TRequiredValidator());
        $cidade_uf_id->addValidation("UF", new TRequiredValidator());
        $cidade_id->addValidation("Cidade", new TRequiredValidator());

        $ativo->setValue('Sim');
        $ativo->setDefaultOption(false);
        $grupo->setLayout('horizontal');
        $grupo->setUseButton();
        $grupo->setBreakItems(4);
        $ativo->addItems(['S'=>'Sim','N'=>'Não']);
        $tipo_pessoa->addItems(['F'=>'Física','J'=>'Jurídica']);

        $cidade_id->autofocus = 'autofocus';
        $tipo_pessoa->autofocus = 'autofocus';

        $grupo->setSize(140);
        $oculto->setSize(200);
        $ativo->setSize('70%');
        $nome->setSize('100%');
        $cpf_cnpj->setSize('100%');
        $cidade_id->setSize('100%');
        $tipo_pessoa->setSize('80%');
        $cidade_uf_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Tipo", '#ff0000', '14px', 'B', '100%'),$tipo_pessoa],[new TLabel("Ativo", '#ff0000', '14px', 'B', '100%'),$ativo],[new TLabel("Grupo", '#ff0000', '14px', 'B', '100%'),$grupo]);
        $row1->layout = [' col-sm-2',' col-sm-2',' col-sm-8'];

        $row2 = $this->form->addFields([new TLabel("Nome", '#ff0000', '14px', 'B', '100%'),$nome],[$oculto],[new TLabel("CPF/CNPJ", '#ff0000', '14px', 'B', '100%'),$cpf_cnpj]);
        $row2->layout = [' col-sm-6','col-sm-2',' col-sm-3'];

        $row3 = $this->form->addFields([new TLabel("UF", '#ff0000', '14px', 'B', '100%'),$cidade_uf_id],[new TLabel("Cidade", '#ff0000', '14px', 'B', '100%'),$cidade_id]);
        $row3->layout = [' col-sm-1',' col-sm-5'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'far:save #ffffff');
        $btn_onsave->addStyleClass('btn-primary');

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');

        parent::add($this->form);

    }

    public static function onChangecidade_uf_id($param)
    {
        try
        {

            if (isset($param['cidade_uf_id']) && $param['cidade_uf_id'])
            {
                $criteria = TCriteria::create(['uf_id' => (int) $param['cidade_uf_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'cidade_id', 'ancorati_erpsimplesHS', 'Cidade', 'id', '{descricao}', 'descricao asc', $criteria, TRUE);
            }
            else
            {
                TCombo::clearField(self::$formName, 'cidade_id');
            }

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public static function onChangeTipoPessoa($param = null)
    {
        try
        {
            //code here
            if( isset($param['key']) && $param['key'] == 'F')
            {
                TScript::create( "$('input[name=cpf_cnpj]').off();
                                  $('input[name=cpf_cnpj]').val('');
                                  $('input[name=cpf_cnpj]').keypress(function() {  tentry_mask(this, event, '999.999.999-99') });" );

                TScript::create( "$('label:contains(\"Nome\")').html('Nome Completo');" );
                TScript::create( "$('label:contains(\"CPF/CNPJ\")').html('CPF');" );
            }
            elseif( isset($param['key']) && $param['key'] == 'J')
            {
                TScript::create( "$('input[name=cpf_cnpj]').off();
                                  $('input[name=cpf_cnpj]').val('');
                                  $('input[name=cpf_cnpj]').keypress(function() {  tentry_mask(this, event, '99.999.999/9999-99') });" );

                TScript::create( "$('label:contains(\"Nome\")').html('Razão Social');" );
                TScript::create( "$('label:contains(\"CPF/CNPJ\")').html('CNPJ');" );
            }

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onSave($param = null)
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Pessoa(); // create an empty object

            $data = $this->form->getData(); // get form data as array

            // Validação CPF ou CNPJ
            if($data->tipo_pessoa == "F")
            {
                (new TCPFValidator())->validate('CPF',$data->cpf_cnpj);
            }

            if($data->tipo_pessoa == "J")
            {
                (new TCNPJValidator())->validate('CNPJ',$data->cpf_cnpj);
            }
            // Fim validação

            $object->fromArray( (array) $data); // load the object with data

            // Registrando data de cadastro (data_registro) e usuário logado
            if(!$object->id)
            {
                $object->data_registro = date('Y-m-d H:i:s');
                $object->usuario_registro = TSession::getValue('username');
            }

            // Fim registro de datas e usuário logado

            $object->store(); // save the object

            $this->fireEvents($object);

            $repository = PessoaGrupo::where('pessoa_id', '=', $object->id);
            $repository->delete();

            if ($data->grupo)
            {
                foreach ($data->grupo as $grupo_value)
                {
                    $pessoa_grupo = new PessoaGrupo;

                    $pessoa_grupo->grupo_id = $grupo_value;
                    $pessoa_grupo->pessoa_id = $object->id;
                    $pessoa_grupo->store();
                }
            }

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id;

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            // new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $messageAction);
            // new TMessage('info', 'Registro Salvo!');
            new TMessage('warning', 'Não esqueça de completar este novo Cadastro!');

            // TWindow

            if (!empty($data->oculto)) {
               $items = [$object->id => $object->nome];
               TCombo::reload('', $data->oculto, $items);
            }

                TWindow::closeWindow(parent::getId());

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode>

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Pessoa($key); // instantiates the Active Record

                                $object->cidade_uf_id = $object->cidade->uf_id;

                $criteria = TCriteria::create(['pessoa_id'=>$object->id]);
                $object->grupo = PessoaGrupo::getIndexedArray('grupo_id', 'grupo_id', $criteria);

                $this->form->setData($object); // fill the form

                $this->fireEvents($object);

                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear();

                // TWindow
                if (!empty($param['oculto'])) {
                   $object = new stdClass;
                   $object->oculto = $param['oculto'];
                   $this->form->setData($object);
                }
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

    }

    public function onShow($param = null)
    {

    }

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->cidade_uf_id))
            {
                $obj->cidade_uf_id = $object->cidade_uf_id;
            }
            if(isset($object->cidade_id))
            {
                $obj->cidade_id = $object->cidade_id;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->cidade->uf_id))
            {
                $obj->cidade_uf_id = $object->cidade->uf_id;
            }
            if(isset($object->cidade_id))
            {
                $obj->cidade_id = $object->cidade_id;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }

}

