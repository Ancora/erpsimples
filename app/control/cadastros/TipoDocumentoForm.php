<?php

class TipoDocumentoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'ancorati_erpsimplesHS';
    private static $activeRecord = 'TipoDocumento';
    private static $primaryKey = 'id';
    private static $formName = 'form_TipoDocumento';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de Tipos de Documentos");


        $id = new TEntry('id');
        $descricao = new TEntry('descricao');
        $sigla = new TEntry('sigla');
        $data_registro = new TDateTime('data_registro');
        $usuario_registro = new TEntry('usuario_registro');
        $data_atualizacao = new TDateTime('data_atualizacao');
        $usuario_atualizacao = new TEntry('usuario_atualizacao');

        $descricao->addValidation("Descrição", new TRequiredValidator());
        $sigla->addValidation("Sigla", new TRequiredValidator());

        $sigla->forceUpperCase();

        $descricao->autofocus = 'autofocus';

        $data_registro->setMask('dd/mm/yyyy hh:ii');
        $data_atualizacao->setMask('dd/mm/yyyy hh:ii');

        $data_registro->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_atualizacao->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setEditable(false);
        $data_registro->setEditable(false);
        $usuario_registro->setEditable(false);
        $data_atualizacao->setEditable(false);
        $usuario_atualizacao->setEditable(false);

        $id->setSize(100);
        $sigla->setSize('100%');
        $descricao->setSize('100%');
        $data_registro->setSize('100%');
        $usuario_registro->setSize('100%');
        $data_atualizacao->setSize('100%');
        $usuario_atualizacao->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Código", '#ff0000', '14px', 'B', '100%'),$id],[new TLabel("Descrição", '#ff0000', '14px', 'B', '100%'),$descricao],[new TLabel("Sigla", '#ff0000', '14px', 'B', '100%'),$sigla]);
        $row1->layout = [' col-sm-2',' col-sm-7','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Cadastrado em", null, '14px', null, '100%'),$data_registro],[new TLabel("Cadastrado por", null, '14px', null, '100%'),$usuario_registro],[new TLabel("Atualizado em", null, '14px', null, '100%'),$data_atualizacao],[new TLabel("Atualizado por", null, '14px', null, '100%'),$usuario_atualizacao]);
        $row2->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $btn_onsave->addStyleClass('btn-primary');

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        $container->add(TBreadCrumb::create(["Cadastros","Cadastro de Tipos de Documentos"]));
        $container->add($this->form);

        parent::add($container);

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

            $object = new TipoDocumento(); // create an empty object

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            // Registrando data de cadastro (data_registro) e data de atualização (data_atualizacao) e usuário logado
            if(!$object->id)
            {
                $object->data_registro = date('Y-m-d H:i:s');
                $object->usuario_registro = TSession::getValue('username');
            }

            if($object->id)
            {
                $object->data_atualizacao = date('Y-m-d H:i:s');
                $object->usuario_atualizacao = TSession::getValue('username');
            }
            // Fim registro de datas e usuário logado

            $object->store(); // save the object

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id;

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            $messageAction = new TAction(['TipoDocumentoForm', 'onClear']);

            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $messageAction);

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

                $object = new TipoDocumento($key); // instantiates the Active Record

                $this->form->setData($object); // fill the form

                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear();
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

}

