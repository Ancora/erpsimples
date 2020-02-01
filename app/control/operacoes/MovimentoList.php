<?php

class MovimentoList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'ancor907_erpsimples';
    private static $activeRecord = 'Movimento';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Movimento';
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
        $this->form->setFormTitle("Movimentação");

        $id = new TEntry('id');
        $tipo_estoque = new TCombo('tipo_estoque');
        $tipo_movimento_id = new TDBCombo('tipo_movimento_id', 'ancor907_erpsimples', 'TipoMovimento', 'id', '{descricao}','descricao asc'  );
        $situacao_id = new TDBCombo('situacao_id', 'ancor907_erpsimples', 'Situacao', 'id', '{descricao}','descricao asc'  );
        $pessoa_id = new TDBUniqueSearch('pessoa_id', 'ancor907_erpsimples', 'Pessoa', 'id', 'nome','nome asc'  );
        $data_abertura_ini = new TDate('data_abertura_ini');
        $data_abertura_fim = new TDate('data_abertura_fim');
        $vlr_total_ini = new TNumeric('vlr_total_ini', '2', ',', '.' );
        $vlr_total_fim = new TNumeric('vlr_total_fim', '2', ',', '.' );
        $data_entrega_ini = new TDate('data_entrega_ini');
        $data_entrega_fim = new TDate('data_entrega_fim');

        $tipo_estoque->addItems(['E'=>'Entrada','S'=>'Saída']);
        $pessoa_id->setMinLength(2);

        $tipo_estoque->autofocus = 'autofocus';
        $tipo_movimento_id->autofocus = 'autofocus';

        $data_entrega_ini->setDatabaseMask('yyyy-mm-dd');
        $data_entrega_fim->setDatabaseMask('yyyy-mm-dd');
        $data_abertura_ini->setDatabaseMask('yyyy-mm-dd');
        $data_abertura_fim->setDatabaseMask('yyyy-mm-dd');

        $pessoa_id->setMask('{nome}');
        $data_entrega_ini->setMask('dd/mm/yyyy');
        $data_entrega_fim->setMask('dd/mm/yyyy');
        $data_abertura_ini->setMask('dd/mm/yyyy');
        $data_abertura_fim->setMask('dd/mm/yyyy');

        $id->setSize('60%');
        $pessoa_id->setSize('100%');
        $vlr_total_ini->setSize(120);
        $vlr_total_fim->setSize(120);
        $tipo_estoque->setSize('90%');
        $situacao_id->setSize('100%');
        $data_entrega_ini->setSize('80%');
        $data_entrega_fim->setSize('80%');
        $data_abertura_ini->setSize('80%');
        $data_abertura_fim->setSize('80%');
        $tipo_movimento_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Código", null, '14px', null, '100%'),$id],[new TLabel("Tipo Estoque", null, '14px', null, '100%'),$tipo_estoque],[new TLabel("Tipo Movimento", null, '14px', null, '100%'),$tipo_movimento_id],[new TLabel("Situação", null, '14px', null, '100%'),$situacao_id]);
        $row1->layout = [' col-sm-2',' col-sm-2',' col-sm-4',' col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Cliente/Fornecedor", null, '14px', null, '100%'),$pessoa_id],[new TLabel("Aberto de ...", null, '14px', null, '100%'),$data_abertura_ini],[new TLabel("... até", null, '14px', null, '100%'),$data_abertura_fim]);
        $row2->layout = ['col-sm-6',' col-sm-3',' col-sm-3'];

        $row3 = $this->form->addFields([new TLabel("Valor Total de ...", null, '14px', null, '100%'),$vlr_total_ini],[new TLabel("... até", null, '14px', null, '100%'),$vlr_total_fim],[new TLabel("Entregue de ...", null, '14px', null, '100%'),$data_entrega_ini],[new TLabel("... até", null, '14px', null, '100%'),$data_entrega_fim]);
        $row3->layout = [' col-sm-2',' col-sm-4',' col-sm-3',' col-sm-3'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $btn_onsearch->addStyleClass('btn-primary');

        $btn_onexportcsv = $this->form->addAction("Exportar como CSV", new TAction([$this, 'onExportCsv']), 'fas:file-alt #ffffff');
        $btn_onexportcsv->addStyleClass('btn-info');

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['MovimentoForm', 'onShow']), 'fas:plus #ffffff');
        $btn_onshow->addStyleClass('btn-success');

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        $this->datagrid->datatable = 'true';

        $column_id = new TDataGridColumn('id', "Código", 'center' , '69.8px');
        $column_tipo_estoque = new TDataGridColumn('tipo_estoque', "Tipo Est", 'center');
        $column_tipo_movimento_descricao = new TDataGridColumn('tipo_movimento->descricao', "Tipo Mov", 'left');
        $column_pessoa_nome = new TDataGridColumn('pessoa->nome', "Pessoa", 'left');
        $column_situacao_descricao = new TDataGridColumn('situacao->descricao', "Situacao", 'center');
        $column_data_abertura_transformed = new TDataGridColumn('data_abertura', "Aberto em", 'center');
        $column_vlr_total_transformed = new TDataGridColumn('vlr_total', "Valor Total", 'right');

        $column_vlr_total_transformed->setTotalFunction( function($values) {
            return array_sum((array) $values);
        });

        $column_data_abertura_transformed->setTransformer(function($value, $object, $row)
        {
            if(!empty(trim($value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_vlr_total_transformed->setTransformer(function($value, $object, $row)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        $order_tipo_estoque = new TAction(array($this, 'onReload'));
        $order_tipo_estoque->setParameter('order', 'tipo_estoque');
        $column_tipo_estoque->setAction($order_tipo_estoque);
        $order_data_abertura_transformed = new TAction(array($this, 'onReload'));
        $order_data_abertura_transformed->setParameter('order', 'data_abertura');
        $column_data_abertura_transformed->setAction($order_data_abertura_transformed);
        $order_vlr_total_transformed = new TAction(array($this, 'onReload'));
        $order_vlr_total_transformed->setParameter('order', 'vlr_total');
        $column_vlr_total_transformed->setAction($order_vlr_total_transformed);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_tipo_estoque);
        $this->datagrid->addColumn($column_tipo_movimento_descricao);
        $this->datagrid->addColumn($column_pessoa_nome);
        $this->datagrid->addColumn($column_situacao_descricao);
        $this->datagrid->addColumn($column_data_abertura_transformed);
        $this->datagrid->addColumn($column_vlr_total_transformed);

        $action_onEdit = new TDataGridAction(array('MovimentoForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('MovimentoList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('far:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

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
        $container->add(TBreadCrumb::create(["Operações","Movimentação"]));
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
                $object = new Movimento($key, FALSE);

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

        if (isset($data->tipo_estoque) AND ( (is_scalar($data->tipo_estoque) AND $data->tipo_estoque !== '') OR (is_array($data->tipo_estoque) AND (!empty($data->tipo_estoque)) )) )
        {

            $filters[] = new TFilter('tipo_estoque', '=', $data->tipo_estoque);// create the filter
        }

        if (isset($data->tipo_movimento_id) AND ( (is_scalar($data->tipo_movimento_id) AND $data->tipo_movimento_id !== '') OR (is_array($data->tipo_movimento_id) AND (!empty($data->tipo_movimento_id)) )) )
        {

            $filters[] = new TFilter('tipo_movimento_id', '=', $data->tipo_movimento_id);// create the filter
        }

        if (isset($data->situacao_id) AND ( (is_scalar($data->situacao_id) AND $data->situacao_id !== '') OR (is_array($data->situacao_id) AND (!empty($data->situacao_id)) )) )
        {

            $filters[] = new TFilter('situacao_id', '=', $data->situacao_id);// create the filter
        }

        if (isset($data->pessoa_id) AND ( (is_scalar($data->pessoa_id) AND $data->pessoa_id !== '') OR (is_array($data->pessoa_id) AND (!empty($data->pessoa_id)) )) )
        {

            $filters[] = new TFilter('pessoa_id', '=', $data->pessoa_id);// create the filter
        }

        if (isset($data->data_abertura_ini) AND ( (is_scalar($data->data_abertura_ini) AND $data->data_abertura_ini !== '') OR (is_array($data->data_abertura_ini) AND (!empty($data->data_abertura_ini)) )) )
        {

            $filters[] = new TFilter('data_abertura', '>=', $data->data_abertura_ini);// create the filter
        }

        if (isset($data->data_abertura_fim) AND ( (is_scalar($data->data_abertura_fim) AND $data->data_abertura_fim !== '') OR (is_array($data->data_abertura_fim) AND (!empty($data->data_abertura_fim)) )) )
        {

            $filters[] = new TFilter('data_abertura', '<=', $data->data_abertura_fim);// create the filter
        }

        if (isset($data->vlr_total_ini) AND ( (is_scalar($data->vlr_total_ini) AND $data->vlr_total_ini !== '') OR (is_array($data->vlr_total_ini) AND (!empty($data->vlr_total_ini)) )) )
        {

            $filters[] = new TFilter('vlr_total', '>=', $data->vlr_total_ini);// create the filter
        }

        if (isset($data->vlr_total_fim) AND ( (is_scalar($data->vlr_total_fim) AND $data->vlr_total_fim !== '') OR (is_array($data->vlr_total_fim) AND (!empty($data->vlr_total_fim)) )) )
        {

            $filters[] = new TFilter('vlr_total', '<=', $data->vlr_total_fim);// create the filter
        }

        if (isset($data->data_entrega_ini) AND ( (is_scalar($data->data_entrega_ini) AND $data->data_entrega_ini !== '') OR (is_array($data->data_entrega_ini) AND (!empty($data->data_entrega_ini)) )) )
        {

            $filters[] = new TFilter('data_entrega', '>=', $data->data_entrega_ini);// create the filter
        }

        if (isset($data->data_entrega_fim) AND ( (is_scalar($data->data_entrega_fim) AND $data->data_entrega_fim !== '') OR (is_array($data->data_entrega_fim) AND (!empty($data->data_entrega_fim)) )) )
        {

            $filters[] = new TFilter('data_entrega', '<=', $data->data_entrega_fim);// create the filter
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

            // creates a repository for Movimento
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

