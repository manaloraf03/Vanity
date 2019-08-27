<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Spoilage_Report extends CORE_Controller
    {
        
        function __construct()
        {
            parent::__construct('');
            $this->validate_session();
            $this->load->model(
                array(
                    'Adjustment_model',
                    'Suppliers_model',
                    'Users_model',
                    'Company_model'
                )
            );
            $this->load->library('excel');
            $this->load->model('Email_settings_model');
        }

		public function index()
		{	
			$this->Users_model->validate();
		 	$data['_def_css_files'] = $this->load->view('template/assets/css_files', '', true);
	        $data['_def_js_files'] = $this->load->view('template/assets/js_files', '', true);
	        $data['_switcher_settings'] = $this->load->view('template/elements/switcher', '', true);
	        $data['_side_bar_navigation'] = $this->load->view('template/elements/side_bar_navigation', '', true);
	        $data['_top_navigation'] = $this->load->view('template/elements/top_navigation', '', true);
	        $data['title'] = 'Spoilage Report Report';

        //data required by active view
        $data['suppliers']=$this->Suppliers_model->get_list(
            array('suppliers.is_active'=>TRUE,'suppliers.is_deleted'=>FALSE),
            'suppliers.*,IFNULL(tax_types.tax_rate,0)as tax_rate',
            array(
                array('tax_types','tax_types.tax_type_id=suppliers.tax_type_id','left')
            )
        );


        (in_array('15-7',$this->session->user_rights)? 
        $this->load->view('spoilage_report_view',$data)
        :redirect(base_url('dashboard')));
            
        }

        function transaction($txn=null){
            switch($txn){

                case 'summary':

                    $start_Date=date('Y-m-d',strtotime($this->input->get('startDate',TRUE)));
                    $end_Date=date('Y-m-d',strtotime($this->input->get('endDate',TRUE)));
                    $tid=$this->input->get('tid',TRUE);
                    $m_adjustments=$this->Adjustment_model;
                    $response['data']=$m_adjustments->spoilage_by_product_summary($start_Date,$end_Date,$tid);
                    echo json_encode($response);

                break;

                case 'summary-print':
                    $m_adjustments=$this->Adjustment_model;
                    $m_company=$this->Company_model;

                    $start_Date=date('Y-m-d',strtotime($this->input->get('startDate',TRUE)));
                    $end_Date=date('Y-m-d',strtotime($this->input->get('endDate',TRUE)));
                    $tid=$this->input->get('tid',TRUE);
                    $data['start_Date'] = $this->input->get('startDate',TRUE);
                    $data['end_Date'] = $this->input->get('endDate',TRUE);
                    $data['products']=$m_adjustments->spoilage_by_product_summary($start_Date,$end_Date,$tid);
                    $data['company_info']=$company=$m_company->get_list()[0];
                    echo $this->load->view('template/spoilage_report_summary_content',$data,TRUE);

                break;

                case 'detailed':

                    $start_Date=date('Y-m-d',strtotime($this->input->get('startDate',TRUE)));
                    $end_Date=date('Y-m-d',strtotime($this->input->get('endDate',TRUE)));
                    $tid=$this->input->get('tid',TRUE);
                    $m_adjustments=$this->Adjustment_model;
                    $response['data']=$m_adjustments->spoilage_by_product_detailed($start_Date,$end_Date,$tid);
                    echo json_encode($response);

                break;

                case 'detailed-print':
                    $m_adjustments=$this->Adjustment_model;
                    $m_company=$this->Company_model;

                    $start_Date=date('Y-m-d',strtotime($this->input->get('startDate',TRUE)));
                    $end_Date=date('Y-m-d',strtotime($this->input->get('endDate',TRUE)));
                    $tid=$this->input->get('tid',TRUE);

                    $data['start_Date'] = $this->input->get('startDate',TRUE);
                    $data['end_Date'] = $this->input->get('endDate',TRUE);
                    $data['company_info']=$company=$m_company->get_list()[0];
                    $data['products']=$m_adjustments->spoilage_by_product_detailed($start_Date,$end_Date,$tid);
                    $data['adjustments']=$m_adjustments->spoilage_by_product_detailed($start_Date,$end_Date,$tid,$distinct_code=1);
                    echo $this->load->view('template/spoilage_report_detailed_content',$data,TRUE);

                break;

                case 'summary-excel':

                    $start_Date=date('Y-m-d',strtotime($this->input->get('startDate',TRUE)));
                    $end_Date=date('Y-m-d',strtotime($this->input->get('endDate',TRUE)));
                    $tid=$this->input->get('tid',TRUE);
                    $m_adjustments=$this->Adjustment_model;
                    $products=$m_adjustments->spoilage_by_product_summary($start_Date,$end_Date,$tid);
                    // echo json_encode($response);


                    $excel=$this->excel;
                    $m_company_info=$this->Company_model;

                    $company_info=$m_company_info->get_list()[0];

                $excel->setActiveSheetIndex(0);

                $excel->getActiveSheet()->setTitle('Spoilage Report');

                $excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
                $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

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


                    $excel->getActiveSheet()
                            ->getStyle('A4:D4')
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $excel->getActiveSheet()->setCellValue('A4','Spoilage Report Summary')
                                            ->mergeCells('A4:D4')
                                            ->getStyle('A4:D4')->getFont()->setBold(True)
                                            ->setSize(14);

                if($tid == 1 ){ $type = 'Adjustments - OUT'; }else { $type =  'Sales Returns - IN';}
                $excel->getActiveSheet()->setCellValue('A5','Date:')->getStyle('A5')->getFont()->setBold(True);
                $excel->getActiveSheet()->setCellValue('B5',$this->input->get('startDate',TRUE).' - '.$this->input->get('endDate',TRUE));
                $excel->getActiveSheet()->setCellValue('C5','Adjustment Type:')->getStyle('C5')->getFont()->setBold(True);
                $excel->getActiveSheet()->setCellValue('D5', $type);

                $excel->getActiveSheet()->setCellValue('A7','Product Code')->getStyle('A7')->getFont()->setBold(True);
                $excel->getActiveSheet()->setCellValue('B7','Product Description')->getStyle('B7')->getFont()->setBold(True);
                $excel->getActiveSheet()->setCellValue('C7','Quantity')->getStyle('C7')->getFont()->setBold(True);
                $excel->getActiveSheet()->setCellValue('D7','Total Amount')->getStyle('D7')->getFont()->setBold(True);
                $excel->getActiveSheet()->getStyle('C7:D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $i= 8;
                $total_qty = 0;
                $total_price = 0;
                foreach ($products as $product) {
                    $excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $excel->getActiveSheet()->setCellValue('A'.$i,$product->product_code);
                    $excel->getActiveSheet()->setCellValue('B'.$i,$product->product_desc);
                    $excel->getActiveSheet()->setCellValue('C'.$i,number_format($product->adjust_qty,2));
                    $excel->getActiveSheet()->setCellValue('D'.$i,number_format($product->adjust_line_total_price,2));;
                    $excel->getActiveSheet()->getStyle('C'.$i.':D'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)');
                    $excel->getActiveSheet()->getStyle('C'.$i.':D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $total_qty += $product->adjust_qty;
                    $total_price  += $product->adjust_line_total_price;
                    $i++;
                }
                    $excel->getActiveSheet()->setCellValue('B'.$i,'TOTAL:')->getStyle('B'.$i)->getFont()->setBold(True);
                    $excel->getActiveSheet()->setCellValue('C'.$i,number_format($total_qty,2))->getStyle('C'.$i)->getFont()->setBold(True);
                    $excel->getActiveSheet()->setCellValue('D'.$i,number_format($total_price,2))->getStyle('D'.$i)->getFont()->setBold(True);
                    $excel->getActiveSheet()->getStyle('C'.$i.':D'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)');
                    $excel->getActiveSheet()->getStyle('B'.$i.':D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $i++;


                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename='."Spoilage Report (Summary).xlsx".'');
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

                case 'detailed-excel':

                    $start_Date=date('Y-m-d',strtotime($this->input->get('startDate',TRUE)));
                    $end_Date=date('Y-m-d',strtotime($this->input->get('endDate',TRUE)));
                    $tid=$this->input->get('tid',TRUE);
                    $m_adjustments=$this->Adjustment_model;
                    $products=$m_adjustments->spoilage_by_product_detailed($start_Date,$end_Date,$tid);
                    $adjustments=$m_adjustments->spoilage_by_product_detailed($start_Date,$end_Date,$tid,$distinct_code=1);


                    $excel=$this->excel;
                    $m_company_info=$this->Company_model;

                    $company_info=$m_company_info->get_list()[0];

                    $type=$this->input->get('type');
                    $startDate=date('Y-m-d',strtotime($this->input->get('startDate')));
                    $endDate=date('Y-m-d',strtotime($this->input->get('endDate')));
                    $sup_id=$this->input->get('sup_id');

                $excel->setActiveSheetIndex(0);

                $excel->getActiveSheet()->setTitle('Spoilage Report Detailed');

                $excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
                $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

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

                $excel->getActiveSheet()
                        ->getStyle('A4:D4')
                        ->getAlignment()
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $excel->getActiveSheet()->setCellValue('A4','Spoilage Report Detailed')
                                        ->mergeCells('A4:D4')
                                        ->getStyle('A4:D4')->getFont()->setBold(True)
                                        ->setSize(14);

                if($tid == 1 ){ $type = 'Adjustments - OUT'; }else { $type =  'Sales Returns - IN';}
                $excel->getActiveSheet()->setCellValue('A5','Date:')->getStyle('A5')->getFont()->setBold(True);
                $excel->getActiveSheet()->setCellValue('B5',$this->input->get('startDate',TRUE).' - '.$this->input->get('endDate',TRUE));
                $excel->getActiveSheet()->setCellValue('C5','Adjustment Type:')->getStyle('C5')->getFont()->setBold(True);
                $excel->getActiveSheet()->setCellValue('D5', $type);




            $i= 7;
            foreach ($adjustments as $adjustment) {
                $excel->getActiveSheet()->setCellValue('A'.$i,$adjustment->adjustment_code)->getStyle('A'.$i)->getFont()->setBold(True);
                $excel->getActiveSheet()->setCellValue('B'.$i,ucwords($adjustment->customer_name))->getStyle('B'.$i)->getFont()->setBold(True);
                $excel->getActiveSheet()->setCellValue('C'.$i,date("m-d-Y", strtotime($adjustment->date_adjusted)))->getStyle('C'.$i)->getFont()->setBold(True);
                $excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFill()
                                            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                                            ->getStartColor()->setARGB('82d1f5');
                $i++;
                $excel->getActiveSheet()->setCellValue('A'.$i,'Product Code')->getStyle('A'.$i)->getFont()->setBold(True);
                $excel->getActiveSheet()->setCellValue('B'.$i,'Product Description')->getStyle('B'.$i)->getFont()->setBold(True);
                $excel->getActiveSheet()->setCellValue('C'.$i,'Quantity')->getStyle('C'.$i)->getFont()->setBold(True);
                $excel->getActiveSheet()->setCellValue('D'.$i,'Total Amount')->getStyle('D'.$i)->getFont()->setBold(True);
                $excel->getActiveSheet()->getStyle('C'.$i.':D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $i++;
                $total_qty = 0;
                $total_price = 0;
                foreach ($products as $product) {
                    if($adjustment->adjustment_id == $product->adjustment_id){
                        $excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $excel->getActiveSheet()->setCellValue('A'.$i,$product->product_code);
                        $excel->getActiveSheet()->setCellValue('B'.$i,$product->product_desc);
                        $excel->getActiveSheet()->setCellValue('C'.$i,number_format($product->adjust_qty,2));
                        $excel->getActiveSheet()->setCellValue('D'.$i,number_format($product->adjust_line_total_price,2));;
                        $excel->getActiveSheet()->getStyle('C'.$i.':D'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)');
                        $excel->getActiveSheet()->getStyle('C'.$i.':D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $total_qty += $product->adjust_qty;
                        $total_price  += $product->adjust_line_total_price;
                        $i++;
                    } 
                }
                    $excel->getActiveSheet()->setCellValue('B'.$i,'TOTAL:')->getStyle('B'.$i)->getFont()->setBold(True);
                    $excel->getActiveSheet()->setCellValue('C'.$i,number_format($total_qty,2))->getStyle('C'.$i)->getFont()->setBold(True);
                    $excel->getActiveSheet()->setCellValue('D'.$i,number_format($total_price,2))->getStyle('D'.$i)->getFont()->setBold(True);
                    $excel->getActiveSheet()->getStyle('C'.$i.':D'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)');
                    $excel->getActiveSheet()->getStyle('B'.$i.':D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $i++;
                    $i++;
            }

                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename='."Spoilage Report (Detailed).xlsx".'');
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
?>