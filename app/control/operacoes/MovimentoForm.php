<?php

class MovimentoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'ancor907_erpsimples';
    private static $activeRecord = 'Movimento';
    private static $primaryKey = 'id';
    private static $formName = 'form_Movimento';

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
        $this->form->setFormTitle("Editar Movimento");

        $criteria_tipo_movimento_id = new TCriteria();

        $filterVar = TipoMovimento::edc;
        $criteria_tipo_movimento_id->add(new TFilter('id', '!=', $filterVar));
        $filterVar = TipoMovimento::edv;
        $criteria_tipo_movimento_id->add(new TFilter('id', '!=', $filterVar));

        $id = new TEntry('id');
        $tipo_movimento_id = new TDBCombo('tipo_movimento_id', 'ancor907_erpsimples', 'TipoMovimento', 'id', '{descricao}','descricao asc' , $criteria_tipo_movimento_id );
        $situacao_id = new TDBCombo('situacao_id', 'ancor907_erpsimples', 'Situacao', 'id', '{descricao}','descricao asc'  );
        $tipo_estoque = new THidden('tipo_estoque');
        $situacao_id_ant = new THidden('situacao_id_ant');
        $pessoa_id = new TDBUniqueSearch('pessoa_id', 'ancor907_erpsimples', 'Pessoa', 'id', 'nome','nome asc'  );
        $data_abertura = new TDate('data_abertura');
        $data_entrega = new TDate('data_entrega');
        $tipo_documento_id = new TDBCombo('tipo_documento_id', 'ancor907_erpsimples', 'TipoDocumento', 'id', '{descricao}','descricao asc'  );
        $numero_documento = new TEntry('numero_documento');
        $data_documento = new TDate('data_documento');
        $vlr_frete = new TNumeric('vlr_frete', '2', ',', '.' );
        $vlr_icms_doc = new TNumeric('vlr_icms_doc', '2', ',', '.' );
        $vlr_ipi_doc = new TNumeric('vlr_ipi_doc', '2', ',', '.' );
        $vlr_total_doc = new TNumeric('vlr_total_doc', '2', ',', '.' );
        $obs = new TText('obs');
        $data_registro = new TDateTime('data_registro');
        $usuario_registro = new TEntry('usuario_registro');
        $data_atualizacao = new TDateTime('data_atualizacao');
        $usuario_atualizacao = new TEntry('usuario_atualizacao');
        $produtos_movimento_movimento_produto_id = new TDBUniqueSearch('produtos_movimento_movimento_produto_id', 'ancor907_erpsimples', 'Produto', 'id', 'descricao','descricao asc'  );
        $produtos_movimento_movimento_qtd = new TNumeric('produtos_movimento_movimento_qtd', '2', ',', '.' );
        $produtos_movimento_movimento_vlr_unitario = new TNumeric('produtos_movimento_movimento_vlr_unitario', '2', ',', '.' );
        $sld_estoque = new TNumeric('sld_estoque', '2', ',', '.' );
        $sld_reservado = new TNumeric('sld_reservado', '2', ',', '.' );
        $sld_aguardando = new TNumeric('sld_aguardando', '2', ',', '.' );
        $produtos_movimento_movimento_lote = new TEntry('produtos_movimento_movimento_lote');
        $produtos_movimento_movimento_data_validade = new TDate('produtos_movimento_movimento_data_validade');
        $produtos_movimento_movimento_vlr_icms = new TNumeric('produtos_movimento_movimento_vlr_icms', '2', ',', '.' );
        $produtos_movimento_movimento_vlr_ipi = new TNumeric('produtos_movimento_movimento_vlr_ipi', '2', ',', '.' );
        $produtos_movimento_movimento_id = new THidden('produtos_movimento_movimento_id');

        $tipo_movimento_id->setChangeAction(new TAction([$this,'onTipoMovimento']));
        $produtos_movimento_movimento_produto_id->setChangeAction(new TAction([$this,'onTipoSaldos']));

        $tipo_movimento_id->addValidation("Tipo Movimento", new TRequiredValidator());
        $situacao_id->addValidation("Situação", new TRequiredValidator());
        $pessoa_id->addValidation("Cliente/Fornecedor", new TRequiredValidator());
        $data_abertura->addValidation("Data Abertura", new TRequiredValidator());

        $pessoa_id->setMinLength(2);
        $produtos_movimento_movimento_produto_id->setMinLength(2);

        $situacao_id->autofocus = 'autofocus';
        $tipo_documento_id->autofocus = 'autofocus';

        $data_entrega->setDatabaseMask('yyyy-mm-dd');
        $data_abertura->setDatabaseMask('yyyy-mm-dd');
        $data_documento->setDatabaseMask('yyyy-mm-dd');
        $data_registro->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_atualizacao->setDatabaseMask('yyyy-mm-dd hh:ii');
        $produtos_movimento_movimento_data_validade->setDatabaseMask('yyyy-mm-dd');

        $pessoa_id->setMask('{nome}');
        $data_entrega->setMask('dd/mm/yyyy');
        $data_abertura->setMask('dd/mm/yyyy');
        $data_documento->setMask('dd/mm/yyyy');
        $data_registro->setMask('dd/mm/yyyy hh:ii');
        $data_atualizacao->setMask('dd/mm/yyyy hh:ii');
        $produtos_movimento_movimento_produto_id->setMask('{descricao}');
        $produtos_movimento_movimento_data_validade->setMask('dd/mm/yyyy');

        $id->setEditable(false);
        $sld_estoque->setEditable(false);
        $data_abertura->setEditable(false);
        $data_registro->setEditable(false);
        $sld_reservado->setEditable(false);
        $sld_aguardando->setEditable(false);
        $usuario_registro->setEditable(false);
        $data_atualizacao->setEditable(false);
        $usuario_atualizacao->setEditable(false);

        $vlr_frete->setValue('0,00');
        $vlr_ipi_doc->setValue('0,00');
        $vlr_icms_doc->setValue('0,00');
        $vlr_total_doc->setValue('0,00');
        $data_abertura->setValue(date('Y-m-d'));
        $produtos_movimento_movimento_qtd->setValue('0,00');
        $produtos_movimento_movimento_vlr_ipi->setValue('0,00');
        $produtos_movimento_movimento_vlr_icms->setValue('0,00');
        $produtos_movimento_movimento_vlr_unitario->setValue('0,00');

        $id->setSize(100);
        $obs->setSize('100%', 70);
        $vlr_frete->setSize('70%');
        $tipo_estoque->setSize(200);
        $pessoa_id->setSize('100%');
        $sld_estoque->setSize('30%');
        $vlr_ipi_doc->setSize('70%');
        $situacao_id->setSize('100%');
        $data_entrega->setSize('80%');
        $vlr_icms_doc->setSize('70%');
        $situacao_id_ant->setSize(200);
        $data_abertura->setSize('80%');
        $sld_reservado->setSize('30%');
        $vlr_total_doc->setSize('70%');
        $sld_aguardando->setSize('30%');
        $data_registro->setSize('100%');
        $data_documento->setSize('80%');
        $numero_documento->setSize('80%');
        $usuario_registro->setSize('100%');
        $data_atualizacao->setSize('100%');
        $tipo_movimento_id->setSize('100%');
        $tipo_documento_id->setSize('100%');
        $usuario_atualizacao->setSize('100%');
        $produtos_movimento_movimento_qtd->setSize('70%');
        $produtos_movimento_movimento_lote->setSize('80%');
        $produtos_movimento_movimento_vlr_ipi->setSize('90%');
        $produtos_movimento_movimento_vlr_icms->setSize('90%');
        $produtos_movimento_movimento_produto_id->setSize('100%');
        $produtos_movimento_movimento_vlr_unitario->setSize('90%');
        $produtos_movimento_movimento_data_validade->setSize('70%');

        $row1 = $this->form->addFields([new TLabel("Código", '#ff0000', '14px', 'B', '100%'),$id],[new TLabel("Tipo Movimento", '#ff0000', '14px', 'B', '100%'),$tipo_movimento_id],[new TLabel("Situação", '#ff0000', '14px', 'B', '100%'),$situacao_id],[$tipo_estoque],[$situacao_id_ant]);
        $row1->layout = [' col-sm-2',' col-sm-4',' col-sm-4',' col-sm-1',' col-sm-1'];

        $row2 = $this->form->addFields([new TLabel("Cliente/Fornecedor", '#ff0000', '14px', 'B', '100%'),$pessoa_id],[new TLabel("Data de Abertura", '#ff0000', '14px', 'B', '100%'),$data_abertura],[new TLabel("Data de Entrega", null, '14px', null, '100%'),$data_entrega]);
        $row2->layout = ['col-sm-6',' col-sm-3',' col-sm-3'];

        $row3 = $this->form->addFields([new TLabel("Documento", null, '14px', null, '100%'),$tipo_documento_id],[new TLabel("Número", null, '14px', null, '100%'),$numero_documento],[new TLabel("Data de Emissão", null, '14px', null, '100%'),$data_documento]);
        $row3->layout = [' col-sm-6',' col-sm-3',' col-sm-3'];

        $row4 = $this->form->addFields([new TLabel("Valor do Frete", null, '14px', null, '100%'),$vlr_frete],[new TLabel("Valor ICMS", null, '14px', null, '100%'),$vlr_icms_doc],[new TLabel("Valor IPI", null, '14px', null, '100%'),$vlr_ipi_doc],[new TLabel("Valor Total", null, '14px', null, '100%'),$vlr_total_doc]);
        $row4->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row5 = $this->form->addFields([new TLabel("Observação", null, '14px', null, '100%'),$obs]);
        $row5->layout = [' col-sm-12'];

        $row6 = $this->form->addFields([new TLabel("Registrado em", null, '14px', null, '100%'),$data_registro],[new TLabel("Registrado por", null, '14px', null, '100%'),$usuario_registro],[new TLabel("Atualizado em", null, '14px', null, '100%'),$data_atualizacao],[new TLabel("Atualizado por", null, '14px', null, '100%'),$usuario_atualizacao]);
        $row6->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row7 = $this->form->addFields([new TFormSeparator("Produtos", '#ff0000', '18', '#ff0000')]);
        $row7->layout = [' col-sm-12'];

        $row8 = $this->form->addFields([new TLabel("Produto", '#ff0000', '14px', 'B', '100%'),$produtos_movimento_movimento_produto_id],[new TLabel("Qtd", '#ff0000', '14px', 'B', '100%'),$produtos_movimento_movimento_qtd],[new TLabel("Valor Unitário", '#ff0000', '14px', 'B', '100%'),$produtos_movimento_movimento_vlr_unitario]);
        $row8->layout = [' col-sm-8',' col-sm-2',' col-sm-2'];

        $row9 = $this->form->addFields([new TLabel("Estoque Disponível", null, '14px', null),$sld_estoque],[new TLabel("Estoque Reservado", null, '14px', null),$sld_reservado],[new TLabel("Aguardando Entrega", null, '14px', null),$sld_aguardando]);
        $row9->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row10 = $this->form->addFields([new TLabel("Lote", null, '14px', null, '100%'),$produtos_movimento_movimento_lote],[new TLabel("Data de Validade", null, '14px', null, '100%'),$produtos_movimento_movimento_data_validade],[new TLabel("Valor ICMS", null, '14px', null, '100%'),$produtos_movimento_movimento_vlr_icms],[new TLabel("Valor IPI", null, '14px', null, '100%'),$produtos_movimento_movimento_vlr_ipi]);
        $row10->layout = [' col-sm-4',' col-sm-4',' col-sm-2','col-sm-2'];

        $row11 = $this->form->addFields([$produtos_movimento_movimento_id]);
        $add_produtos_movimento_movimento = new TButton('add_produtos_movimento_movimento');

        $action_produtos_movimento_movimento = new TAction(array($this, 'onAddProdutosMovimentoMovimento'));

        $add_produtos_movimento_movimento->setAction($action_produtos_movimento_movimento, "Adicionar");
        $add_produtos_movimento_movimento->setImage('fas:plus #000000');

        $this->form->addFields([$add_produtos_movimento_movimento]);

        $detailDatagrid = new TQuickGrid;
        $detailDatagrid->disableHtmlConversion();
        $this->produtos_movimento_movimento_list = new BootstrapDatagridWrapper($detailDatagrid);
        $this->produtos_movimento_movimento_list->datatable = 'true';
        $this->produtos_movimento_movimento_list->style = 'width:100%';
        $this->produtos_movimento_movimento_list->class .= ' table-bordered';
        $this->produtos_movimento_movimento_list->disableDefaultClick();
        $this->produtos_movimento_movimento_list->addQuickColumn('', 'edit', 'left', 50);
        $this->produtos_movimento_movimento_list->addQuickColumn('', 'delete', 'left', 50);

        $column_produtos_movimento_movimento_produto_id = $this->produtos_movimento_movimento_list->addQuickColumn("Produto", 'produtos_movimento_movimento_produto_id', 'left' , '40%');
        $column_produtos_movimento_movimento_lote = $this->produtos_movimento_movimento_list->addQuickColumn("Lote", 'produtos_movimento_movimento_lote', 'left' , '20%');
        $column_produtos_movimento_movimento_data_validade_transformed = $this->produtos_movimento_movimento_list->addQuickColumn("Validade", 'produtos_movimento_movimento_data_validade', 'center');
        $column_produtos_movimento_movimento_qtd = $this->produtos_movimento_movimento_list->addQuickColumn("Qtd", 'produtos_movimento_movimento_qtd', 'center' , '9%');
        $column_produtos_movimento_movimento_vlr_icms_transformed = $this->produtos_movimento_movimento_list->addQuickColumn("ICMS", 'produtos_movimento_movimento_vlr_icms', 'right');
        $column_produtos_movimento_movimento_vlr_ipi_transformed = $this->produtos_movimento_movimento_list->addQuickColumn("IPI", 'produtos_movimento_movimento_vlr_ipi', 'right');
        $column_produtos_movimento_movimento_vlr_unitario_transformed = $this->produtos_movimento_movimento_list->addQuickColumn("Vlr Unit", 'produtos_movimento_movimento_vlr_unitario', 'right' , '19%');
        $column_calculated_1 = $this->produtos_movimento_movimento_list->addQuickColumn("Vlr Total", '=( {produtos_movimento_movimento_qtd} * {produtos_movimento_movimento_vlr_unitario}  )', 'right' , '10%');

        $column_produtos_movimento_movimento_qtd->setTotalFunction( function($values) {
            return array_sum((array) $values);
        });

        $column_produtos_movimento_movimento_vlr_icms_transformed->setTotalFunction( function($values) {
            return array_sum((array) $values);
        });

        $column_produtos_movimento_movimento_vlr_ipi_transformed->setTotalFunction( function($values) {
            return array_sum((array) $values);
        });

        $column_calculated_1->setTotalFunction( function($values) {
            return array_sum((array) $values);
        });

        $this->produtos_movimento_movimento_list->createModel();
        $this->form->addContent([$this->produtos_movimento_movimento_list]);

        $column_produtos_movimento_movimento_data_validade_transformed->setTransformer(function($value, $object, $row)
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

        $column_produtos_movimento_movimento_vlr_icms_transformed->setTransformer(function($value, $object, $row)
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

        $column_produtos_movimento_movimento_vlr_ipi_transformed->setTransformer(function($value, $object, $row)
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

        $column_produtos_movimento_movimento_vlr_unitario_transformed->setTransformer(function($value, $object, $row)
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

        $column_calculated_1->setTransformer(function($value, $object, $row)
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

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $btn_onsave->addStyleClass('btn-primary');

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        $container->add(TBreadCrumb::create(["Operações","Editar Movimento"]));
        $container->add($this->form);

        parent::add($container);

    }

    public static function onTipoMovimento($param = null)
    {
        try
        {

            TTransaction::open(self::$database);

            $object = new stdClass();
            $object->tipo_movimento_id = $param['tipo_movimento_id'];

            $repo = new TRepository('Situacao');

            $tipos = $repo->load();

            $options = array();

            foreach ($tipos as $tipo)
            {
                if (($object->tipo_movimento_id == 3 || $object->tipo_movimento_id == 7) && ($tipo->id == 4)) {
                    $options[ $tipo->id ] = $tipo->descricao;
                } elseif (($object->tipo_movimento_id == 2 || $object->tipo_movimento_id == 6) && ($tipo->id == 10)) {
                    $options[ $tipo->id ] = $tipo->descricao;
                } elseif (($object->tipo_movimento_id == 1) && ($tipo->id == 2 || $tipo->id == 3 || $tipo->id == 8)) {
                    $options[ $tipo->id ] = $tipo->descricao;
                } elseif (($object->tipo_movimento_id == 5) && ($tipo->id == 2 || $tipo->id == 3 || $tipo->id == 9)) {
                    $options[ $tipo->id ] = $tipo->descricao;
                }
            }

            TTransaction::close();

            TCombo::reload(self::$formName, 'situacao_id', $options);

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public static function onTipoSaldos($param = null)
    {
        try
        {

            TTransaction::open(self::$database);

            // Recupera o id do Produto informado no Form
            $produto_id = $param['produtos_movimento_movimento_produto_id'];

            // Busca os saldos do Produto
            $saldo = Saldos::find( $produto_id );

            // Se encontrou algum saldo
            if($saldo)
            {
                // Recupera os saldos nos campos do Form
                $obj = new stdClass;
                $obj->sld_estoque = $saldo->qtd_estoque;
                $obj->sld_reservado = $saldo->qtd_reservado;
                $obj->sld_aguardando = $saldo->qtd_aguardando;
                // Carrega os saldos no Form
                TForm::sendData(self::$formName, $obj);
            }

            TTransaction::close();

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

            $object = new Movimento(); // create an empty object

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

            $this->fireEvents($object);

            $produtos_movimento_movimento_items = $this->storeItems('ProdutosMovimento', 'movimento_id', $object, 'produtos_movimento_movimento', function($masterObject, $detailObject){

                // Calcular Saldos e salvar
                /**
                situacao_id     sigla   descrição                   tipo_movimento_id   sigla   descrição
                1               ABE     Aberto                      1                   EPC e   Entrada por Compra
                2               PNO     Pendente no Operacional     2                   DDV e   Devolução de Venda
                3               ENO     Encerrado no Operacional    3                   CDC s   Cancelamento de Compra
                4               CAN     Cancelado                   4                   EDC s   Estorno de Compra
                5               PNF     Pendente no Financeiro      5                   SPV s   Saída por Venda
                6               ENF     Encerrado no Financeiro     6                   DDC s   Devolução de Compra
                7               EST     Estorno                     7                   CDV e   Cancelamento de Venda
                8               ENP     Entrada Parcial             8                   EDV e   Estorno de Venda
                9               SAP     Saída Parcial
                10              DEV     Devolvido
                **/

                $saldos = new Saldos( $detailObject->produto_id );

                if($saldos) {
                    // Acrescentar valor total do item no valor total do Movimento, se abertura do Movimento
                    if (!$masterObject->situacao_id_ant) {
                        $masterObject->vlr_total += $detailObject->vlr_unitario * $detailObject->qtd;
//                        $masterObject->situacao_id = TSession::getValue('situacao_op');
                    } else {
                        $masterObject->situacao_id = $masterObject->situacao_id_ant;
                    }

                    // 1 - EPC
                    if ($masterObject->tipo_movimento_id == 1) {
                        $masterObject->tipo_estoque = "E";
                        // 2 - PNO
                        if ($masterObject->situacao_id == 2) {
                            $saldos->qtd_aguardando += $detailObject->qtd;
                        // 3 - ENO ou 8 - ENP
                        } elseif ($masterObject->situacao_id == 3 || $masterObject->situacao_id == 8) {
                            if ($masterObject->situacao_id_ant == 2 || $masterObject->situacao_id_ant == 8) {
                                $saldos->qtd_aguardando -= $detailObject->qtd;
                            }
                            $saldos->qtd_estoque += $detailObject->qtd;
                        }
                    // 3 - CDC (depende de 2 - PNO)
                    } elseif ($masterObject->tipo_movimento_id == 3) {
                        $masterObject->tipo_estoque = "S";
                        // 4 - CAN
                        $masterObject->situacao_id = 4;
                        $saldos->qtd_aguardando -= $detailObject->qtd;
                    // 6 - DDC (depende de 3 - ENO)
                    } elseif ($masterObject->tipo_movimento_id == 6) {
                        $masterObject->tipo_estoque = "S";
                        // 10 - DEV
                        $masterObject->situacao_id = 10;
                        $saldos->qtd_estoque -= $detailObject->qtd;
                    // 5 - SPV
                    } elseif ($masterObject->tipo_movimento_id == 5) {
                        $masterObject->tipo_estoque = "S";
                        // 2 - PNO
                        if ($masterObject->situacao_id == 2) {
                            $saldos->qtd_reservado += $detailObject->qtd;
                        // 3 - ENO ou 9 - SAP
                        } elseif ($masterObject->situacao_id == 3 || $masterObject->situacao_id == 9) {
                            if ($masterObject->situacao_id_ant == 2 || $masterObject->situacao_id_ant == 9) {
                                $saldos->qtd_reservado -= $detailObject->qtd;
                            }
                            $saldos->qtd_estoque -= $detailObject->qtd;
                        }
                    // 7 - CDV (depende de 2 - PNO)
                    } elseif ($masterObject->tipo_movimento_id == 7) {
                        $masterObject->tipo_estoque = "E";
                        // 4 - CAN
                        $masterObject->situacao_id = 4;
                        $saldos->qtd_reservado -= $detailObject->qtd;
                    // 2 - DDV (depende de 3 - ENO)
                    } elseif ($masterObject->tipo_movimento_id == 2) {
                        $masterObject->tipo_estoque = "E";
                        // 10 - DEV
                        $masterObject->situacao_id = 10;
                        $saldos->qtd_estoque += $detailObject->qtd;
                    }
                }

                $saldos->store();
                // -----------

            });
            $object->store();

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id;

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/
            $messageAction = new TAction(['MovimentoForm', 'onClear']);

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

                $object = new Movimento($key); // instantiates the Active Record

                $produtos_movimento_movimento_items = $this->loadItems('ProdutosMovimento', 'movimento_id', $object, 'produtos_movimento_movimento', function($masterObject, $detailObject, $objectItems){

                    $masterObject->situacao_id_ant = $masterObject->situacao_id;

                    $objectItems->sld_estoque = null;
                    if(isset($detailObject->sld_estoque) && $detailObject->sld_estoque)
                    {
                        $objectItems->sld_estoque = $detailObject->sld_estoque;
                    }
                    $objectItems->tipo_movimento_id = null;
                    if(isset($detailObject->tipo_movimento_id) && $detailObject->tipo_movimento_id)
                    {
                        $objectItems->tipo_movimento_id = $detailObject->tipo_movimento_id;
                    }
                    $objectItems->sld_reservado = null;
                    if(isset($detailObject->sld_reservado) && $detailObject->sld_reservado)
                    {
                        $objectItems->sld_reservado = $detailObject->sld_reservado;
                    }
                    $objectItems->tipo_movimento_id = null;
                    if(isset($detailObject->tipo_movimento_id) && $detailObject->tipo_movimento_id)
                    {
                        $objectItems->tipo_movimento_id = $detailObject->tipo_movimento_id;
                    }
                    $objectItems->sld_aguardando = null;
                    if(isset($detailObject->sld_aguardando) && $detailObject->sld_aguardando)
                    {
                        $objectItems->sld_aguardando = $detailObject->sld_aguardando;
                    }
                    $objectItems->tipo_movimento_id = null;
                    if(isset($detailObject->tipo_movimento_id) && $detailObject->tipo_movimento_id)
                    {
                        $objectItems->tipo_movimento_id = $detailObject->tipo_movimento_id;
                    }

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

        TSession::setValue('produtos_movimento_movimento_items', null);

        $this->onReload();
    }

    public function onAddProdutosMovimentoMovimento( $param )
    {
        try
        {
            $data = $this->form->getData();

            if(!$data->produtos_movimento_movimento_produto_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Produto"));
            }
            if(!$data->produtos_movimento_movimento_qtd)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Qtd"));
            }
            if(!$data->produtos_movimento_movimento_vlr_unitario)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Valor Unitário"));
            }
            $produtos_movimento_movimento_items = TSession::getValue('produtos_movimento_movimento_items');

            /* AQUI: salvar Situação escolhida */
            if (!$produtos_movimento_movimento_items) {
                $obj = new stdClass();
                $obj->situacao_op = $param['situacao_id'];
                TSession::setValue('situacao_op', $obj->situacao_op);
            }
            /* --- */

            $key = isset($data->produtos_movimento_movimento_id) && $data->produtos_movimento_movimento_id ? $data->produtos_movimento_movimento_id : uniqid();
            $fields = [];

            $fields['produtos_movimento_movimento_produto_id'] = $data->produtos_movimento_movimento_produto_id;
            $fields['produtos_movimento_movimento_qtd'] = $data->produtos_movimento_movimento_qtd;
            $fields['produtos_movimento_movimento_vlr_unitario'] = $data->produtos_movimento_movimento_vlr_unitario;
            $fields['sld_estoque'] = $data->sld_estoque;
            $fields['sld_reservado'] = $data->sld_reservado;
            $fields['sld_aguardando'] = $data->sld_aguardando;
            $fields['produtos_movimento_movimento_lote'] = $data->produtos_movimento_movimento_lote;
            $fields['produtos_movimento_movimento_data_validade'] = $data->produtos_movimento_movimento_data_validade;
            $fields['produtos_movimento_movimento_vlr_icms'] = $data->produtos_movimento_movimento_vlr_icms;
            $fields['produtos_movimento_movimento_vlr_ipi'] = $data->produtos_movimento_movimento_vlr_ipi;
            $produtos_movimento_movimento_items[ $key ] = $fields;

            TSession::setValue('produtos_movimento_movimento_items', $produtos_movimento_movimento_items);

            $data->produtos_movimento_movimento_id = '';
            $data->produtos_movimento_movimento_produto_id = '';
            $data->produtos_movimento_movimento_qtd = '';
            $data->produtos_movimento_movimento_vlr_unitario = '';
            $data->sld_estoque = '';
            $data->sld_reservado = '';
            $data->sld_aguardando = '';
            $data->produtos_movimento_movimento_lote = '';
            $data->produtos_movimento_movimento_data_validade = '';
            $data->produtos_movimento_movimento_vlr_icms = '';
            $data->produtos_movimento_movimento_vlr_ipi = '';
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

    public function onEditProdutosMovimentoMovimento( $param )
    {
        $data = $this->form->getData();

        // read session items
        $items = TSession::getValue('produtos_movimento_movimento_items');

        // get the session item
        $item = $items[$param['produtos_movimento_movimento_id_row_id']];

        $data->produtos_movimento_movimento_produto_id = $item['produtos_movimento_movimento_produto_id'];
        $data->produtos_movimento_movimento_qtd = $item['produtos_movimento_movimento_qtd'];
        $data->produtos_movimento_movimento_vlr_unitario = $item['produtos_movimento_movimento_vlr_unitario'];
        $data->sld_estoque = $item['sld_estoque'];
        $data->sld_reservado = $item['sld_reservado'];
        $data->sld_aguardando = $item['sld_aguardando'];
        $data->produtos_movimento_movimento_lote = $item['produtos_movimento_movimento_lote'];
        $data->produtos_movimento_movimento_data_validade = $item['produtos_movimento_movimento_data_validade'];
        $data->produtos_movimento_movimento_vlr_icms = $item['produtos_movimento_movimento_vlr_icms'];
        $data->produtos_movimento_movimento_vlr_ipi = $item['produtos_movimento_movimento_vlr_ipi'];

        $data->produtos_movimento_movimento_id = $param['produtos_movimento_movimento_id_row_id'];

        // fill product fields
        $this->form->setData( $data );

        $this->fireEvents($data);

        $this->onReload( $param );

    }

    public function onDeleteProdutosMovimentoMovimento( $param )
    {
        $data = $this->form->getData();

        $data->produtos_movimento_movimento_produto_id = '';
        $data->produtos_movimento_movimento_qtd = '';
        $data->produtos_movimento_movimento_vlr_unitario = '';
        $data->sld_estoque = '';
        $data->sld_reservado = '';
        $data->sld_aguardando = '';
        $data->produtos_movimento_movimento_lote = '';
        $data->produtos_movimento_movimento_data_validade = '';
        $data->produtos_movimento_movimento_vlr_icms = '';
        $data->produtos_movimento_movimento_vlr_ipi = '';

        // clear form data
        $this->form->setData( $data );

        // read session items
        $items = TSession::getValue('produtos_movimento_movimento_items');

        // delete the item from session
        unset($items[$param['produtos_movimento_movimento_id_row_id']]);
        TSession::setValue('produtos_movimento_movimento_items', $items);

        $this->fireEvents($data);

        // reload sale items
        $this->onReload( $param );

    }

    public function onReloadProdutosMovimentoMovimento( $param )
    {
        $items = TSession::getValue('produtos_movimento_movimento_items');

        $this->produtos_movimento_movimento_list->clear();

        if($items)
        {
            $cont = 1;
            foreach ($items as $key => $item)
            {
                $rowItem = new StdClass;

                $action_del = new TAction(array($this, 'onDeleteProdutosMovimentoMovimento'));
                $action_del->setParameter('produtos_movimento_movimento_id_row_id', $key);
                $action_del->setParameter('row_data', base64_encode(serialize($item)));
                $action_del->setParameter('key', $key);

                $action_edi = new TAction(array($this, 'onEditProdutosMovimentoMovimento'));
                $action_edi->setParameter('produtos_movimento_movimento_id_row_id', $key);
                $action_edi->setParameter('row_data', base64_encode(serialize($item)));
                $action_edi->setParameter('key', $key);

                $button_del = new TButton('delete_produtos_movimento_movimento'.$cont);
                $button_del->setAction($action_del, '');
                $button_del->setFormName($this->form->getName());
                $button_del->class = 'btn btn-link btn-sm';
                $button_del->title = "Excluir";
                $button_del->setImage('far:trash-alt #dd5a43');

                $rowItem->delete = $button_del;

                $button_edi = new TButton('edit_produtos_movimento_movimento'.$cont);
                $button_edi->setAction($action_edi, '');
                $button_edi->setFormName($this->form->getName());
                $button_edi->class = 'btn btn-link btn-sm';
                $button_edi->title = "Editar";
                $button_edi->setImage('far:edit #478fca');

                $rowItem->edit = $button_edi;

                $rowItem->produtos_movimento_movimento_produto_id = '';
                if(isset($item['produtos_movimento_movimento_produto_id']) && $item['produtos_movimento_movimento_produto_id'])
                {
                    TTransaction::open('ancor907_erpsimples');
                    $produto = Produto::find($item['produtos_movimento_movimento_produto_id']);
                    if($produto)
                    {
                        $rowItem->produtos_movimento_movimento_produto_id = $produto->render('{descricao}');
                    }
                    TTransaction::close();
                }

                $rowItem->produtos_movimento_movimento_qtd = isset($item['produtos_movimento_movimento_qtd']) ? $item['produtos_movimento_movimento_qtd'] : '';
                $rowItem->produtos_movimento_movimento_vlr_unitario = isset($item['produtos_movimento_movimento_vlr_unitario']) ? $item['produtos_movimento_movimento_vlr_unitario'] : '';
                $rowItem->sld_estoque = isset($item['sld_estoque']) ? $item['sld_estoque'] : '';
                $rowItem->sld_reservado = isset($item['sld_reservado']) ? $item['sld_reservado'] : '';
                $rowItem->sld_aguardando = isset($item['sld_aguardando']) ? $item['sld_aguardando'] : '';
                $rowItem->produtos_movimento_movimento_lote = isset($item['produtos_movimento_movimento_lote']) ? $item['produtos_movimento_movimento_lote'] : '';
                $rowItem->produtos_movimento_movimento_data_validade = isset($item['produtos_movimento_movimento_data_validade']) ? $item['produtos_movimento_movimento_data_validade'] : '';
                $rowItem->produtos_movimento_movimento_vlr_icms = isset($item['produtos_movimento_movimento_vlr_icms']) ? $item['produtos_movimento_movimento_vlr_icms'] : '';
                $rowItem->produtos_movimento_movimento_vlr_ipi = isset($item['produtos_movimento_movimento_vlr_ipi']) ? $item['produtos_movimento_movimento_vlr_ipi'] : '';

                $row = $this->produtos_movimento_movimento_list->addItem($rowItem);

                $cont++;
            }
        }
    }

    public function onShow($param = null)
    {
        TSession::setValue('produtos_movimento_movimento_items', null);

        $this->onReload();

    }

    public function fireEvents( $object )
    {
        /* AQUI: recuperar Situação escolhida */
        $object->situacao_id = TSession::getValue('situacao_op');
        /* --- */
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->tipo_movimento_id))
            {
                $obj->tipo_movimento_id = $object->tipo_movimento_id;
            }
            if(isset($object->sld_estoque))
            {
                $obj->sld_estoque = $object->sld_estoque;
            }
            if(isset($object->sld_reservado))
            {
                $obj->sld_reservado = $object->sld_reservado;
            }
            if(isset($object->sld_aguardando))
            {
                $obj->sld_aguardando = $object->sld_aguardando;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->tipo_movimento_id))
            {
                $obj->tipo_movimento_id = $object->tipo_movimento_id;
            }
            if(isset($object->sld_estoque))
            {
                $obj->sld_estoque = $object->sld_estoque;
            }
            if(isset($object->sld_reservado))
            {
                $obj->sld_reservado = $object->sld_reservado;
            }
            if(isset($object->sld_aguardando))
            {
                $obj->sld_aguardando = $object->sld_aguardando;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }

    public function onReload($params = null)
    {
        $this->loaded = TRUE;

        $this->onReloadProdutosMovimentoMovimento($params);
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

