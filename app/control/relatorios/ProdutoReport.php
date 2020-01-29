<?php

class ProdutoReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'ancor907_erpsimples';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'formReport_Produto';

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
        $this->form->setFormTitle("Listagem de Produtos");

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

        $btn_ongeneratehtml = $this->form->addAction("Gerar HTML", new TAction([$this, 'onGenerateHtml']), 'far:file-code #ffffff');
        $btn_ongeneratehtml->addStyleClass('btn-primary'); 

        $btn_ongeneratepdf = $this->form->addAction("Gerar PDF", new TAction([$this, 'onGeneratePdf']), 'far:file-pdf #d44734');

        $btn_ongeneratexls = $this->form->addAction("Gerar XLS", new TAction([$this, 'onGenerateXls']), 'far:file-excel #00a65a');

        $btn_ongeneratertf = $this->form->addAction("Gerar RTF", new TAction([$this, 'onGenerateRtf']), 'far:file-alt #324bcc');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        $container->add(TBreadCrumb::create(["Relatórios","Listagem de Produtos"]));
        $container->add($this->form);

        parent::add($container);

    }

    public function onGenerateHtml($param = null) 
    {
        $this->onGenerate('html');
    }
    public function onGeneratePdf($param = null) 
    {
        $this->onGenerate('pdf');
    }
    public function onGenerateXls($param = null) 
    {
        $this->onGenerate('xls');
    }
    public function onGenerateRtf($param = null) 
    {
        $this->onGenerate('rtf');
    }

    /**
     * Register the filter in the session
     */
    public function getFilters()
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

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);

        return $filters;
    }

    public function onGenerate($format)
    {
        try
        {
            $filters = $this->getFilters();
            // open a transaction with database 'ancor907_erpsimples'
            TTransaction::open(self::$database);
            $param = [];
            // creates a repository for Produto
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $param['order'] = 'tipo_produto_id,id';
            $param['direction'] = 'asc';

            $criteria->setProperties($param);

            if ($filters)
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            if ($objects)
            {
                $widths = array(40,130,200,30,200,100,100);

                switch ($format)
                {
                    case 'html':
                        $tr = new TTableWriterHTML($widths);
                        break;
                    case 'xls':
                        $tr = new TTableWriterXLS($widths);
                        break;
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths, 'L');
                        break;
                    case 'rtf':
                        if (!class_exists('PHPRtfLite_Autoloader'))
                        {
                            PHPRtfLite::registerAutoloader();
                        }
                        $tr = new TTableWriterRTF($widths, 'L');
                        break;
                }

                if (!empty($tr))
                {
                    // create the document styles
                    $tr->addStyle('title', 'Helvetica', '10', 'B',   '#000000', '#dbdbdb');
                    $tr->addStyle('datap', 'Arial', '10', '',    '#333333', '#f0f0f0');
                    $tr->addStyle('datai', 'Arial', '10', '',    '#333333', '#ffffff');
                    $tr->addStyle('header', 'Helvetica', '16', 'B',   '#5a5a5a', '#6B6B6B');
                    $tr->addStyle('footer', 'Helvetica', '10', 'B',  '#5a5a5a', '#A3A3A3');
                    $tr->addStyle('break', 'Helvetica', '10', 'B',  '#ffffff', '#9a9a9a');
                    $tr->addStyle('total', 'Helvetica', '10', 'I',  '#000000', '#c7c7c7');
                    $tr->addStyle('breakTotal', 'Helvetica', '10', 'I',  '#000000', '#c6c8d0');

                    // Quando o relatório a ser gerado for PDF
                    if ($format == 'pdf')
                    {
                        // Adiciona o cabeçalho
                        $tr->setHeaderCallback(
                            function($tr)
                            {
                                $pdf = $tr->getNativeWriter();

                                // Define a fonte/ estilos
                                $pdf->SetFont('Arial','B',15); 

                                // Define o posicionamento do texto
                                $pdf->Cell(80); 

                                // Texto do cabeçalho
                                $pdf->Cell(30,10, utf8_decode('Listagem de Produtos') ,0,0,'C'); 

                                // Line break 
                                $pdf->Ln(20); 

                            }
                        );

                        // Adiciona o footer do relatório
                        $tr->setFooterCallback(
                            function($tr)
                            {
                                $pdf = $tr->getNativeWriter();

                                // Necessário para obter o número total de páginas
                                $pdf->AliasNbPages();

                                // Posiciona o footer no final da página
                                $pdf->SetY(-40);

                                // Define o estilho do footer
                                $pdf->SetFont('Arial'   ,'B',12); 
                                $pdf->Cell(110); 

                                // Obtém o número da página atual
                                $numero = $pdf->PageNo();

                                // Footer
                                $pdf->Cell(0,10, utf8_decode("Pg: {$numero}/{nb}") ,0,0,'R');

                                // Line break 
                                $pdf->Ln(20); 
                            }
                        );
                    }
                    else // Para os demais formatos
                    {
                         $tr->setHeaderCallback(
                            function($tr)
                            {
                                $tr->addRow();
                                $tr->addCell('Listagem de Produtos', 'center', 'header', 5);
                            }
                        );

                        $tr->setFooterCallback(
                            function($tr)
                            {
                                $tr->addRow();
                                $tr->addCell(date('d-m-Y H:i:s'), 'center', 'footer', 5);
                            }
                        );
                    }

                    // add titles row
                    $tr->addRow();
                    $tr->addCell("Código", 'center', 'title');
                    $tr->addCell("Tipo", 'center', 'title');
                    $tr->addCell("Fornecedor", 'left', 'title');
                    $tr->addCell("Ativo", 'center', 'title');
                    $tr->addCell("Descrição", 'left', 'title');
                    $tr->addCell("Data do Cadastro", 'center', 'title');
                    $tr->addCell("Usuário", 'left', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;                
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        if ($object->tipo_produto_id !== $breakValue)
                        {
                            if (!$firstRow)
                            {
                                $tr->addRow();

                                $breakTotal_descricao = count($breakTotal['descricao']);

                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell($breakTotal_descricao, 'left', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                            }
                            $tr->addRow();
                            $tr->addCell($object->render('{tipo_produto->descricao}'), 'left', 'break', 7);
                            $breakTotal = [];
                        }
                        $breakValue = $object->tipo_produto_id;

                        $grandTotal['descricao'][] = $object->descricao;
                        $breakTotal['descricao'][] = $object->descricao;

                        $firstRow = false;

                        $object->data_registro = call_user_func(function($value, $object, $row) 
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
                        }, $object->data_registro, $object, null);

                        $tr->addRow();

                        $tr->addCell($object->id, 'center', $style);
                        $tr->addCell($object->tipo_produto->descricao, 'center', $style);
                        $tr->addCell($object->pessoa->nome, 'left', $style);
                        $tr->addCell($object->ativo, 'center', $style);
                        $tr->addCell($object->descricao, 'left', $style);
                        $tr->addCell($object->data_registro, 'center', $style);
                        $tr->addCell($object->usuario_registro, 'left', $style);

                        $colour = !$colour;
                    }

                    $tr->addRow();

                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell($breakTotal_descricao, 'left', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');

                    $tr->addRow();

                    $grandTotal_descricao = count($grandTotal['descricao']);

                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell($grandTotal_descricao, 'left', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');

                    $file = 'report_'.uniqid().".{$format}";
                    // stores the file
                    if (!file_exists("app/output/{$file}") || is_writable("app/output/{$file}"))
                    {
                        $tr->save("app/output/{$file}");
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied') . ': ' . "app/output/{$file}");
                    }

                    parent::openFile("app/output/{$file}");

                    // shows the success message
                    new TMessage('info', _t('Report generated. Please, enable popups'));
                }
            }
            else
            {
                new TMessage('error', _t('No records found'));
            }

            // close the transaction
            TTransaction::close();
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

}

