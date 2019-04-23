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

        @media screen and (max-width: 1000px) {
            .custom_frame{
                padding-left:5%;

            }
        }

        @media screen and (max-width: 480px) {

            table{
                min-width: 700px;
            }




            .dataTables_filter{
                min-width: 700px;
            }

            .dataTables_info{
                min-width: 700px;
            }

            .dataTables_paginate{
                float: left;
                width: 100%;
            }
        }

        .boldlabel {
            font-weight: bold;
        }

        #modal-supplier {
            padding-left:0px !important;
        }

        .form-group {
            padding:0;
            margin:5px;
        }

        .input-group {
            padding:0;
            margin:0;
        }

        textarea {
            resize: none;
        }

        .modal-body p {
            margin-left: 20px !important;
        }

        #img_user {
            padding-bottom: 15px;
        }

        .right-align{
            text-align: right;
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
    <li><a href="Dashboard">Dashboard</a></li>
    <li><a href="Sales_report_source">Sales Report by Source</a></li>
</ol>


<div class="container-fluid"">
<div data-widget-group="group1">
<div class="row">
<div class="col-md-12">

<div id="div_delivery_list">
    <div class="panel panel-default">
       <!--  <div class="panel-heading">
            <b style="color: white; font-size: 12pt;"><i class="fa fa-bars"></i>&nbsp; </b>
        </div> -->
        <div class="panel-body table-responsive">


        <h2 class="h2-panel-heading">Sales Report By Source</h2><hr>
            <div class="row">
                <div class="col-sm-3">
                   <b>* </b>  Order Source :<br />
                    <select name="order_source_id" id="cbo_order_source">
                        <option value="0">ALL</option>
                        <?php foreach($order_sources as $order_source){ ?>
                            <option value="<?php echo $order_source->order_source_id; ?>"><?php echo $order_source->order_source_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-sm-2">
                   <b>* </b>  Invoice Source :<br />
                    <select name="source_invoice" id="source_invoice">
                        <option value="0">ALL</option>
                        <option value="1">Sales Invoices</option>
                        <option value="2">Cash Invoices</option>
                       
                    </select>
                </div>
                <div class="col-sm-3">
                   <b>* </b>  Supplier :<br />
                    <select name="supplier_id" id="supplier_id">
                        <option value="0">ALL</option>
                        <?php foreach($suppliers as $supplier){ ?>
                            <option value="<?php echo $supplier->supplier_id; ?>"><?php echo $supplier->supplier_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2">
                        From :<br />
                        <div class="input-group">
                            <input type="text" id="txt_start_date" name="" class="date-picker form-control" value="<?php echo date("m").'/01/'.date("Y"); ?>">
                             <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                             </span>
                        </div>
                </div>
                <div class="col-lg-2">
                        To :<br />
                        <div class="input-group">
                            <input type="text" id="txt_end_date" name="" class="date-picker form-control" value="<?php echo date("m/t/Y"); ?>">
                             <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                             </span>
                        </div>
                </div> 
            </div><br>
            <div class="row">
                <div class="col-sm-3"> 
                    <button class="btn btn-primary pull-left" style="margin-right: 5px; margin-top: 0; margin-bottom: 10px;" id="btn_print" style="text-transform: none; font-family: Tahoma, Georgia, Serif; ">
                        <i class="fa fa-print"></i> Print Report
                    </button>
                </div>
                <div class="col-sm-3"> 
                    <button class="btn btn-success pull-left" style="margin-right: 5px; margin-top: 0; margin-bottom: 10px;" id="btn_export"  data-placement="left" title="Export" ><i class="fa fa-file-excel-o"></i> Export Report
                </button>
                </div>
            </div>

<!--                 
                
                <button class="btn btn-success pull-left" style="margin-right: 5px; margin-top: 0; margin-bottom: 10px;" id="btn_email"  data-toggle="modal" data-target="#salesInvoice" data-placement="left" title="Email" ><i class="fa fa-share"></i> Email Report
                </button> -->
            <div id="tbl_delivery_invoice">
                
            </div>
        </div>
        <div class="panel-footer"></div>
    </div>
</div>



</div>









<footer role="contentinfo">
    <div class="clearfix">
        <ul class="list-unstyled list-inline pull-left">
            <li><h6 style="margin: 0;">&copy; 2016 - JDEV OFFICE SOLUTIONS</h6></li>
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
    var dt;  var _cboSource;  var _cboInvoice; var _cboSupplierid;




    var initializeControls=function(){
        _cboSource=$("#cbo_order_source").select2({
            placeholder: "Please select Order Source."
        });
        _cboSupplierid=$("#supplier_id").select2({
            placeholder: "Please select a Supplier."
        });

        _cboInvoice=$("#source_invoice").select2({
            placeholder: "Please select Invoice Source."
        });

        $('#txt_end_date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true

        });

        $('#txt_start_date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true

        });
        reinitializeBalances();

    }();

       var bindEventHandlers=function(){

        $('#btn_print').click(function(){
            window.open('Sales_report_source/transaction/report?oi='+$('#cbo_order_source').val()+'&si='+$('#source_invoice').val()+'&start='+$('#txt_start_date').val()+'&end='+$('#txt_end_date').val()+'&supid='+$('#supplier_id').val());
        });

        _cboSource.on("select2:select", function (e) {
            reinitializeBalances(); 
        });

        _cboInvoice.on("select2:select", function (e) {
            reinitializeBalances(); 
        });

        _cboSupplierid.on("select2:select", function (e) {
            reinitializeBalances(); 
        });

        $("#txt_start_date").on("change", function () {        
             reinitializeBalances(); 
        });

        $("#txt_end_date").on("change", function () {        
             reinitializeBalances(); 
        });

            $('#btn_export').on('click', function() {
                window.open('Sales_report_source/transaction/export?oi='+$('#cbo_order_source').val()+'&si='+$('#source_invoice').val()+'&start='+$('#txt_start_date').val()+'&end='+$('#txt_end_date').val()+'&supid='+$('#supplier_id').val(),'_self');
            });


            var showSpinningProgress=function(e){
                $(e).toggleClass('disabled');
                $(e).find('span').toggleClass('glyphicon glyphicon-refresh spinning');
            };


            var showNotification=function(obj){
                PNotify.removeAll(); //remove all notifications
                new PNotify({
                    title:  obj.title,
                    text:  obj.msg,
                    type:  obj.stat
                });
            };

    }();
    var getFloat=function(f){
        return parseFloat(accounting.unformat(f));
    };



    function reinitializeBalances() {
        $('#tbl_delivery_invoice').html('<tr><td align="center" colspan="8"><br /><img src="assets/img/loader/ajax-loader-sm.gif" /><br /><br /></td></tr>');
        var data = [];
         data.push({name : "oi" ,value : $('#cbo_order_source').val()});
         data.push({name : "si" ,value : $('#source_invoice').val()});
         data.push({name : "start" ,value : $('#txt_start_date').val()});
         data.push({name : "end" ,value : $('#txt_end_date').val()});
         data.push({name : "supid" ,value : $('#supplier_id').val()});
        $.ajax({
            url : 'Sales_report_source/transaction/list',
            type : "GET",
            cache : false,
            dataType : 'json',
            "data":data,
            success : function(response){
                 $('#tbl_delivery_invoice').html('');
                $('#tbl_delivery_invoice').append(
                     '<h4><strong>Sales</strong></h4><hr>'
                );
                 $.each(response.sources, function(index,value){
                    $('#tbl_delivery_invoice').append(
                    '<h4>'+value.order_source_name+'</h4>'+
                    '<table style="width:100%" class="table table-striped">'+
                    '<thead>'+
                    '<th>Invoice No.</th>'+
                    '<th>Invoice Date</th>'+
                    '<th>Customer Name</th>'+
                    '<th>Product Desription</th>'+
                    '<th class="right-align">Quantity</th>'+
                    '<th class="right-align">Unit Cost</th>'+
                    '<th class="right-align">Gross</th>'+
                    '<th class="right-align">Discount(%)</th>'+
                    '<th class="right-align">Net </th>'+
                    
                    '</thead>'+
                        '<tbody id="'+value.order_source_id+'">'+
                        '</tbody>'+
                         '</table>'
                    );
                 });

                 $.each(response.data, function(index,value){
                     $('#'+value.order_source_id).append(
                        '<tr>'+
                        '<td>'+value.inv_no+'</td>'+
                        '<td>'+value.date+'</td>'+
                        '<td>'+value.customer_name+'</td>'+
                        '<td>'+value.product_desc+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.inv_qty,2)+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.inv_price,2)+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.inv_gross,2)+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.inv_discount,2)+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.inv_line_total_price,2)+'</td>'+
                        '</tr>'
                    );
                 });

                 grand_total = 0;
                 $.each(response.totals, function(index,value){
                    grand_total += getFloat(value.sub_total);
                    $('#tbl_delivery_invoice #'+value.order_source_id).append(
                        '<tr>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td style="font-weight:bold;">Sub Total:</td>'+
                        '<td class="right-align"><strong>'+accounting.formatNumber(value.sub_total,2)+'</strong></td>'+
                        '</tr>'
                    );
                 });

                 $('#tbl_delivery_invoice ').append(

                    '<tr style="font-size18px;font-weight:bold;float:right;padding-right:10px;"><td>Total Sales: </td><td>&nbsp;'+accounting.formatNumber(grand_total,2)+'</td></tr>'
                )



                $('#tbl_delivery_invoice').append(
                     '<br><h4><strong>Returns</strong></h4><hr>'
                );


                 $.each(response.return_sources, function(index,value){
                    $('#tbl_delivery_invoice').append(
                    '<h4>'+value.order_source_name+'</h4>'+
                    '<table style="width:100%" class="table table-striped">'+
                    '<thead>'+
                    '<th>Invoice No.</th>'+
                    '<th>Reference No.</th>'+
                    '<th>Return Date</th>'+
                    '<th>Customer Name</th>'+
                    '<th>Product Desription</th>'+
                    '<th class="right-align">Quantity</th>'+
                    '<th class="right-align">Unit Cost</th>'+
                    '<th class="right-align">Total</th>'+
                    '</thead>'+
                        '<tbody id="return_'+value.order_source_id+'">'+
                        '</tbody>'+
                         '</table>'
                    );
                 });




                 $.each(response.return_data, function(index,value){
                     $('#return_'+value.order_source_id).append(
                        '<tr>'+
                        '<td>'+value.inv_no+'</td>'+
                        '<td>'+value.adjustment_code+'</td>'+
                        '<td>'+value.date_adjusted+'</td>'+
                        '<td>'+value.customer_name+'</td>'+
                        '<td>'+value.product_desc+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.adjust_qty,2)+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.adjust_price,2)+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.adjust_line_total_price,2)+'</td>'+
                        '</tr>'
                    );
                 });


                 grand_total_returns = 0;
                 $.each(response.return_totals, function(index,value){
                    grand_total_returns += getFloat(value.sub_total);
                    $('#tbl_delivery_invoice #return_'+value.order_source_id).append(
                        '<tr>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td style="font-weight:bold;">Sub Total:</td>'+
                        '<td class="right-align"><strong>'+accounting.formatNumber(value.sub_total,2)+'</strong></td>'+
                        '</tr>'
                    );
                 });


                 $('#tbl_delivery_invoice ').append(

                    '<table style="width:100%;border:none!important;"><tr style="font-size18px;font-weight:bold;float:right;padding-right:10px;"><td>Total Returns: </td><td>&nbsp;'+accounting.formatNumber(grand_total_returns,2)+'</td></tr></table><br><br>'
                )

                 $('#tbl_delivery_invoice ').append(

                    '<table style="width:100%;border:none!important;"><tr style="font-size18px;font-weight:bold;float:right;padding-right:10px;"><td>Net Sales: </td><td>&nbsp;'+accounting.formatNumber(grand_total - grand_total_returns,2)+'</td></tr></table>'
                )

            }
        });
    }






































});




</script>


</body>


</html>