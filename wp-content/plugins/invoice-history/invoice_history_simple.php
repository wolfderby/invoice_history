<?php 

// echo 'hi ';

global $wpdb;

$sql="select info.invoice, info.qty, info.quan_sold, info.cost, sum(info.cost), sum(info.order_cogs) as cogs, sum(instockcost) as cost_on_hand
from (
	select postdata.ID as pID, invoicedata.ID as iID, orderproductdata.product_id as opID, productstockqty.qty as qty, postdata.post_title, invoicedata.invoice as invoice, sum(orderproductdata.quan_sold) as quan_sold, costdata.cost as cost, costdata.cost * quan_sold as order_cogs, costdata.cost * qty as instockcost
	from ( #get products
		select wmp.ID, wmp.post_title
			from wp1m_posts wmp 
			where wmp.post_type=\"product\"
		 ) as postdata
	left join # add invoice_name as invoice
		(		select p.ID, p.post_content, p.post_status, pm.meta_value as invoice
				from wp1m_posts p
				join wp1m_postmeta pm
					on p.ID = pm.post_id
				where p.post_type = \"product\" and pm.meta_key = \"invoice_name\"
		) as invoicedata
			on postdata.ID = invoicedata.ID
	left join # add qty_on_hand
		(
			select pml.product_id, pml.stock_quantity as qty
			from wp1m_wc_product_meta_lookup pml
		) as productstockqty
		on postdata.ID = productstockqty.product_id
	left join # add quan_sold
		(
			select opl.product_id, opl.product_qty as quan_sold
			from wp1m_wc_order_product_lookup opl
		) as orderproductdata
		on postdata.ID = orderproductdata.product_id
	left join ( #add cost
		select pm.post_id, pm.meta_value as cost
			from wp1m_postmeta pm
			where pm.meta_key = \"cost\"
			) as costdata
				on postdata.ID = costdata.post_id
	group by postdata.ID
    ) as info
    group by info.invoice";
    ?>
    <html>
        <head>
        <!-- //cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css
        //cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js -->
        
        <!-- jquery -->
        <!-- <script src="https://code.jquery.com/jquery-latest.min.js"></script> -->
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        
        <!-- datatables css -->
        <!-- non min -->
        <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css"> -->
        <!-- min                                 https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
        <!-- datatables js -->
            <!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script> -->
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
            
            <!--call datatables -->
        <script type="text/javascript">
        $(document).ready( function () {
            $('#the_table').DataTable();
        } );

        </script>
        </head>
        <body>
    <h1>This is a simple invoice history page</h1>
<?php 

$results=$wpdb->get_results($sql);

?>
<!-- <table id="the_table" class="display">  style="width:100%"> -->
<table id="the_table" class="display" style="width:100%">
        <thead>
        <tr>
            <th>invoice</th>
            <th>qty</th>
            <th>quan_sold</th>
            <th>cost</th>
            <th>cogs</th>
            <th>cost_on_hand</th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($results as $result){ ?>
    <!-- <pre> -->
<?php //print_r($result); ?>
    <!-- </pre> -->
<tr>
    <td class="invoice"> <?php echo $result->invoice; ?> </td>
    <td class="qty"> <?php echo $result->qty; ?> </td>
    <td class="quan_sold"> <?php echo $result->quan_sold; ?> </td>
    <td class="cost"> <?php echo $result->cost; ?> </td>
    
    <td class="cogs"> <?php echo $result->cogs; ?> </td>
    <td class="cost_on_hand"> <?php echo $result->cost_on_hand; ?> </td>
</tr>
<?php } ?>
</tbody>
</table>



</body>
