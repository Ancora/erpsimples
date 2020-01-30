<?php

class PessoaForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'ancor907_erpsimples';
    private static $activeRecord = 'Pessoa';
    private static $primaryKey = 'id';
    private static $formName = 'form_Pessoa';

    use Adianti\Base\AdiantiMasterDetailTrait;

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
        $this->form->setFormTitle("Cadastro de Pessoas");

        $criteria_grupo = new TCriteria();

        $id = new TEntry('id');
        $tipo_pessoa = new TCombo('tipo_pessoa');
        $ativo = new TCombo('ativo');
        $grupo = new TDBCheckGroup('grupo', 'ancor907_erpsimples', 'Grupo', 'id', '{descricao}','descricao asc' , $criteria_grupo );
        $nome = new TEntry('nome');
        $nome_reduzido = new TEntry('nome_reduzido');
        $sexo = new TCombo('sexo');
        $rg = new TEntry('rg');
        $orgao_emissor = new TEntry('orgao_emissor');
        $data_aniversario = new TDate('data_aniversario');
        $cpf_cnpj = new TEntry('cpf_cnpj');
        $insc_municipal = new TEntry('insc_municipal');
        $insc_estadual = new TEntry('insc_estadual');
        $logradouro = new TEntry('logradouro');
        $numero = new TEntry('numero');
        $complemento = new TEntry('complemento');
        $bairro = new TEntry('bairro');
        $cidade_uf_id = new TDBCombo('cidade_uf_id', 'ancor907_erpsimples', 'Uf', 'id', '{sigla}','descricao asc'  );
        $cidade_id = new TCombo('cidade_id');
        $button_ = new TButton('button_');
        $cep = new TEntry('cep');
        $email = new TEntry('email');
        $tel_celular = new TEntry('tel_celular');
        $tel_fixo = new TEntry('tel_fixo');
        $obs = new TText('obs');
        $data_registro = new TDateTime('data_registro');
        $usuario_registro = new TEntry('usuario_registro');
        $data_atualizacao = new TDateTime('data_atualizacao');
        $usuario_atualizacao = new TEntry('usuario_atualizacao');
        $contato_pessoa_nome = new TEntry('contato_pessoa_nome');
        $contato_pessoa_nome_reduzido = new TEntry('contato_pessoa_nome_reduzido');
        $contato_pessoa_cargo = new TEntry('contato_pessoa_cargo');
        $contato_pessoa_email = new TEntry('contato_pessoa_email');
        $contato_pessoa_tel_celular = new TEntry('contato_pessoa_tel_celular');
        $contato_pessoa_tel_fixo = new TEntry('contato_pessoa_tel_fixo');
        $contato_pessoa_id = new THidden('contato_pessoa_id');

        $cidade_uf_id->setChangeAction(new TAction([$this,'onChangecidade_uf_id']));
        $tipo_pessoa->setChangeAction(new TAction([$this,'onChangeTipoPessoa']));

        $tipo_pessoa->addValidation("Tipo", new TRequiredValidator());
        $ativo->addValidation("Ativo", new TRequiredValidator());
        $grupo->addValidation("Grupo", new TRequiredValidator());
        $nome->addValidation("Nome", new TRequiredValidator());
        $cidade_uf_id->addValidation("UF", new TRequiredValidator());
        $cidade_id->addValidation("Cidade", new TRequiredValidator());

        $ativo->setValue('Sim');
        $ativo->setDefaultOption(false);
        $grupo->setLayout('horizontal');
        $grupo->setUseButton();
        $grupo->setBreakItems(4);
        $cidade_id->enableSearch();
        $button_->setAction(new TAction(['CidadeFormWindow', 'onEdit'],['oculto' => 'cidade_id']), "");
        $button_->addStyleClass('btn-success');
        $button_->setImage('fas:plus #ffffff');
        $ativo->addItems(['S'=>'Sim','N'=>'Não']);
        $sexo->addItems(['F'=>'Feminino','M'=>'Masculino']);
        $tipo_pessoa->addItems(['F'=>'Física','J'=>'Jurídica']);

        $cidade_id->autofocus = 'autofocus';
        $tipo_pessoa->autofocus = 'autofocus';

        $data_aniversario->setDatabaseMask('yyyy-mm-dd');
        $data_registro->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_atualizacao->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setEditable(false);
        $data_registro->setEditable(false);
        $usuario_registro->setEditable(false);
        $data_atualizacao->setEditable(false);
        $usuario_atualizacao->setEditable(false);

        $cep->setMask('99999-999');
        $numero->setMask('(999999)');
        $tel_fixo->setMask('(99)9999-9999');
        $tel_celular->setMask('(99)99999-9999');
        $data_aniversario->setMask('dd/mm/yyyy');
        $data_registro->setMask('dd/mm/yyyy hh:ii');
        $data_atualizacao->setMask('dd/mm/yyyy hh:ii');
        $contato_pessoa_tel_fixo->setMask('(99)9999-9999');
        $contato_pessoa_tel_celular->setMask('(99)99999-9999');

        $id->setSize('100%');
        $grupo->setSize(210);
        $rg->setSize('100%');
        $cep->setSize('100%');
        $ativo->setSize('70%');
        $nome->setSize('100%');
        $sexo->setSize('100%');
        $email->setSize('100%');
        $bairro->setSize('100%');
        $numero->setSize('100%');
        $obs->setSize('100%', 70);
        $tel_fixo->setSize('100%');
        $cidade_id->setSize('80%');
        $cpf_cnpj->setSize('100%');
        $logradouro->setSize('100%');
        $tel_celular->setSize('100%');
        $complemento->setSize('100%');
        $tipo_pessoa->setSize('100%');
        $cidade_uf_id->setSize('100%');
        $insc_estadual->setSize('100%');
        $orgao_emissor->setSize('100%');
        $nome_reduzido->setSize('100%');
        $data_registro->setSize('100%');
        $insc_municipal->setSize('100%');
        $data_aniversario->setSize('100%');
        $usuario_registro->setSize('100%');
        $data_atualizacao->setSize('100%');
        $usuario_atualizacao->setSize('100%');
        $contato_pessoa_nome->setSize('100%');
        $contato_pessoa_cargo->setSize('100%');
        $contato_pessoa_email->setSize('100%');
        $contato_pessoa_tel_fixo->setSize('100%');
        $contato_pessoa_tel_celular->setSize('100%');
        $contato_pessoa_nome_reduzido->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Código", '#ff0000', '14px', 'B', '100%'),$id],[new TLabel("Tipo", '#ff0000', '14px', 'B', '100%'),$tipo_pessoa],[new TLabel("Ativo", '#ff0000', '14px', 'B', '100%'),$ativo]);
        $row1->layout = [' col-sm-2',' col-sm-2',' col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Grupo", '#ff0000', '14px', 'B', '100%'),$grupo]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([new TLabel("Nome", '#ff0000', '14px', 'B', '100%'),$nome],[new TLabel("Reduzido", null, '14px', null, '100%'),$nome_reduzido]);
        $row3->layout = [' col-sm-8',' col-sm-4'];

        $row4 = $this->form->addFields([new TLabel("Sexo", null, '14px', null, '100%'),$sexo],[new TLabel("Identidade", null, '14px', null, '100%'),$rg],[new TLabel("Órgão Emissor", null, '14px', null, '100%'),$orgao_emissor]);
        $row4->layout = [' col-sm-2',' col-sm-3',' col-sm-4'];

        $row5 = $this->form->addFields([new TLabel("Data de Aniversário/Fundação", null, '14px', null, '100%'),$data_aniversario],[new TLabel("CPF/CNPJ", '#ff0000', '14px', 'B', '100%'),$cpf_cnpj],[new TLabel("Inscrição Municipal", null, '14px', null, '100%'),$insc_municipal],[new TLabel("Inscrição Estadual", null, '14px', null, '100%'),$insc_estadual]);
        $row5->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row6 = $this->form->addFields([new TLabel("Rua/Avenida...", null, '14px', null, '100%'),$logradouro],[new TLabel("Número", null, '14px', null, '100%'),$numero],[new TLabel("Complemento (bloco, apart, sala etc.)", null, '14px', null, '100%'),$complemento]);
        $row6->layout = ['col-sm-6',' col-sm-2',' col-sm-4'];

        $row7 = $this->form->addFields([new TLabel("Bairro", null, '14px', null, '100%'),$bairro],[new TLabel("UF", '#ff0000', '14px', 'B', '100%'),$cidade_uf_id],[new TLabel("Cidade", '#ff0000', '14px', 'B', '100%'),$cidade_id,$button_],[new TLabel("CEP", null, '14px', null, '100%'),$cep]);
        $row7->layout = [' col-sm-4',' col-sm-1',' col-sm-5','col-sm-2'];

        $row8 = $this->form->addFields([new TLabel("E-mail", null, '14px', null, '100%'),$email],[new TLabel("Tel Celular", null, '14px', null, '100%'),$tel_celular],[new TLabel("Tel Fixo", null, '14px', null, '100%'),$tel_fixo]);
        $row8->layout = [' col-sm-6',' col-sm-3',' col-sm-3'];

        $row9 = $this->form->addFields([new TLabel("Observação", null, '14px', null, '100%'),$obs]);
        $row9->layout = [' col-sm-12'];

        $row10 = $this->form->addFields([new TLabel("Cadastrado em", null, '14px', null, '100%'),$data_registro],[new TLabel("Cadastrado por", null, '14px', null, '100%'),$usuario_registro],[new TLabel("Atualizado em", null, '14px', null, '100%'),$data_atualizacao],[new TLabel("Atualizado por", null, '14px', null, '100%'),$usuario_atualizacao]);
        $row10->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row11 = $this->form->addFields([new TFormSeparator("Contato", '#333333', '18', '#ff0000')]);
        $row11->layout = [' col-sm-12'];

        $row12 = $this->form->addFields([new TLabel("Nome Contato", null, '14px', null, '100%'),$contato_pessoa_nome],[new TLabel("Apelido", null, '14px', null, '100%'),$contato_pessoa_nome_reduzido]);
        $row12->layout = [' col-sm-8',' col-sm-4'];

        $row13 = $this->form->addFields([new TLabel("Cargo/Função", null, '14px', null, '100%'),$contato_pessoa_cargo],[new TLabel("E-mail", null, '14px', null, '100%'),$contato_pessoa_email]);
        $row13->layout = [' col-sm-6','col-sm-6'];

        $row14 = $this->form->addFields([new TLabel("Tel Celular", null, '14px', null, '100%'),$contato_pessoa_tel_celular],[new TLabel("Tel Fixo", null, '14px', null, '100%'),$contato_pessoa_tel_fixo]);
        $row14->layout = ['col-sm-3','col-sm-3'];

        $row15 = $this->form->addFields([$contato_pessoa_id]);
        $add_contato_pessoa = new TButton('add_contato_pessoa');

        $action_contato_pessoa = new TAction(array($this, 'onAddContatoPessoa'));

        $add_contato_pessoa->setAction($action_contato_pessoa, "Adicionar");
        $add_contato_pessoa->setImage('fas:plus #000000');

        $this->form->addFields([$add_contato_pessoa]);

        $detailDatagrid = new TQuickGrid;
        $detailDatagrid->disableHtmlConversion();
        $this->contato_pessoa_list = new BootstrapDatagridWrapper($detailDatagrid);
        $this->contato_pessoa_list->style = 'width:100%';
        $this->contato_pessoa_list->class .= ' table-bordered';
        $this->contato_pessoa_list->disableDefaultClick();
        $this->contato_pessoa_list->addQuickColumn('', 'edit', 'left', 50);
        $this->contato_pessoa_list->addQuickColumn('', 'delete', 'left', 50);

        $column_contato_pessoa_nome = $this->contato_pessoa_list->addQuickColumn("Nome", 'contato_pessoa_nome', 'left' , '57%');
        $column_contato_pessoa_cargo = $this->contato_pessoa_list->addQuickColumn("Cargo/Função", 'contato_pessoa_cargo', 'left' , '30%');

        $this->contato_pessoa_list->createModel();
        $this->form->addContent([$this->contato_pessoa_list]);

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

    public static function onChangecidade_uf_id($param)
    {
        try
        {

            if (isset($param['cidade_uf_id']) && $param['cidade_uf_id'])
            {
                $criteria = TCriteria::create(['uf_id' => (int) $param['cidade_uf_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'cidade_id', 'ancor907_erpsimples', 'Cidade', 'id', '{descricao}', 'descricao asc', $criteria, TRUE);
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
                TScript::create( "$('label:contains(\"Reduzido\")').html('Apelido');" );
                TScript::create( "$('label:contains(\"Data de Aniversário/Fundação\")').html('Data de Aniversário');" );
                TScript::create( "$('label:contains(\"CPF/CNPJ\")').html('CPF');" );

                TScript::create( "$('label:contains(\"Inscrição Municipal\")').hide();");
                TScript::create( "$(\"[name='insc_municipal']\").closest('.fb-inline-field-container').hide()");
                TScript::create( "$('label:contains(\"Inscrição Estadual\")').hide();");
                TScript::create( "$(\"[name='insc_estadual']\").closest('.fb-inline-field-container').hide()");

            }
            elseif( isset($param['key']) && $param['key'] == 'J')
            {
                TScript::create( "$('input[name=cpf_cnpj]').off();
                                  $('input[name=cpf_cnpj]').val('');
                                  $('input[name=cpf_cnpj]').keypress(function() {  tentry_mask(this, event, '99.999.999/9999-99') });" );

                TScript::create( "$('label:contains(\"Nome\")').html('Razão Social');" );
                TScript::create( "$('label:contains(\"Reduzido\")').html('Nome Fantasia');" );
                TScript::create( "$('label:contains(\"Data de Aniversário/Fundação\")').html('Data da Fundação');" );
                TScript::create( "$('label:contains(\"CPF/CNPJ\")').html('CNPJ');" );

                TScript::create( "$('label:contains(\"Sexo\")').hide();");
                TScript::create( "$(\"[name='sexo']\").closest('.fb-inline-field-container').hide()");
                TScript::create( "$('label:contains(\"Identidade\")').hide();");
                TScript::create( "$(\"[name='rg']\").closest('.fb-inline-field-container').hide()");
                TScript::create( "$('label:contains(\"Órgão Emissor\")').hide();");
                TScript::create( "$(\"[name='orgao_emissor']\").closest('.fb-inline-field-container').hide()");

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

            // Validação CPF ou CNPJ e e-mails
            if (($data->nome) != "Consumidor Final") {
                if($data->tipo_pessoa == "F")
                {
                    (new TCPFValidator())->validate('CPF',$data->cpf_cnpj);
                }
                if($data->tipo_pessoa == "J")
                {
                    (new TCNPJValidator())->validate('CNPJ',$data->cpf_cnpj);
                }
            } else {
                $data->cpf_cnpj = "000.000.000-00";
            }

            (new TEmailValidator())->validate('E-mail',$data->email);
            (new TEmailValidator())->validate('E-mail do Contato',$data->contato_pessoa_email);
            // Fim validação

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

            $contato_pessoa_items = $this->storeItems('Contato', 'pessoa_id', $object, 'contato_pessoa', function($masterObject, $detailObject){

                //code here

            });

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id;

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            $messageAction = new TAction(['PessoaForm', 'onClear']);

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

                $object = new Pessoa($key); // instantiates the Active Record

                                $object->cidade_uf_id = $object->cidade->uf_id;

                $criteria = TCriteria::create(['pessoa_id'=>$object->id]);
                $object->grupo = PessoaGrupo::getIndexedArray('grupo_id', 'grupo_id', $criteria);

                $contato_pessoa_items = $this->loadItems('Contato', 'pessoa_id', $object, 'contato_pessoa', function($masterObject, $detailObject, $objectItems){

                //code here

                });

                $this->form->setData($object); // fill the form

                $this->fireEvents($object);
                $this->onReload();

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

        TSession::setValue('contato_pessoa_items', null);

        $this->onReload();
    }

    public function onAddContatoPessoa( $param )
    {
        try
        {
            $data = $this->form->getData();

            $contato_pessoa_items = TSession::getValue('contato_pessoa_items');
            $key = isset($data->contato_pessoa_id) && $data->contato_pessoa_id ? $data->contato_pessoa_id : uniqid();
            $fields = [];

            $fields['contato_pessoa_nome'] = $data->contato_pessoa_nome;
            $fields['contato_pessoa_nome_reduzido'] = $data->contato_pessoa_nome_reduzido;
            $fields['contato_pessoa_cargo'] = $data->contato_pessoa_cargo;
            $fields['contato_pessoa_email'] = $data->contato_pessoa_email;
            $fields['contato_pessoa_tel_celular'] = $data->contato_pessoa_tel_celular;
            $fields['contato_pessoa_tel_fixo'] = $data->contato_pessoa_tel_fixo;
            $contato_pessoa_items[ $key ] = $fields;

            TSession::setValue('contato_pessoa_items', $contato_pessoa_items);

            $data->contato_pessoa_id = '';
            $data->contato_pessoa_nome = '';
            $data->contato_pessoa_nome_reduzido = '';
            $data->contato_pessoa_cargo = '';
            $data->contato_pessoa_email = '';
            $data->contato_pessoa_tel_celular = '';
            $data->contato_pessoa_tel_fixo = '';

            $this->form->setData($data);
            $this->fireEvents($data);
            $this->onReload( $param );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());
            $this->fireEvents($data);
            new TMessage('error', $e->getMessage());
        }
    }

    public function onEditContatoPessoa( $param )
    {
        $data = $this->form->getData();

        // read session items
        $items = TSession::getValue('contato_pessoa_items');

        // get the session item
        $item = $items[$param['contato_pessoa_id_row_id']];

        $data->contato_pessoa_nome = $item['contato_pessoa_nome'];
        $data->contato_pessoa_nome_reduzido = $item['contato_pessoa_nome_reduzido'];
        $data->contato_pessoa_cargo = $item['contato_pessoa_cargo'];
        $data->contato_pessoa_email = $item['contato_pessoa_email'];
        $data->contato_pessoa_tel_celular = $item['contato_pessoa_tel_celular'];
        $data->contato_pessoa_tel_fixo = $item['contato_pessoa_tel_fixo'];

        $data->contato_pessoa_id = $param['contato_pessoa_id_row_id'];

        // fill product fields
        $this->form->setData( $data );

        $this->fireEvents($data);

        $this->onReload( $param );

    }

    public function onDeleteContatoPessoa( $param )
    {
        $data = $this->form->getData();

        $data->contato_pessoa_nome = '';
        $data->contato_pessoa_nome_reduzido = '';
        $data->contato_pessoa_cargo = '';
        $data->contato_pessoa_email = '';
        $data->contato_pessoa_tel_celular = '';
        $data->contato_pessoa_tel_fixo = '';

        // clear form data
        $this->form->setData( $data );

        // read session items
        $items = TSession::getValue('contato_pessoa_items');

        // delete the item from session
        unset($items[$param['contato_pessoa_id_row_id']]);
        TSession::setValue('contato_pessoa_items', $items);

        $this->fireEvents($data);

        // reload sale items
        $this->onReload( $param );

    }

    public function onReloadContatoPessoa( $param )
    {
        $items = TSession::getValue('contato_pessoa_items');

        $this->contato_pessoa_list->clear();

        if($items)
        {
            $cont = 1;
            foreach ($items as $key => $item)
            {
                $rowItem = new StdClass;

                $action_del = new TAction(array($this, 'onDeleteContatoPessoa'));
                $action_del->setParameter('contato_pessoa_id_row_id', $key);
                $action_del->setParameter('row_data', base64_encode(serialize($item)));
                $action_del->setParameter('key', $key);

                $action_edi = new TAction(array($this, 'onEditContatoPessoa'));
                $action_edi->setParameter('contato_pessoa_id_row_id', $key);
                $action_edi->setParameter('row_data', base64_encode(serialize($item)));
                $action_edi->setParameter('key', $key);

                $button_del = new TButton('delete_contato_pessoa'.$cont);
                $button_del->setAction($action_del, '');
                $button_del->setFormName($this->form->getName());
                $button_del->class = 'btn btn-link btn-sm';
                $button_del->title = "Excluir";
                $button_del->setImage('far:trash-alt #dd5a43');

                $rowItem->delete = $button_del;

                $button_edi = new TButton('edit_contato_pessoa'.$cont);
                $button_edi->setAction($action_edi, '');
                $button_edi->setFormName($this->form->getName());
                $button_edi->class = 'btn btn-link btn-sm';
                $button_edi->title = "Editar";
                $button_edi->setImage('far:edit #478fca');

                $rowItem->edit = $button_edi;

                $rowItem->contato_pessoa_nome = isset($item['contato_pessoa_nome']) ? $item['contato_pessoa_nome'] : '';
                $rowItem->contato_pessoa_nome_reduzido = isset($item['contato_pessoa_nome_reduzido']) ? $item['contato_pessoa_nome_reduzido'] : '';
                $rowItem->contato_pessoa_cargo = isset($item['contato_pessoa_cargo']) ? $item['contato_pessoa_cargo'] : '';
                $rowItem->contato_pessoa_email = isset($item['contato_pessoa_email']) ? $item['contato_pessoa_email'] : '';
                $rowItem->contato_pessoa_tel_celular = isset($item['contato_pessoa_tel_celular']) ? $item['contato_pessoa_tel_celular'] : '';
                $rowItem->contato_pessoa_tel_fixo = isset($item['contato_pessoa_tel_fixo']) ? $item['contato_pessoa_tel_fixo'] : '';

                $row = $this->contato_pessoa_list->addItem($rowItem);

                $cont++;
            }
        }
    }

    public function onShow($param = null)
    {
        TSession::setValue('contato_pessoa_items', null);

        $this->onReload();

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

    public function onReload($params = null)
    {
        $this->loaded = TRUE;

        $this->onReloadContatoPessoa($params);
    }

    public function show()
    {
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') )
        {
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }

}

