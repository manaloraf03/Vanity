<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_sale_history extends CORE_Controller
{

    function __construct() {
        parent::__construct('');
        $this->validate_session();

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

    }
}
//**************************************user defined*************************************************



}