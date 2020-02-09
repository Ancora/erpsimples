<?php

class SaldosReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'ancorati_erpsimplesHS';
    private static $activeRecord = 'Saldos';
    private static $primaryKey = 'id';
    private static $formName = 'formReport_Saldos';

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
        $this->form->setFormTitle("Saldos dos Produtos");

        $id = new TEntry('id');
        $produto_id = new TDBUniqueSearch('produto_id', 'ancorati_erpsimplesHS', 'Produto', 'id', 'descricao','descricao asc'  );

        $produto_id->setMask('{descricao}');
        $produto_id->setMinLength(2);

        $id->setSize(100);
        $produto_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Código", null, '14px', null, '100%'),$id],[new TLabel("Produto", null, '14px', null, '100%'),$produto_id]);
        $row1->layout = [' col-sm-2',' col-sm-6'];

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
        $container->add(TBreadCrumb::create(["Relatórios","Saldos dos Produtos"]));
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
        if (isset($data->produto_id) AND ( (is_scalar($data->produto_id) AND $data->produto_id !== '') OR (is_array($data->produto_id) AND (!empty($data->produto_id)) )) )
        {

            $filters[] = new TFilter('produto_id', '=', $data->produto_id);// create the filter
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
            // open a transaction with database 'ancorati_erpsimplesHS'
            TTransaction::open(self::$database);
            $param = [];
            // creates a repository for Saldos
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

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
                $widths = array(40,180,80,80,80);

                switch ($format)
                {
                    case 'html':
                        $tr = new TTableWriterHTML($widths);
                        break;
                    case 'xls':
                        $tr = new TTableWriterXLS($widths);
                        break;
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths, 'P');
                        break;
                    case 'rtf':
                        if (!class_exists('PHPRtfLite_Autoloader'))
                        {
                            PHPRtfLite::registerAutoloader();
                        }
                        $tr = new TTableWriterRTF($widths, 'P');
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
                                $pdf->Cell(30,10, utf8_decode('Saldos dos Produtos') ,0,0,'C');

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
                                $tr->addCell('Saldos dos Produtos', 'center', 'header', 5);
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
                    $tr->addCell("Produto", 'left', 'title');
                    $tr->addCell("Estoque", 'center', 'title');
                    $tr->addCell("Reservado", 'center', 'title');
                    $tr->addCell("Aguardando", 'center', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        $grandTotal['produto_descricao'][] = $object->produto->descricao;
                        $breakTotal['produto_descricao'][] = $object->produto->descricao;
                        $grandTotal['qtd_estoque'][] = $object->qtd_estoque;
                        $breakTotal['qtd_estoque'][] = $object->qtd_estoque;
                        $grandTotal['qtd_reservado'][] = $object->qtd_reservado;
                        $breakTotal['qtd_reservado'][] = $object->qtd_reservado;
                        $grandTotal['qtd_aguardando'][] = $object->qtd_aguardando;
                        $breakTotal['qtd_aguardando'][] = $object->qtd_aguardando;

                        $firstRow = false;

                        $tr->addRow();

                        $tr->addCell($object->id, 'center', $style);
                        $tr->addCell($object->produto->descricao, 'left', $style);
                        $tr->addCell($object->qtd_estoque, 'center', $style);
                        $tr->addCell($object->qtd_reservado, 'center', $style);
                        $tr->addCell($object->qtd_aguardando, 'center', $style);

                        $colour = !$colour;
                    }

                    $tr->addRow();

                    $grandTotal_produto_descricao = count($grandTotal['produto_descricao']);
                    $grandTotal_qtd_estoque = array_sum($grandTotal['qtd_estoque']);
                    $grandTotal_qtd_reservado = array_sum($grandTotal['qtd_reservado']);
                    $grandTotal_qtd_aguardando = array_sum($grandTotal['qtd_aguardando']);

                    $tr->addCell('', 'center', 'total');
                    $tr->addCell($grandTotal_produto_descricao, 'left', 'total');
                    $tr->addCell($grandTotal_qtd_estoque, 'center', 'total');
                    $tr->addCell($grandTotal_qtd_reservado, 'center', 'total');
                    $tr->addCell($grandTotal_qtd_aguardando, 'center', 'total');

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

