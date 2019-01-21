<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profit extends CORE_Controller
{

    function __construct() {
        parent::__construct('');
        $this->validate_session();

        $this->load->model('Profit_model');

        $this->load->model('Users_model');
        $this->load->model('Company_model');
        $this->load->library('excel');
        $this->load->model('Email_settings_model');
        $this->load->model('Order_source_model');
    }

    public function index() {
        $this->Users_model->validate();
        //default resources of the active view
        $data['_def_css_files'] = $this->load->view('template/assets/css_files', '', TRUE);
        $data['_def_js_files'] = $this->load->view('template/assets/js_files', '', TRUE);
        $data['_switcher_settings'] = $this->load->view('template/elements/switcher', '', TRUE);
        $data['_side_bar_navigation'] = $this->load->view('template/elements/side_bar_navigation', '', TRUE);
        $data['_top_navigation'] = $this->load->view('template/elements/top_navigation', '', TRUE);


        $data['title'] = 'Profit Report';
        (in_array('8-6',$this->session->user_rights)? 
        $this->load->view('profit_view', $data)
        :redirect(base_url('dashboard')));

    }

    function transaction($txn = null,$id_filter=null) {
        switch ($txn){

                case'report-by-product';
                $m_sales=$this->Profit_model;
                $start = date('Y-m-d',strtotime($this->input->get('start')));
                $end = date('Y-m-d',strtotime($this->input->get('end')));

                $response['data']=$m_sales->get_profit_by_product($start,$end);

                    echo json_encode($response);
                break;      


                case'report-by-invoice-detailed';
                $m_sales=$this->Profit_model;
                $start = date('Y-m-d',strtotime($this->input->get('start')));
                $end = date('Y-m-d',strtotime($this->input->get('end')));

                $response['data']=$m_sales->get_profit_by_invoice_detailed($start,$end);
                $response['distinct']=$m_sales->get_profit_by_invoice_detailed($start,$end,TRUE);
                $response['subtotal']=$m_sales->get_profit_by_invoice_detailed($start,$end,FALSE,TRUE);

                    echo json_encode($response);
                break;     


                case'report-by-invoice-summary';
                $m_sales=$this->Profit_model;
                $start = date('Y-m-d',strtotime($this->input->get('start')));
                $end = date('Y-m-d',strtotime($this->input->get('end')));

                $response['summary']=$m_sales->get_profit_by_invoice_detailed($start,$end,FALSE,TRUE);

                    echo json_encode($response);
                break;           


                case'print-by-product';                
                $m_company=$this->Company_model;
                $company_info=$m_company->get_list();
                $data['company_info']=$company_info[0];

                $m_sales=$this->Profit_model;
                $start = date('Y-m-d',strtotime($this->input->get('start')));
                $type = $this->input->get('type');
                $end = date('Y-m-d',strtotime($this->input->get('end')));

                if($type=='1'){
                    $data['items']=$m_sales->get_profit_by_product($start,$end);
                    $this->load->view('template/profit_content',$data);
                }

                if($type=='2'){
                $data['items']=$m_sales->get_profit_by_invoice_detailed($start,$end);
                $data['distinct']=$m_sales->get_profit_by_invoice_detailed($start,$end,TRUE);
                $data['subtotal']=$m_sales->get_profit_by_invoice_detailed($start,$end,FALSE,TRUE);
                    $this->load->view('template/profit_content_detailed',$data);
                }
                if($type=='3'){
                $data['summary']=$m_sales->get_profit_by_invoice_detailed($start,$end,FALSE,TRUE);
                    $this->load->view('template/profit_content_summary',$data);
                }
                
                break;      


                case'export-by-product';
                $excel = $this->excel;
                $m_company=$this->Company_model;
                $company_info=$m_company->get_list();
                $company_info=$company_info[0];
                $start = date('Y-m-d',strtotime($this->input->get('start')));
                $end = date('Y-m-d',strtotime($this->input->get('end')));

                $m_sales=$this->Profit_model;
                $items = $m_sales->get_profit_by_product($start,$end);
                $excel->setActiveSheetIndex(0);
                $excel->getActiveSheet()->setTitle('Profit Report by Product');
                $excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
                $excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
                $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                $excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

                $rettotal = 0;
                $gtotal = 0;
                $excel->getActiveSheet()->setCellValue('A1',$company_info->company_name)
                                        ->mergeCells('A1:D1')
                                        ->getStyle('A1:D1')->getFont()->setBold(True)
                                        ->setSize(16);

                $excel->getActiveSheet()->setCellValue('A2',$company_info->company_address)
                                        ->mergeCells('A2:D2')
                                        ->setCellValue('A3',$company_info->landline.' / '.$company_info->mobile_no)
                                        ->mergeCells('A3:D3');

                    $border_bottom= array(
                    'borders' => array(
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '292929')
                        )
                    ));

                    // $excel->getActiveSheet()->setCellValue('A5')
                    //                         ->mergeCells('A5:G5')
                    //                         ->getStyle('A5:G5')->applyFromArray($border_bottom);

                    $excel->getActiveSheet()
                            ->getStyle('A6:G6')
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $excel->getActiveSheet()->setCellValue('A6','Profit Report by Product')
                                            ->mergeCells('A6:G6')
                                            ->getStyle('A6:G6')->getFont()->setBold(True)
                                            ->setSize(14);

                $i=8;
                $excel->getActiveSheet()->setCellValue('A'.$i,'Period : '.$start.' - '.$end); $i++;

                            $excel->getActiveSheet()
                            ->getStyle('D'.$i.':'.'H'.$i)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $excel->getActiveSheet()->setCellValue('A'.$i,'Item Code')
                                        ->getStyle('A'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('B'.$i,'Description')
                                        ->getStyle('B'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('C'.$i,'UM')
                                        ->getStyle('C'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('D'.$i,'Qty Sold')
                                        ->getStyle('D'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('E'.$i,'SRP')
                                        ->getStyle('E'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('F'.$i,'Gross')
                                        ->getStyle('F'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('G'.$i,'Unit Cost')
                                        ->getStyle('G'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('H'.$i,'Net Profit')
                                        ->getStyle('H'.$i)->getFont()->setBold(TRUE);


                $i++;

                $p_qty = 0;
                $p_gross = 0;
                $p_net = 0;



                foreach ($items  as $value) {
                            $excel->getActiveSheet()
                            ->getStyle('D'.$i.':'.'H'.$i)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $excel->getActiveSheet()->setCellValue('A'.$i,$value->product_code);
                $excel->getActiveSheet()->setCellValue('B'.$i,$value->product_desc);
                $excel->getActiveSheet()->setCellValue('C'.$i,$value->unit_name);
                $excel->getActiveSheet()->setCellValue('D'.$i,number_format($value->qty_sold,2))->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
                $excel->getActiveSheet()->setCellValue('E'.$i,number_format($value->srp,2))->getStyle('E'.$i)->getNumberFormat()->setFormatCode('0.00');
                $excel->getActiveSheet()->setCellValue('F'.$i,number_format($value->gross,2))->getStyle('F'.$i)->getNumberFormat()->setFormatCode('0.00');
                $excel->getActiveSheet()->setCellValue('G'.$i,number_format($value->purchase_cost,2))->getStyle('G'.$i)->getNumberFormat()->setFormatCode('0.00');
                $excel->getActiveSheet()->setCellValue('H'.$i,number_format($value->net_profit,2))->getStyle('H'.$i)->getNumberFormat()->setFormatCode('0.00');
                $p_qty+=$value->qty_sold;
                $p_gross+=$value->gross;
                $p_net+=$value->net_profit;
                $i++;


                }

                $excel->getActiveSheet()
                ->getStyle('D'.$i.':'.'H'.$i)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $excel->getActiveSheet()
                ->getStyle('A'.$i.':'.'H'.$i)
                ->getFont()->setBold(TRUE);

                $excel->getActiveSheet()->setCellValue('A'.$i,'TOTAL');
                $excel->getActiveSheet()->setCellValue('D'.$i,number_format($p_qty,2))->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
                $excel->getActiveSheet()->setCellValue('F'.$i,number_format($p_gross,2))->getStyle('F'.$i)->getNumberFormat()->setFormatCode('0.00');
                $excel->getActiveSheet()->setCellValue('H'.$i,number_format($p_net,2))->getStyle('H'.$i)->getNumberFormat()->setFormatCode('0.00');





                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename='."Profit Report by Product.xlsx".'');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
                $objWriter->save('php://output');      

                break;




                case'export-by-invoice-detailed';
                $excel = $this->excel;
                $m_company=$this->Company_model;
                $company_info=$m_company->get_list();
                $company_info=$company_info[0];
                $start = date('Y-m-d',strtotime($this->input->get('start')));
                $end = date('Y-m-d',strtotime($this->input->get('end')));

                $m_sales=$this->Profit_model;
                $data=$m_sales->get_profit_by_invoice_detailed($start,$end);
                $distinct=$m_sales->get_profit_by_invoice_detailed($start,$end,TRUE);
                $subtotal=$m_sales->get_profit_by_invoice_detailed($start,$end,FALSE,TRUE);

                $excel->setActiveSheetIndex(0);
                $excel->getActiveSheet()->setTitle('Profit Report by Invoice');
                $excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
                $excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
                $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                $excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

                $rettotal = 0;
                $gtotal = 0;
                $excel->getActiveSheet()->setCellValue('A1',$company_info->company_name)
                                        ->mergeCells('A1:D1')
                                        ->getStyle('A1:D1')->getFont()->setBold(True)
                                        ->setSize(16);

                $excel->getActiveSheet()->setCellValue('A2',$company_info->company_address)
                                        ->mergeCells('A2:D2')
                                        ->setCellValue('A3',$company_info->landline.' / '.$company_info->mobile_no)
                                        ->mergeCells('A3:D3');

                    $border_bottom= array(
                    'borders' => array(
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '292929')
                        )
                    ));

                    // $excel->getActiveSheet()->setCellValue('A5')
                    //                         ->mergeCells('A5:G5')
                    //                         ->getStyle('A5:G5')->applyFromArray($border_bottom);

                    $excel->getActiveSheet()
                            ->getStyle('A6:G6')
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $excel->getActiveSheet()->setCellValue('A6','Profit Report by Invoice (Detailed)')
                                            ->mergeCells('A6:G6')
                                            ->getStyle('A6:G6')->getFont()->setBold(True)
                                            ->setSize(14);

                $i=8;
                $excel->getActiveSheet()->setCellValue('A'.$i,'Period : '.$start.' - '.$end); $i++;


                $p_qty = 0;
                $p_gross = 0;
                $p_net = 0;

                foreach ($distinct as $inv) {

                $excel->getActiveSheet()
                    ->getStyle('A'.$i.':'.'H'.$i)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('99ffff');

                $excel->getActiveSheet()->mergeCells('A'.$i.':'.'H'.$i);
                $excel->getActiveSheet()->setCellValue('A'.$i,$inv->inv_no)
                                        ->getStyle('A'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->getStyle('A'.$i.':'.'I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $i++;

                $excel->getActiveSheet()
                            ->getStyle('D'.$i.':'.'H'.$i)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $excel->getActiveSheet()->setCellValue('A'.$i,'Item Code')
                                        ->getStyle('A'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('B'.$i,'Description')
                                        ->getStyle('B'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('C'.$i,'UM')
                                        ->getStyle('C'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('D'.$i,'Qty Sold')
                                        ->getStyle('D'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('E'.$i,'SRP')
                                        ->getStyle('E'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('F'.$i,'Gross')
                                        ->getStyle('F'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('G'.$i,'Unit Cost')
                                        ->getStyle('G'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('H'.$i,'Net Profit')
                                        ->getStyle('H'.$i)->getFont()->setBold(TRUE);

                $i++;

                foreach ($data as $value) {
                    if($value->identifier == $inv->identifier && $value->invoice_id == $inv->invoice_id ){
                                $excel->getActiveSheet()->getStyle('D'.$i.':'.'H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                                $excel->getActiveSheet()->setCellValue('A'.$i,$value->product_code);
                                $excel->getActiveSheet()->setCellValue('B'.$i,$value->product_desc);
                                $excel->getActiveSheet()->setCellValue('C'.$i,$value->unit_name);
                                $excel->getActiveSheet()->setCellValue('D'.$i,number_format($value->inv_qty,2))->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
                                $excel->getActiveSheet()->setCellValue('E'.$i,number_format($value->srp,2))->getStyle('E'.$i)->getNumberFormat()->setFormatCode('0.00');
                                $excel->getActiveSheet()->setCellValue('F'.$i,number_format($value->inv_gross,2))->getStyle('F'.$i)->getNumberFormat()->setFormatCode('0.00');
                                $excel->getActiveSheet()->setCellValue('G'.$i,number_format($value->purchase_cost,2))->getStyle('G'.$i)->getNumberFormat()->setFormatCode('0.00');
                                $excel->getActiveSheet()->setCellValue('H'.$i,number_format($value->net_profit,2))->getStyle('H'.$i)->getNumberFormat()->setFormatCode('0.00');
                                $p_qty+=$value->inv_qty;
                                $p_gross+=$value->inv_gross;
                                $p_net+=$value->net_profit;
                        $i++;
                    }
                }
                     

                foreach ($subtotal as $sub) {
                    if($sub->identifier == $inv->identifier && $sub->invoice_id == $inv->invoice_id ){
                        $excel->getActiveSheet()
                        ->getStyle('D'.$i.':'.'H'.$i)
                        ->getAlignment()
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        $excel->getActiveSheet()
                        ->getStyle('A'.$i.':'.'H'.$i)
                        ->getFont()->setBold(TRUE);
                        $excel->getActiveSheet()->setCellValue('A'.$i,'TOTAL ('.$sub->inv_no.')');
                        $excel->getActiveSheet()->setCellValue('D'.$i,number_format($sub->qty_total,2))->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
                        $excel->getActiveSheet()->setCellValue('F'.$i,number_format($sub->gross_total,2))->getStyle('F'.$i)->getNumberFormat()->setFormatCode('0.00');
                        $excel->getActiveSheet()->setCellValue('H'.$i,number_format($sub->profit_total,2))->getStyle('H'.$i)->getNumberFormat()->setFormatCode('0.00');
                        $i++;
                    }
                }

                $i++;


                }






                $excel->getActiveSheet()
                ->getStyle('D'.$i.':'.'H'.$i)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $excel->getActiveSheet()
                ->getStyle('A'.$i.':'.'H'.$i)
                ->getFont()->setBold(TRUE);

                $excel->getActiveSheet()->setCellValue('A'.$i,'GRAND TOTAL');
                $excel->getActiveSheet()->setCellValue('D'.$i,number_format($p_qty,2))->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
                $excel->getActiveSheet()->setCellValue('F'.$i,number_format($p_gross,2))->getStyle('F'.$i)->getNumberFormat()->setFormatCode('0.00');
                $excel->getActiveSheet()->setCellValue('H'.$i,number_format($p_net,2))->getStyle('H'.$i)->getNumberFormat()->setFormatCode('0.00');





                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename='."Profit Report by Invoice Detailed.xlsx".'');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
                $objWriter->save('php://output');      

                break;


    case'export-by-invoice-summary';
                $excel = $this->excel;
                $m_company=$this->Company_model;
                $company_info=$m_company->get_list();
                $company_info=$company_info[0];
                $start = date('Y-m-d',strtotime($this->input->get('start')));
                $end = date('Y-m-d',strtotime($this->input->get('end')));

                $m_sales=$this->Profit_model;
                $summary=$m_sales->get_profit_by_invoice_detailed($start,$end,FALSE,TRUE);

                $excel->setActiveSheetIndex(0);
                $excel->getActiveSheet()->setTitle('Profit Report by Invoice');
                $excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                $excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

                $rettotal = 0;
                $gtotal = 0;
                $excel->getActiveSheet()->setCellValue('A1',$company_info->company_name)
                                        ->mergeCells('A1:D1')
                                        ->getStyle('A1:D1')->getFont()->setBold(True)
                                        ->setSize(16);

                $excel->getActiveSheet()->setCellValue('A2',$company_info->company_address)
                                        ->mergeCells('A2:D2')
                                        ->setCellValue('A3',$company_info->landline.' / '.$company_info->mobile_no)
                                        ->mergeCells('A3:D3');

                    $border_bottom= array(
                    'borders' => array(
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '292929')
                        )
                    ));

                    // $excel->getActiveSheet()->setCellValue('A5')
                    //                         ->mergeCells('A5:G5')
                    //                         ->getStyle('A5:G5')->applyFromArray($border_bottom);

                    $excel->getActiveSheet()
                            ->getStyle('A6:G6')
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $excel->getActiveSheet()->setCellValue('A6','Profit Report by Invoice (Summary)')
                                            ->mergeCells('A6:G6')
                                            ->getStyle('A6:G6')->getFont()->setBold(True)
                                            ->setSize(14);

                $i=8;
                $excel->getActiveSheet()->setCellValue('A'.$i,'Period : '.$start.' - '.$end); $i++;


                $p_qty = 0;
                $p_gross = 0;
                $p_net = 0;

                $excel->getActiveSheet()
                            ->getStyle('D'.$i.':'.'H'.$i)
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $excel->getActiveSheet()->setCellValue('A'.$i,'Invoice No')
                                        ->getStyle('A'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('B'.$i,'Customer Name')
                                        ->getStyle('B'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('C'.$i,'Date')
                                        ->getStyle('C'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('D'.$i,'Date')
                                        ->getStyle('D'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('E'.$i,'Gross')
                                        ->getStyle('E'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('F'.$i,'Net Profit')
                                        ->getStyle('F'.$i)->getFont()->setBold(TRUE);

                $i++;

                foreach ($summary as $value) {
                                $excel->getActiveSheet()->getStyle('D'.$i.':'.'H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                                $excel->getActiveSheet()->setCellValue('A'.$i,$value->inv_no);
                                $excel->getActiveSheet()->setCellValue('B'.$i,$value->customer_name);
                                $excel->getActiveSheet()->setCellValue('C'.$i,$value->date_invoice);
                                $excel->getActiveSheet()->setCellValue('D'.$i,number_format($value->qty_total,2))->getStyle('C'.$i)->getNumberFormat()->setFormatCode('0.00');
                                $excel->getActiveSheet()->setCellValue('E'.$i,number_format($value->gross_total,2))->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
                                $excel->getActiveSheet()->setCellValue('F'.$i,number_format($value->profit_total,2))->getStyle('E'.$i)->getNumberFormat()->setFormatCode('0.00');
                                $p_qty+=$value->qty_total;
                                $p_gross+=$value->gross_total;
                                $p_net+=$value->profit_total;
                        $i++;
                }
                


                $excel->getActiveSheet()
                ->getStyle('D'.$i.':'.'H'.$i)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $excel->getActiveSheet()
                ->getStyle('A'.$i.':'.'H'.$i)
                ->getFont()->setBold(TRUE);

                $excel->getActiveSheet()->setCellValue('A'.$i,'GRAND TOTAL');
                $excel->getActiveSheet()->setCellValue('D'.$i,number_format($p_qty,2))->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
                $excel->getActiveSheet()->setCellValue('E'.$i,number_format($p_gross,2))->getStyle('F'.$i)->getNumberFormat()->setFormatCode('0.00');
                $excel->getActiveSheet()->setCellValue('F'.$i,number_format($p_net,2))->getStyle('H'.$i)->getNumberFormat()->setFormatCode('0.00');





                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename='."Profit Report by Invoice Summary.xlsx".'');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
                $objWriter->save('php://output');      

                break;
        }
    }
}


