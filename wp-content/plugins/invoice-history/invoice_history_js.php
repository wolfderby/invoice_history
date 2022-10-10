<?php	 

//require('includes/application_top.php');
error_reporting(-1);
require('includes/classes/currencies.php'); 										   
?>							  
<html>  
    <head>  
		<meta http-equiv="Content-Type" content="text/html; charset=<?php // echo CHARSET; ?>">															
        <title>Invoice History JS</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>--> <!-- using this one makes the nav menu not work-->
		<link href="includes/jSunPicker.v1.css" rel="stylesheet"></script>
		<script src="includes/jSunPicker.v1.min.js"></script>
  <?php //require(DIR_WS_INCLUDES . 'header.php'); ?>
  <?php /*echo 'DIR_WS_ADMIN is ' . DIR_WS_ADMIN . '<br />'; ?>
  <?php echo 'DIR_WS_INCLUDES is ' . DIR_WS_INCLUDES . '<br />'; ?>
  <?php require(DIR_WS_ADMIN  . DIR_WS_INCLUDES . 'stickyTableHeaders.js');*/ ?>
  <?php //require('includes/stickyTableHeaders.js'); ?>
		<style>		
			table.getit {
			  table-layout: auto;
			  width: 100%;  
			}
			th.imported,td.imported{
				word-wrap: break-word;
				width: 5px;
				background-color: red;
			}
			tr.rowEqual {
				background-color: lightgreen !important;
			}
			tr.rowNotEqual {
				background-color: rgba(255, 0, 0, 0.5) !important;
			}
			tr.invoiceTableOnly {
				background-color: lightblue !important;
			}
			.alert-success{
				position: fixed;
				z-index: 100;
				bottom: -20px;
				-moz-animation: cssAnimation 0s ease-in 5s forwards;
				/* Firefox */
				-webkit-animation: cssAnimation 0s ease-in 5s forwards;
				/* Safari and Chrome */
				-o-animation: cssAnimation 0s ease-in 5s forwards;
				/* Opera */
				animation: cssAnimation 0s ease-in 5s forwards;
				-webkit-animation-fill-mode: forwards;
				animation-fill-mode: forwards;
			}
			@keyframes cssAnimation {
				to {
					width:0;
					height:0;
					overflow:hidden;
				}
			}
			@-webkit-keyframes cssAnimation {
				to {
					width:0;
					height:0;
					visibility:hidden;
				}
			}
			bodyz {
				padding-top:50px;
			}
			table.floatThead-table {
				border-top: none;
				border-bottom: none;
				background-color: #fff;
			}
		</style>					
    </head>  
    <body>  
       <!--<div class="container">  -->
			<span id="result"></span>
				<ul>Goals of this:<br/>
					<li>✔ To create a work from list of invoices to import into the website</li>
					<li>✔ To "add invoices" (... to this list) before they're added to the website</li>
					<li>__ To highlight by row when cogs is greater than invoice total</li>
					<li>__ To </li>
					<li>__ To audit/compare invoice totals with amount in website (+shipping)</li>
					<li>__ To pull in data like the easypopulate table (for import dates)</li>
				</ul>
				<div class="table-nonresponsive" id="live_data"></div>                 
<!--			</div>  -->
		<!--</div> end div container -->		  
			<div class="table-responsive">  
				<h3 align="center">Back to Tutorial - <a href="http://www.webslesson.info/2016/02/live-table-add-edit-delete-using-ajax-jquery-in-php-mysql.html" title="Live Table Add Edit Delete using Ajax Jquery in PHP Mysql">Live Table Add Edit Delete using Ajax Jquery in PHP Mysql</a></h3><br />
				<div>
				Bulk invoice name change sql: <br />
				UPDATE `products` SET `products_pebs_invoice` = '043013AuniqueImpression001' WHERE `products`.`products_pebs_invoice` = 'AuniqueImpression001'
				</div>
				<div>
				Bulk invoice name change sql: <br />
				UPDATE `products` SET `products_invoice` = CONCAT("102319TumYeto004", RIGHT(`products_invoice`, 4)) WHERE `products`.`products_pebs_invoice` = '102319TumYeto004'
				</div>
				<span id="result"></span>
				<div id="live_data"></div>                 
			</div>  
		</div>
    </body>  
