<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <title>JCORE - <?php echo $title; ?></title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-cdjp-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="description" content="Avenxo Admin Theme">
    <meta name="author" content="">

    <?php echo $_def_css_files; ?>

    <link rel="stylesheet" href="assets/plugins/spinner/dist/ladda-themeless.min.css">
    <link type="text/css" href="assets/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
    <link type="text/css" href="assets/plugins/datatables/dataTables.themify.css" rel="stylesheet">
    <link href="assets/plugins/select2/select2.min.css" rel="stylesheet">
    <!--<link href="assets/dropdown-enhance/dist/css/bootstrap-select.min.css" rel="stylesheet" type="text/css">-->
    <link href="assets/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link type="text/css" href="assets/plugins/iCheck/skins/minimal/blue.css" rel="stylesheet">              <!-- iCheck -->
    <link type="text/css" href="assets/plugins/iCheck/skins/minimal/_all.css" rel="stylesheet">                   <!-- Custom Checkboxes / iCheck -->
    <style>
        .numericCol {
            text-align: right;
        }

        .toolbar{
            float: left;
        }

        td:nth-child(6),td:nth-child(7){
            text-align: right;
        }

        td:nth-child(8){
            text-align: right;
            font-weight: bolder;
        }
    </style>

</head>

<body class="animated-content" style="font-family: tahoma;">

<?php echo $_top_navigation; ?>

<div id="wrapper">
    <div id="layout-static">

        <?php echo $_side_bar_navigation;?>

        <div class="static-content-wrapper white-bg">
            <div class="static-content"  >

                <div class="page-content"><!-- #page-content -->

                    <ol class="breadcrumb" style="margin-bottom: 0px;">
                        <li><a href="dashboard">Dashboard</a></li>
                        <li><a href="Spoilage_report">Spoilage Report</a></li>
                    </ol>

                    <div class="container-fluid">
                        <div data-widget-group="group1">
                            <div class="row">
                                <div class="col-md-12">

                                    <div id="div_payable_list">

                                        <div class="panel-group panel-default" id="accordionA">
                                            <div class="panel panel-default">
                                                <!-- <a data-toggle="collapse" data-parent="#accordionA" href="#collapseTwo"><div class="panel-heading" style="background: #2ecc71;border-bottom: 1px solid lightgrey;;"><b style="color:white;font-size: 12pt;"><i class="fa fa-bars"></i> Purchase Invoice Report</b></div></a> -->
                                                <div id="collapseTwo" class="collapse in">
                                                    <div class="panel-body">
                                                    <h2 class="h2-panel-heading">Spoilage Report</h2><hr>
                                                        <div>
                                                            <div class="row">
                                                                <div class="col-lg-4">
                                                                    Period Start * :<br />
                                                                    <div class="input-group">
                                                                        <input type="text" id="txt_date" name="date_from" class="date-picker form-control" value="01/01/<?php echo date("Y"); ?>">
                                                                         <span class="input-group-addon">
                                                                                <i class="fa fa-calendar"></i>
                                                                         </span>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-4">
                                                                    Period End * :<br />
                                                                    <div class="input-group">
                                                                        <input type="text" id="txt_date" name="date_to" class="date-picker form-control" value="<?php echo date("m/d/Y"); ?>">
                                                                         <span class="input-group-addon">
                                                                                <i class="fa fa-calendar"></i>
                                                                         </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    Transaction Type * : <br />
                                                                    <select name="supplier" id="cbo_transtype" style="width: 100%;">
                                                                        <option value="1">Adjustments - OUT</option>
                                                                        <option value="2">Sales Returns - IN</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br />

                                                        <div>
                                                            <div class="tab-container tab-top tab-primary">
                                                                <ul class="nav nav-tabs">
                                                                    <li class="active"><a data-toggle="tab" href="#summary">Summary</a></li>
                                                                    <li><a data-toggle="tab" href="#detailed">Detailed</a></li>
                                                                </ul>
                                                                <div class="tab-content">
                                                                    <div id="summary" class="tab-pane fade in active">
                                                                        <button class="btn btn-primary pull-left" id="btn_print_summary"><i class="fa fa-print"></i>&nbsp; Print Report</button>
                                                                        <button class="btn btn-success pull-left" style="margin-left: 5px;" id="btn_export_summary"><i class="fa fa-file-excel-o"></i>&nbsp; Export</button>
                                                                        <table id="tbl_pi_summary" class="table table-striped" cellspacing="0" width="100%">
                                                                            <thead class="">
                                                                            <tr>
                                                                                <th>Product Code</th>
                                                                                <th>Product Description</th>
                                                                                <th>Quantity</th>
                                                                                <th>Total Amount</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            </tbody>
                                                                            <tfoot>
                                                                                <tr>
                                                                                    <td align="right" colspan="2">Total : </td>
                                                                                    <td id="td_grand_qty_summary" align="right"></td>
                                                                                    <td id="td_grand_total_summary" align="right"></td>
                                                                                </tr>
                                                                            </tfoot>
                                                                        </table>
                                                                    </div>
                                                                    <div id="detailed" class="tab-pane fade">
                                                                        <button class="btn btn-primary pull-left" id="btn_print_detailed"><i class="fa fa-print"></i>&nbsp; Print Report</button>
                                                                        <button class="btn btn-success pull-left" style="margin-left: 5px;" id="btn_export_detailed"><i class="fa fa-file-excel-o"></i>&nbsp; Export</button>                                                               
                                                                        <table id="tbl_pi_detailed" class="table table-striped" cellspacing="0" width="100%">
                                                                            <thead class="">
                                                                            <tr>
                                                                                <th>Adjustment Code</th>
                                                                                <th>Adjustment Date</th>
                                                                                <th>Customer Name</th>
                                                                                <th>Product Code</th>
                                                                                <th>Product Description</th>
                                                                                <th>Qty</th>
                                                                                <th>Price</th>
                                                                                <th>Total</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            </tbody>
                                                                            <tfoot>
                                                                                <tr>
                                                                                    <td align="right" colspan="5"> Total : </td>
                                                                                    <td id="td_grand_qty_detailed" align="right"></td>
                                                                                    <td></td>
                                                                                    <td id="td_grand_total_detailed" align="right"></td>
                                                                                </tr>
                                                                            </tfoot>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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

        <footer role="contentinfo">
            <div class="clearfix">
                <ul class="list-unstyled list-inline pull-left">
                    <li><h6 style="margin: 0;">&copy; 2016 - Paul Christian Rueda</h6></li>
                </ul>
                <button class="pull-right btn btn-link btn-xs hidden-print" id="back-to-top"><i class="ti ti-arrow-up"></i></button>
            </div>
        </footer>

    </div>
