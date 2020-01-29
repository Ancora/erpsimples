<?php

class MovimentoReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'ancor907_erpsimples';
    private static $activeRecord = 'Movimento';
    private static $primaryKey = 'id';
    private static $formName = 'formReport_Movimento';

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
        $this->form->setFormTitle("Listagem das Movimentações");

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

        $btn_ongeneratehtml = $this->form->addAction("Gerar HTML", new TAction([$this, 'onGenerateHtml']), 'far:file-code #ffffff');
        $btn_ongeneratehtml->addStyleClass('btn-primary'); 

        $btn_ongeneratepdf = $this->form->addAction("Gerar PDF", new TAction([$this, 'onGeneratePdf']), 'far:file-pdf #d44734');

        $btn_ongeneratexls = $this->form->addAction("Gerar XLS", new TAction([$this, 'onGenerateXls']), 'far:file-excel #00a65a');

        $btn_ongeneratertf = $this->form->addAction("Gerar RTF", new TAction([$this, 'onGenerateRtf']), 'far:file-alt #324bcc');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        $container->add(TBreadCrumb::create(["Relatórios","Listagem das Movimentações"]));
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
            // creates a repository for Movimento
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $param['order'] = 'data_abertura,id';
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
                $widths = array(40,20,130,130,120,120,120,120);

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
                                $pdf->Cell(30,10, utf8_decode('Listagem das Movimentações') ,0,0,'C'); 

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
                                $tr->addCell('Listagem das Movimentações', 'center', 'header', 5);
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
                    $tr->addCell("E/S", 'center', 'title');
                    $tr->addCell("Tipo Movimento", 'left', 'title');
                    $tr->addCell("Situação", 'left', 'title');
                    $tr->addCell("Valor Total", 'right', 'title');
                    $tr->addCell("Data de Entrega", 'center', 'title');
                    $tr->addCell("Data de Abertura", 'center', 'title');
                    $tr->addCell("Usuário do Registro", 'left', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;                
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        if ($object->data_abertura !== $breakValue)
                        {
                            if (!$firstRow)
                            {
                                $tr->addRow();

                                $breakTotal_vlr_total = array_sum($breakTotal['vlr_total']);

                                $breakTotal_vlr_total = call_user_func(function($value)
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
                                }, $breakTotal_vlr_total); 

                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell($breakTotal_vlr_total, 'right', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                            }
                            $tr->addRow();
                            $tr->addCell($object->render('{data_abertura}'), 'left', 'break', 8);
                            $breakTotal = [];
                        }
                        $breakValue = $object->data_abertura;

                        $grandTotal['vlr_total'][] = $object->vlr_total;
                        $breakTotal['vlr_total'][] = $object->vlr_total;

                        $firstRow = false;

                        $object->vlr_total = call_user_func(function($value, $object, $row) 
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
                        }, $object->vlr_total, $object, null);

                        $object->data_entrega = call_user_func(function($value, $object, $row) 
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
                        }, $object->data_entrega, $object, null);

                        $object->data_abertura = call_user_func(function($value, $object, $row) 
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
                        }, $object->data_abertura, $object, null);

                        $tr->addRow();

                        $tr->addCell($object->id, 'center', $style);
                        $tr->addCell($object->tipo_estoque, 'center', $style);
                        $tr->addCell($object->tipo_movimento->descricao, 'left', $style);
                        $tr->addCell($object->situacao->descricao, 'left', $style);
                        $tr->addCell($object->vlr_total, 'right', $style);
                        $tr->addCell($object->data_entrega, 'center', $style);
                        $tr->addCell($object->data_abertura, 'center', $style);
                        $tr->addCell($object->usuario_registro, 'left', $style);

                        $colour = !$colour;
                    }

                    $tr->addRow();

                    $breakTotal_vlr_total = array_sum($breakTotal['vlr_total']);

                    $breakTotal_vlr_total = call_user_func(function($value)
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
                    }, $breakTotal_vlr_total); 

                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell($breakTotal_vlr_total, 'right', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');

                    $tr->addRow();

                    $grandTotal_vlr_total = array_sum($grandTotal['vlr_total']);

                    $grandTotal_vlr_total = call_user_func(function($value)
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
                    }, $grandTotal_vlr_total); 

                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell($grandTotal_vlr_total, 'right', 'total');
                    $tr->addCell('', 'center', 'total');
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

