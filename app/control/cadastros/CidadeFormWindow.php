<?php

class CidadeFormWindow extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'ancor907_erpsimples';
    private static $activeRecord = 'Cidade';
    private static $primaryKey = 'id';
    private static $formName = 'form_Cidade';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.50, null);
        parent::setTitle("Cadastro de Cidades");
        parent::setProperty('class', 'window_modal');

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de Cidades");


        $descricao = new TEntry('descricao');
        $oculto = new THidden('oculto');
        $uf_id = new TDBCombo('uf_id', 'ancor907_erpsimples', 'Uf', 'id', '{descricao}','descricao asc'  );

        $uf_id->addValidation("Uf id", new TRequiredValidator()); 

        $oculto->setSize(200);
        $uf_id->setSize('70%');
        $descricao->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Cidade", '#ff0000', '14px', 'B', '100%'),$descricao],[$oculto],[new TLabel("UF", '#ff0000', '14px', 'B', '100%'),$uf_id]);
        $row1->layout = [' col-sm-6','col-sm-2','col-sm-2'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'far:save #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');

        parent::add($this->form);

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

            $object = new Cidade(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $messageAction);

            // TWindow
            if (!empty($data->oculto)) {
               $items = [$object->id => $object->descricao];
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

                $object = new Cidade($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

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

}

