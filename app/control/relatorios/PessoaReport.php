<?php

class PessoaReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'ancorati_erpsimplesHS';
    private static $activeRecord = 'Pessoa';
    private static $primaryKey = 'id';
    private static $formName = 'formReport_Pessoa';

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
        $this->form->setFormTitle("Listagem de Pessoas");

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

        $btn_ongeneratehtml = $this->form->addAction("Gerar HTML", new TAction([$this, 'onGenerateHtml']), 'far:file-code #ffffff');
        $btn_ongeneratehtml->addStyleClass('btn-primary');

        $btn_ongeneratepdf = $this->form->addAction("Gerar PDF", new TAction([$this, 'onGeneratePdf']), 'far:file-pdf #d44734');

        $btn_ongeneratexls = $this->form->addAction("Gerar XLS", new TAction([$this, 'onGenerateXls']), 'far:file-excel #00a65a');

        $btn_ongeneratertf = $this->form->addAction("Gerar RTF", new TAction([$this, 'onGenerateRtf']), 'far:file-alt #324bcc');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        $container->add(TBreadCrumb::create(["Relatórios","Listagem de Pessoas"]));
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
            // creates a repository for Pessoa
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $param['order'] = 'tipo_pessoa,id';
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
                $widths = array(70,70,260,70,110,110);

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
                                $pdf->Cell(30,10, utf8_decode('Listagem de Pessoas') ,0,0,'C');

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
                                $tr->addCell('Listagem de Pessoas', 'center', 'header', 5);
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
                    $tr->addCell("Código", 'left', 'title');
                    $tr->addCell("Ativo", 'center', 'title');
                    $tr->addCell("Nome", 'left', 'title');
                    $tr->addCell("Fís/Jur", 'center', 'title');
                    $tr->addCell("Data de Cadastro", 'center', 'title');
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

                        if ($object->tipo_pessoa !== $breakValue)
                        {
                            if (!$firstRow)
                            {
                                $tr->addRow();

                                $breakTotal_usuario_registro = count($breakTotal['usuario_registro']);

                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell($breakTotal_usuario_registro, 'left', 'breakTotal');
                            }
                            $tr->addRow();
                            $tr->addCell($object->render('{tipo_pessoa}'), 'left', 'break', 6);
                            $breakTotal = [];
                        }
                        $breakValue = $object->tipo_pessoa;

                        $grandTotal['usuario_registro'][] = $object->usuario_registro;
                        $breakTotal['usuario_registro'][] = $object->usuario_registro;

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

                        $tr->addCell($object->id, 'left', $style);
                        $tr->addCell($object->ativo, 'center', $style);
                        $tr->addCell($object->nome, 'left', $style);
                        $tr->addCell($object->tipo_pessoa, 'center', $style);
                        $tr->addCell($object->data_registro, 'center', $style);
                        $tr->addCell($object->usuario_registro, 'left', $style);

                        $colour = !$colour;
                    }

                    $tr->addRow();

                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell($breakTotal_usuario_registro, 'left', 'breakTotal');

                    $tr->addRow();

                    $grandTotal_usuario_registro = count($grandTotal['usuario_registro']);

                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell($grandTotal_usuario_registro, 'left', 'total');

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

