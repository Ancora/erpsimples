<?php

class ProdutoList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'ancor907_erpsimples';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Produto';
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
        $this->form->setFormTitle("Produtos");

        $id = new TEntry('id');
        $tipo_produto_id = new TDBCombo('tipo_produto_id', 'ancor907_erpsimples', 'TipoProduto', 'id', '{descricao}','descricao asc'  );
        $pessoa_id = new TDBUniqueSearch('pessoa_id', 'ancor907_erpsimples', 'Pessoa', 'id', 'nome','nome asc'  );
        $ativo = new TCombo('ativo');
        $descricao = new TEntry('descricao');
        $medida_id = new TDBCombo('medida_id', 'ancor907_erpsimples', 'Medida', 'id', '{descricao}','descricao asc'  );
        $data_registro_ini = new TDate('data_registro_ini');
        $data_registro_fim = new TDate('data_registro_fim');
        $usuario_registro = new TEntry('usuario_registro');

        $pessoa_id->setMinLength(2);
        $ativo->addItems(['S'=>'Sim','N'=>'Não']);

        $data_registro_ini->setDatabaseMask('yyyy-mm-dd');
        $data_registro_fim->setDatabaseMask('yyyy-mm-dd');

        $pessoa_id->setMask('{nome}');
        $data_registro_ini->setMask('dd/mm/yyyy');
        $data_registro_fim->setMask('dd/mm/yyyy');

        $id->setSize('70%');
        $ativo->setSize('70%');
        $pessoa_id->setSize('100%');
        $descricao->setSize('100%');
        $medida_id->setSize('100%');
        $tipo_produto_id->setSize('100%');
        $usuario_registro->setSize('100%');
        $data_registro_ini->setSize('100%');
        $data_registro_fim->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Código", null, '14px', null, '100%'),$id],[new TLabel("Tipo", null, '14px', null, '100%'),$tipo_produto_id],[new TLabel("Fabricante", null, '14px', null, '100%'),$pessoa_id],[new TLabel("Ativo", null, '14px', null, '100%'),$ativo]);
        $row1->layout = [' col-sm-2',' col-sm-3',' col-sm-5','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Descrição", null, '14px', null, '100%'),$descricao],[new TLabel("Unidade de Medida", null, '14px', null, '100%'),$medida_id]);
        $row2->layout = [' col-sm-7',' col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("Cadastrado de ...", null, '14px', null, '100%'),$data_registro_ini],[new TLabel("... até", null, '14px', null, '100%'),$data_registro_fim],[new TLabel("Cadastrado por", null, '14px', null, '100%'),$usuario_registro]);
        $row3->layout = [' col-sm-3',' col-sm-3',' col-sm-3'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onexportcsv = $this->form->addAction("Exportar como CSV", new TAction([$this, 'onExportCsv']), 'far:file-alt #ffffff');
        $btn_onexportcsv->addStyleClass('btn-info'); 

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['ProdutoForm', 'onShow']), 'fas:plus #ffffff');
        $btn_onshow->addStyleClass('btn-success'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        $this->datagrid->datatable = 'true';

        $column_id = new TDataGridColumn('id', "Código", 'center');
        $column_descricao = new TDataGridColumn('descricao', "Descrição", 'left');
        $column_tipo_produto_descricao = new TDataGridColumn('tipo_produto->descricao', "Tipo", 'left');
        $column_ativo_transformed = new TDataGridColumn('ativo', "Ativo", 'left');
        $column_pessoa_nome = new TDataGridColumn('pessoa->nome', "Fabricante", 'left');
        $column_medida_descricao = new TDataGridColumn('medida->descricao', "Unid de Med", 'left');
        $column_data_registro_transformed = new TDataGridColumn('data_registro', "Cadastrado em", 'left');
        $column_usuario_registro = new TDataGridColumn('usuario_registro', "Usuário", 'left');

        $column_ativo_transformed->setTransformer(function($value, $object, $row)
        {
            //code here
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

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_tipo_produto_descricao);
        $this->datagrid->addColumn($column_ativo_transformed);
        $this->datagrid->addColumn($column_pessoa_nome);
        $this->datagrid->addColumn($column_medida_descricao);
        $this->datagrid->addColumn($column_data_registro_transformed);
        $this->datagrid->addColumn($column_usuario_registro);

        $action_onEdit = new TDataGridAction(array('ProdutoForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('ProdutoList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('far:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

        $action_onAtivaDesativa = new TDataGridAction(array('ProdutoList', 'onAtivaDesativa'));
        $action_onAtivaDesativa->setUseButton(false);
        $action_onAtivaDesativa->setButtonClass('btn btn-default btn-sm');
        $action_onAtivaDesativa->setLabel("Ativar/Desativar");
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
        $container->add(TBreadCrumb::create(["Cadastros","Produtos"]));
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
                $object = new Produto($key, FALSE); 

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
            $object = Produto::find($param['id']);
            if ($object instanceof Produto)
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

        if (isset($data->tipo_produto_id) AND ( (is_scalar($data->tipo_produto_id) AND $data->tipo_produto_id !== '') OR (is_array($data->tipo_produto_id) AND (!empty($data->tipo_produto_id)) )) )
        {

            $filters[] = new TFilter('tipo_produto_id', 'like', "%{$data->tipo_produto_id}%");// create the filter 
        }

        if (isset($data->pessoa_id) AND ( (is_scalar($data->pessoa_id) AND $data->pessoa_id !== '') OR (is_array($data->pessoa_id) AND (!empty($data->pessoa_id)) )) )
        {

            $filters[] = new TFilter('pessoa_id', 'in', "(SELECT id FROM pessoa WHERE nome like '%{$data->pessoa_id}%')");// create the filter 
        }

        if (isset($data->ativo) AND ( (is_scalar($data->ativo) AND $data->ativo !== '') OR (is_array($data->ativo) AND (!empty($data->ativo)) )) )
        {

            $filters[] = new TFilter('ativo', '=', $data->ativo);// create the filter 
        }

        if (isset($data->descricao) AND ( (is_scalar($data->descricao) AND $data->descricao !== '') OR (is_array($data->descricao) AND (!empty($data->descricao)) )) )
        {

            $filters[] = new TFilter('descricao', 'like', "%{$data->descricao}%");// create the filter 
        }

        if (isset($data->medida_id) AND ( (is_scalar($data->medida_id) AND $data->medida_id !== '') OR (is_array($data->medida_id) AND (!empty($data->medida_id)) )) )
        {

            $filters[] = new TFilter('medida_id', 'like', "%{$data->medida_id}%");// create the filter 
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

            // creates a repository for Produto
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

