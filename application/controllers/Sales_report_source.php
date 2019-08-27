<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_report_source extends CORE_Controller
{

    function __construct() {
        parent::__construct('');
        $this->validate_session();

        $this->load->model('Sales_report_source_model');

        $this->load->model('Users_model');
        $this->load->model('Company_model');
        $this->load->library('excel');
        $this->load->model('Email_settings_model');
        $this->load->model('Order_source_model');
        $this->load->model('Suppliers_model');
    }

    public function index() {
        $this->Users_model->validate();
        //default resources of the active view
        $data['_def_css_files'] = $this->load->view('template/assets/css_files', '', TRUE);
        $data['_def_js_files'] = $this->load->view('template/assets/js_files', '', TRUE);
        $data['_switcher_settings'] = $this->load->view('template/elements/switcher', '', TRUE);
        $data['_side_bar_navigation'] = $this->load->view('template/elements/side_bar_navigation', '', TRUE);
        $data['_top_navigation'] = $this->load->view('template/elements/top_navigation', '', TRUE);


        $data['title'] = 'Sales Report By Source';
         $data['order_sources'] = $this->Order_source_model->get_list(array('is_deleted'=>FALSE,'is_active'=>TRUE));
         $data['suppliers'] = $this->Suppliers_model->get_list(array('is_deleted'=>FALSE,'is_active'=>TRUE,'is_brand_partner'=>TRUE),null,null,'supplier_name asc');
        (in_array('8-5',$this->session->user_rights)? 
        $this->load->view('sales_report_source_view', $data)
        :redirect(base_url('dashboard')));

    }

    function transaction($txn = null,$id_filter=null) {
        switch ($txn){

                case'list';
                $m_sales=$this->Sales_report_source_model;
                $order_source_id = $this->input->get('oi');
                $source_invoice = $this->input->get('si');
                $supplier_id = $this->input->get('bp');
                $start = date('Y-m-d',strtotime($this->input->get('start')));
                $end = date('Y-m-d',strtotime($this->input->get('end')));
                    $response['data']=$m_sales->get_sales_source(null,null,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                    $response['sources']=$m_sales->get_sales_source(TRUE,null,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                    $response['totals']=$m_sales->get_sales_source(null,TRUE,$order_source_id,$start,$end,$source_invoice,$supplier_id);

                    $response['return_data']=$m_sales->get_sales_return(null,null,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                    $response['return_sources']=$m_sales->get_sales_return(TRUE,null,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                    $response['return_totals']=$m_sales->get_sales_return(null,TRUE,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                    echo json_encode($response);
                break;

 
                case'report';
                $m_company=$this->Company_model;
                $company_info=$m_company->get_list();
                $data['company_info']=$company_info[0];


                $m_sales=$this->Sales_report_source_model;
                $order_source_id = $this->input->get('oi');
                $source_invoice = $this->input->get('si');
                $supplier_id = $this->input->get('bp');

                $start = date('Y-m-d',strtotime($this->input->get('start')));
                $end = date('Y-m-d',strtotime($this->input->get('end')));
                    $data['data']=$m_sales->get_sales_source(null,null,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                    $data['sources']=$m_sales->get_sales_source(TRUE,null,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                    $data['totals']=$m_sales->get_sales_source(null,TRUE,$order_source_id,$start,$end,$source_invoice,$supplier_id);

                    $data['return_data']=$m_sales->get_sales_return(null,null,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                    $data['return_sources']=$m_sales->get_sales_return(TRUE,null,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                    $data['return_totals']=$m_sales->get_sales_return(null,TRUE,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                    $source_name = $this->Order_source_model->get_list($order_source_id);
                    if(count($source_name) == 0){

                        $data['source_name'] = 'ALL';
                    }else{
                        $data['source_name'] = $source_name[0]->order_source_name;
                    }

                    if($supplier_id == 0){
                         $data['supplier_name'] = '';
                    }else{
                        $data['supplier_name'] = $this->Suppliers_model->get_list($supplier_id)[0]->supplier_name;
                    }
                $this->load->view('template/sales_report_source_content',$data);
                break;

                case'export';
                $excel = $this->excel;
                $m_company=$this->Company_model;
                $company_info=$m_company->get_list();
                $company_info=$company_info[0];


                $m_sales=$this->Sales_report_source_model;
                $order_source_id = $this->input->get('oi');
                $source_invoice = $this->input->get('si');
                $supplier_id = $this->input->get('bp');
                $start = date('Y-m-d',strtotime($this->input->get('start')));
                $end = date('Y-m-d',strtotime($this->input->get('end')));
                $data=$m_sales->get_sales_source(null,null,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                $sources=$m_sales->get_sales_source(TRUE,null,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                $totals=$m_sales->get_sales_source(null,TRUE,$order_source_id,$start,$end,$source_invoice,$supplier_id);

                $return_data=$m_sales->get_sales_return(null,null,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                $return_sources=$m_sales->get_sales_return(TRUE,null,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                $return_totals=$m_sales->get_sales_return(null,TRUE,$order_source_id,$start,$end,$source_invoice,$supplier_id);
                $source_name = $this->Order_source_model->get_list($order_source_id);
                if(count($source_name) == 0){ $source_name= 'ALL'; }else{$source_name= $source_name[0]->order_source_name; }
                if($source_invoice == 0){ $inv_src = 'All Invoices'; }else if ($source_invoice == 1){ $inv_src = 'Sales Invoices Only'; }else if($source_invoice== 2){ $inv_src = 'Cash Invoice Only' ;} 
                if($supplier_id == 0){
                    $supplier_name = '';
                }else{
                    $supplier_name = $this->Suppliers_model->get_list($supplier_id)[0]->supplier_name;
                }
                $excel->setActiveSheetIndex(0);

                $excel->getActiveSheet()->setTitle('Sales Report by Source');

                $excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
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

                    $excel->getActiveSheet()->setCellValue('A6','Sales Report by Source')
                                            ->mergeCells('A6:G6')
                                            ->getStyle('A6:G6')->getFont()->setBold(True)
                                            ->setSize(14);

                $i=8;
                $excel->getActiveSheet()->setCellValue('A'.$i,'Period: ');
                $excel->getActiveSheet()->setCellValue('B'.$i,$start.' - '.$end);   $i++;
                $excel->getActiveSheet()->setCellValue('A'.$i,'Source:');
                $excel->getActiveSheet()->setCellValue('B'.$i,$source_name);        $i++;
                $excel->getActiveSheet()->setCellValue('A'.$i,'Invoice Source: ');
                $excel->getActiveSheet()->setCellValue('B'.$i,$inv_src);            $i++;
                if($supplier_id != 0){
                $excel->getActiveSheet()->setCellValue('A'.$i,'Brand Partner: ');
                $excel->getActiveSheet()->setCellValue('B'.$i,$supplier_name);   
                }

                $i++;


                $excel->getActiveSheet()
                    ->getStyle('A'.$i.':'.'I'.$i)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFC0CB');

                $excel->getActiveSheet()->mergeCells('A'.$i.':'.'I'.$i);
                $excel->getActiveSheet()->setCellValue('A'.$i,'SALES')
                                        ->getStyle('A'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->getStyle('A'.$i.':'.'I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $i++;
                foreach ($sources as $source) {

                $excel->getActiveSheet()
                    ->getStyle('A'.$i.':'.'I'.$i)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('b3ffff');

                $excel->getActiveSheet()->mergeCells('A'.$i.':'.'I'.$i);
                $excel->getActiveSheet()->setCellValue('A'.$i,$source->order_source_name)
                                        ->getStyle('A'.$i)->getFont()->setBold(TRUE);
                $i++;
                                         
                $excel->getActiveSheet()->setCellValue('A'.$i,'Invoice No')
                                        ->getStyle('A'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('B'.$i,'Date')
                                        ->getStyle('B'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('C'.$i,'Customer Name')
                                        ->getStyle('C'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('D'.$i,'Product Description')
                                        ->getStyle('D'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('E'.$i,'Quantity')
                                        ->getStyle('E'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('F'.$i,'Unit Cost')
                                        ->getStyle('F'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('G'.$i,'Gross')
                                        ->getStyle('G'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('H'.$i,'Discount(%)')
                                        ->getStyle('H'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('I'.$i,'Net')
                                        ->getStyle('I'.$i)->getFont()->setBold(TRUE);

                $i++;
                foreach ($data as $raw) {
                    if($source->order_source_id == $raw->order_source_id){
                    $excel->getActiveSheet()->setCellValue('A'.$i, $raw->inv_no);
                    $excel->getActiveSheet()->setCellValue('B'.$i,$raw->date);
                    $excel->getActiveSheet()->setCellValue('C'.$i,$raw->customer_name);
                    $excel->getActiveSheet()->setCellValue('D'.$i,$raw->product_desc);
                    $excel->getActiveSheet()->setCellValue('E'.$i,number_format($raw->inv_qty,2));
                    $excel->getActiveSheet()->setCellValue('F'.$i,number_format($raw->inv_price,2));
                    $excel->getActiveSheet()->setCellValue('G'.$i,number_format($raw->inv_gross,2));
                    $excel->getActiveSheet()->setCellValue('H'.$i,number_format($raw->inv_discount,2));
                    $excel->getActiveSheet()->setCellValue('I'.$i,number_format($raw->inv_line_total_price,2));
                    $excel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $excel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $excel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $excel->getActiveSheet()->getStyle('H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $excel->getActiveSheet()->getStyle('I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $i++;
                    }
                }

                $gtotal = 0;
                foreach ($totals as $total) {
                    $gtotal+= $total->sub_total;
                    if($source->order_source_id == $total->order_source_id){
                    $excel->getActiveSheet()->setCellValue('H'.$i,'Sub Total:');
                    $excel->getActiveSheet()->getStyle('H'.$i)->getFont()->setBold(True);
                    $excel->getActiveSheet()->getStyle('H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $excel->getActiveSheet()->setCellValue('I'.$i,number_format($total->sub_total,2));
                    $excel->getActiveSheet()->getStyle('I'.$i)->getFont()->setBold(True);
                    $excel->getActiveSheet()->getStyle('I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


                    $i++;
                    }
                }

                $i++;

                }

                $excel->getActiveSheet()->setCellValue('H'.$i,'Total Sales:');
                $excel->getActiveSheet()->getStyle('H'.$i)->getFont()->setBold(True);
                $excel->getActiveSheet()->getStyle('H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $excel->getActiveSheet()->getStyle('I'.$i)->getFont()->setBold(True);
                $excel->getActiveSheet()->setCellValue('I'.$i,number_format($gtotal,2));
                $excel->getActiveSheet()->getStyle('I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $i++;$i++;
                $excel->getActiveSheet()
                    ->getStyle('A'.$i.':'.'H'.$i)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFC0CB');

                $excel->getActiveSheet()->mergeCells('A'.$i.':'.'H'.$i);
                $excel->getActiveSheet()->setCellValue('A'.$i,'RETURNS')
                                        ->getStyle('A'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->getStyle('A'.$i.':'.'H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $i++;

                foreach ($return_sources as $rsource) {
                $excel->getActiveSheet()
                    ->getStyle('A'.$i.':'.'H'.$i)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('b3ffff');

                $excel->getActiveSheet()->mergeCells('A'.$i.':'.'H'.$i);
                $excel->getActiveSheet()->setCellValue('A'.$i,$rsource->order_source_name)
                                        ->getStyle('A'.$i)->getFont()->setBold(TRUE);
                $i++;
                                         
                $excel->getActiveSheet()->setCellValue('A'.$i,'Invoice No')
                                        ->getStyle('A'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('B'.$i,'Reference')
                                        ->getStyle('B'.$i)->getFont()->setBold(TRUE);

                $excel->getActiveSheet()->setCellValue('C'.$i,'Customer Name')
                                        ->getStyle('C'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('D'.$i,'Product Description')
                                        ->getStyle('D'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('E'.$i,'Return Date')
                                        ->getStyle('E'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('F'.$i,'Quantity')
                                        ->getStyle('F'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('G'.$i,'Unit Cost')
                                        ->getStyle('G'.$i)->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('H'.$i,'Net')
                                        ->getStyle('H'.$i)->getFont()->setBold(TRUE);
                $i++;


                foreach ($return_data as $return_raw) {
                    if($rsource->order_source_id == $return_raw->order_source_id){
                    $excel->getActiveSheet()->setCellValue('A'.$i, $return_raw->inv_no);
                    $excel->getActiveSheet()->setCellValue('B'.$i,$return_raw->adjustment_code);
                    $excel->getActiveSheet()->setCellValue('C'.$i,$return_raw->customer_name);
                    $excel->getActiveSheet()->setCellValue('D'.$i,$return_raw->product_desc);
                    $excel->getActiveSheet()->setCellValue('E'.$i,$return_raw->date_adjusted);
                    $excel->getActiveSheet()->setCellValue('F'.$i,number_format($return_raw->adjust_qty,2));
                    $excel->getActiveSheet()->setCellValue('G'.$i,number_format($return_raw->adjust_price,2));
                    $excel->getActiveSheet()->setCellValue('H'.$i,number_format($return_raw->adjust_line_total_price,2));
                    $excel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $excel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $excel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $excel->getActiveSheet()->getStyle('H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $i++;
                    }
                }

                $rettotal = 0;
                foreach ($return_totals as $rtotal) {
                    $rettotal+= $rtotal->sub_total;
                    if($rsource->order_source_id == $rtotal->order_source_id){
                    $excel->getActiveSheet()->setCellValue('G'.$i,'Sub Total:');
                    $excel->getActiveSheet()->getStyle('G'.$i)->getFont()->setBold(True);
                    $excel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $excel->getActiveSheet()->setCellValue('H'.$i,number_format($rtotal->sub_total,2));
                    $excel->getActiveSheet()->getStyle('H'.$i)->getFont()->setBold(True);
                    $excel->getActiveSheet()->getStyle('H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


                    $i++;
                    }
                } // END OF SUB TOTALS

                }

                $i++;
                $excel->getActiveSheet()->setCellValue('G'.$i,'Total Returns:');
                $excel->getActiveSheet()->getStyle('G'.$i)->getFont()->setBold(True);
                $excel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $excel->getActiveSheet()->setCellValue('H'.$i,number_format($rettotal,2));
                $excel->getActiveSheet()->getStyle('H'.$i)->getFont()->setBold(True);
                $excel->getActiveSheet()->getStyle('H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $i++;

                $excel->getActiveSheet()->setCellValue('G'.$i,'Net Sales:');
                $excel->getActiveSheet()->getStyle('G'.$i)->getFont()->setBold(True);
                $excel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $excel->getActiveSheet()->setCellValue('H'.$i,number_format($gtotal - $rettotal,2));
                $excel->getActiveSheet()->getStyle('H'.$i)->getFont()->setBold(True);
                $excel->getActiveSheet()->getStyle('H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename='."Sales Report by Source.xlsx".'');
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


