<?php

class ProdutoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'ancor907_erpsimples';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'form_Produto';

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
        $this->form->setFormTitle("Cadastro de Produtos");


        $id = new TEntry('id');
        $tipo_produto_id = new TDBCombo('tipo_produto_id', 'ancor907_erpsimples', 'TipoProduto', 'id', '{descricao}','descricao asc'  );
        $button_ = new TButton('button_');
        $ativo = new TCombo('ativo');
        $pessoa_id = new TDBCombo('pessoa_id', 'ancor907_erpsimples', 'Pessoa', 'id', '{nome}','nome asc'  );
        $button_1 = new TButton('button_1');
        $descricao = new TEntry('descricao');
        $codigo_barras = new TEntry('codigo_barras');
        $estoque_minimo = new TNumeric('estoque_minimo', '2', ',', '.' );
        $estoque_maximo = new TNumeric('estoque_maximo', '2', ',', '.' );
        $medida_id = new TDBCombo('medida_id', 'ancor907_erpsimples', 'Medida', 'id', '{descricao}','descricao asc'  );
        $button_2 = new TButton('button_2');
        $obs = new TText('obs');
        $data_registro = new TDateTime('data_registro');
        $usuario_registro = new TEntry('usuario_registro');
        $data_atualizacao = new TDateTime('data_atualizacao');
        $usuario_atualizacao = new TEntry('usuario_atualizacao');

        $tipo_produto_id->addValidation("Tipo", new TRequiredValidator()); 
        $descricao->addValidation("Descrição", new TRequiredValidator()); 
        $medida_id->addValidation("Unidade de Medida", new TRequiredValidator()); 

        $ativo->addItems(['S'=>'Sim','N'=>'Não']);
        $ativo->setValue('Sim');
        $ativo->setDefaultOption(false);

        $tipo_produto_id->autofocus = 'autofocus';

        $data_registro->setMask('dd/mm/yyyy hh:ii');
        $data_atualizacao->setMask('dd/mm/yyyy hh:ii');

        $data_registro->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_atualizacao->setDatabaseMask('yyyy-mm-dd hh:ii');

        $button_2->setAction(new TAction(['MedidaFormWindow', 'onEdit'],['oculto' => 'medida_id']), "");
        $button_1->setAction(new TAction(['PessoaFormWindow', 'onEdit'],['oculto' => 'pessoa_id']), "");
        $button_->setAction(new TAction(['TipoProdutoFormWindow', 'onEdit'],['oculto' => 'tipo_produto_id']), "");

        $button_->addStyleClass('btn-success');
        $button_1->addStyleClass('btn-success');
        $button_2->addStyleClass('btn-success');

        $button_->setImage('fas:plus #ffffff');
        $button_1->setImage('fas:plus #ffffff');
        $button_2->setImage('fas:plus #ffffff');

        $id->setEditable(false);
        $data_registro->setEditable(false);
        $usuario_registro->setEditable(false);
        $data_atualizacao->setEditable(false);
        $usuario_atualizacao->setEditable(false);

        $id->setSize('100%');
        $ativo->setSize('70%');
        $obs->setSize('100%', 70);
        $pessoa_id->setSize('85%');
        $medida_id->setSize('80%');
        $descricao->setSize('100%');
        $codigo_barras->setSize('100%');
        $estoque_minimo->setSize('70%');
        $estoque_maximo->setSize('70%');
        $data_registro->setSize('100%');
        $tipo_produto_id->setSize('80%');
        $usuario_registro->setSize('100%');
        $data_atualizacao->setSize('100%');
        $usuario_atualizacao->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Código", null, '14px', null, '100%'),$id],[new TLabel("Tipo", '#ff0000', '14px', 'B', '100%'),$tipo_produto_id,$button_],[new TLabel("Ativo", null, '14px', null, '100%'),$ativo]);
        $row1->layout = [' col-sm-2',' col-sm-4','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Fabricante", null, '14px', null, '100%'),$pessoa_id,$button_1],[]);
        $row2->layout = [' col-sm-6',' col-sm-2'];

        $row3 = $this->form->addFields([new TLabel("Descrição", '#ff0000', '14px', 'B', '100%'),$descricao],[new TLabel("Código de Barras", null, '14px', null, '100%'),$codigo_barras]);
        $row3->layout = [' col-sm-7',' col-sm-4'];

        $row4 = $this->form->addFields([new TLabel("Estoque Mínimo", null, '14px', null, '100%'),$estoque_minimo],[new TLabel("Estoque Máximo", null, '14px', null, '100%'),$estoque_maximo],[new TLabel("Unidade de Medida", '#ff0000', '14px', 'B', '100%'),$medida_id,$button_2]);
        $row4->layout = [' col-sm-2',' col-sm-2',' col-sm-5'];

        $row5 = $this->form->addFields([new TLabel("Observação", null, '14px', null, '100%'),$obs]);
        $row5->layout = [' col-sm-12'];

        $row6 = $this->form->addFields([new TLabel("Cadastrado em", null, '14px', null, '100%'),$data_registro],[new TLabel("Cadastrado por", null, '14px', null, '100%'),$usuario_registro],[new TLabel("Atualizado em", null, '14px', null, '100%'),$data_atualizacao],[new TLabel("Atualizado por", null, '14px', null, '100%'),$usuario_atualizacao]);
        $row6->layout = ['col-sm-3','col-sm-3',' col-sm-3',' col-sm-3'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'far:save #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
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

            $object = new Produto(); // create an empty object 

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

            // Instanciar produto na tabela Saldos
            $saldos = new Saldos();

            $saldos->produto_id = $object->id;
            $saldos->qtd_estoque = 0;
            $saldos->qtd_reservado = 0;
            $saldos->qtd_aguardando = 0;

            $saldos->store();
            // Fim instanciar produto na tabela Saldos

            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            $messageAction = new TAction(['ProdutoForm', 'onClear']);

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

                $object = new Produto($key); // instantiates the Active Record 

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