</html>
<?php require("includes/stickyTableHeaders.js"); ?>
<script>  
$(document).ready(function(){  
    function fetch_data()  
    {  
        $.ajax({  
            url:"invoicehistoryJS_select.php",  
            method:"POST",  
            success:function(data){  
				$('#live_data').html(data);  
            }  
        });  
    } 
    fetch_data();
    $(document).on('click', '#btn_add', function(){		console.log($(this));
		console.log($(this));			   
		
		var row_id = $(this).data("nid1");
		console.log('row_id is '+ row_id);
		//var id=$(this).data("id3");  
		var invoice_date_name = '#invoice_date_' + row_id;
		var invoice_name_name = '#invoice_name_' + row_id;
		var invoice_dist_name = '#invoice_dist_' + row_id;
		var invoice_payment_name = '#invoice_payment_' + row_id;
		var invoice_comments_name = '#invoice_comments_' + row_id;
		
		//alert(invoice_name_name);
		//alert('invoice_date_name is ' + invoice_date_name);
		//alert($(this).text());
        //var invoice_date = $('#invoice_date').text();  
        //var invoice_name = $('#invoice_name').text();  
        //var invoice_dist = $('#invoice_dist').text();
        var invoice_date = $(invoice_date_name).text();  
        var invoice_name = $(invoice_name_name).text();  
        var invoice_dist = $(invoice_dist_name).text();
        var invoice_payment = $(invoice_payment_name).text();
        var invoice_comments = $(invoice_comments_name).text();
		//alert('invoice_date is ' + invoice_date);
		//alert('invoice_name is ' + invoice_name);
        if(invoice_date == '')  
        {  
            invoice_date = '2003-01-01';
        }  
        if(invoice_name == '')  
        {  
            alert("Enter Invoice Name");  
            return false;  
        }  
        if(invoice_dist == '')  
        {  
            invoice_dist = invoice_name;
						   
        }  
        $.ajax({
            url:"invoicehistoryJS_insert.php",  
            method:"POST",  
            data:{invoice_date:invoice_date, invoice_name:invoice_name, invoice_dist:invoice_dist, invoice_payment:invoice_payment, invoice_comments:invoice_comments},  
            dataType:"text",  
            success:function(data)  
            {
                alert(data);  
                fetch_data();  
            }
        })  
    });  
    
	function edit_data(id, text, column_name)  
    {  
        $.ajax({  
            url:"invoicehistoryJS_edit.php",  
            method:"POST",  
            data:{id:id, text:text, column_name:column_name},  
            dataType:"text",  
            success:function(data){  
                //alert(data); //makes constant pop-ups ? 
				$('#result').html("<div class='alert alert-success'>"+data+"</div>");
            }  
        });  
    }  
    $(document).on('blur', '.invoice_name', function(){  
        var id = $(this).data("id1");  
        var invoice_name = $(this).text();  
        edit_data(id, invoice_name, "invoice_name");  
    });  
    $(document).on('blur', '.invoice_dist', function(){  
        var id = $(this).data("id2");  
        var invoice_dist = $(this).text();  
        edit_data(id, invoice_dist, "invoice_dist");  
    });
	
    $(document).on('blur', '.invoice_date', function(){
        var id = $(this).data("id1");
        //var invoice_purchase_date = $(this).data("selectedValue");
        var invoice_date = $(this).val();
        //var invoice_purchase_date = $(this).data("invoice_purchase_date");
		console.log($(this));
		//console.log($(...)[0]);

		//alert(typeof(invoice_purchase_date));
		//alert(invoice_purchase_date);
        edit_data(id, invoice_date, "invoice_date");  
    });  
    	
    $(document).on('blur', '.invoice_purchase_date', function(){
        var id = $(this).data("id4");
        //var invoice_purchase_date = $(this).data("selectedValue");
        var invoice_purchase_date = $(this).val();
        //var invoice_purchase_date = $(this).data("invoice_purchase_date");
		console.log($(this));
		//console.log($(...)[0]);

		//alert(typeof(invoice_purchase_date));
		//alert(invoice_purchase_date);
        edit_data(id, invoice_purchase_date, "invoice_purchase_date");  
    });  
    $(document).on('blur', '.invoice_comments', function(){
        var id = $(this).data("nid5");  
        //alert($(this).data("id5"));  
        var invoice_comments = $(this).text();
		console.log($(this));
		console.log('invoice_comments is ' + invoice_comments);		
        edit_data(id, invoice_comments, "invoice_comments");  
    });
    $(document).on('blur', '.invoice_total', function(){
        var id = $(this).data("nid5");  
        //alert($(this).data("id5"));  
        var invoice_total = $(this).text();
		console.log($(this));
		console.log('invoice_total is ' + invoice_total);		
        edit_data(id, invoice_total, "invoice_total");  
    });
    $(document).on('blur', '.invoice_payment', function(){
        var id = $(this).data("id1");  
        //alert($(this).data("id5"));  
        var invoice_payment = $(this).text();
		console.log($(this));
		console.log('invoice_payment is ' + invoice_payment);		
        edit_data(id, invoice_payment, "invoice_payment");  
    });
        $(document).on('blur', '.invoice_shipping', function(){
        var id = $(this).data("nid5");  
        //alert($(this).data("id5"));  
        var invoice_shipping = $(this).text();
		console.log($(this));
		console.log('invoice_shipping is ' + invoice_shipping);		
        edit_data(id, invoice_shipping, "invoice_shipping");  
    });
    $(document).on('click', '.btn_delete', function(){
        var id=$(this).data("id3");  
        if(confirm("Are you sure you want to delete this?"))  
        {
            $.ajax({
                url:"invoicehistoryJS_delete.php",  
                method:"POST",  
                data:{id:id},  
                dataType:"text",  
                success:function(data){
                    alert(data);  
                    fetch_data();  
                }
            });  
        }
    });
	
});


</script>