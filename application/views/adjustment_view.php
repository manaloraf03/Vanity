<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from avenxo.kaijuthemes.com/ui-typography.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 06 Jun 2016 12:09:25 GMT -->
<head>
    <meta charset="utf-8">
    <title>JCORE - <?php echo $title; ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="description" content="Avenxo Admin Theme">
    <meta name="author" content="">


    <?php echo $_def_css_files; ?>

    <link rel="stylesheet" href="assets/plugins/spinner/dist/ladda-themeless.min.css">
    <link type="text/css" href="assets/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
    <link type="text/css" href="assets/plugins/datatables/dataTables.themify.css" rel="stylesheet">
    <link href="assets/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="assets/plugins/select2/select2.min.css" rel="stylesheet">
    <!--/twitter typehead-->
    <link href="assets/plugins/twittertypehead/twitter.typehead.css" rel="stylesheet">
    <style>
        #tbl_items td,#tbl_items tr,#tbl_items th{
            table-layout: fixed;
            border: 1px solid gray;
            border-collapse: collapse;
        }
        .toolbar{
            float: left;
        }

        td.details-control {
            background: url('assets/img/Folder_Closed.png') no-repeat center center;
            cursor: pointer;
        }
        tr.details td.details-control {
            background: url('assets/img/Folder_Opened.png') no-repeat center center;
        }

        .child_table{
            padding: 5px;
            border: 1px #ff0000 solid;
        }

        .glyphicon.spinning {
            animation: spin 1s infinite linear;
            -webkit-animation: spin2 1s infinite linear;
        }

        .select2-container{
            min-width: 100%;
        }

        .dropdown-menu > .active > a,.dropdown-menu > .active > a:hover{
            background-color: dodgerblue;
        }

        @keyframes spin {
            from { transform: scale(1) rotate(0deg); }
            to { transform: scale(1) rotate(360deg); }
        }

        @-webkit-keyframes spin2 {
            from { -webkit-transform: rotate(0deg); }
            to { -webkit-transform: rotate(360deg); }
        }

        .custom_frame{

            border: 1px solid lightgray;
            margin: 1% 1% 1% 1%;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }

        .numeric{
            text-align: right;
        }



        @media screen and (max-width: 480px) {
            table {
                min-width: 800px;
            }

            .dataTables_filter{
                min-width: 800px;
            }

            .dataTables_info{
                min-width: 800px;
            }

            .dataTables_paginate{
                float: left;
                width: 100%;
            }
        }




    </style>
</head>

<body class="animated-content"  style="font-family: tahoma;">

<?php echo $_top_navigation; ?>

<div id="wrapper">
<div id="layout-static">


<?php echo $_side_bar_navigation;

?>


<div class="static-content-wrapper white-bg">


<div class="static-content"  >
<div class="page-content"><!-- #page-content -->

<ol class="breadcrumb"  style="margin-bottom: 10px;">
    <li><a href="Dashboard">Dashboard</a> > </li>
    <li><a href="Adjustments">Adjustment</a></li>
</ol>


<div class="container-fluid"">
<div data-widget-group="group1">
<div class="row">
<div class="col-md-12">

<div id="div_user_list">




    <div class="panel panel-default">
<!--         <div class="panel-heading">
            <b style="color: white; font-size: 12pt;"><i class="fa fa-bars"></i>&nbsp; Adjustment</b>
        </div> -->
        <div class="panel-body table-responsive">
        <h2 class="h2-panel-heading">Adjustment</h2><hr>
        <button class="btn btn-primary"  id="btn_new" style="text-transform: none;font-family: Tahoma, Georgia, Serif;" data-toggle="modal" data-target="" data-placement="left" title="Adjust Inventory" ><i class="fa fa-plus"></i> Record item adjustment</button>
            <table id="tbl_issuances" class="table table-striped" cellspacing="0" width="100%">
                <thead class="">
                <tr>
                    <th></th>
                    <th>Adjustment #</th>
                    <th>Transaction Type</th>
                    <th>Invoice No</th>
                    <th>Date Adjusted</th>
                    <th>Department</th>
                    <th style="width: 20%;">Remarks</th>
                    <th>Adjustment</th>
                    <th><center>Action</center></th>
                    <th></th>

                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="panel-footer"></div>
    </div>

</div>


