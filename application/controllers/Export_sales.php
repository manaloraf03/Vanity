<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export_sales extends CORE_Controller
{
    function __construct()
    {
        parent::__construct('');
        $this->validate_session();

        $this->load->library('excel');

        $this->load->model(
            array
            (
                'Account_type_model',
                'Account_class_model',
                'Account_title_model',
                'Departments_model',
                'Users_model',
                'Sales_invoice_model',
                'Sales_invoice_item_model',
                'Cash_invoice_model',
                'Cash_invoice_items_model',
                'Company_model'
            )
        );
        $this->load->model('Email_settings_model');
    }

    public function index() {
        $this->Users_model->validate();
        $data['_def_css_files'] = $this->load->view('template/assets/css_files', '', true);
        $data['_def_js_files'] = $this->load->view('template/assets/js_files', '', true);
        $data['_switcher_settings'] = $this->load->view('template/elements/switcher', '', true);
        $data['_side_bar_navigation'] = $this->load->view('template/elements/side_bar_navigation', '', true);
        $data['_top_navigation'] = $this->load->view('template/elements/top_navigation', '', true);
        $data['title'] = 'Inventory Report';

        $data['departments']=$this->Departments_model->get_list(array('is_deleted'=>FALSE,'is_active'=>TRUE));
        $data['title']="Trial Balance Report";
        
        (in_array('9-3',$this->session->user_rights)? 
        $this->load->view('export_sales_view',$data)
        :redirect(base_url('dashboard')));
    }


    public function transaction($txn=null){
        switch($txn){
            case 'test':


            case 'export':
                $m_company=$this->Company_model;
                $company_info=$m_company->get_list();
                $start=date('Y-m-d',strtotime($this->input->get('start',TRUE)));
                $end=date('Y-m-d',strtotime($this->input->get('end',TRUE)));
                $excel=$this->excel;
                $excel->setActiveSheetIndex(0);
                //name the worksheet
                $excel->getActiveSheet()->setTitle('Sales and Cash Invoices');
               //company info
                $excel->getActiveSheet()->setCellValue('A1',$company_info[0]->company_name)
                                        ->setCellValue('A2',$company_info[0]->company_address)
                                        ->setCellValue('A3',$company_info[0]->email_address)
                                        ->setCellValue('A4',$company_info[0]->mobile_no);
                //$excel->getActiveSheet()->getProtection()->setSheet(TRUE);
                $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);

                $excel->getActiveSheet()->getColumnDimensionByColumn('A')->setWidth('20');

                $i = 9;

                $infos=$this->Sales_invoice_model->get_list(
                    "sales_invoice.is_active = TRUE AND sales_invoice.is_deleted = FALSE AND sales_invoice.date_invoice BETWEEN '".$start."' AND '".$end."'",
                    array(
                        'sales_invoice.sales_invoice_id',
                        'sales_invoice.sales_inv_no',
                        'sales_invoice.remarks', 
                        'sales_invoice.date_created',
                        'sales_invoice.customer_id',
                        'sales_invoice.inv_type',
                        'sales_invoice.*',
                        'DATE_FORMAT(sales_invoice.date_invoice,"%m/%d/%Y") as date_invoice',
                        'DATE_FORMAT(sales_invoice.date_due,"%m/%d/%Y") as date_due',
                        'departments.department_id',
                        'departments.department_name',
                        'customers.customer_name',
                        'sales_invoice.salesperson_id',
                        'sales_invoice.address',
                        'sales_order.so_no',
                        'order_source.order_source_name',
                        'CONCAT(salesperson.firstname," ",salesperson.lastname) AS salesperson_name',
                        'CONCAT_WS(" ",user_accounts.user_fname,user_accounts.user_lname)as user'
                    
                    ),
                    array(
                        array('departments','departments.department_id=sales_invoice.department_id','left'),
                        array('salesperson','salesperson.salesperson_id=sales_invoice.salesperson_id','left'),
                        array('customers','customers.customer_id=sales_invoice.customer_id','left'),
                        array('sales_order','sales_order.sales_order_id=sales_invoice.sales_order_id','left'),
                        array('order_source','order_source.order_source_id=sales_invoice.order_source_id','left'),
                        array('user_accounts','user_accounts.user_id=sales_invoice.posted_by_user','left')
                    )
                );

// print_r($infos);

                $items=$this->Sales_invoice_item_model->get_list(
                    "sales_invoice.is_active = TRUE AND sales_invoice.is_deleted = FALSE AND sales_invoice.date_invoice BETWEEN '".$start."' AND '".$end."'",
                    'sales_invoice_items.*,products.product_desc,products.size,units.unit_name,products.product_code',
                    array(
                        array('products','products.product_id=sales_invoice_items.product_id','left'),
                        array('sales_invoice','sales_invoice.sales_invoice_id=sales_invoice_items.sales_invoice_id','left'),
                        array('units','units.unit_id=sales_invoice_items.unit_id','left')
                    )
                );




                $cashinfo=$this->Cash_invoice_model->get_list(
                    "cash_invoice.is_active = TRUE AND cash_invoice.is_deleted = FALSE AND cash_invoice.date_invoice BETWEEN '".$start."' AND '".$end."'",
                array(
                    'cash_invoice.*',
                    'DATE_FORMAT(cash_invoice.date_invoice,"%m/%d/%Y") as date_invoice',
                    'DATE_FORMAT(cash_invoice.date_due,"%m/%d/%Y") as date_due',
                    'departments.department_id',
                    'departments.department_name',
                    'cash_invoice.salesperson_id',
                    'cash_invoice.address',
                    'sales_order.so_no',
                    'order_source.order_source_name',
                    'customers.customer_name',
                    'CONCAT(salesperson.firstname," ",salesperson.lastname) AS salesperson_name',
                    'CONCAT_WS(" ",user_accounts.user_fname,user_accounts.user_lname)as user'

                ),
                array(
                    array('departments','departments.department_id=cash_invoice.department_id','left'),
                    array('customers','customers.customer_id=cash_invoice.customer_id','left'),
                    array('sales_order','sales_order.sales_order_id=cash_invoice.sales_order_id','left'),
                    array('order_source','order_source.order_source_id=cash_invoice.order_source_id','left'),
                    array('salesperson','salesperson.salesperson_id=cash_invoice.salesperson_id','left'),
                    array('user_accounts','user_accounts.user_id=cash_invoice.posted_by_user','left')
                ),
                'cash_invoice.cash_invoice_id DESC'
            );

                $cashitems=$this->Cash_invoice_items_model->get_list(
                    "cash_invoice.is_active = TRUE AND cash_invoice.is_deleted = FALSE AND cash_invoice.date_invoice BETWEEN '".$start."' AND '".$end."'",
                    'cash_invoice_items.*,products.product_desc,products.size,units.unit_name,products.product_code',
                    array(
                        array('products','products.product_id=cash_invoice_items.product_id','left'),
                        array('units','units.unit_id=cash_invoice_items.unit_id','left')
                    )
                );



                foreach ($cashinfos as $cashinfo) {

                     $excel->getActiveSheet()->setCellValue('A'.$i, "Invoice No");
                     $excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(TRUE);
                     $excel->getActiveSheet()->setCellValue('B'.$i,$cashinfo->sales_inv_no); 
                     $excel->getActiveSheet()->setCellValue('D'.$i, "Date");
                     $excel->getActiveSheet()->getStyle('D'.$i)->getFont()->setBold(TRUE);
                     $excel->getActiveSheet()->setCellValue('E'.$i, $cashinfo->date_invoice); $i++;


                     $excel->getActiveSheet()->setCellValue('A'.$i, "Customer");
                     $excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(TRUE);
                     $excel->getActiveSheet()->setCellValue('B'.$i,$cashinfo->customer_name); 
                     $excel->getActiveSheet()->setCellValue('D'.$i, "Sales Type");
                     $excel->getActiveSheet()->getStyle('D'.$i)->getFont()->setBold(TRUE);
                     $excel->getActiveSheet()->setCellValue('E'.$i, "Charge"); $i++;


                     $excel->getActiveSheet()->setCellValue('A'.$i, "Type");
                     $excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(TRUE);
                     $excel->getActiveSheet()->setCellValue('B'.$i,$cashinfo->order_source_name); 
                     $excel->getActiveSheet()->setCellValue('D'.$i, "Clerk");
                     $excel->getActiveSheet()->getStyle('D'.$i)->getFont()->setBold(TRUE);
                     $excel->getActiveSheet()->setCellValue('E'.$i, $cashinfo->user); $i++;

                    $excel->getActiveSheet()
                    ->getStyle('A'.$i.':'.'E'.$i)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('6fefff');
                    $excel->getActiveSheet()->setCellValue('A'.$i,'Code')
                                            ->getStyle('A'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->setCellValue('B'.$i,'Description')
                                            ->getStyle('B'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->setCellValue('C'.$i,'QTY')
                                            ->getStyle('C'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->setCellValue('D'.$i,'S Price')
                                            ->getStyle('D'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->setCellValue('E'.$i,'Total')
                                            ->getStyle('E'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->getStyle('C'.$i.':E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $i++;

                     $inv_total = 0;
                     foreach ($items as $item) {
                        if($cashinfo->sales_invoice_id == $item->sales_invoice_id) {
                            $inv_total += $item->inv_gross;
                            $excel->getActiveSheet()->setCellValue('A'.$i,$item->product_code); 
                            $excel->getActiveSheet()->setCellValue('B'.$i,$item->product_desc); 
                            $excel->getActiveSheet()->setCellValue('C'.$i,$item->inv_qty); 
                            $excel->getActiveSheet()->setCellValue('D'.$i,$item->inv_price); 
                            $excel->getActiveSheet()->setCellValue('E'.$i,$item->inv_gross); 
                            $excel->getActiveSheet()->getStyle('C'.$i.':E'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)'); $i++;
                        }

                     }
                    $excel->getActiveSheet()->setCellValue('D'.$i,'Invoice Total')
                                            ->getStyle('D'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->setCellValue('E'.$i,$inv_total)
                                            ->getStyle('E'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)');
                    $excel->getActiveSheet()->getStyle('D'.$i.':E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $i++;$i++;$i++;
                }


                foreach ($infos as $info) {

                     $excel->getActiveSheet()->setCellValue('A'.$i, "Invoice No");
                     $excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(TRUE);
                     $excel->getActiveSheet()->setCellValue('B'.$i,$info->sales_inv_no); 
                     $excel->getActiveSheet()->setCellValue('D'.$i, "Date");
                     $excel->getActiveSheet()->getStyle('D'.$i)->getFont()->setBold(TRUE);
                     $excel->getActiveSheet()->setCellValue('E'.$i, $info->date_invoice); $i++;


                     $excel->getActiveSheet()->setCellValue('A'.$i, "Customer");
                     $excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(TRUE);
                     $excel->getActiveSheet()->setCellValue('B'.$i,$info->customer_name); 
                     $excel->getActiveSheet()->setCellValue('D'.$i, "Sales Type");
                     $excel->getActiveSheet()->getStyle('D'.$i)->getFont()->setBold(TRUE);
                     $excel->getActiveSheet()->setCellValue('E'.$i, "Charge"); $i++;


                     $excel->getActiveSheet()->setCellValue('A'.$i, "Type");
                     $excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(TRUE);
                     $excel->getActiveSheet()->setCellValue('B'.$i,$info->order_source_name); 
                     $excel->getActiveSheet()->setCellValue('D'.$i, "Clerk");
                     $excel->getActiveSheet()->getStyle('D'.$i)->getFont()->setBold(TRUE);
                     $excel->getActiveSheet()->setCellValue('E'.$i, $info->user); $i++;

                    $excel->getActiveSheet()
                    ->getStyle('A'.$i.':'.'E'.$i)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('6fefff');
                    $excel->getActiveSheet()->setCellValue('A'.$i,'Code')
                                            ->getStyle('A'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->setCellValue('B'.$i,'Description')
                                            ->getStyle('B'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->setCellValue('C'.$i,'QTY')
                                            ->getStyle('C'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->setCellValue('D'.$i,'S Price')
                                            ->getStyle('D'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->setCellValue('E'.$i,'Total')
                                            ->getStyle('E'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->getStyle('C'.$i.':E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $i++;

                     $inv_total = 0;
                     foreach ($items as $item) {
                        if($info->sales_invoice_id == $item->sales_invoice_id) {
                            $inv_total += $item->inv_gross;
                            $excel->getActiveSheet()->setCellValue('A'.$i,$item->product_code); 
                            $excel->getActiveSheet()->setCellValue('B'.$i,$item->product_desc); 
                            $excel->getActiveSheet()->setCellValue('C'.$i,$item->inv_qty); 
                            $excel->getActiveSheet()->setCellValue('D'.$i,$item->inv_price); 
                            $excel->getActiveSheet()->setCellValue('E'.$i,$item->inv_gross); 
                            $excel->getActiveSheet()->getStyle('C'.$i.':E'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)'); $i++;
                        }

                     }
                    $excel->getActiveSheet()->setCellValue('D'.$i,'Invoice Total')
                                            ->getStyle('D'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->setCellValue('E'.$i,$inv_total)
                                            ->getStyle('E'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)');
                    $excel->getActiveSheet()->getStyle('D'.$i.':E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $i++;$i++;$i++;
                }



                $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(TRUE);
                $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(TRUE);
                $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(TRUE);
                $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(TRUE);
                $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(TRUE);

                // $excel->getActiveSheet()->getStyle('B'.$i.':D'.$i)->getFont()->setBold(TRUE);
                // $excel->getActiveSheet()->getStyle('B'.$i.':D'.$i)->getNumberFormat()->setFormatCode('###,##0.00;(###,##0.00)');

                // Redirect output to a client’s web browser (Excel2007)




                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Sales and Cash Invoices '.date('Y-m-d',strtotime($end)).'.xlsx"');
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