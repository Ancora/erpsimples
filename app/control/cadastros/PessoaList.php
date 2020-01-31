<?php

class PessoaList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'ancor907_erpsimples';
    private static $activeRecord = 'Pessoa';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Pessoa';
    private $showMethods = ['onReload', 'onSearch'];

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Pessoas");

        $id = new TEntry('id');
        $tipo_pessoa = new TCombo('tipo_pessoa');
        $ativo = new TCombo('ativo');
        $nome = new TEntry('nome');
        $data_registro_ini = new TDate('data_registro_ini');
        $data_registro_fim = new TDate('data_registro_fim');
        $usuario_registro = new TEntry('usuario_registro');

        $ativo->addItems(['S'=>'Sim','N'=>'Não']);
        $tipo_pessoa->addItems(['F'=>'Física','J'=>'Jurídica']);

        $id->autofocus = 'autofocus';

        $data_registro_ini->setMask('dd/mm/yyyy');
        $data_registro_fim->setMask('dd/mm/yyyy');

        $data_registro_ini->setDatabaseMask('yyyy-mm-dd');
        $data_registro_fim->setDatabaseMask('yyyy-mm-dd');

        $id->setSize('100%');
        $ativo->setSize('70%');
        $nome->setSize('100%');
        $tipo_pessoa->setSize('70%');
        $usuario_registro->setSize('100%');
        $data_registro_ini->setSize('100%');
        $data_registro_fim->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Código", null, '14px', null, '100%'),$id],[new TLabel("Tipo", null, '14px', null, '100%'),$tipo_pessoa],[new TLabel("Ativo", null, '14px', null, '100%'),$ativo]);
        $row1->layout = [' col-sm-2',' col-sm-3','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Descrição", null, '14px', null, '100%'),$nome]);
        $row2->layout = ['col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Cadastrado de ...", null, '14px', null, '100%'),$data_registro_ini],[new TLabel("... até", null, '14px', null, '100%'),$data_registro_fim],[new TLabel("Usuário", null, '14px', null, '100%'),$usuario_registro]);
        $row3->layout = [' col-sm-3',' col-sm-3',' col-sm-3'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onexportcsv = $this->form->addAction("Exportar como CSV", new TAction([$this, 'onExportCsv']), 'far:file-alt #ffffff');
        $btn_onexportcsv->addStyleClass('btn-info'); 

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['PessoaForm', 'onShow']), 'fas:plus #ffffff');
        $btn_onshow->addStyleClass('btn-success'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', "Código", 'center');
        $column_nome = new TDataGridColumn('nome', "Descrição", 'left');
        $column_tipo_pessoa_transformed = new TDataGridColumn('tipo_pessoa', "Tipo", 'left');
        $column_ativo_transformed = new TDataGridColumn('ativo', "Ativo", 'center');
        $column_data_registro_transformed = new TDataGridColumn('data_registro', "Cadastrado em", 'center');
        $column_usuario_registro = new TDataGridColumn('usuario_registro', "Usuário", 'left');

        $column_tipo_pessoa_transformed->setTransformer(function($value, $object, $row)
        {
            if ($object->tipo_pessoa == "F") {
                return '<span>Física</span>';
            }

            if ($object->tipo_pessoa == "J") {
                return '<span>Jurídica</span>';
            }

        });

        $column_ativo_transformed->setTransformer(function($value, $object, $row)
        {
            if ($object->ativo == "S") {
                return '<span style="background-color: green !important;" class="label label-success">Sim</span>';
            }

            if ($object->ativo == "N") {
                return '<span style="background-color: red !important;" class="label label-danger">Não</span>';
            }

        });

        $column_data_registro_transformed->setTransformer(function($value, $object, $row)
        {
            if(!empty(trim($value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y H:i');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });        

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $column_nome->setAction($order_nome);
        $order_tipo_pessoa_transformed = new TAction(array($this, 'onReload'));
        $order_tipo_pessoa_transformed->setParameter('order', 'tipo_pessoa');
        $column_tipo_pessoa_transformed->setAction($order_tipo_pessoa_transformed);
        $order_ativo_transformed = new TAction(array($this, 'onReload'));
        $order_ativo_transformed->setParameter('order', 'ativo');
        $column_ativo_transformed->setAction($order_ativo_transformed);
        $order_data_registro_transformed = new TAction(array($this, 'onReload'));
        $order_data_registro_transformed->setParameter('order', 'data_registro');
        $column_data_registro_transformed->setAction($order_data_registro_transformed);
        $order_usuario_registro = new TAction(array($this, 'onReload'));
        $order_usuario_registro->setParameter('order', 'usuario_registro');
        $column_usuario_registro->setAction($order_usuario_registro);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_tipo_pessoa_transformed);
        $this->datagrid->addColumn($column_ativo_transformed);
        $this->datagrid->addColumn($column_data_registro_transformed);
        $this->datagrid->addColumn($column_usuario_registro);

        $action_onEdit = new TDataGridAction(array('PessoaForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('PessoaList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('far:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

        $action_onAtivaDesativa = new TDataGridAction(array('PessoaList', 'onAtivaDesativa'));
        $action_onAtivaDesativa->setUseButton(false);
        $action_onAtivaDesativa->setButtonClass('btn btn-default btn-sm');
        $action_onAtivaDesativa->setLabel('');
        $action_onAtivaDesativa->setImage('fas:power-off #008000');
        $action_onAtivaDesativa->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onAtivaDesativa);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup;
        $panel->add($this->datagrid);

        $panel->addFooter($this->pageNavigation);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(TBreadCrumb::create(["Cadastros","Pessoas"]));
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

    }

    public function onExportCsv($param = null) 
    {
        try
        {
            $this->onSearch();

            TTransaction::open(self::$database); // open a transaction
            $repository = new TRepository(self::$activeRecord); // creates a repository for Customer
            $criteria = $this->filter_criteria;

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            $records = $repository->load($criteria); // load the objects according to criteria
            if ($records)
            {
                $file = 'tmp/'.uniqid().'.csv';
                $handle = fopen($file, 'w');
                $columns = $this->datagrid->getColumns();

                $csvColumns = [];
                foreach($columns as $column)
                {
                    $csvColumns[] = $column->getLabel();
                }
                fputcsv($handle, $csvColumns, ';');

                foreach ($records as $record)
                {
                    $csvColumns = [];
                    foreach($columns as $column)
                    {
                        $name = $column->getName();
                        $csvColumns[] = $record->{$name};
                    }
                    fputcsv($handle, $csvColumns, ';');
                }
                fclose($handle);

                TPage::openFile($file);
            }
            else
            {
                new TMessage('info', _t('No records found'));       
            }

            TTransaction::close(); // close the transaction
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public function onDelete($param = null) 
    { 
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                // get the paramseter $key
                $key = $param['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                // instantiates object
                $object = new Pessoa($key, FALSE); 

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload( $param );
                // shows the success message
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
            }
            catch (Exception $e) // in case of exception
            {
                // shows the exception error message
                new TMessage('error', $e->getMessage());
                // undo all pending operations
                TTransaction::rollback();
            }
        }
        else
        {
            // define the delete action
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters($param); // pass the key paramseter ahead
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
        }
    }
    public function onAtivaDesativa($param = null) 
    {
        try 
        {
            //code here
            TTransaction::open(self::$database);
            $object = Pessoa::find($param['id']);
            if ($object instanceof Pessoa)
            {
                $object->ativo = $object->ativo == 'S' ? 'N' : 'S';
                $object->data_atualizacao = date('Y-m-d H:i:s');
                $object->usuario_atualizacao = TSession::getValue('username');
                $object->store();
            }

            TTransaction::close();

            $this->onReload($param);

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }

        if (isset($data->tipo_pessoa) AND ( (is_scalar($data->tipo_pessoa) AND $data->tipo_pessoa !== '') OR (is_array($data->tipo_pessoa) AND (!empty($data->tipo_pessoa)) )) )
        {

            $filters[] = new TFilter('tipo_pessoa', '=', $data->tipo_pessoa);// create the filter 
        }

        if (isset($data->ativo) AND ( (is_scalar($data->ativo) AND $data->ativo !== '') OR (is_array($data->ativo) AND (!empty($data->ativo)) )) )
        {

            $filters[] = new TFilter('ativo', '=', $data->ativo);// create the filter 
        }

        if (isset($data->nome) AND ( (is_scalar($data->nome) AND $data->nome !== '') OR (is_array($data->nome) AND (!empty($data->nome)) )) )
        {

            $filters[] = new TFilter('nome', 'like', "%{$data->nome}%");// create the filter 
        }

        if (isset($data->data_registro_ini) AND ( (is_scalar($data->data_registro_ini) AND $data->data_registro_ini !== '') OR (is_array($data->data_registro_ini) AND (!empty($data->data_registro_ini)) )) )
        {

            $filters[] = new TFilter('data_registro', '>=', $data->data_registro_ini);// create the filter 
        }

        if (isset($data->data_registro_fim) AND ( (is_scalar($data->data_registro_fim) AND $data->data_registro_fim !== '') OR (is_array($data->data_registro_fim) AND (!empty($data->data_registro_fim)) )) )
        {

            $filters[] = new TFilter('data_registro', '<=', $data->data_registro_fim);// create the filter 
        }

        if (isset($data->usuario_registro) AND ( (is_scalar($data->usuario_registro) AND $data->usuario_registro !== '') OR (is_array($data->usuario_registro) AND (!empty($data->usuario_registro)) )) )
        {

            $filters[] = new TFilter('usuario_registro', 'like', "%{$data->usuario_registro}%");// create the filter 
        }

        $param = array();
        $param['offset']     = 0;
        $param['first_page'] = 1;

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload($param);
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'ancor907_erpsimples'
            TTransaction::open(self::$database);

            // creates a repository for Pessoa
            $repository = new TRepository(self::$activeRecord);
            $limit = 20;

            $criteria = $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'id';    
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid

                    $this->datagrid->addItem($object);

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit

            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onShow($param = null)
    {

    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  $this->showMethods))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }

}