<div id="div_user_fields" style="display: none;">
    <div class="panel panel-default" style="border: 4px solid #2980b9;border-radius: 8px;">
    <!-- <div class="panel-heading">
        <h2>Item Adjustment</h2>
        <div class="panel-ctrls" data-actions-container=""></div>
    </div> -->

    <div class="panel-body">
    <h2 class="h2-panel-heading">ADJ # : <span id="span_invoice_no">ADJ-YYYYMMDD-XXX</span></h2><hr>
        <div class="row" style="padding: 1%;margin-top: 0%;font-family: "Source Sans Pro", "Segoe UI", "Droid Sans", Tahoma, Arial, sans-serif">
            <form id="frm_adjustments" role="form" class="form-horizontal">
                
                <div >

                    <div class="row">
                        <div class="col-sm-3">
                           <b class="required">*</b>  Department : <br />
                            <select name="department" id="cbo_departments" data-error-msg="Department is required." required>
                                <option value="0">[ Create New Department ]</option>
                                <?php foreach($departments as $department){ ?>
                                    <option value="<?php echo $department->department_id; ?>" data-tax-type="<?php echo $department->department_id; ?>"><?php echo $department->department_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            
                            <input type="checkbox" name="accounting[]" value="is_adjustment" id="is_adjustment" class="css-checkbox" style="font-size: 12px!important;"><label class="css-label " for="is_adjustment" style="font-size: 12px!important;">Adjustment</label><br>
                            <input type="checkbox" name="accounting[]" value="is_returns" id="is_returns" class="css-checkbox" style="font-size: 12px!important;"><label class="css-label " for="is_returns" style="font-size: 12px!important;">Sales Return</label><br>
                            <input type="hidden" name="adjustment_is_return" id="adjustment_is_return" class="form-control">
                        </div>
                        <div class="col-sm-3"><div class="checkhidden">
                            <b class="required">*</b>Customer : <br />
                            <select name="customer_id" id="cbo_customers" data-error-msg="Customer is required." >
                                <?php foreach($customers as $customer){ ?>
                                    <option data-address="<?php echo $customer->address; ?>" data-contact="<?php echo $customer->contact_name; ?>" value="<?php echo $customer->customer_id; ?>" data-term-default="<?php echo ($customer->term=="none"?"":$customer->term); ?>" data-customer_type="<?php echo $customer->customer_type_id; ?>" data-name-customer="<?php echo $customer->customer_name; ?>"><?php echo $customer->customer_name; ?></option>
                                <?php } ?>
                            </select></div>
                        </div>


                        <div class="col-sm-3">
                            Reference # : <br />
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-code"></i>
                                </span>

                                <input type="text" name="slip_no" class="form-control" placeholder="ADJ-YYYYMMDD-XXX" readonly>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-3">
                           <b class="required">*</b>  Adjustment type : <br />
                            <select name="adjustment_type" id="cbo_adjustments" data-error-msg="Adjustment Type is required" required>
                                <option value="IN" selected>Adjustment IN</option>
                                <option value="OUT">Adjustment OUT</option>
                            </select>
                        </div>

                        <div class="col-sm-3"><br>
                            <input type="checkbox" name="accounting[]" value="is_spoilage" id="is_spoilage" class="css-checkbox" style="font-size: 12px!important;"><label class="css-label " for="is_spoilage" style="font-size: 12px!important;">Spoilage</label><br>
                            <input type="hidden" name="adjustment_is_spoilage" id="adjustment_is_spoilage" class="form-control">
                        </div>

                        <div class="col-sm-3"><div class="checkhidden">
                            Invoice # :<br />
                            <div class="input-group">
                                <input type="text" name="inv_no" id="inv_no" class="form-control" readonly required data-error-msg="Invoice is Required for the Sales Return.">
                                <span class="input-group-addon">
                                    <a href="#" id="link_browse_inv" style="text-decoration: none;color:black;"><b>...</b></a>
                                </span>.
                            </div>
                            <i style="font-size: 9px;">Note: Process Item/s from 1 Invoice only.</i><br>
                            <i style="font-size: 9px;color: red;" id="note"></i>
                            </div>
                        </div>

                        <div class="col-sm-3 ">
                            Date Adjusted / Date Returned:<br />
                            <div class="input-group">

                                <input type="text" name="date_adjusted" class="date-picker form-control" value="<?php echo date("m/d/Y"); ?>" placeholder="Due Date" data-error-msg="Delivery Date is required!" required>
                         <span class="input-group-addon">
                             <i class="fa fa-calendar"></i>
                        </span>
                            </div>
                        </div>
                    </div>

                </div>




            </form>
        </div>


        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <label class="control-label" style="font-family: Tahoma;"><strong>Enter PLU or Search Item :</strong></label>
                <button id="refreshproducts" class="btn-primary btn pull-right" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;"><span class=""></span>  Refresh</button>
                <div id="custom-templates">
                    <input class="typeahead" id="typeaheadsearch" type="text" placeholder="Enter PLU or Search Item">
                </div><br />

                <form id="frm_items">
                    <div class="table-responsive" style="min-height: 200px;padding: 1px;max-height: 350px;overflow: auto;">
                        <table id="tbl_items" class="table table-striped" cellspacing="0" width="100%" style="font-font:tahoma;">
                            <thead class="">
                            <tr>
                                <th width="10%">Qty</th>
                                <th width="10%">UM</th>
                                <th width="30%">Item</th>
                                <th width="20%" style="text-align: right">Unit Price</th>
                                <th width="12%" style="text-align: right;display: none;">Discount</th>
                                <th style="display: none;">T.D</th> <!-- total discount -->
                                <th style="display:none;">Tax %</th>
                                <th width="20%" style="text-align: right">Total</th>
                                <th style="display: none;">V.I</th> <!-- vat input -->
                                <th style="display: none;">N.V</th> <!-- net of vat -->
                                <td style="display: none;">Item ID</td><!-- product id -->
                                <th colspan="3"><center>Action</center></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>

                            <tfoot>
                            <tr>
                                <td colspan="8" style="height: 50px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: right;"><strong><i class="glyph-icon icon-star"></i> Discount :</strong></td>
                                <td align="right" colspan="1" id="td_discount color="red">0.00</td>
                                <td colspan="3" id="" style="text-align: right;"><strong><i class="glyph-icon icon-star"></i> Total Before Tax :</strong></td>
                                <td align="right" colspan="1" id="td_before_tax" color="red">0.00</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: right;"><strong><i class="glyph-icon icon-star"></i> Tax :</strong></td>
                                <td align="right" colspan="1" id="td_tax" color="red">0.00</td>
                                <td colspan="3" style="text-align: right;"><strong><i class="glyph-icon icon-star"></i> Total After Tax :</strong></td>
                                <td align="right" colspan="1" id="td_after_tax" color="red">0.00</td>
                            </tr>
                            </tfoot>

                        </table>
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  form-group">
                        <strong>Remarks :</strong><br />
                        <textarea name="remarks" id="remarks" class="form-control" placeholder="Remarks"></textarea>
                    </div>
                </div>



                <div class="row" style="display: none;">
                    <div class="col-lg-4 col-lg-offset-8">
                        <div class="table-responsive">
                            <table id="tbl_adjustment_summary" class="table invoice-total" style="font-family: tahoma;">
                                <tbody>
                                    <tr>
                                        <td>Discount :</td>
                                        <td align="right">0.00</td>
                                    </tr>

                                    <tr>
                                        <td>Total before Tax :</td>
                                        <td align="right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Tax :</td>
                                        <td align="right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total After Tax :</strong></td>
                                        <td align="right"><b>0.00</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>








    </div>


    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-12">
                <button id="btn_save" class="btn-primary btn" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;"><span class=""></span>  Save Changes</button>
                <button id="btn_cancel" class="btn-default btn" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;"">Cancel</button>
            </div>
        </div>
    </div>



</div>




</div>




</div>
</div>
</div>
</div> <!-- .container-fluid -->

</div> <!-- #page-content -->
</div>


<div id="modal_confirmation" class="modal fade" tabindex="-1" role="dialog"><!--modal-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"   data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" style="color:white;"><span id="modal_mode"> </span>Confirm Deletion</h4>

            </div>

            <div class="modal-body">
                <p id="modal-body-message">Are you sure ?</p>
            </div>

            <div class="modal-footer">
                <button id="btn_yes" type="button" class="btn btn-danger" data-dismiss="modal" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;">Yes</button>
                <button id="btn_close" type="button" class="btn btn-default" data-dismiss="modal" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;">No</button>
            </div>
        </div>
    </div>
</div>





<div id="modal_new_department" class="modal fade" tabindex="-1" role="dialog"><!--modal-->
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header ">
                <button type="button" class="close"   data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" style="color:white;"><span id="modal_mode"> </span>New Department</h4>

            </div>

            <div class="modal-body">
                <form id="frm_department_new">

                    <div class="form-group">
                        <label><b class="required">*</b>  Department :</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-users"></i>
                            </span>
                            <input type="text" name="department_name" class="form-control" placeholder="Department" data-error-msg="Department name is required." required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description :</label>
                        <textarea name="department_desc" class="form-control"></textarea>
                    </div>

                </form>


            </div>

            <div class="modal-footer">
                <button id="btn_create_department" type="button" class="btn btn-primary"  style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;"><span class=""></span> Create</button>
                <button id="btn_close_close_department" type="button" class="btn btn-default" data-dismiss="modal" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;">Cancel</button>
            </div>
        </div>
    </div>
</div><!---modal-->

<div id="modal_inv_list" class="modal fade" tabindex="-1" role="dialog"><!--modal-->
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header ">
                <button type="button" class="close"   data-dismiss="modal" aria-hidden="true">X</button>
                <h2 class="modal-title" style="color: white;"><span id="modal_mode"> </span>Invoices of <label style="font-weight: normal;" id="modal_customer_name"></label></h2>
            </div>
            <div class="modal-body">
                <table id="tbl_inv_list" class="table table-striped" cellspacing="0" width="100%">
                    <thead class="">
                    <tr>
                        <th></th>
                        <th>Invoice #</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th><center>Action</center></th>
                    </tr>
                    </thead>
                    <tbody>
                        <!-- Sales Order Content -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
            <br>
                <button id="cancel_modal" class="btn btn-default" data-dismiss="modal"  style="text-transform: none;font-family: Tahoma, Georgia, Serif;">Close</button>
            </div>
        </div>
    </div>
<div class="clearfix"></div>
</div><!---modal-->





<footer role="contentinfo">
    <div class="clearfix">
        <ul class="list-unstyled list-inline pull-left">
            <li><h6 style="margin: 0;">&copy; 2017 - JDEV OFFICE SOLUTIONS</h6></li>
        </ul>
        <button class="pull-right btn btn-link btn-xs hidden-print" id="back-to-top"><i class="ti ti-arrow-up"></i></button>
    </div>
</footer>




</div>
</div>


</div>


<?php echo $_switcher_settings; ?>
<?php echo $_def_js_files; ?>

<script src="assets/plugins/spinner/dist/spin.min.js"></script>
<script src="assets/plugins/spinner/dist/ladda.min.js"></script>


<script type="text/javascript" src="assets/plugins/datatables/jquery.dataTables.js"></script>
<script type="text/javascript" src="assets/plugins/datatables/dataTables.bootstrap.js"></script>




<!-- Date range use moment.js same as full calendar plugin -->
<script src="assets/plugins/fullcalendar/moment.min.js"></script>
<!-- Data picker -->
<script src="assets/plugins/datapicker/bootstrap-datepicker.js"></script>

<!-- Select2 -->
<script src="assets/plugins/select2/select2.full.min.js"></script>



<!-- twitter typehead -->
<script src="assets/plugins/twittertypehead/handlebars.js"></script>
<script src="assets/plugins/twittertypehead/bloodhound.min.js"></script>
<script src="assets/plugins/twittertypehead/typeahead.bundle.min.js"></script>
<script src="assets/plugins/twittertypehead/typeahead.jquery.min.js"></script>

<!-- touchspin -->
<script type="text/javascript" src="assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js"></script>

<!-- numeric formatter -->
<script src="assets/plugins/formatter/autoNumeric.js" type="text/javascript"></script>
<script src="assets/plugins/formatter/accounting.js" type="text/javascript"></script>

<script>




$(document).ready(function(){
    var dt; var _txnMode; var _selectedID; var _selectRowObj; var _cboDepartments; var _cboAdjustments; var products; var _cboCustomers; var dtCustomer;
    var _line_unit; var changetxn;
    var oTableItems={
        qty : 'td:eq(0)',
        unit_value: 'td:eq(1)',
        unit_identifier : 'td:eq(2)',
        unit_price : 'td:eq(3)',
        discount : 'td:eq(4)',
        total_line_discount : 'td:eq(5)',
        tax : 'td:eq(6)',
        total : 'td:eq(7)',
        vat_input : 'td:eq(8)',
        net_vat : 'td:eq(9)',
        item_id : 'td:eq(10)',
        bulk_price : 'td:eq(12)',
        retail_price : 'td:eq(13)'
    };


    var oTableDetails={
        discount : 'tr:eq(0) > td:eq(1)',
        before_tax : 'tr:eq(1) > td:eq(1)',
        adjust_tax_amount : 'tr:eq(2) > td:eq(1)',
        after_tax : 'tr:eq(3) > td:eq(1)'
    };

    /*var oTableItems={
        qty : 'td:eq(0)',
        unit_price : 'td:eq(4)',
        discount : 'td:eq(5)',
        total_line_discount : 'td:eq(6)',
        tax : 'td:eq(7)',
        total : 'td:eq(8)', //Total
        vat_input : 'td:eq(9)',
        net_vat : 'td:eq(10)' //Before Tax

    };


    var oTableDetails={
        discount : 'tr:eq(0) > td:eq(1)',
        before_tax : 'tr:eq(1) > td:eq(1)',
        adjust_tax_amount : 'tr:eq(2) > td:eq(1)',
        after_tax : 'tr:eq(3) > td:eq(1)'
    };*/


    var initializeControls=function(){
        dtCustomer=$('#tbl_inv_list').DataTable({
            "dom": '<"toolbar">frtip',
            "bLengthChange":false,
                "order": [[ 1, "desc" ]],
            "ajax" : {
                "url" : "Adjustments/transaction/list-per-customer",
                "bDestroy": true,            
                "data": function ( d ) {
                        return $.extend( {}, d, {
                            "cus":$('#cbo_customers').val()
                        });
                    }
            }, 
            "columns": [
                { visible:false,targets:[0],data: "product_id" },
                { targets:[1],data: "inv_no" },
                { targets:[2],data: "product_desc" },
                { targets:[3],data: "inv_qty" },
                { targets:[4],data: "unit_name" },
                {  targets:[6],
                    render: function (data, type, full, meta){
                        var btn_accept='<button class="btn btn-success btn-sm" name="accept_item"  style="margin-left:-15px;text-transform: none;" data-toggle="tooltip" data-placement="top" title="Accept"><i class="fa fa-check"></i> </button>';
                        return '<center>'+btn_accept+'</center>';
                    }
                }
            ]
        });
        dt=$('#tbl_issuances').DataTable(
{            "dom": '<"toolbar">frtip',
            "bLengthChange":false,
            "order": [[ 9, "desc" ]],
            "ajax" : "Adjustments/transaction/list",
            "columns": [
                {
                    "targets": [0],
                    "class":          "details-control",
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": ""
                },
                { targets:[1],data: "adjustment_code" },
                { targets:[2],data: "transaction_type" },
                { targets:[3],data: "inv_no" },
                { targets:[4],data: "date_adjusted" },
                { targets:[5],data: "department_name" },
                { targets:[6],data: "remarks" },
                { targets:[7],data: "adjustment_type" },
                {
                    targets:[8],
                    render: function (data, type, full, meta){
                        var btn_edit='<button class="btn btn-primary btn-sm <?php echo (in_array("20-1",$this->session->user_rights)?"":"hidden")?>" name="edit_info"  style="margin-left:-15px;" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i> </button>';
                        var btn_trash='<button class="btn btn-red btn-sm" name="remove_info" style="margin-right:0px;" data-toggle="tooltip" data-placement="top" title="Move to trash"><i class="fa fa-trash-o"></i> </button>';

                        return '<center>'+btn_edit+'&nbsp;'+btn_trash+'</center>';
                    }
                },
                { targets:[9],data: "adjustment_id",visible:false }
            ]

        });



        $('.date-picker').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true

        });




        _cboDepartments=$("#cbo_departments").select2({
            placeholder: "Issue item to Department.",
            allowClear: true
        });


        _cboAdjustments=$("#cbo_adjustments").select2({
            placeholder: "Please select type of adjustment."
        });

        _cboAdjustments.select2('val',null);

        _cboDepartments.select2('val',null);


        $('#custom-templates .typeahead').keypress(function(event){
            if (event.keyCode == 13) {
                $('.tt-suggestion:first').click();
            }
        });

        _cboCustomers=$("#cbo_customers").select2({
            placeholder: "Please select customer.",
        });
        _cboCustomers.select2('val',null);

        products = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('product_code','product_desc','product_desc1'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local : products
        });

        var _objTypeHead=$('#custom-templates .typeahead');

        _objTypeHead.typeahead(null, {
            name: 'products',
            display: 'description',
            source: products,
            templates: {
                header: [
                    '<table class="tt-head"><tr><td width=20%" style="padding-left: 1%;"><b>PLU</b></td><td width="30%" align="left"><b>Description 1</b></td><td width="20%" align="left"><b>Description 2</b></td><td width="10%" align="right" style="padding-right: 2%;"><b>On Hand</b></td><td width="10%" align="right" style="padding-right: 2%;"><b>Cost</b></td></tr></table>'
                ].join('\n'),

                suggestion: Handlebars.compile('<table class="tt-items"><tr><td width="20%" style="padding-left: 1%">{{product_code}}</td><td width="30%" align="left">{{product_desc}}</td><td width="20%" align="left">{{produdct_desc1}}</td><td width="10%" align="right" style="padding-right: 2%;">{{CurrentQty}}</td><td width="10%" align="right" style="padding-right: 2%;">{{purchase_cost}}</td></tr></table>')

            }
        }).on('keyup', this, function (event) {
            if (event.keyCode == 13) {
                // $('.tt-suggestion:first').click();
                _objTypeHead.typeahead('close');
                _objTypeHead.typeahead('val','');
            }
        }).bind('typeahead:select', function(ev, suggestion) {

            if(!(checkProduct(suggestion.product_id))){ // Checks if item is already existing in the Table of Items for invoice
                showNotification({title: suggestion.product_desc,stat:"error",msg: "Item is Already Added."});
                return;
            }

            if(getFloat(suggestion.CurrentQty) <= 0 && _cboAdjustments.val() == "OUT"){
                showNotification({title: suggestion.product_desc ,stat:"info",msg: "This item is currently out of stock.<br>Continuing will result to negative inventory."});
            }else if(getFloat(suggestion.CurrentQty) <= getFloat(suggestion.product_warn) && _cboAdjustments.val() == "OUT"){
                showNotification({title: suggestion.product_desc ,stat:"info",msg:"This item has low stock remaining.<br>It might result to negative inventory."});
            }

            var tax_rate=suggestion.tax_rate;

            var total=getFloat(suggestion.purchase_cost);
            var net_vat=0;
            var vat_input=0;
                var bulk_price = 0;
                var retail_price = 0;
                var a = ''; 
            if(suggestion.is_tax_exempt=="0"){ //not tax excempt
                net_vat=total/(1+(getFloat(tax_rate)/100));
                vat_input=total-net_vat;
            }else{
                tax_rate=0;
                net_vat=total;
                vat_input=0;
            }
                bulk_price = suggestion.purchase_cost;

                if(suggestion.is_bulk == 1){
                    retail_price = getFloat(suggestion.purchase_cost) / getFloat(suggestion.child_unit_desc);

                }else if (suggestion.is_bulk== 0){
                    retail_price = 0;
                }
                if(suggestion.primary_unit == 1){ suggis_parent = 1;}else{ suggis_parent = 0;}
            $('#tbl_items > tbody').prepend(newRowItem({
                adjust_qty : "1",
                product_code : suggestion.product_code,

                product_id: suggestion.product_id,
                product_desc : suggestion.product_desc,
                adjust_line_total_discount : "0.00",
                tax_exempt : false,
                adjust_tax_rate : tax_rate,
                adjust_price : suggestion.purchase_cost,
                adjust_discount : "0.00",
                tax_type_id : null,
                adjust_line_total_price : total,
                adjust_non_tax_amount: net_vat,
                adjust_tax_amount:vat_input,
                bulk_price: bulk_price,
                retail_price: retail_price,
                is_bulk: suggestion.is_bulk,
                parent_unit_id : suggestion.parent_unit_id,
                child_unit_id : suggestion.child_unit_id,
                child_unit_name : suggestion.child_unit_name,
                parent_unit_name : suggestion.parent_unit_name,
                    is_parent: suggis_parent ,// INITIALLY , UNIT USED IS THE PARENT , 1 for PARENT 0 for CHILD
                    primary_unit:suggestion.primary_unit,
                a:a
            }));

                changetxn ='active';
                _line_unit=$('.line_unit'+a).select2({
                minimumResultsForSearch: -1
                });

            reInitializeNumeric();
            reComputeTotal();

            //alert("dd")
        });

        $('div.tt-menu').on('click','table.tt-suggestion',function(){
            _objTypeHead.typeahead('val','');
        });

        $("input#touchspin4").TouchSpin({
            verticalbuttons: true,
            verticalupclass: 'fa fa-fw fa-plus',
            verticaldownclass: 'fa fa-fw fa-minus'
        });

        // InitializeCustomerDT();
    }();

    function InitializeCustomerDT() {

    };


    $('#link_browse_inv').click(function(){
         iCus= _cboCustomers.select2('val');
        if(iCus == 0 || iCus == null){
            showNotification({title: "Error !",stat:"error",msg: "Please Select a Customer before proceeding."});
        }else{
            $('#tbl_inv_list tbody').html('<tr><td colspan="7"><center><br /><img src="assets/img/loader/ajax-loader-lg.gif" /><br /><br /></center></td></tr>');
            $('#tbl_inv_list').DataTable().ajax.reload();
            var obj_cusmodal=$('#cbo_customers').find('option[value="' + iCus + '"]');
            $('#modal_customer_name').text(obj_cusmodal.data('name-customer'));
            $('#modal_inv_list').modal('show');
        }
    });


    var bindEventHandlers=(function(){
            $('[id=is_returns]').click(function(event) {
                if(this.checked == true) {
                    $('input[id="is_adjustment"]').prop('checked', false);
                    $("#cbo_customers").prop('required',true);
                    $("#inv_no").prop('required',true);
                    $('.checkhidden').show();
                    $('#adjustment_is_return').val('1');
                    

                }else{
                     $('[id=is_adjustment]').trigger('click');
                }
            });

            $('[id=is_adjustment]').click(function(event) {
                if(this.checked == true) {
                    $('input[id="is_returns"]').prop('checked', false);
                    $("#cbo_customers").prop('required',false);
                    $("#inv_no").prop('required',false);
                    _cboCustomers.select2('val',null);
                    $('.checkhidden').hide();
                    $('#adjustment_is_return').val('0');
                    $("input[name=inv_no]").val('');
                }else{
                    $('[id=is_returns]').trigger('click');
                }
            });
            $('[id=is_spoilage]').click(function(event) {
                if(this.checked == true) {
                    $('#adjustment_is_spoilage').val('1');
                }else{
                    $('#adjustment_is_spoilage').val('0');
                }
            });


        var detailRows = [];

        $('#tbl_issuances tbody').on( 'click', 'tr td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = dt.row( tr );
            var idx = $.inArray( tr.attr('id'), detailRows );

            if ( row.child.isShown() ) {
                tr.removeClass( 'details' );
                row.child.hide();

                // Remove from the 'open' array
                detailRows.splice( idx, 1 );
            }
            else {
                tr.addClass( 'details' );
                //console.log(row.data());
                var d=row.data();

                $.ajax({
                    "dataType":"html",
                    "type":"POST",
                    "url":"Templates/layout/adjustments/"+ d.adjustment_id,
                    "beforeSend" : function(){
                        row.child( '<center><br /><img src="assets/img/loader/ajax-loader-lg.gif" /><br /><br /></center>' ).show();
                    }
                }).done(function(response){
                    row.child( response,'no-padding' ).show();
                    // Add to the 'open' array
                    if ( idx === -1 ) {
                        detailRows.push( tr.attr('id') );
                    }
                });
            }
        });

        //loads modal to create new department
        _cboDepartments.on("select2:select", function (e) {

            var i=$(this).select2('val');

            if(i==0){ //new departmet
                _cboDepartments.select2('val',null)
                $('#modal_new_department').modal('show');
                clearFields($('#modal_new_department').find('form'));
            }

        });

         $('#refreshproducts').click(function(){
            getproduct().done(function(data){
                products.clear();
                products.local = data.data;
                products.initialize(true);
                    showNotification({title:"Success !",stat:"success",msg:"Products List successfully updated."});
                    $('#typeaheadsearch').val('');
            }).always(function(){
                });
         });

        //create new department
        $('#btn_create_department').click(function(){
            var btn=$(this);

            if(validateRequiredFields($('#frm_department_new'))){
                var data=$('#frm_department_new').serializeArray();

                $.ajax({
                    "dataType":"json",
                    "type":"POST",
                    "url":"Departments/transaction/create",
                    "data":data,
                    "beforeSend" : function(){
                        showSpinningProgress(btn);
                    }
                }).done(function(response){
                    showNotification(response);
                    $('#modal_new_department').modal('hide');

                    var _department=response.row_added[0];
                    $('#cbo_departments').append('<option value="'+_department.department_id+'" selected>'+_department.department_name+'</option>');
                    $('#cbo_departments').select2('val',_department.department_id);

                }).always(function(){
                    showSpinningProgress(btn);
                });
            }


        });





        $('#tbl_issuances tbody').on('click','#btn_email',function(){
            _selectRowObj=$(this).parents('tr').prev();
            var d=dt.row(_selectRowObj).data();
            var btn=$(this);

            $.ajax({
                "dataType":"json",
                "type":"POST",
                "url":"Email/send/po/"+ d.adjustment_id,
                "data": {email:$(this).data('supplier-email')},
                "beforeSend" : function(){
                    showSpinningProgress(btn);
                }
            }).done(function(response){
                showNotification(response);
                dt.row(_selectRowObj).data(response.row_updated[0]).draw();
            }).always(function(){
                showSpinningProgress(btn);
            });
        });

        $('#btn_new').click(function(){
            _txnMode="new";
            //$('.toggle-fullscreen').click();
            _cboAdjustments.select2('val',null);
            _cboDepartments.select2('val',null);
            clearFields($('#frm_adjustments'));
            $('#tbl_items > tbody').html('');
            $('#cbo_departments').select2('val', null);
            $('#typeaheadsearch').val('');
            // REMOVE CHECKED ATTRIBUTE FOR BOTH
            $('input[id="is_returns"]').prop('checked', false);
            $('input[id="is_adjustment"]').prop('checked', false);
            // THEN ADD TO ADJUSTMENT CHECKBOX
            $('input[id="is_adjustment"]').prop('checked', true);
            $('.checkhidden').hide();
            $('#adjustment_is_return').val('0');
            $('#adjustment_is_spoilage').val('0');
            $("#cbo_customers").prop('required',false);
            $("#inv_no").prop('required',false);
            $('#note').text('');
            $('.date-picker').datepicker('setDate', 'today');

            getproduct().done(function(data){
                products.clear();
                products.local = data.data;
                products.initialize(true);
                countproducts = data.data.length;
                    if(countproducts > 100){
                    showNotification({title:"Success !",stat:"success",msg:"Products List successfully updated."});
                    }

            }).always(function(){  });

            showList(false);
            reComputeTotal();
        });

        $('#btn_browse').click(function(event){
            event.preventDefault();
            $('input[name="file_upload[]"]').click();
        });


        $('#btn_remove_photo').click(function(event){
            event.preventDefault();
            $('img[name="img_user"]').attr('src','assets/img/anonymous-icon.png');
        });

        $('#tbl_inv_list tbody').on('click','button[name="accept_item"]',function(){
        _selectRowObjCus=$(this).closest('tr');
        var value=dtCustomer.row(_selectRowObjCus).data();
        $('#note').text(value.note);
        $("input[name=inv_no]").val(value.inv_no);
            a='';
                var retail_price;
                        if(value.is_bulk == 1){
                            retail_price = getFloat(value.sale_price) / getFloat(value.child_unit_desc);
                        }else if (value.is_bulk == 0){
                            retail_price = 0;
                        }
                    
                        $('#tbl_items > tbody').prepend(newRowItem({
                            adjust_qty : value.inv_qty,
                            product_code : value.product_code,
                            product_id: value.product_id,
                            product_desc : value.product_desc,
                            adjust_line_total_discount : value.inv_line_total_discount,
                            tax_exempt : false,
                            adjust_tax_rate : value.inv_tax_rate,
                            adjust_price : value.inv_price,
                            adjust_discount : value.inv_discount,
                            tax_type_id : null,
                            adjust_line_total_price : value.inv_line_total_price,
                            adjust_non_tax_amount: value.inv_non_tax_amount,
                            adjust_tax_amount:value.inv_tax_amount,
                            child_unit_id : value.child_unit_id,
                            child_unit_name : value.child_unit_name,
                            parent_unit_name : value.parent_unit_name,
                            parent_unit_id : getFloat(value.parent_unit_id),
                            is_bulk: value.is_bulk,
                            is_parent : value.is_parent,
                            bulk_price: value.sale_price,
                            retail_price: retail_price,
                            a:a
                        }));
                        changetxn = 'inactive';
                        _line_unit=$('.line_unit'+a).select2({
                            minimumResultsForSearch: -1
                        });
                        _line_unit.select2('val',value.unit_id);
                        a++;
                    reComputeTotal();
                    changetxn = 'active';

                    showNotification({title:"Successful !",stat:"success",msg: value.product_desc+' has been chosen'});
                    $(this).hide();
        });





        $('#tbl_issuances tbody').on('click','button[name="edit_info"]',function(){
            ///alert("ddd");
            _txnMode="edit";
            _selectRowObj=$(this).closest('tr');
            var data=dt.row(_selectRowObj).data();
            _selectedID=data.adjustment_id;

            _is_journal_posted=data.is_journal_posted;
            if(_is_journal_posted > 0){
                showNotification({title:"<b style='color:white;'> Error!</b>",stat:"error",msg:"Cannot Edit: Invoice is already Posted in General Journal."});
            }else{



            $('#span_invoice_no').html(data.adjustment_code);

            getproduct().done(function(data){
                products.clear();
                products.local = data.data;
                products.initialize(true);
                countproducts = data.data.length;
                    if(countproducts > 100){
                    showNotification({title:"Success !",stat:"success",msg:"Products List successfully updated."});
                    }

            }).always(function(){ });
            $('#typeaheadsearch').val('');

            $('input,textarea').each(function(){
                var _elem=$(this);
                $.each(data,function(name,value){
                    if(_elem.attr('name')==name&&_elem.attr('type')!='password'){
                        _elem.val(value);
                    }

                });
            });


            $('#note').text('');
            _cboAdjustments.select2('val',data.adjustment_type);
            $('#cbo_departments').select2('val',data.department_id);
            $('#cbo_customers').select2('val',data.customer_id);


            $("#is_returns").prop('checked', false); 
            $("#is_adjustment").prop('checked', false); 

            if(data.adjustment_is_return == '1'){ // is return
                $('input[id="is_returns"]').trigger('click');
            }else if(data.adjustment_is_return == '0'){// is adjustment
                $('input[id="is_adjustment"]').trigger('click');                
            } 

            if(data.adjustment_is_spoilage == '1'){
                $("#is_spoilage").prop('checked', true); 
            }else if(data.adjustment_is_spoilage == '0'){
                $("#is_spoilage").prop('checked', false);             
            } 
            $.ajax({
                url : 'Adjustments/transaction/items/'+data.adjustment_id,
                type : "GET",
                cache : false,
                dataType : 'json',
                processData : false,
                contentType : false,
                beforeSend : function(){
                    $('#tbl_items > tbody').html('<tr><td align="center" colspan="8"><br /><img src="assets/img/loader/ajax-loader-sm.gif" /><br /><br /></td></tr>');
                },
                success : function(response){
                    var rows=response.data;
                    $('#tbl_items > tbody').html('');
                     a=0;
                    $.each(rows,function(i,value){
                        var retail_price;
                        if(value.is_bulk == 1){
                            retail_price = getFloat(value.purchase_cost) / getFloat(value.child_unit_desc);
                        }else if (value.is_bulk == 0){
                            retail_price = 0;
                        }
 
                        $('#tbl_items > tbody').prepend(newRowItem({
                            adjust_qty : value.adjust_qty,
                            product_code : value.product_code,
                            product_id: value.product_id,
                            product_desc : value.product_desc,
                            adjust_line_total_discount : value.adjust_line_total_discount,
                            tax_exempt : false,
                            adjust_tax_rate : value.adjust_tax_rate,
                            adjust_price : value.adjust_price,
                            adjust_discount : value.adjust_discount,
                            tax_type_id : null,
                            adjust_line_total_price : value.adjust_line_total_price,
                            adjust_non_tax_amount: value.adjust_non_tax_amount,
                            adjust_tax_amount:value.adjust_tax_amount,
                            child_unit_id : value.child_unit_id,
                            child_unit_name : value.child_unit_name,
                            parent_unit_name : value.parent_unit_name,
                            parent_unit_id : getFloat(value.parent_unit_id),
                            is_bulk: value.is_bulk,
                            is_parent : value.is_parent,
                            bulk_price: value.purchase_cost,
                            retail_price: retail_price,
                            a:a
                        }));
                        changetxn = 'inactive';
                        _line_unit=$('.line_unit'+a).select2({
                            minimumResultsForSearch: -1
                        });
                        _line_unit.select2('val',value.unit_id);
                        a++;
                    });

                    reComputeTotal();
                    changetxn = 'active';
                }
            });




            showList(false);

            } // end of else if for validation if adjustment is posted in Accounting
        });

        $('#tbl_issuances tbody').on('click','button[name="remove_info"]',function(){
            _selectRowObj=$(this).closest('tr');
            var data=dt.row(_selectRowObj).data();
            _selectedID=data.adjustment_id;


            _is_journal_posted=data.is_journal_posted;
            if(_is_journal_posted > 0){
                showNotification({title:"<b style='color:white;'> Error!</b>",stat:"error",msg:"Cannot Delete: Invoice is already Posted in General Journal."});
            }else{

            $('#modal_confirmation').modal('show');
            }
        });
        $('#tbl_items tbody').on('change','select',function(){
        if(changetxn == 'active'){
            var row=$(this).closest('tr');
            var unit_value=row.find(oTableItems.unit_value).find('option:selected').attr("data-unit-identifier"); 
            if(getFloat(unit_value) == 1 ){
                var price=parseFloat(accounting.unformat(row.find(oTableItems.bulk_price).find('input.numeric').val()));
            }else{
                var price=parseFloat(accounting.unformat(row.find(oTableItems.retail_price).find('input.numeric').val()));
            }
            $(oTableItems.unit_price,row).find('input').val(accounting.formatNumber(price,2));   
            $(oTableItems.unit_identifier,row).find('input').val(unit_value); 
            $('.trigger-change').keyup();
        }

        
        });



        //track every changes on numeric fields
        $('#tbl_items tbody').on('input keyup','input.numeric',function(){
            var row=$(this).closest('tr');

            var price=parseFloat(accounting.unformat(row.find(oTableItems.unit_price).find('input.numeric').val()));
            var discount=parseFloat(accounting.unformat(row.find(oTableItems.discount).find('input.numeric').val()));
            var qty=parseFloat(accounting.unformat(row.find(oTableItems.qty).find('input.numeric').val()));
            var tax_rate=parseFloat(accounting.unformat(row.find(oTableItems.tax).find('input.numeric').val()))/100;

            if(discount>price){
                showNotification({title:"Invalid",stat:"error",msg:"Discount must not greater than unit price."});
                row.find(oTableItems.discount).find('input.numeric').val('0.00');
                //$(this).trigger('keyup');
                //return;
            }

            var discounted_price=price-discount;
            var line_total_discount=discount*qty;
            var line_total=discounted_price*qty;
            var net_vat=line_total/(1+tax_rate);
            var vat_input=line_total-net_vat;

            $(oTableItems.total,row).find('input.numeric').val(accounting.formatNumber(line_total,2)); // line total amount
            $(oTableItems.total_line_discount,row).find('input.numeric').val(line_total_discount); //line total discount
            $(oTableItems.net_vat,row).find('input.numeric').val(net_vat); //net of vat
            $(oTableItems.vat_input,row).find('input.numeric').val(vat_input); //vat input

            //console.log(net_vat);
            reComputeTotal();


        });







        $('#btn_yes').click(function(){
            //var d=dt.row(_selectRowObj).data();
            //if(getFloat(d.order_status_id)>1){
            //showNotification({title:"Error!",stat:"error",msg:"Sorry, you cannot delete purchase order that is already been recorded on purchase invoice."});
            //}else{
            removeAdjustmentRecord().done(function(response){
                showNotification(response);
                if(response.stat=="success"){
                    dt.row(_selectRowObj).remove().draw();
                }

            });
            //}
        });





        $('input[name="file_upload[]"]').change(function(event){
            var _files=event.target.files;

            $('#div_img_user').hide();
            $('#div_img_loader').show();


            var data=new FormData();
            $.each(_files,function(key,value){
                data.append(key,value);
            });

            //console.log(_files);

            $.ajax({
                url : 'Users/transaction/upload',
                type : "POST",
                data : data,
                cache : false,
                dataType : 'json',
                processData : false,
                contentType : false,
                success : function(response){
                    //console.log(response);
                    //alert(response.path);
                    $('#div_img_loader').hide();
                    $('#div_img_user').show();
                    $('img[name="img_user"]').attr('src',response.path);

                }
            });

        });

        $('#btn_cancel').click(function(){
            showList(true);
        });



        $('#btn_save').click(function(){

            if(validateRequiredFields($('#frm_adjustments'))){
                if(_txnMode=="new"){
                    createAdjustment().done(function(response){
                        showNotification(response);
                        dt.row.add(response.row_added[0]).draw();
                        clearFields($('#frm_adjustments'));
                        showList(true);
                    }).always(function(){
                        showSpinningProgress($('#btn_save'));
                    });
                }else{
                    updateIssuances().done(function(response){
                        showNotification(response);
                        dt.row(_selectRowObj).data(response.row_updated[0]).draw();
                        clearFields($('#frm_adjustments'));
                        showList(true);
                    }).always(function(){
                        showSpinningProgress($('#btn_save'));
                    });
                }

            }

        });



        $('#tbl_items > tbody').on('click','button[name="remove_item"]',function(){
            $(this).closest('tr').remove();
            reComputeTotal();
        });


    })();


    var validateRequiredFields=function(f){
        var stat=true;

        $('div.form-group').removeClass('has-error');
        $('input[required],textarea[required],select[required]',f).each(function(){

            if($(this).is('select')){
                if($(this).select2('val')==0||$(this).select2('val')==null){
                    showNotification({title:"Error!",stat:"error",msg:$(this).data('error-msg')});
                    $(this).closest('div.form-group').addClass('has-error');
                    $(this).focus();
                    stat=false;
                    return false;
                }
            }else{
                if($(this).val()==""){
                    showNotification({title:"Error!",stat:"error",msg:$(this).data('error-msg')});
                    $(this).closest('div.form-group').addClass('has-error');
                    $(this).focus();
                    stat=false;
                    return false;
                }
            }



        });

        return stat;
    };

    var getproduct=function(){
       return $.ajax({
           "dataType":"json",
           "type":"POST",
           "url":"products/transaction/list",
           "beforeSend": function(){
                countproducts = products.local.length;
                if(countproducts > 100){
                    showNotification({title:"Please Wait !",stat:"info",msg:"Refreshing your Products List."});
                }
           }
      });
    };

    var createAdjustment=function(){
        var _data=$('#frm_adjustments,#frm_items').serializeArray();

        var tbl_summary=$('#tbl_adjustment_summary');

        _data.push({name : "remarks", value : $('textarea[name="remarks"]').val()});
        _data.push({name : "summary_discount", value : tbl_summary.find(oTableDetails.discount).text()});
        _data.push({name : "summary_before_discount", value :tbl_summary.find(oTableDetails.before_tax).text()});
        _data.push({name : "summary_tax_amount", value : tbl_summary.find(oTableDetails.adjust_tax_amount).text()});
        _data.push({name : "summary_after_tax", value : tbl_summary.find(oTableDetails.after_tax).text()});

        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Adjustments/transaction/create",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save'))
        });
    };

    var updateIssuances=function(){
        var _data=$('#frm_adjustments,#frm_items').serializeArray();

        var tbl_summary=$('#tbl_adjustment_summary');

        _data.push({name : "remarks", value : $('textarea[name="remarks"]').val()});
        _data.push({name : "summary_discount", value : tbl_summary.find(oTableDetails.discount).text()});
        _data.push({name : "summary_before_discount", value :tbl_summary.find(oTableDetails.before_tax).text()});
        _data.push({name : "summary_tax_amount", value : tbl_summary.find(oTableDetails.adjust_tax_amount).text()});
        _data.push({name : "summary_after_tax", value : tbl_summary.find(oTableDetails.after_tax).text()});
        _data.push({name : "adjustment_id" ,value : _selectedID});

        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Adjustments/transaction/update",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save'))
        });
    };

    var removeAdjustmentRecord=function(){
        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Adjustments/transaction/delete",
            "data":{adjustment_id : _selectedID}
        });
    };

    var showList=function(b){
        if(b){
            $('#div_user_list').show();
            $('#div_user_fields').hide();
        }else{
            $('#div_user_list').hide();
            $('#div_user_fields').show();
        }
    };

    var showNotification=function(obj){
        PNotify.removeAll(); //remove all notifications
        new PNotify({
            title:  obj.title,
            text:  obj.msg,
            type:  obj.stat
        });
    };



    var showSpinningProgress=function(e){
        $(e).find('span').toggleClass('glyphicon glyphicon-refresh spinning');
        $(e).toggleClass('disabled');
    };

    var clearFields=function(f){
        $('input:not(.date-picker),textarea,select',f).val('');
        $('#remarks').val('');
        $(f).find('input:first').focus();
    };


    function format ( d ) {

        //return


    };


    var getFloat=function(f){
        return parseFloat(accounting.unformat(f));
    };

    var newRowItem=function(d){
        if(d.primary_unit == 1){ parent = ' selected'; child = ' '; }else{ parent = ' '; child = ' selected'; } // This does not cause conflict with value of select when editing 
        if(d.is_bulk == '1'){ 
            unit = '<td ><select class="line_unit'+d.a+'" name="unit_id[]"><option value="'+d.parent_unit_id+'" data-unit-identifier="1" '+parent+'>'+d.parent_unit_name+'</option><option value="'+d.child_unit_id+'" data-unit-identifier="0" '+child+'>'+d.child_unit_name+'</option></select></td>';
        }else{ 
            unit  = '<td ><select class="line_unit'+d.a+'" name="unit_id[]" ><option value="'+d.parent_unit_id+'" data-unit-identifier="1" '+parent+'>'+d.parent_unit_name+'</option></select></td>';
        }
        return '<tr>'+
        '<td width="10%"><input name="adjust_qty[]" type="text" class="numeric form-control trigger-change" value="'+ d.adjust_qty+'"></td>'+unit+
        '<td width="30%">'+d.product_desc+'<input type="text" style="display:none;" class="form-control" name="is_parent[]" value="'+d.is_parent+'"></td>'+
        '<td width="11%"><input name="adjust_price[]" type="text" class="numeric form-control" value="'+accounting.formatNumber(d.adjust_price,2)+'" style="text-align:right;"></td>'+
        '<td width="11%" style="display:none;"><input name="adjust_discount[]" type="text" class="numeric form-control" value="'+ accounting.formatNumber(d.adjust_discount,2)+'" style="text-align:right;"></td>'+
        '<td style="display: none;" width="11%"><input name="adjust_line_total_discount[]" type="text" class="numeric form-control" value="'+ accounting.formatNumber(d.adjust_line_total_discount,2)+'" readonly></td>'+
        '<td width="11%" style="display:none;"><input name="adjust_tax_rate[]" type="text" class="numeric form-control" value="'+ accounting.formatNumber(d.adjust_tax_rate,2)+'"></td>'+
        '<td width="11%" align="right"><input name="adjust_line_total_price[]" type="text" class="numeric form-control" value="'+ accounting.formatNumber(d.adjust_line_total_price,2)+'" readonly></td>'+
        '<td style="display: none;"><input name="adjust_tax_amount[]" type="text" class="numeric form-control" value="'+ d.adjust_tax_amount+'" readonly></td>'+
        '<td style="display: none;"><input name="adjust_non_tax_amount[]" type="text" class="numeric form-control" value="'+ d.adjust_non_tax_amount+'" readonly></td>'+
        '<td style="display: none;"><input name="product_id[]" type="text" class="numeric form-control" value="'+ d.product_id+'" readonly></td>'+
        '<td align="center" colspan="3"><button type="button" name="remove_item" class="btn btn-red"><i class="fa fa-trash"></i></button></td>'+
        '<td style="display: none;"  width="5%"><input type="text" class="numeric form-control" value="'+ d.bulk_price+'" readonly></td>'+
        '<td style="display: none;"  width="5%"><input type="text" class="numeric form-control" value="'+ d.retail_price+'" readonly></td>'+
        '</tr>';
    };



    var reComputeTotal=function(){
        var rows=$('#tbl_items > tbody tr');


        var discounts=0; var before_tax=0; var after_tax=0; var adjust_tax_amount=0;

        $.each(rows,function(){
            //console.log($(oTableItems.net_vat,$(this)));
            discounts+=parseFloat(accounting.unformat($(oTableItems.total_line_discount,$(this)).find('input.numeric').val()));
            before_tax+=parseFloat(accounting.unformat($(oTableItems.net_vat,$(this)).find('input.numeric').val()));
            adjust_tax_amount+=parseFloat(accounting.unformat($(oTableItems.vat_input,$(this)).find('input.numeric').val()));
            after_tax+=parseFloat(accounting.unformat($(oTableItems.total,$(this)).find('input.numeric').val()));
        });

        var tbl_summary=$('#tbl_adjustment_summary');
        tbl_summary.find(oTableDetails.discount).html(accounting.formatNumber(discounts,2));
        tbl_summary.find(oTableDetails.before_tax).html(accounting.formatNumber(before_tax,2));
        tbl_summary.find(oTableDetails.adjust_tax_amount).html(accounting.formatNumber(adjust_tax_amount,2));
        tbl_summary.find(oTableDetails.after_tax).html('<b>'+accounting.formatNumber(after_tax,2)+'</b>');

        $('#td_before_tax').html(accounting.formatNumber(before_tax,4));
        $('#td_after_tax').html('<b>'+accounting.formatNumber(after_tax,4)+'</b>');
        $('#td_discount').html(accounting.formatNumber(discounts,4));
        $('#td_tax').html(accounting.formatNumber(adjust_tax_amount,4));

    };

    var checkProduct= function(check_id){
        var prodstat=true;
        var rowcheck=$('#tbl_items > tbody tr');
        $.each(rowcheck,function(){
            item = parseFloat(accounting.unformat($(oTableItems.item_id,$(this)).find('input.numeric').val()));
            if(check_id == item){
                prodstat=false;
                return false;
            }
        });
         return prodstat;    
    };   
    var reInitializeNumeric=function(){
        $('.numeric').autoNumeric('init');
    };








});




</script>


</body>


</html>