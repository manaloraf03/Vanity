<?php
ini_set('max_execution_time', '0');

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_sale_history extends CORE_Controller
{

    function __construct() {
        parent::__construct('');
        $this->validate_session();
        $this->load->library('excel');
        $this->load->model('Sales_invoice_model');
        $this->load->model('Sales_invoice_item_model');
        $this->load->model('Refproduct_model');
        $this->load->model('Sales_order_model');
        $this->load->model('Departments_model');
        $this->load->model('Customers_model');
        $this->load->model('Products_model');
        $this->load->model('Invoice_counter_model');
        $this->load->model('Company_model');
        $this->load->model('Salesperson_model');
        $this->load->model('Users_model');
        $this->load->model('Trans_model');
        $this->load->model('Cash_invoice_model');
        $this->load->model('Customer_type_model');
        $this->load->model('Order_source_model');
        $this->load->model('Products_model');
    }

    public function index() {
        $this->Users_model->validate();
        //default resources of the active view
        $data['_def_css_files'] = $this->load->view('template/assets/css_files', '', TRUE);
        $data['_def_js_files'] = $this->load->view('template/assets/js_files', '', TRUE);
        $data['_switcher_settings'] = $this->load->view('template/elements/switcher', '', TRUE);
        $data['_side_bar_navigation'] = $this->load->view('template/elements/side_bar_navigation', '', TRUE);
        $data['_top_navigation'] = $this->load->view('template/elements/top_navigation', '', TRUE);

        //data required by active view
        $data['customers']=$this->Customers_model->get_list(
            array('customers.is_active'=>TRUE,'customers.is_deleted'=>FALSE),'*',null,'customers.customer_name ASC'
        );
        $data['products']=$this->Products_model->get_list(array('products.is_deleted'=>FALSE),'*',null,'products.product_desc ASC');
        $data['title'] = 'Customer Sales History';
        
        (in_array('3-7',$this->session->user_rights)? 
        $this->load->view('customer_sale_history_view', $data)
        :redirect(base_url('dashboard')));
    }


    function transaction($txn = null,$id_filter=null) {
        switch ($txn){

            case 'list':  //this returns JSON of Issuance to be rendered on Datatable
                $m_invoice=$this->Sales_invoice_model;
                $customer_id = $this->input->get('id');
                $product_id = $this->input->get('pid');
                $response['data']=$m_invoice->get_customer_sale_history($customer_id,$product_id);
                echo json_encode($response);
            break;

            case 'export-selected':
                $excel=$this->excel;
                $excel->setActiveSheetIndex(0);
                $excel->getActiveSheet()->setTitle('Customer Sales History');


                $customer_id = $this->input->get('id');
                $product_id = $this->input->get('pid');
                $m_company=$this->Company_model;
                $company_info=$m_company->get_list();
                $m_customer_info = $this->Customers_model->get_list($customer_id)[0];
                $excel->getActiveSheet()->setCellValue('A1',$company_info[0]->company_name)
                                        ->setCellValue('A2',$company_info[0]->company_address)
                                        ->setCellValue('A3',$company_info[0]->email_address)
                                        ->setCellValue('A4',$company_info[0]->mobile_no)
                                        ->setCellValue('A6','Customer Name: '.trim($m_customer_info->customer_name));

                    $excel->getActiveSheet()->mergeCells('A6:H6');
                    $excel->getActiveSheet()->getStyle('A6:H6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('aaf2fb');   
                                                             
                    $excel->getActiveSheet()->setCellValue('A7', 'Product Code')
                                            ->setCellValue('B7', 'Product Description')
                                            ->setCellValue('C7', 'Price')
                                            ->setCellValue('D7', 'Quantity')
                                            ->setCellValue('E7', 'Gross')
                                            ->setCellValue('F7', 'Invoice #')
                                            ->setCellValue('G7', 'Invoice Date')
                                            ->setCellValue('H7', 'Remarks');
                    $excel->getActiveSheet()->getStyle('A7:H7')->getFont()->setBold(TRUE);

                    $i=8;
                    $total_sales = 0;
                    $invoices = $this->Sales_invoice_model->get_customer_sale_history($customer_id,$product_id);
                        foreach($invoices as $invoice){
                                // $excel->Align_right('E',$i);
                                $excel->getActiveSheet()->setCellValue('A'.$i,$invoice->product_code);
                                $excel->getActiveSheet()->setCellValue('B'.$i,$invoice->product_desc);
                                $excel->getActiveSheet()->setCellValue('C'.$i,$invoice->inv_price);
                                $excel->getActiveSheet()->setCellValue('D'.$i,$invoice->inv_qty);
                                $excel->getActiveSheet()->setCellValue('E'.$i,$invoice->inv_gross);
                                $excel->getActiveSheet()->setCellValue('F'.$i,$invoice->inv_no);
                                $excel->getActiveSheet()->setCellValue('G'.$i,$invoice->date_invoice);
                                $excel->getActiveSheet()->setCellValue('H'.$i,$invoice->remarks);
                                $total_sales+=$invoice->inv_gross; 
                                $i++;
                       }
                    $excel->getActiveSheet()->getStyle('D'.$i.':E'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->setCellValue('D'.$i,'Total:');
                    $excel->getActiveSheet()->setCellValue('E'.$i,$total_sales);


                    $highestRow = $excel->getActiveSheet()->getHighestRow();
                    for($i=8;$i<=$highestRow;$i++){
                        $excel->getActiveSheet()->getStyle('C'.$i.':E'.$i)->getNumberFormat()->setFormatCode('#,##0.00');
                    }



                    //autofit column
                    foreach(range('A','H') as $columnID)
                    {
                        $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(TRUE);
                    }


                    // Redirect output to a client’s web browser (Excel2007)
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="Sales History of '.trim($m_customer_info->customer_name).'.xlsx"');
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

            case 'export-all':
                $excel=$this->excel;
                $excel->setActiveSheetIndex(0);
                $excel->getActiveSheet()->setTitle('Customer Sales History');


                $customer_id = $this->input->get('id');
                $product_id = $this->input->get('pid');
                $m_company=$this->Company_model;
                $company_info=$m_company->get_list();
                $m_customer_info = $this->Sales_invoice_model->get_customers_with_sales();
                
                $excel->getActiveSheet()->setCellValue('A1',$company_info[0]->company_name)
                                        ->setCellValue('A2',$company_info[0]->company_address)
                                        ->setCellValue('A3',$company_info[0]->email_address)
                                        ->setCellValue('A4',$company_info[0]->mobile_no);
                $i=6;
                foreach ($m_customer_info as $customer) {
                $excel->getActiveSheet()->setCellValue('A'.$i,'Customer Name: '.trim($customer->customer_name));
                $excel->getActiveSheet()->mergeCells('A'.$i.':H'.$i);
                $excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('aaf2fb');                 
                $i++;
                                                             
                    $excel->getActiveSheet()->setCellValue('A'.$i, 'Product Code')
                                            ->setCellValue('B'.$i, 'Product Description')
                                            ->setCellValue('C'.$i, 'Price')
                                            ->setCellValue('D'.$i, 'Quantity')
                                            ->setCellValue('E'.$i, 'Gross')
                                            ->setCellValue('F'.$i, 'Invoice #')
                                            ->setCellValue('G'.$i, 'Invoice Date')
                                            ->setCellValue('H'.$i, 'Remarks');
                    $excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setBold(TRUE);

                    $i++;


                    $total_sales = 0;
                    $invoices = $this->Sales_invoice_model->get_customer_sale_history($customer->customer_id,0);
                        foreach($invoices as $invoice){
                            if($invoice->customer_id == $customer->customer_id){
                                $excel->getActiveSheet()->setCellValue('A'.$i,$invoice->product_code);
                                $excel->getActiveSheet()->setCellValue('B'.$i,$invoice->product_desc);
                                $excel->getActiveSheet()->setCellValue('C'.$i,$invoice->inv_price);
                                $excel->getActiveSheet()->setCellValue('D'.$i,$invoice->inv_qty);
                                $excel->getActiveSheet()->setCellValue('E'.$i,$invoice->inv_gross);
                                $excel->getActiveSheet()->setCellValue('F'.$i,$invoice->inv_no);
                                $excel->getActiveSheet()->setCellValue('G'.$i,$invoice->date_invoice);
                                $excel->getActiveSheet()->setCellValue('H'.$i,$invoice->remarks);
                                $total_sales+=$invoice->inv_gross; 
                            }

                                $i++;
                       }
                    $excel->getActiveSheet()->getStyle('D'.$i.':E'.$i)->getFont()->setBold(TRUE);
                    $excel->getActiveSheet()->setCellValue('D'.$i,'Total:');
                    $excel->getActiveSheet()->setCellValue('E'.$i,$total_sales);


                    $i++;
                    $i++;
                }

                    $highestRow = $excel->getActiveSheet()->getHighestRow();
                    for($i=8;$i<=$highestRow;$i++){
                        $excel->getActiveSheet()->getStyle('C'.$i.':E'.$i)->getNumberFormat()->setFormatCode('#,##0.00');
                    }

                    //autofit column
                    foreach(range('A','H') as $columnID)
                    {
                        $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(TRUE);
                    }


                    // Redirect output to a client’s web browser (Excel2007)
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="Sales History.xlsx"');
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
//**************************************user defined*************************************************



}