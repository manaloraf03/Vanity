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
    <li><a href="Profit">Profit Report</a></li>
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


        <h2 class="h2-panel-heading">Profit Report</h2><hr>
            <div class="row">
                <div class="col-sm-4">
                   <b>* </b> Report Type :<br />
                    <select name="report_type" id="report_type">
                        <option value="1">By Product</option>
                        <option value="2">By Invoice (Detailed)</option>
                        <option value="3">By Invoice (Summary)</option>
                    </select>
                </div>
                <div class="col-lg-4">
                        From :<br />
                        <div class="input-group">
                            <input type="text" id="txt_start_date" name="" class="date-picker form-control" value="<?php echo date("m/d/Y"); ?>">
                             <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                             </span>
                        </div>
                </div>
                <div class="col-lg-4">
                        To :<br />
                        <div class="input-group">
                            <input type="text" id="txt_end_date" name="" class="date-picker form-control" value="<?php echo date("m/d/Y"); ?>">
                             <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                             </span>
                        </div>
                </div> 
            </div><br>
            <div class="row">
                <div class="col-sm-2"> 
                    <button class="btn btn-primary pull-left" style="margin-right: 5px; margin-top: 0; margin-bottom: 10px;" id="btn_print" style="text-transform: none; font-family: Tahoma, Georgia, Serif; ">
                        <i class="fa fa-print"></i> Print Report
                    </button>
                </div>
                <div class="col-sm-2"> 
                    <button class="btn btn-success pull-left" style="margin-right: 5px; margin-top: 0; margin-bottom: 10px;" id="btn_export"  data-placement="left" title="Export" ><i class="fa fa-file-excel-o"></i> Export Report
                </button>
                </div>
            </div>
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
    var dt;  var _cboReportType; 




    var initializeControls=function(){
        _cboReportType=$("#report_type").select2({
            placeholder: "Please select Order Source."
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
         reportProducts();
    }();

       var bindEventHandlers=function(){

        $('#btn_print').click(function(){
            window.open('Profit/transaction/print-by-product?type='+_cboReportType.val()+'&start='+$('#txt_start_date').val()+'&end='+$('#txt_end_date').val());
        });

        _cboReportType.on("select2:select", function (e) {
            type = _cboReportType.val();
            if(type == '1'){ reportProducts();}else if(type == '2'){ reportInvoiceDetailed();}else if(type == '3'){ reportInvoiceSummary(); }
        });



        $("#txt_start_date").datepicker().on("change", function () {        
            type = _cboReportType.val();
            if(type == '1'){ reportProducts();}else if(type == '2'){ reportInvoiceDetailed();}else if(type == '3'){ reportInvoiceSummary(); }
        });

        $("#txt_end_date").datepicker().on("change", function () {        
            type = _cboReportType.val();
            if(type == '1'){ reportProducts();}else if(type == '2'){ reportInvoiceDetailed();}else if(type == '3'){ reportInvoiceSummary(); }
        });

        $('#btn_export').on('click', function() {
            type = _cboReportType.val();
            if(type == '1'){
                window.open('Profit/transaction/export-by-product?type='+_cboReportType.val()+'&start='+$('#txt_start_date').val()+'&end='+$('#txt_end_date').val(),'_self');
            }else if(type == '2'){
                window.open('Profit/transaction/export-by-invoice-detailed?type='+_cboReportType.val()+'&start='+$('#txt_start_date').val()+'&end='+$('#txt_end_date').val(),'_self');
            }else if(type == '3'){
                window.open('Profit/transaction/export-by-invoice-summary?type='+_cboReportType.val()+'&start='+$('#txt_start_date').val()+'&end='+$('#txt_end_date').val(),'_self');
            }
               
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



    function reportProducts() {
        $('#tbl_delivery_invoice').html('<tr><td align="center" colspan="8"><br /><img src="assets/img/loader/ajax-loader-sm.gif" /><br /><br /></td></tr>');
        var data = [];
         data.push({name : "start" ,value : $('#txt_start_date').val()});
         data.push({name : "end" ,value : $('#txt_end_date').val()});
        $.ajax({
            url : 'Profit/transaction/report-by-product',
            type : "GET",
            cache : false,
            dataType : 'json',
            "data":data,
            success : function(response){
                    p_qty = 0;
                    p_gross= 0;
                    p_net=0;
                    $('#tbl_delivery_invoice').html('');
                    $('#tbl_delivery_invoice').append(
                    '<h4>Profit Report by Products</h4>'+
                    '<table style="width:100%" class="table table-striped" id="tbl_products">'+
                    '<thead>'+
                    '<th>Item Code</th>'+
                    '<th>Item Description</th>'+
                    '<th>UM</th>'+
                    '<th class="right-align">QTY Sold</th>'+
                    '<th class="right-align">SRP</th>'+
                    '<th class="right-align">Gross</th>'+
                    '<th class="right-align">Unit Cost</th>'+
                    '<th class="right-align">Net Profit</th>'+
                    
                    '</thead>'+
                        '<tbody >'+
                        '</tbody>'+
                         '</table>'
                    );

                 $.each(response.data, function(index,value){
                    $('#tbl_products  tbody').append(
                        '<tr>'+
                        '<td>'+value.product_code+'</td>'+
                        '<td>'+value.product_desc+'</td>'+
                        '<td>'+value.unit_name+'</td>'+
                        '<td class="right-align">'+value.qty_sold+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.srp,2)+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.gross,2)+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.purchase_cost,2)+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.net_profit,2)+'</td>'+
                        '</tr>'
                    );
                    p_qty +=getFloat(value.qty_sold);
                    p_gross += getFloat(value.gross);
                    p_net +=getFloat(value.net_profit);
                 });

                    $('#tbl_products tbody').append(
                        '<tr>'+
                        '<td><strong>TOTAL</strong></td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td class="right-align"><strong>'+accounting.formatNumber(p_qty,2)+'</strong></td>'+
                        '<td class="right-align"></td>'+
                        '<td class="right-align"><strong>'+accounting.formatNumber(p_gross,2)+'</strong></td>'+
                        '<td class="right-align"></td>'+
                        '<td class="right-align"><strong>'+accounting.formatNumber(p_net,2)+'</strong></td>'+
                        '</tr>'
                    );

            }
        });
    }


    function reportInvoiceDetailed() {
        $('#tbl_delivery_invoice').html('<tr><td align="center" colspan="8"><br /><img src="assets/img/loader/ajax-loader-sm.gif" /><br /><br /></td></tr>');
        var data = [];
         data.push({name : "start" ,value : $('#txt_start_date').val()});
         data.push({name : "end" ,value : $('#txt_end_date').val()});
        $.ajax({
            url : 'Profit/transaction/report-by-invoice-detailed',
            type : "GET",
            cache : false,
            dataType : 'json',
            "data":data,
            success : function(response){


                    $('#tbl_delivery_invoice').html('');
                    $('#tbl_delivery_invoice').append('<h4>Profit Report by Invoice (Detailed)</h4><br>');
                    detailed_grand_qty= 0;
                    detailed_grand_gross= 0;
                    detailed_grand_profit= 0;
                 $.each(response.distinct, function(index,value){
                    $('#tbl_delivery_invoice').append(
                    '<h4>'+value.inv_no+'</h4>'+
                    '<table style="width:100%" class="table table-striped" id="'+value.identifier+'_'+value.invoice_id+'">'+
                    '<thead>'+
                    '<th>Item Code</th>'+
                    '<th>Item Description</th>'+
                    '<th>UM</th>'+
                    '<th class="right-align">QTY Sold</th>'+
                    '<th class="right-align">SRP</th>'+
                    '<th class="right-align">Gross</th>'+
                    '<th class="right-align">Unit Cost</th>'+
                    '<th class="right-align">Net Profit</th>'+
                    
                    '</thead>'+
                        '<tbody >'+
                        '</tbody>'+
                         '</table>'
                    );
                 });


                 $.each(response.data, function(index,value){
                    $('#'+value.identifier+'_'+value.invoice_id+'  tbody').append(
                        '<tr>'+
                        '<td>'+value.product_code+'</td>'+
                        '<td>'+value.product_desc+'</td>'+
                        '<td>'+value.unit_name+'</td>'+
                        '<td class="right-align">'+value.inv_qty+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.srp,2)+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.inv_gross,2)+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.purchase_cost,2)+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.net_profit,2)+'</td>'+
                        '</tr>'
                    );
                 });

                 $.each(response.subtotal, function(index,value){
                    $('#'+value.identifier+'_'+value.invoice_id+'  tbody').append(
                        '<tr>'+
                        '<td><strong> Total ('+value.inv_no+')</strong></td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td class="right-align"><strong>'+value.qty_total+'</strong></td>'+
                        '<td class="right-align"></td>'+
                        '<td class="right-align"><strong>'+accounting.formatNumber(value.gross_total,2)+'</strong></td>'+
                        '<td class="right-align"></td>'+
                        '<td class="right-align"><strong>'+accounting.formatNumber(value.profit_total,2)+'</strong></td>'+
                        '</tr>'
                    );

                    detailed_grand_qty += getFloat(value.qty_total);
                    detailed_grand_gross += getFloat(value.gross_total);
                    detailed_grand_profit += getFloat(value.profit_total);
                 });


                 $('#tbl_delivery_invoice ').append(

                    '<table style="width:100%;border:none!important;font-size:12px!important;font-weight:bold;">'+
                    '<tr style="font-size:18px;font-weight:bold;float:right;padding-right:10px;"><tr>'+
                        '<td style="width:70%;"> </td><td style="width:15%;">Total Quantity Sold: </td><td  style="width:15%;text-align:right;">&nbsp;'+accounting.formatNumber(detailed_grand_qty,2)+'</td>'+
                    '</tr>'+
                    '<tr style="font-size:18px;font-weight:bold;float:right;padding-right:10px;"><tr>'+
                        '<td style="width:70%;"> </td><td style="width:15%;">Total Gross: </td><td  style="width:15%;text-align:right;">&nbsp;'+accounting.formatNumber(detailed_grand_gross,2)+'</td>'+
                    '</tr>'+
                    '<tr style="font-size:18px;font-weight:bold;float:right;padding-right:10px;"><tr>'+
                        '<td style="width:70%;"> </td><td style="width:15%;">Total Profit: </td><td  style="width:15%;text-align:right;">&nbsp;'+accounting.formatNumber(detailed_grand_profit,2)+'</td>'+
                    '</tr>'+
                    '</table><br><br>'
                )
            }
        });
    }


    function reportInvoiceSummary() {
        $('#tbl_delivery_invoice').html('<tr><td align="center" colspan="8"><br /><img src="assets/img/loader/ajax-loader-sm.gif" /><br /><br /></td></tr>');
        var data = [];
         data.push({name : "start" ,value : $('#txt_start_date').val()});
         data.push({name : "end" ,value : $('#txt_end_date').val()});
        $.ajax({
            url : 'Profit/transaction/report-by-invoice-summary',
            type : "GET",
            cache : false,
            dataType : 'json',
            "data":data,
            success : function(response){

                summary_grand_qty= 0;
                summary_grand_gross= 0;
                summary_grand_profit= 0;
                    $('#tbl_delivery_invoice').html('');
                    $('#tbl_delivery_invoice').append(
                    '<h4>Profit Report by Invoice (Summary)</h4><br>'+
                    '<table style="width:100%" class="table table-striped" id="tbl_summary">'+
                    '<thead>'+
                    '<th>Invoice No</th>'+
                    '<th>Customer Name</th>'+
                    '<th>Date</th>'+
                    '<th class="right-align">QTY Sold</th>'+
                    '<th class="right-align">Gross</th>'+
                    '<th class="right-align">Net Profit</th>'+
                    
                    '</thead>'+
                        '<tbody >'+
                        '</tbody>'+
                         '</table>'
                    );
                 $.each(response.summary, function(index,value){
                    $('#tbl_summary  tbody').append(
                        '<tr>'+
                        '<td>'+value.inv_no+'</td>'+
                        '<td>'+value.customer_name+'</td>'+
                        '<td>'+value.date_invoice+'</td>'+
                        '<td class="right-align">'+value.qty_total+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.gross_total,2)+'</td>'+
                        '<td class="right-align">'+accounting.formatNumber(value.profit_total,2)+'</td>'+
                        '</tr>'
                    );
                    summary_grand_qty += getFloat(value.qty_total);
                    summary_grand_gross += getFloat(value.gross_total);
                    summary_grand_profit += getFloat(value.profit_total);
        
                 });

                    $('#tbl_summary  tbody').append(
                        '<tr>'+
                        '<td><strong>Total : </strong></td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '<td class="right-align"><strong>'+accounting.formatNumber(summary_grand_qty,2)+'</strong></td>'+
                        '<td class="right-align"><strong>'+accounting.formatNumber(summary_grand_gross,2)+'</strong></td>'+
                        '<td class="right-align"><strong>'+accounting.formatNumber(summary_grand_profit,2)+'</strong></td>'+
                        '</tr>'
                    );


            }
        });
    }






































});




</script>


</body>


</html>