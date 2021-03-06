<?php

class TipoProdutoFormWindow extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'ancorati_erpsimplesHS';
    private static $activeRecord = 'TipoProduto';
    private static $primaryKey = 'id';
    private static $formName = 'form_TipoProduto';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.50, null);
        parent::setTitle("Cadastro de Tipos de Produtos");
        parent::setProperty('class', 'window_modal');

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de Tipos de Produtos");


        $descricao = new TEntry('descricao');
        $oculto = new THidden('oculto');
        $sigla = new TEntry('sigla');

        $sigla->forceUpperCase();

        $descricao->autofocus = 'autofocus';

        $oculto->setSize(200);
        $sigla->setSize('100%');
        $descricao->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Tipo", '#ff0000', '14px', 'B', '100%'),$descricao],[$oculto],[new TLabel("Sigla", '#ff0000', '14px', 'B', '100%'),$sigla]);
        $row1->layout = [' col-sm-4','col-sm-2','col-sm-2'];

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

            $object = new TipoProduto(); // create an empty object

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

            // TWindow Tipo de Produto
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

                $object = new TipoProduto($key); // instantiates the Active Record

                $this->form->setData($object); // fill the form

                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear();

                // TWindow Tipo de Produto
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