</div>
</div>








<?php echo $_switcher_settings; ?>
<?php echo $_def_js_files; ?>


<script type="text/javascript" src="assets/plugins/datatables/jquery.dataTables.js"></script>
<script type="text/javascript" src="assets/plugins/datatables/dataTables.bootstrap.js"></script>

<!-- Select2-->
<script src="assets/plugins/select2/select2.full.min.js"></script>
<!---<script src="assets/plugins/dropdown-enhance/dist/js/bootstrap-select.min.js"></script>-->

<!-- Date range use moment.js same as full calendar plugin -->
<script src="assets/plugins/fullcalendar/moment.min.js"></script>
<!-- Data picker -->
<script src="assets/plugins/datapicker/bootstrap-datepicker.js"></script>

<!-- numeric formatter -->
<script src="assets/plugins/formatter/autoNumeric.js" type="text/javascript"></script>
<script src="assets/plugins/formatter/accounting.js" type="text/javascript"></script>

<script>
    $(document).ready(function(){
        var dtSummary, dtDetailed;
        var _cboTransType;
        var _date_from = $('input[name="date_from"]');
        var _date_to = $('input[name="date_to"]');

        var initializeControls=function() {
            $('.date-picker').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        _cboTransType=$('#cbo_transtype').select2({
            placeholder: "Please Select Transaction Type.",
            minimumResultsForSearch: -1,
            allowClear: false
        });

            initializeDataTable();
        }();


        function initializeDataTable(){
                dtSummary=$('#tbl_pi_summary').DataTable({   
                    "dom": '<"toolbar">frtip',
                    "bLengthChange":false,
                    "bPaginate":false,
                    "language": { searchPlaceholder: "Search" },
                    "ajax": {
                        "url":"Spoilage_report/transaction/summary",
                        "type":"GET",
                        "bDestroy":true,
                        "data": function (d) {
                            return $.extend({}, d, {
                                "startDate":_date_from.val(),
                                "endDate":_date_to.val(),
                                "tid":_cboTransType.val()

                            });
                        }
                    },
                    
                        "columns":[
                            { targets:[0],data: "product_code" },
                            { targets:[1],data: "product_desc" },
                            { sClass: "numericCol", targets:[2],data: "adjust_qty" },
                            {
                                sClass: "numericCol", 
                                targets:[3],data: "adjust_line_total_price",
                                render: function(data,type,full,meta){
                                    return accounting.formatNumber(data,2);
                                }
                            }
                        ],
                        "footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;

                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };

                            // Total over all pages
                            qty = api
                                .column( 2 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );

                            total = api
                                .column( 3 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );

                            $('#td_grand_qty_summary').html('<b>'+accounting.formatNumber(qty,2)+'</b>');
                            $('#td_grand_total_summary').html('<b>'+accounting.formatNumber(total,2)+'</b>');
                        }

                });

                dtDetailed=$('#tbl_pi_detailed').DataTable({
                    "dom": '<"toolbar">frtip',
                    "bLengthChange":false,
                    "bPaginate":false,
                    "language": { searchPlaceholder: "Search" },
                    "ajax": {
                        "url":"Spoilage_report/transaction/detailed",
                        "type":"GET",
                        "bDestroy":true,
                        "data": function (d) {
                            return $.extend({}, d, {
                                "startDate":_date_from.val(),
                                "endDate":_date_to.val(),
                                "tid":_cboTransType.val()
                            });
                        }
                    },
                    
                        "columns":[
                            { targets:[0],data: "adjustment_code" ,visible:false },
                            { targets:[1],data: "date_adjusted" ,visible:false},
                            { targets:[2],data: "customer_name" ,visible:false},
                            { targets:[3],data: "product_code" },
                            { targets:[4],data: "product_desc" },
                            { 
                                sClass: "numericCol", 
                                targets:[5],data: "adjust_qty", 
                                render: function(data,type,full,meta){
                                    return accounting.formatNumber(data,2);
                                } 
                            },
                            {   sClass: "numericCol", 
                                targets:[6],data: "adjust_price",
                                render: function(data,type,full,meta){
                                    return accounting.formatNumber(data,2);
                                } 
                            },
                            {
                                sClass: "numericCol", 
                                targets:[7],data: "adjust_line_total_price",
                                render: function(data,type,full,meta){
                                    return accounting.formatNumber(data,2);
                                }
                            },
                        ],
                    "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;

                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if ( last !== group ) {
                                var rowData = api.row(i).data();
                                $(rows).eq( i ).before(
                                    '<tr class="group"><td colspan="7" style="background-color:#82d1f5;"><strong>'+'Adjustment #: <i>'+rowData.adjustment_code+'</i> | '+rowData.date_adjusted+' | '+rowData.customer_name+'</strong></td></tr>'
                                );

                                last = group;
                            }
                        } );

                    },
                        "footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;

                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };

                            // Total over all pages
                            qty = api
                                .column( 5 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );

                            total = api
                                .column( 7 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );

                            $('#td_grand_qty_detailed').html('<b>'+accounting.formatNumber(qty,2)+'</b>');
                            $('#td_grand_total_detailed').html('<b>'+accounting.formatNumber(total,2)+'</b>');
                        }

                });

        };

                var bindEventControls=function(){

            $('#btn_print_summary').on('click', function(){
                window.open('Spoilage_report/transaction/summary-print?&startDate='+_date_from.val()+'&endDate='+_date_to.val()+'&tid='+_cboTransType.val());
            });

            $('#btn_print_detailed').on('click', function(){
                window.open('Spoilage_report/transaction/detailed-print?startDate='+_date_from.val()+'&endDate='+_date_to.val()+'&tid='+_cboTransType.val());
            });

            $('#btn_export_summary').on('click', function(){
                window.open('Spoilage_report/transaction/summary-excel?startDate='+_date_from.val()+'&endDate='+_date_to.val()+'&tid='+_cboTransType.val());
            });

            $('#btn_export_detailed').on('click', function(){
                window.open('Spoilage_report/transaction/detailed-excel?startDate='+_date_from.val()+'&endDate='+_date_to.val()+'&tid='+_cboTransType.val());
            });

            _date_from.on('change', function(){
                dtSummary.destroy();
                dtDetailed.destroy();
                initializeDataTable();
            });

            _date_to.on('change', function(){
                dtSummary.destroy();
                dtDetailed.destroy();
                initializeDataTable();
            });

            _cboTransType.on("select2:select", function (e) {
                dtSummary.destroy();
                dtDetailed.destroy();
                initializeDataTable();

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

    });
</script>


</body>

</html>