<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_source extends CORE_Controller {
    function __construct() {
        parent::__construct('');
        $this->validate_session();
        $this->load->model('Order_source_model');
        $this->load->model('Users_model');
        $this->load->model('Trans_model');
    }

    public function index() {
        $this->Users_model->validate();
        $data['_def_css_files'] = $this->load->view('template/assets/css_files', '', TRUE);
        $data['_def_js_files'] = $this->load->view('template/assets/js_files', '', TRUE);
        $data['_switcher_settings'] = $this->load->view('template/elements/switcher', '', TRUE);
        $data['_side_bar_navigation'] = $this->load->view('template/elements/side_bar_navigation', '', TRUE);
        $data['_top_navigation'] = $this->load->view('template/elements/top_navigation', '', TRUE);
        $data['title'] = 'Order Source Management';
        (in_array('4-8',$this->session->user_rights)? 
        $this->load->view('order_source_view', $data)
        :redirect(base_url('dashboard')));
        
    }

    function transaction($txn = null) {
        switch ($txn) {
            case 'list':
                $m_order_source = $this->Order_source_model;
                $response['data'] = $m_order_source->get_list(array('is_deleted'=>FALSE,'is_active'=>TRUE));
                echo json_encode($response);
                break;

            case 'create':
               $m_order_source = $this->Order_source_model;

                $m_order_source->order_source_name = $this->input->post('order_source_name', TRUE);
                $m_order_source->order_source_description = $this->input->post('order_source_description', TRUE);
                $m_order_source->save();

                $order_source_id = $m_order_source->last_insert_id();

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=1; //CRUD
                $m_trans->trans_type_id=47; // TRANS TYPE
                $m_trans->trans_log='Created Order Source: '.$this->input->post('order_source_name', TRUE);
                $m_trans->save();

                $response['title'] = 'Success!';
                $response['stat'] = 'success';
                $response['msg'] = 'Order Source Information successfully created.';
                $response['row_added'] = $m_order_source->get_list($order_source_id);
                echo json_encode($response);

                break;

            case 'delete':
                $m_order_source=$this->Order_source_model;

                $order_source_id=$this->input->post('order_source_id',TRUE);

                if($order_source_id == '1'){
                    $response['stat']='error';
                    $response['title']='<b>Cannot Delete!</b>';
                    $response['msg']='Cannot delete the default order source!<br />';
                    die(json_encode($response));
                }


                $m_order_source->is_deleted=1;
                if($m_order_source->modify($order_source_id)){
                    $response['title']='Success!';
                    $response['stat']='success';
                    $response['msg']='Order Source Information successfully deleted.';

                    $order_source_name = $m_order_source->get_list($order_source_id,'order_source_name');
                    $m_trans=$this->Trans_model;
                    $m_trans->user_id=$this->session->user_id;
                    $m_trans->set('trans_date','NOW()');
                    $m_trans->trans_key_id=3; //CRUD
                    $m_trans->trans_type_id=67; // TRANS TYPE
                    $m_trans->trans_log='Deleted Order Source: '.$order_source_name[0]->order_source_name;
                    $m_trans->save();

                    echo json_encode($response);
                }

                break;

            case 'update':
                $m_order_source=$this->Order_source_model;

                $order_source_id=$this->input->post('order_source_id',TRUE);
                $m_order_source->order_source_name = $this->input->post('order_source_name', TRUE);
                $m_order_source->order_source_description = $this->input->post('order_source_description', TRUE);
                $m_order_source->modify($order_source_id);

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=2; //CRUD
                $m_trans->trans_type_id=67; // TRANS TYPE
                $m_trans->trans_log='Updated Order Source: '.$this->input->post('order_source_name',TRUE).' ID('.$order_source_id.')';
                $m_trans->save();

                $response['title']='Success!';
                $response['stat']='success';
                $response['msg']='Order Source Information successfully updated.';
                $response['row_updated']=$m_order_source->get_list($order_source_id);
                echo json_encode($response);

                break;
        }
    }
}
