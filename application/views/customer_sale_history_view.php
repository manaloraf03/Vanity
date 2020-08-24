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
        #span_invoice_no{
            min-width: 50px;
        }
        #span_invoice_no:focus{
            border: 3px solid orange;
            background-color: yellow;
        }
        .alert {
            border-width: 0;
            border-style: solid;
            padding: 24px;
            margin-bottom: 32px;
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
        .select2-dropdown{
            z-index: 999999;
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
       /* .container-fluid {
            padding: 0 !important;
        }
        .panel-body {
            padding: 0 !important;
        }*/
        #btn_new {
            margin-top: 10px;
            margin-bottom: 10px;
            text-transform: uppercase!important;
        }
        @media screen and (max-width: 480px) {
            table{
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
        .form-group {
            margin-bottom: 15px;
        }
        #tbl_sales_invoice_filter{

            display: none;
        }
        .right_align{
            text-align: right;
        }
    </style>
    <link type="text/css" href="assets/css/light-theme.css" rel="stylesheet">
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
<ol class="breadcrumb"  style="margin-bottom: 0;">
    <li><a href="Dashboard">Dashboard</a></li>
    <li><a href="Sales_history">Sales History</a></li>
</ol>
<div class="container-fluid"">
<div data-widget-group="group1">
<div class="row">
<div class="col-md-12">
<div id="div_sales_invoice_list">
    <div class="panel panel-default">
        <!-- <div class="panel-heading">
            <b style="color: white; font-size: 12pt;"><i class="fa fa-bars"></i>&nbsp; Sales Invoice</b>
        </div> -->
        <div class="panel-body table-responsive">
        <div class="row panel-row">
        <h2 class="h2-panel-heading">
        <div class="row">
            <div class="col-sm-4">Sales/Cash Invoice History  </div>
            <div class="col-sm-8">
                <button class="btn btn-primary" id="btn_export_selected" title="Export selected Customer" ><i class="fa fa-file-excel-o"></i> Export Selected </button>
                <button class="btn btn-primary" id="btn_export" title="Export all Customers with Sales" ><i class="fa fa-file-excel-o"></i> Export All </button>
            </div>
        </div>
                               
            </h2><hr>

                    <div class="row">
                        <div class="col-lg-3">
                            <b class="required"></b>Customer : <br />
                            <select name="customer" id="cbo_customers" class="form-control">
                            <!-- <option value="0"> All</option> -->
                                <?php foreach($customers as $customer){ ?>
                                    <option value="<?php echo $customer->customer_id; ?>"><?php echo $customer->customer_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-6" >
                            <b class="required"></b>Product : <br />
                            <select id="product_id" class="form-control">
                                <option value="0"> ALL PRODUCTS</option>
                                <?php foreach($products as $row) { echo '<option value="'.$row->product_id.'">'.$row->product_desc.'</option>'; } ?>
                            </select>

                        </div>
                    <div class="col-lg-3">
                            Search :<br />
                             <input type="text" id="searchbox_sales_history" class="form-control">
                    </div>
                </div>
                <br>
            <table id="tbl_sales_invoice" class="table table-striped" cellspacing="0" width="100%" style="">
                <thead >
                <tr>
                    <th></th>
                    <th width="25%">Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Gross</th>
                    <th>Invoice #</th>
                    <th width="10%">Invoice Date</th>
                    <th width="20%">Remarks</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        </div>
        <!-- <div class="panel-footer"></div> -->
    </div>
</div>
</div>
</div>
</div>
</div>
</div> <!-- .container-fluid -->
</div> <!-- #page-content -->
</div>
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
<script type="text/javascript" src="assets/plugins/datatables/ellipsis.js"></script>

<!-- Date range use moment.js same as full calendar plugin -->
<script src="assets/plugins/fullcalendar/moment.min.js"></script>
<!-- Data picker -->
<script src="assets/plugins/datapicker/bootstrap-datepicker.js"></script>
<!-- Select2 -->
<script src="assets/plugins/select2/select2.full.min.js"></script>
<!-- twitter typehead -->
<script src="assets/plugins/twittertypehead/handlebars.js"></script>
<script src="assets/plugins/twittertypehead/bloodhound.min.js"></script>
<script src="assets/plugins/twittertypehead/typeahead.bundle.js"></script>
<script src="assets/plugins/twittertypehead/typeahead.jquery.min.js"></script>
<!-- touchspin -->
<script type="text/javascript" src="assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js"></script>
<!-- numeric formatter -->
<script src="assets/plugins/formatter/autoNumeric.js" type="text/javascript"></script>
<script src="assets/plugins/formatter/accounting.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
    var dt; var _txnMode; var _selectedID; var _selectRowObj; var _cboDepartments; var _cboDepartments; var _cboCustomers; var dt_so; var products; var changetxn;
    var _cboCustomerType; var prodstat; var _cboProducts;
    var _cboCustomerTypeCreate; var _cboSource;
     var _line_unit;

    var initializeControls=function(){
        dt=$('#tbl_sales_invoice').DataTable({
            "dom": '<"toolbar">frtip',
            "pageLength": 50,
            "bLengthChange":false,
                "order": [[ 2, "desc" ]],
            "ajax" : {
                "url" : "Customer_sale_history/transaction/list",
                "bDestroy": true,            
                "data": function ( d ) {
                        return $.extend( {}, d, {
                            "id":$('#cbo_customers').val(),
                            "pid":$('#product_id').val()


                        });
                    }
            }, 
            "columns": [
                { visible:false,
                    "targets": [0],
                    "class":          "details-control",
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": ""
                },
                { targets:[1],data: "product_desc" ,render: $.fn.dataTable.render.ellipsis(100)},
                {sClass:"right_align", targets:[2],data: "inv_price" , render: $.fn.dataTable.render.number( ',', '.', 2)},
                {sClass:"right_align", targets:[3],data: "inv_qty" , render: $.fn.dataTable.render.number( ',', '.', 2)},
                {sClass:"right_align", targets:[4],data: "inv_gross" , render: $.fn.dataTable.render.number( ',', '.', 2)},
                { targets:[5],data: "inv_no" },
                { targets:[6],data: "date_invoice" },
                { targets:[7],data: "remarks"  ,render: $.fn.dataTable.render.ellipsis(80)},
            ]
        });
        _cboCustomers=$("#cbo_customers").select2({
            placeholder: "Please select customer.",
            allowClear: false
        });

        _cboProducts=$("#product_id").select2({
            placeholder: "Please Select a Product.",
            allowClear: false
        });
      
    }();
    var bindEventHandlers=(function(){

        $('#btn_export_selected').click(function(){
            window.open('Customer_sale_history/transaction/export-selected?id='+$('#cbo_customers').val()+"&pid="+$('#product_id').val());
        });

        $('#btn_export').click(function(){
            window.open('Customer_sale_history/transaction/export-all');
        });


        var detailRows = [];
        $('#tbl_sales_invoice tbody').on( 'click', 'tr td.details-control', function () {
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

                if(d.type == 'SI'){
                    $.ajax({
                        "dataType":"html",
                        "type":"POST",
                        "url":'Templates/layout/sales-invoice/'+ d.invoice_id+'?type=viewport',
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


                if(d.type == 'CI'){
                    $.ajax({
                        "dataType":"html",
                        "type":"POST",
                        "url":'Templates/layout/cash-invoice/'+ d.invoice_id+'?type=viewport',
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



            }
        } );

        $("#searchbox_sales_history").keyup(function(){         
            dt
                .search(this.value)
                .draw();
        });

        _cboCustomers.on("select2:select", function (e) {
            $('#tbl_sales_invoice').DataTable().ajax.reload()
        });

        _cboProducts.on("select2:select", function (e) {
            $('#tbl_sales_invoice').DataTable().ajax.reload()
        });
    })();
  
});
</script>
</body>
</html>