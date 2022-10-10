<?php
/**
 * @bucknelius 
 *  SELECT * FROM `orders` WHERE `payment_module_code` = 'cash' AND `date_purchased` LIKE '%2018-08-07%'
 */

  //require('includes/application_top.php');
  //require(DIR_WS_CLASSES . 'currencies.php');

  error_reporting(-1);
  
  //define('STRICT_ERROR_REPORTING', true);
  define('NUMBER_OF_ROWS', 100);
  
  //$currencies = new currencies();
  $invoice_totals = array();
  $invoice_cogs_array = array();  
  $messageIdent = 0;
  $sessionMessageIdent = 1; //something not messageIdent;

if(!empty($_POST['options'])){
}elseif(!empty($_POST)){
	$messageIdent = md5($_POST['drawerdate_field'] . $_POST['hundos_field'] . $_POST['twenties_field'] . $_POST['comments_field']);
	$sessionMessageIdent = isset($_SESSION['messageIdent'])?$_SESSION['messageIdent']:'';
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
}																					   
if(($messageIdent!=$sessionMessageIdent) && isset($_POST) && isset($_POST['drawerdate_field']) && ($_POST['drawerdate_field'] > 1)){		//if its different:
		$_SESSION['messageIdent'] = $messageIdent;
			//$currencies = new currencies();
			$drawerdate = $_POST['drawerdate_field'];
			$drawerdate = str_replace("T", " ", $_POST['drawerdate_field']); //datetime-local 		
			$updatesql = "INSERT INTO `drawers` 
			(`id`, `drawertype`, `drawerdate`, `hundos`, `fifties`, `twenties`, `tens`, `fives`, `twos`, `ones`, `qrolls`, 
										   
			`drolls`, `nrolls`, `prolls`, `odcoins`, `hdcoins`, `qcoins`, `dcoins`, `ncoins`, `pcoins`, `total`, `initials`,  `comments`)
									
			VALUES (NULL,'" .  
                    $_POST['drawertype_field'] . "','" .  $drawerdate . "',' " .  
                    $_POST['hundos_field'] . "','" .  
                    $_POST['fifties_field'] . "','" .  
                    $_POST['twenties_field'] . "','" .  
                    $_POST['tens_field'] . "','" .  
                    $_POST['fives_field'] . "','" .  
                    $_POST['twos_field'] . "','" .  
                    $_POST['ones_field'] . "','" .  
                    $_POST['qrolls_field'] . "','" .  
                    $_POST['drolls_field'] . "','" .  
                    $_POST['nrolls_field'] . "','" .  
                    $_POST['prolls_field'] . "','" .  
                    $_POST['odcoins_field'] . "','" .  
                    $_POST['hdcoins_field'] . "','" .  
                    $_POST['qcoins_field'] . "','" .  
                    $_POST['dcoins_field'] . "','" .  
                    $_POST['ncoins_field'] . "','" .  
                    $_POST['pcoins_field'] . "','" .  
                    $_POST['total_field'] . "','" .  
                    $_POST['initials_field'] . "','" .  
                    $_POST['comments_field'] . "')";  //$_POST['total_field'] . ")"; //build db insert query
			
			if ($db->Execute($updatesql) == TRUE) { // triple = is identical to and of the same type
				echo "New record created successfully";
			} else {
				echo "Error: " . $updatesql . "<br>" . $con->error;}
			unset($_POST); //clear $_POST

	}/*elseif(!empty($_POST)){
		/*echo $messageIdent . '<br />' .
			 $sessionMessageIdent . '<br />' .
				'did not match';*/
		/*var_dump($messageIdent);
		echo '<br />';
		var_dump($sessionMessageIdent);
		echo '<br />messageIdent comparison ' . ($messageIdent!=$sessionMessageIdent) . '<br />';
		echo '!emptyt post ' . (!empty($_POST));
	}else{
		echo 'messageIdent weirdness';
	}*/
	global $db;

    if(!empty($_POST['options'])){
		/*echo '<pre>';
		print_r($_POST);
		echo '</pre>';*/
		//exit;
		if(isset($_POST['options']['date']['from'])){
			$date_from = str_replace('T', ' ', $_POST['options']['date']['from']);
			$date_to = str_replace('T', ' ', $_POST['options']['date']['to']);
		}
        //$date_to = $_POST['date']['to'];
		
        $sql_start = "SELECT *  
				FROM `drawers` ";

if(!empty($_POST['options']['date']['from'])){
	$sql_date = 
				"WHERE `drawerdate` 
				BETWEEN '" . $date_from . "'
				AND '" . $date_to . "' AND ";
		}else{
			$sql_date = " WHERE ";
		}
		if(isset($_POST['options']['comments'])){
			$sql_comments = " comments LIKE '%" . $_POST['options']['comments'] . "%' ";
		}

			$sql_end = "ORDER BY `drawerdate` DESC;";

			$sql = $sql_start . $sql_date . $sql_comments . $sql_end;
        /*$sql = "SELECT *  
		FROM `drawers` 
		WHERE `drawerdate` 
		BETWEEN " . $date_from . " 
		AND " . $date_to . "                 
		AND `total` > 0 
		AND `comments` 
		LIKE '%pay%' 
		ORDER BY `drawertype` ASC, 
                `total` DESC;"*/

				echo $sql;
				//exit;
        
    }else{

	$sql =  'SELECT * FROM `drawers` ORDER By `drawerdate` DESC'; //	LIMIT ' . NUMBER_OF_ROWS;

    }
	$drawercountsarray = array();
	$result = $db->Execute($sql);

	
	
	/*echo '<pre>';
	echo '$result is ' . print_r($result);
	echo '</pre>';*/ //$result is an object
	
	if ($result->RecordCount() > 0) //within foreach($exp_by_ln as $exl)
		  {
			  $dateSortOrder = 0;
			  $opencloseindex = 0;
			  $lastopenclose = 0;						
			  $negations = 0;
			  $contributions = 0;
			  $notOpenCloseCount = 1; 
//####################################################################################################################################################################################################################
//########FIRST LOOP##################################################################################################################################################################################################
//####################################################################################################################################################################################################################
				  // Fetch one and one row
				   //while ($row=mysqli_fetch_assoc($result)) //runs while the expression evaluates to TRUE //works w/ old db connection
				   //calculates...
				   //  $negations
				   //  $contributions
				   while (!$result->EOF) //runs while the expression evaluates to TRUE
					{
						/*echo '<pre>';
						print_r($result);
						echo '</pre>';*/						
	  
						$dateSortOrder++;
																			
						$drawercountsarray[$dateSortOrder]['id'] = $result->fields['id'];
						$drawercountsarray[$dateSortOrder]['dateSortOrderISresultloopid'] = $dateSortOrder;
						$drawercountsarray[$dateSortOrder]['openclosearrayindex'] = ''; //initialize here for organization
						$drawercountsarray[$dateSortOrder]['lastOpenCloseIndex2ndloop'] = ''; //initialize here for organization  //lastOpenCloseIndex
						$drawercountsarray[$dateSortOrder]['opencloseindex2ndloop'] = ''; //initialize here for organization  //opencloseindex2ndloop
						$drawercountsarray[$dateSortOrder]['drawertype'] = $result->fields['drawertype'];
						$drawercountsarray[$dateSortOrder]['drawerdate'] = $result->fields['drawerdate'];
						$drawercountsarray[$dateSortOrder]['hundos'] = $result->fields['hundos'];
																		 
						$drawercountsarray[$dateSortOrder]['fifties'] = $result->fields['fifties'];
						$drawercountsarray[$dateSortOrder]['twenties'] = $result->fields['twenties'];
						$drawercountsarray[$dateSortOrder]['tens'] = $result->fields['tens'];
						$drawercountsarray[$dateSortOrder]['fives'] = $result->fields['fives'];
						$drawercountsarray[$dateSortOrder]['twos'] = $result->fields['twos'];
						$drawercountsarray[$dateSortOrder]['ones'] = $result->fields['ones'];
						$drawercountsarray[$dateSortOrder]['qrolls'] = $result->fields['qrolls'];
						$drawercountsarray[$dateSortOrder]['drolls'] = $result->fields['drolls'];
						$drawercountsarray[$dateSortOrder]['nrolls'] = $result->fields['nrolls'];
						$drawercountsarray[$dateSortOrder]['prolls'] = $result->fields['prolls'];
						$drawercountsarray[$dateSortOrder]['odcoins'] = $result->fields['odcoins'];
						$drawercountsarray[$dateSortOrder]['hdcoins'] = $result->fields['hdcoins'];
						$drawercountsarray[$dateSortOrder]['qcoins'] = $result->fields['qcoins'];
						$drawercountsarray[$dateSortOrder]['dcoins'] = $result->fields['dcoins'];
						$drawercountsarray[$dateSortOrder]['ncoins'] = $result->fields['ncoins'];
						$drawercountsarray[$dateSortOrder]['pcoins'] = $result->fields['pcoins'];
						$drawercountsarray[$dateSortOrder]['total'] = $result->fields['total'];
						$drawercountsarray[$dateSortOrder]['initials'] = $result->fields['initials'];
						$drawercountsarray[$dateSortOrder]['comments'] = $result->fields['comments']; //
						$drawercountsarray[$dateSortOrder]['lastopenclose'] = ''; //
						//$drawercountsarray[$dateSortOrder-1]['negations'] = $negations; //
						//$drawercountsarray[$dateSortOrder-1]['contributions'] = $contributions; //
						$drawercountsarray[$dateSortOrder]['variance'] = ''; // place holder $variance not defined yet
					if($drawercountsarray[$dateSortOrder]['drawertype'] == 'open' || $drawercountsarray[$dateSortOrder]['drawertype'] == 'close'){
						//$openclosearray[] = $result->fields['total'];
						//$openclosearray = $dateSortOrder;
						$opencloseindex++;
						$openclosearray[$opencloseindex]['total'] = $result->fields['total'];
						$openclosearray[$opencloseindex]['drawerdate'] = $result->fields['drawerdate'];
						$openclosearray[$opencloseindex]['opencloseindex'] = $opencloseindex-1;
						$openclosearray[$opencloseindex]['dateSortOrder'] = $dateSortOrder;
						$negations = 0; //reset here
						$contributions = 0; //reset here
						$notOpenCloseCount = 0;
						//$opencloseindex--;
						
					}elseif($result->fields['drawertype'] == 'ownerscontribution' || $result->fields['drawertype'] == 'cashsales' || $result->fields['drawertype'] == 'qbcashsales' || $result->fields['drawertype'] == 'hwcashsales' || $result->fields['drawertype'] == 'other'){
						$notOpenCloseCount++;
						$contributions += $result->fields['total'];
						$drawercountsarray[(($dateSortOrder-$notOpenCloseCount) > 1) ? $dateSortOrder-$notOpenCloseCount : 1]['contributions'] = $contributions; //ternary logic keeps from 0 or negative index if multiple things before close count
						$drawercountsarray[(($dateSortOrder-$notOpenCloseCount) > 1) ? $dateSortOrder-$notOpenCloseCount : 1]['negations'] = $negations;
					}elseif($result->fields['drawertype'] == 'drawerpurchase' || $result->fields['drawertype'] == 'ownersdraw' || $result->fields['drawertype'] == 'deposit' || $result->fields['drawertype'] == 'refund'){
						$notOpenCloseCount++;
						$negations += $result->fields['total'];
						$drawercountsarray[(($dateSortOrder-$notOpenCloseCount) > 1) ? $dateSortOrder-$notOpenCloseCount : 1]['negations'] = $negations;
						$drawercountsarray[(($dateSortOrder-$notOpenCloseCount) > 1) ? $dateSortOrder-$notOpenCloseCount : 1]['contributions'] = $contributions;
					}else{
						$drawercountsarray[$dateSortOrder]['flagdat'] = 'flag-ated';
						//$drawercountsarray[$dateSortOrder-1]['contributions'] = $contributions;
						//$drawercountsarray[$dateSortOrder-1]['negations'] = $negations;
					}
					
						$drawercountsarray[$dateSortOrder]['openclosearrayindex'] = $opencloseindex;
						//echo 'turd ' . $i++ . '</br>';						
						$result -> MoveNext (); //new for zen-cart db // Does not... //works w/ old db connection
					} //close while
		  } else {
			echo '<p>Sorry, nothing found.</p>';
		}		
								
							  
				  
?>

<!-- <!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN"> -->
<html <?php // echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php // echo CHARSET; ?>">
<title><?php echo 'Invoice History'; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript">
function totalit(){
	//alert("hi there");
	//alert((document.getElementById("hundos").value*100) + " is type " + typeof (reduce(document.getElementById("hundos"))));
	totalval = parseFloat(0);
	//alert("1 type of " + typeof(totalval));
	totalval += parseFloat(document.getElementById("hundos").value*100 || 0); // ? parseFloat(document.getElementById("hundos").value)*100 : NaN);
	//alert("2 type of " + typeof(totalval));
	totalval += parseFloat(document.getElementById("fifties").value*50 || 0); // ? parseFloat(document.getElementById("fifties").value)*50 : NaN); //+
	//alert("3 type of " + typeof(totalval));
	totalval += parseFloat(document.getElementById("twenties").value*20 || 0);
	totalval += parseFloat(document.getElementById("tens").value*10 || 0);
	totalval += parseFloat(document.getElementById("fives").value*5 || 0);
	totalval += parseFloat(document.getElementById("twos").value*2 || 0);
	totalval += parseFloat(document.getElementById("ones").value || 0);
	totalval += parseFloat(document.getElementById("qrolls").value*10 || 0);
	totalval += parseFloat(document.getElementById("drolls").value*5 || 0);
	totalval += parseFloat(document.getElementById("nrolls").value*2 || 0);
	totalval += parseFloat(document.getElementById("prolls").value*.5 || 0);
	totalval += parseFloat(document.getElementById("odcoins").value || 0);
	totalval += parseFloat(document.getElementById("hdcoins").value*.5 || 0);
	totalval += parseFloat(document.getElementById("qcoins").value*.25 || 0);
	totalval += parseFloat(document.getElementById("dcoins").value*.10 || 0);
	totalval += parseFloat(document.getElementById("ncoins").value*.05 || 0);
	totalval += parseFloat(document.getElementById("pcoins").value*.01 || 0); 
	//totalval = hundosval+fiftiesval;
	document.getElementById("total").value = totalval.toFixed(2);
											  
										 
							 
						  
											  
				 
												   
				   
}
</script>
<style>
.alerts-border {
    border: 1px #ff0000 solid;
    
    animation: blink 1s;
    animation-iteration-count: 10;
}

@keyframes blink { 50% { border-color:#fff ; }  }
</style>
</head>
<body onLoad="init()">
<!-- header //-->
<?php // require(DIR_WS_INCLUDES . 'header.php'); ?>
<?php require('includes/header.php'); ?>
<?php require('zen_to_woo_functions.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<p id="finalcountdown" align="center"></p>

<form action="" method="post">

<?php
echo '<input type="datetime-local" name= "options[date][from]">';
echo '<input type="datetime-local" name= "options[date][to]">';
echo 'comments: <input type="text" name= "options[comments]" style="width:300px">';
?>
<button type="submit">Update Date Range</button>
</form>
<form action="" method="post">
<table class="table" name="table2" border="1" style="width: 3500px;" cellspacing="0" cellpadding="0">
<tbody>

<colgroup>
 <col width="20">
 <col width="30">
 <col width="10">
</colgroup>

<!-- <script>
// Set the date we're counting down to
										   
															  

	var countDownDate = new Date();
	countDownDate.setSeconds(countDownDate.getSeconds() + 900);

																				 

	// Update the count down every 1 second
	var x = setInterval(function() {

		// Get todays date and time
		var now = new Date().getTime();		
  
		// Find the distance between now and the count down date
		var distance = countDownDate - now;		
  
		// Time calculations for days, hours, minutes and seconds
		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);		
  
		// Output the result in an element with id="finalcountdown"
		//document.getElementById("finalcountdown").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
		document.getElementById("finalcountdown").innerHTML = "time you have to count the drawer " + minutes + "m " + seconds + "s ";		
  
		// If the count down is over, write some text 
		if (distance < 0) {
			clearInterval(x);
			document.getElementById("finalcountdown").innerHTML = "SESSION EXPIRED AFTER 15 minutes - OPEN ADMIN IN ANOTHER TAB BEFORE SUBMITTING COUNTS";
		}
	}, 1000);
</script> -->
<script>
$(document).keydown(
    function(e)
    {    
		//select all text on over keys
	        $(function () {
            var focusedElement;
            $(document).on('focus', 'input', function () {
                if (focusedElement == this) return; //already focused, return so user can now place cursor at specific point in input.
                focusedElement = this;
                setTimeout(function () { focusedElement.select(); }, 50); //select all text in any field on focus for easy re-entry. Delay sightly to allow focus to "stick" before selecting.
            });
        });	
 
        if (e.keyCode == 39) {      
			//alert("smell my rubber fingers");
			//alert().nextAll('input');            
			
			$(".move:focus").parent().next().find('input').focus(); 
   

        }
        if (e.keyCode == 37) {      
            $(".move:focus").parent().prev().find('input').focus();

        }
    }
);
</script>

  <tr>
	 <td class="pageHeading" valign="right" align="center" colspan="3"><?php echo ' '; ?></td>
     <td class="pageHeading" valign="right" align="center" colspan="7"><?php echo 'Bills'; ?></td>
	 <td class="pageHeading" valign="right" align="center" colspan="4"><?php echo 'Rolls'; ?></td>
	 <td class="pageHeading" valign="right" align="center" colspan="6"><?php echo 'Coins'; ?></td>
  </tr>
  <tr class="fixed_headers" >
	 <!--<td class="id"></td>--><!-- id is the id from the db value id, sortid is the value from the loop sorted by date-->
	 <td class="sortid"></td>
	 <div class="header">
	 <td class="drawertype alerts">Type</td>
	 <td class="pageHeading date" style="width: 151px;"> Date</span></td>
	 <td class="pageHeading bills" style="text-align: center; width: 4%;">2's</td>
	 <td class="pageHeading bills" style="text-align: center; width: 5%;">100's</td>
	 <td class="pageHeading bills" style="text-align: center; width: 4%;">50's</td>
	 <td class="pageHeading bills" style="text-align: center; width: 4%;">20's</td>
	 <td class="pageHeading bills" style="text-align: center; width: 4%;">10's</td>
	 <td class="pageHeading bills" style="text-align: center; width: 4%;">5's</td>
	 <td class="pageHeading bills" style="text-align: center; width: 4%;">1's</td>
	 <td class="rolls">Q-rolls</td>
	 <td class="" style="text-align: center; width: 1%;">D-rolls</td>
	 <td class="" style="text-align: center; width: 1%;">N-rolls</td>
	 <td class="" style="text-align: center; width: 1%;">P-rolls</td>
	 <td class="" style="text-align: center; width: 1%;">$1-coins</td>
	 <td class="" style="text-align: center; width: 1%;">$.5-coins</td>
	 <td class="" style="font-size:12px; text-align: center; width: 1%;">Quarter-coins</td>
	 <td class="" style="text-align: center; width: 1%;">Dimes</td>
	 <td class="" style="font-size:12px; text-align: center; width: 1%;">Nickles</td>
	 <td class="" style="font-size:12px; text-align: center; width: 1%;">Pennies</td>
	 <td class="" style="text-align: center; width: 1%;">Total</td>
	 <td class="" style="text-align: center; width: 1%;">Initials</td>
	 <td class="" style="text-align: center; width: 1%;">Comments</td>
	 <td class="" style="text-align: center; width: 115px;">Calc'ed Variance</td>
	 <td class="" style="text-align: center; ">Base</td>
	 <td class="" style="text-align: center; ">Sales</td>
	 <td class="" style="text-align: center; ">Contri butions</td>
	 <td class="" style="text-align: center; ">Negations</td>
	 </div>
  </tr>

  <tr>
<?php	
	/*echo '<h3>Goals for today: </h3><br />';
	
	echo 'do a start/open count <br />';
	echo 'add qbcashsales... Go to Quickbooks > Reports > Memorized Reports > Day Totals Undeposited Funds Dec \'19 - Jan. \'19 <br />';
	echo 'add hand-written cashsales <br />';
	echo 'do a close count <br /><br />';
	echo 'make sure cash zen sales are coming out of variance <br />'
			. 'add qbcashsales and hwcashsales to drop down<br / >'
			. ' ...to array <br /> '
			. ' ... <br /> '
			. ' ... <br />'
			. ' ... ';*/
			
//##################################################################################################################################################################
// PRINT INPUT FIELDS
//##################################################################################################################################################################
$drawertypeoptions = array(
	array('id' => 'open', 'text' => 'open'),
	array('id' => 'close', 'text' => 'close'),
	array('id' => 'deposit', 'text' => 'deposit'),
	array('id' => 'drawerpurchase', 'text' => 'drawer purchase'),
	array('id' => 'refund', 'text' => 'refund'),
	array('id' => 'ownerscontribution', 'text' => 'Owner\'s Contribution'),
	array('id' => 'ownersdraw', 'text' => 'Owner\'s Draw'),
	array('id' => 'cashsales', 'text' => 'Cash Sales'),
	array('id' => 'hwcashsales', 'text' => 'Handwritten Cash Sales'),
	array('id' => 'qbcashsales', 'text' => 'Quickbooks Cash Sales'),
	array('id' => 'other', 'text' => 'other (neg. num=cash came out)')
	);
			 
			   
	$timestamp = date("Y-m-d\TH:i:s");
	echo '<tr>';
	echo '<td class="id"></td>';
	echo '<td class="drawertype">' . zen_draw_pull_down_menu('drawertype_field', $drawertypeoptions, '0', 'class="alerts-border"') . '</td>';
	echo '<td class="date dateinputcell"><input class="date dateinput" type="datetime-local" name= "drawerdate_field" value="' . $timestamp . '"></td>';
	echo '<td class="numinputs bills"><input type="text" name= "twos_field" onkeyup=totalit(); class="move numinputs billsinput" id="twos" length="8" ></td>';
	echo '<td class="numinputs bills"><input type="text" name= "hundos_field" onkeyup=totalit(); class="move numinputs billsinput" id="hundos" length="8" ></td>';
	echo '<td class="numinputs bills"><input type="text" name= "fifties_field" onkeyup=totalit(); class="move numinputs billsinput" id="fifties" length="8" ></td>';
	echo '<td class="numinputs bills"><input type="text" name= "twenties_field" onkeyup=totalit(); class="move numinputs billsinput" id="twenties" length="8" ></td>';
	echo '<td class="numinputs bills"><input type="text" name= "tens_field" onkeyup=totalit(); class="move numinputs billsinput" id="tens" length="8" ></td>';
	echo '<td class="numinputs bills"><input type="text" name= "fives_field" onkeyup=totalit(); class="move numinputs billsinput" id="fives" length="8" ></td>';
	echo '<td class="numinputs bills"><input type="text" name= "ones_field" onkeyup=totalit(); class="move numinputs billsinput" id="ones" length="8" ></td>';
	echo '<td class="numinputs coinrolls"><input type="text" name= "qrolls_field" onkeyup=totalit(); class="move numinputs rollsinput" id="qrolls" length="8" ></td>';
	echo '<td class="numinputs coinrolls"><input type="text" name= "drolls_field" onkeyup=totalit(); class="move numinputs rollsinput" id="drolls" length="8" ></td>';
	echo '<td class="numinputs coinrolls"><input type="text" name= "nrolls_field" onkeyup=totalit(); class="move numinputs rollsinput" id="nrolls" length="8" ></td>';
	echo '<td class="numinputs coinrolls"><input type="text" name= "prolls_field" onkeyup=totalit(); class="move numinputs rollsinput" id="prolls" length="8" ></td>';
	echo '<td class="numinputs coins"><input type="text" name= "odcoins_field" onkeyup=totalit(); class="move numinputs coinsinput" id="odcoins" length="8" ></td>';
	echo '<td class="numinputs coins"><input type="text" name= "hdcoins_field" onkeyup=totalit(); class="move numinputs coinsinput" id="hdcoins" length="8" ></td>';
	echo '<td class="numinputs coins"><input type="text" name= "qcoins_field" onkeyup=totalit(); class="move numinputs coinsinput" id="qcoins" length="8" ></td>';
	echo '<td class="numinputs coins"><input type="text" name= "dcoins_field" onkeyup=totalit(); class="move numinputs coinsinput" id="dcoins" length="8" ></td>';
	echo '<td class="numinputs coins"><input type="text" name= "ncoins_field" onkeyup=totalit(); class="move numinputs coinsinput" id="ncoins" length="8" ></td>';
	echo '<td class="numinputs coins"><input type="text" name= "pcoins_field" onkeyup=totalit(); class="move numinputs coinsinput" id="pcoins" length="8" ></td>';
	echo '<td><input type="text" name= "total_field" class="move" id="total" length="8" required ></td>';
	echo '<td><input type="text" name= "initials_field" class="move" length="8" required ></td>';
	echo '<td><input type="text" name= "comments_field" class="move" length="8"" </td>';
	echo '<td><div class="buttonRow forward"><input type="submit" name="SubmitButton"/></div></td></form>';
	echo '</tr>';
	


//##################################################################################################################################################################
// LOOP THROUGH EACH ROW OF RESULTS AS KEY => VALUE
// build up drawercountsarray after openclosearray has been built
//what's the point of this?
////assigns lastopenclose
////
//##################################################################################################################################################################
/*echo '<pre>';
								  
print_r($openclosearray);
echo '</pre>';*/
	$lastOpenCloseIndex = 0;
	$nextOpenCloseIndex = 1;
	//$contributions = 0; //calculated in first loop
	//$negations = 0; //calculated in first loop
	$opencloseindex = 0;
	//$drawercountsarray = array_reverse($drawercountsarray);  //IF un-commented change +1 to -1
	foreach($drawercountsarray as $key => $v){ //https://stackoverflow.com/questions/28216877/add-values-to-an-array-inside-a-foreach-loop

		$lastopenclose = $v['total'];
		//$opencloseindex = 0;
	if($v['drawertype'] == 'open' || $v['drawertype'] == 'close'){
		//$openclosearray[] = $v['total'];
		//set lastopenclose and lastopenclosedate
		if(isset($openclosearray[$drawercountsarray[$key]['openclosearrayindex']+1])){ //if index of openclosearray won't be out of bounds by adding one (ie last element in array)
			$lastopenclose     = $openclosearray[$drawercountsarray[$key]['openclosearrayindex']]['total'];
			$lastopenclosedate = $openclosearray[$drawercountsarray[$key]['openclosearrayindex']+1]['drawerdate'];
		}else{
			$lastopenclose     = $openclosearray[$drawercountsarray[$key]['openclosearrayindex']]['total']; //it is the last query
			$lastopenclosedate = $openclosearray[$drawercountsarray[$key]['openclosearrayindex']]['drawerdate']; //it is the last query
		}
		$drawercountsarray[$key]['lastopenclosedate'] = $lastopenclosedate;
		$drawercountsarray[$key]['lastOpenCloseIndex2ndloop'] = $lastOpenCloseIndex;
		$drawercountsarray[$key]['opencloseindex2ndloop'] = $opencloseindex;
		$drawercountsarray[$key]['lastopenclose'] = (!empty($openclosearray[$drawercountsarray[$key]['openclosearrayindex']+1]['total']) ? $openclosearray[$drawercountsarray[$key]['openclosearrayindex']+1]['total'] : ''); //will throw Undefined offset: on last loop without ternary op
		//$drawercountsarray[$key]['lastopenclose'] = 100;
		$drawercountsarray[$key]['nextopenclose'] = (!empty($openclosearray[$drawercountsarray[$key]['openclosearrayindex']+1]['total']) ? $openclosearray[$drawercountsarray[$key]['openclosearrayindex']+1]['total'] : ''); //will throw Undefined offset: on last loop
		$sd = $drawercountsarray[$key]['drawerdate'];
		$ed = $drawercountsarray[$key]['lastopenclosedate'];
		$sql = "SELECT DISTINCT o.orders_id, o.date_purchased, SUM(o.order_total) AS rangecashordertotalssum 
					from orders o 
					WHERE o.date_purchased >= '" . date("Y-m-d H:i:s", strtotime($ed)) . "' AND o.date_purchased < '" . date("Y-m-d H:i:s", strtotime($sd)) . "' AND o.payment_module_code = 'cash'";
		//removed LEFT JOIN orders_status_history osh on o.orders_id = osh.orders_id from 3rd line of SQL query
		$result = $db->Execute($sql);
		/*echo '<pre>';
		print_r($db);
		echo '</pre>';*/
		if($key > 0){ 
			$drawercountsarray[$key]['rangeordersum'] = $result->fields['rangecashordertotalssum'];			
		}else{
			echo ' key was not greater than zero <br />';
		}
  
		

		//increment counters
		$lastOpenCloseIndex++;
		$opencloseindex++;	
	} // close if open close
	
		//$drawercountsarray[$key]['lastOpenCloseIndex2ndloop'] = $lastOpenCloseIndex;
	} // end foreach loop*/
				
  
	
		//echo '<pre>$v is ' . $i;
		//var_dump($v);
		//echo '</pre>';

		/*if(isset($drawercountsarray[0]))
			unset($drawercountsarray[0]); //unsets [0] if created by calculated index.  (if code not fixed then will create a row in the output table like "wednesday" with no values*/

		
	//#########################################################################################################################################
	// OUTPUT HTML TABLE FOR EACH DRAWER COUNT/ADJUSTMENT
	//#########################################################################################################################################
	
	foreach($drawercountsarray as $drawerday){
	//foreach($drawercountsarray as $drawerday => $value){ //doesn't work
	//foreach($drawercountsarray as $drawercountday){ //how it was
		
		//first loop sets lastopenclose
		//second loop sets sales data in array
		//third loop calculates variance and outputs html table
		
		//$variance = $drawerday['total'] + $drawerday['negations'] - $drawerday['contributions'] - $drawerday['rangeordersum'] - $lastopenclose;
		
		//$variance = $drawerday['total'] + $drawerday['negations'] - $drawerday['contributions'] - $drawerday['rangeordersum'] - $drawerday['lastopenclose'];
		$variance = (
			(!empty($drawerday['total']) 		 ? $drawerday['total'] 			: 0) + 
			(!empty($drawerday['negations']) 	 ? $drawerday['negations'] 		: 0) - 
			(!empty($drawerday['contributions']) ? $drawerday['contributions'] : 0) - 
			(!empty($drawerday['rangeordersum']) ? $drawerday['rangeordersum'] : 0) - 			
			(!empty($drawerday['lastopenclose']) ? $drawerday['lastopenclose'] : 0));
		
		//$drawercountsarray['varmath'] = $drawerday['total'] . ' + ' . $drawerday['negations'] . ' - ' . $drawerday['contributions'] . ' - ' . $drawerday['rangeordersum'] . ' - ' . $drawerday['lastopenclose'] . ' for ' . $drawerday;
		
		
		$drawercountsarray['varmath'] = (
			(!empty($drawerday['total']) ? $drawerday['total'] : 0) . ' + ' . 
			(!empty($drawerday['negations']) ? $drawerday['negations'] : 0) . ' - ' . 
			(!empty($drawerday['contributions']) ? $drawerday['contributions'] : 0) . ' - ' . 
			(!empty($drawerday['rangeordersum']) ? $drawerday['rangeordersum'] : 0) . ' - ' . 
			(!empty($drawerday['lastopenclose']) ? $drawerday['lastopenclose'] : 0) . ' for ' . 
			(!empty($drawerday)));
		
		//$varmath 					  = '(' . $drawerday['total'] . ' + ' . $drawerday['negations'] . ' - ' . $drawerday['contributions'] . ' - ' . $drawerday['rangeordersum'] . ' - ' . $drawerday['lastopenclose'] . ')' ; //. ' for ' . $drawerday;
		$varmath 					  = '(' . 
		(!empty($drawerday['total']) ? $drawerday['total'] : 0) . ' + ' . 
		(!empty($drawerday['negations']) ? $drawerday['negations'] : 0) . ' - ' . 
		(!empty($drawerday['contributions']) ? $drawerday['contributions'] : 0) . ' - ' . 
		(!empty($drawerday['rangeordersum']) ? $drawerday['rangeordersum'] : 0) . ' - ' . 
		(!empty($drawerday['lastopenclose']) ? $drawerday['lastopenclose'] : 0) . ')' ; //. ' for ' . $drawerday;
		
		$drawercountsarray['variance'] = $variance;
		
		echo '<tr>';
		echo '<td class="id">' . $drawerday['dateSortOrderISresultloopid'] . '</td>';
																
		echo '<td class="drawertype">' . $drawerday['drawertype']. '</td>';
		$dayofweek = date('w', strtotime($drawerday['drawerdate']));
		$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday');
		echo '<td class="date"> <div class="date" style="text-align: left; width:50px;">' . $days[$dayofweek] . '</div>           <div class="date"> ' . zen_datetime_long($drawerday['drawerdate']). '</div></td>';
		//$dayofweek = date('w', strtotime($drawerday['drawerdate']));
		//$dayofweek    = date('Y-m-d', strtotime(($day - $dayofweek).' day', strtotime($date)));
		echo '<td class="billnums nums" id="two">' . $drawerday['twos']. '</td>';
		echo '<td class="billnums nums" id="hun">' . $drawerday['hundos']. '</td>';
		echo '<td class="billnums nums" id="fif">' . $drawerday['fifties']. '</td>';
		echo '<td class="billnums nums" id="twen">' . $drawerday['twenties']. '</td>';
		echo '<td class="billnums nums" id="te">' . $drawerday['tens']. '</td>';
		echo '<td class="billnums nums" id="fiv">' . $drawerday['fives']. '</td>';
		echo '<td class="billnums nums" id="one">' . $drawerday['ones']. '</td>';
		echo '<td class="nums" id="qr">' . $drawerday['qrolls']. '</td>';
		echo '<td class="nums" id="dr">' . $drawerday['drolls']. '</td>';
		echo '<td class="nums" id="nr">' . $drawerday['nrolls']. '</td>';
		echo '<td class="nums" id="pr">' . $drawerday['prolls']. '</td>';
		echo '<td class="coinnums nums" id="od">' . $drawerday['odcoins']. '</td>';
		echo '<td class="coinnums nums" id="hd">' . $drawerday['hdcoins']. '</td>';
		echo '<td class="coinnums nums" id="qc">' . $drawerday['qcoins']. '</td>';
		echo '<td class="coinnums nums" id="dc">' . $drawerday['dcoins']. '</td>';
		echo '<td class="coinnums nums" id="nc">' . $drawerday['ncoins']. '</td>';
		echo '<td class="coinnums nums" id="pc">' . $drawerday['pcoins']. '</td>';
		echo '<td id="tot">' . $drawerday['total']. '</td>';
		echo '<td id="initals">' . $drawerday['initials']. '</td>';
  
  
  
		echo '<td id="com">' . $drawerday['comments']. '</td>';
//		echo '<td id="variance" bordercolor = "white">';
	if($drawerday['drawertype'] == 'open' || $drawerday['drawertype'] == 'close'){
			/*echo '<div class="variance"> </div><div class="variance">' . sprintf("% 8.2f", $drawerday['total']) . ' t</div>' .				
				'<div class="variance"> - </div><div class="variance">' . sprintf("% 8.2f", $drawerday['negations']) .  ' neg</div>' .
				'<div class="variance"> + </div><div class="variance">' . sprintf("% 8.2f", $drawerday['contributions']) .  ' c</div>' .
				'<div class="variance"> - </div><div class="variance">' . sprintf("% 8.2f", $drawerday['rangeordersum']) .  ' r</div>' .
				'<div class="variance"> - </div><div class="variance">' . sprintf("% 8.2f", $drawerday['nextopenclose']) .  ' noc</div>' .*/
			//echo	'<div class="variance">' . sprintf("% 8.2f", $drawerday['variance']) . '<br />';	
			//echo	'<div class="variance">' . sprintf("% 8.2f", $variance) . '<br />';	
			
			if($variance > 1000){
				echo '<td id="variance"  style="background-color: rgba(0, 255, 0, 1);">';
				echo	'<div class="variance">' . sprintf("% 8.2f", $variance) . '<br />';	
				//echo	'<div class="variance">' . $varmath . '<br />';				
			}elseif(($variance) > 500){				
				echo '<td id="variance">';
				echo	'<div class="variance" style="background-color: rgba(0, 255, 0, 0.8);">' . $variance . '<br />';	
			}elseif(($variance) > 250){
				echo '<td id="variance">';
				//echo	'<div class="variance">' . sprintf("% 8.2f", $variance) . '<br />';	
				echo	'<div class="variance" style="background-color: rgba(0, 255, 0, 0.6);">' . $variance . '<br />';					
			}elseif(($variance) > 100){
				echo '<td id="variance">';
				echo	'<div class="variance" style="background-color: rgba(0, 255, 0, 0.4);">' . $variance . '<br />';				
			}elseif(($variance) > 25){
				echo '<td id="variance">';
				echo	'<div class="variance" style="background-color: rgba(0, 255, 0, 0.3);">' . $variance . '<br />';				
			}elseif(($variance) > 5){
				echo '<td id="variance">';
				echo	'<div class="variance" style="background-color: rgba(0, 255, 0, 0.2);">' . $variance . '<br />';	
			}elseif(($variance) > 0){
				echo '<td id="variance">';
				echo	'<div class="variance" style="background-color: rgba(0, 255, 0, 0.1);">' . $variance . '<br />';	
			}elseif(($variance) == 0){
				echo '<td id="variance">';
				echo	'<div class="variance" style="background-color: rgba(223, 183, 34, 0.9);">' . $variance . '<br />';	
			}elseif(($variance) > -5){
				echo '<td id="variance">';
				echo	'<div class="variance" style="background-color: rgba(255, 0, 0, 0.1);">' . $variance . '<br />';	
			}elseif(($variance) > -25){
				echo '<td id="variance">';
				echo	'<div class="variance" style="background-color: rgba(255, 0, 0, 0.2);">' . $variance . '<br />';	
			}elseif(($variance) > -100){
				echo '<td id="variance">';
				echo	'<div class="variance" style="background-color: rgba(255, 0, 0, 0.4);">' . $variance . '<br />';	
			}elseif(($variance) > -250){
				echo '<td id="variance">';
				echo	'<div class="variance" style="background-color: rgba(255, 0, 0, 0.6);">' . $variance . '<br />';	
			}elseif(($variance) > -500){
				echo '<td id="variance">';
				echo	'<div class="variance" style="background-color: rgba(255, 0, 0, 0.75);">' . $variance . '<br />';	
			}else{
				echo '<td id="variance">';
				//echo '<td id="variance">';
				echo	'<div class="variance" style="background-color: rgba(255, 0, 0, 1);">' . $variance . '<br />';	
			}																			 
			echo	'<div class="variance">' . $varmath . '<br />';	
			echo '<td>' . $drawerday['total'] . '</td>'; //for base column
			
	 
	 
  
		echo '<td>' . $drawerday['rangeordersum'] . '</td>'; //for sales column
		echo '<td>' . (!empty($drawerday['contributions']) ? $drawerday['contributions'] : '') . '</td>'; //for contributions column
									 
  
		echo '<td>' . (!empty($drawerday['negations']) ? $drawerday['negations'] : '') . '</td>'; //for negations column
		if(!empty($drawerday['drawerdate'])){
			$end_date = substr($drawerday['drawerdate'], 5, 2) . '%2F' . substr($drawerday['drawerdate'], 8, 2) . '%2F' . substr($drawerday['drawerdate'], 0, 4);
			$start_date = substr($drawerday['lastopenclosedate'], 5, 2) . '%2F' . substr($drawerday['lastopenclosedate'], 8, 2) . '%2F' . substr($drawerday['lastopenclosedate'], 0, 4);
		}else{
			$end_date = 'EMPTY';
			$start_date = 'EMPTY';
		}
		/*
		2018-12-17
		12 %2F 17 %2F 2018
												   
																									
																								  
																				
									  
		
		'. DIR_WS_HTTPS_CATALOG . 'spelL-MUd-shoRe/stats_sales_report.php?start_date=12%2F28%2F2018&end_date=12%2F27%2F2018&date_targetpurchased&date_status=1&manufacturer=0&timeframe=year&timeframe_sort=asc&detail_level=order&li_sort_a=oID&li_sort_order_a=asc&li_sort_b=oID&li_sort_order_b=asc&new_window=1
		'. DIR_WS_HTTPS_CATALOG . 'spelL-MUd-shoRe/stats_sales_report.php?start_date=12%2F27%2F2018&end_date=12%2F28%2F2018&date_target=purchased&date_status=1&prod_includes=&cust_includes=&payment_method=cash&payment_method_omit=&current_status=&manufacturer=0&timeframe=year&timeframe_sort=asc&detail_level=order&li_sort_a=oID&li_sort_order_a=asc&li_sort_b=oID&li_sort_order_b=asc&output_format=display&new_window=1
		'. DIR_WS_HTTPS_CATALOG . 'spelL-MUd-shoRe/stats_sales_report.php?start_date=12%2F17%2F2018&end_date=12%2F27%2F2018&date_target=purchased&date_status=1&prod_includes=&cust_includes=&payment_method=&payment_method_omit=&current_status=&manufacturer=0&timeframe=year&timeframe_sort=asc&detail_level=order&li_sort_a=oID&li_sort_order_a=asc&li_sort_b=oID&li_sort_order_b=asc&output_format=display&order_total_validation=1&new_window=1
		*/
		if($drawerday['rangeordersum'] > 0){
			echo '<td><a href="' . DIR_WS_ADMIN . 'stats_sales_report.php?start_date=' . $start_date . '&end_date=' . $end_date . '&date_target=purchased&date_status=1&prod_includes=&cust_includes=&payment_method=cash&payment_method_omit=&current_status=&manufacturer=0&timeframe=year&timeframe_sort=asc&detail_level=order&li_sort_a=oID&li_sort_order_a=asc&li_sort_b=oID&li_sort_order_b=asc&output_format=display&new_window=1">sales report</a><br />';
			echo '<br />' . str_replace("%2F", "/", $start_date) . '<br />';
			echo str_replace("%2F", "/", $end_date) . '</td>';
		}
		//echo '<td>' . $drawerday['total'] . '</td>';
	}
	echo '</td>';
		$dayinquestion = strtotime($drawerday['drawerdate']);
		$dayinquestion = date("Y-m-d", strtotime($drawerday['drawerdate']));
		echo '</tr>';
	
	/*echo '<pre>';
	print_r($drawerday);
	echo '</pre>';*/
	}

echo '<tr><td></td><td colspan = "100%">Brian moved the input fields to the top (first row of this table; below the headers)</td></tr>';


//print_r($dayinquestion);

?>
  </tr>
 
<!-- body_text_eof //-->
  </tr>


	 
																				 

  
  </tbody>
</table>
<!-- body_eof //-->
	 
																		
								  
							  
				

  

<style>
    .table {
        display: block;
        /*max-width: 100%;*/
		align: center;
		padding: 0px;
		vertical-align: middle;
		overflow: scroll; /* Available options: visible, hidden, scroll, auto */
    }
	table, td{
		vertical-align: middle !important;
	}
	table, td.pageHeading.dateinput {
		font-size:9px !important;
		max-width: 2.6%
	}
	.pageHeading {
		background: lightgrey;
	}
	.content{
		overflow: auto;
	}
	.id{
		padding: 0px !important;
		max-width: 1%;
	}
	.drawertype{
		width: 100%;
		max-width: 550px;
	}
	.date {
		padding: 0px !important;		
	}

	.dateinputcell{
		padding: 0px !important;
		padding-top: 8px !important;

	}
	.dateinput{
		vertical-align: middle !important;
	}
	.bills {
		padding: 0px !important;
		max-width: 1px !important;
		color: green;
	}
	.coinrolls {
		padding: 0px !important;
		max-width: 1px !important;
	}
	.coins {
		padding: 0px !important;
		max-width: 1px !important;
	}
	.numinputs{
		padding: 0px !important;
		text-align: center;
	}
	.nums{
		padding: 0px !important;
		padding-top: 12px !important;
		text-align: center;
	}
	.billnums{
		padding: 0px !important;
		padding-top: 12px !important;
		background: #85bb65 ;
	}
	.coinnums{
		padding: 0px !important;
		padding-top: 12px !important;
		background: silver;
		max-width: 4%;
	}
	div.variance{
		width: 100%; /*was 40px*/
		display: inline-table;
		text-align: right;
	}
	div.date{
		display: inline-table;
		text-align: right;
	}
@header_background_color: #333;
@header_text_color: #FDFDFD;
@alternate_row_background_color: #DDD;

@table_width: 750px;
@table_body_height: 300px;
@column_one_width: 200px;
@column_two_width: 200px;
@column_three_width: 350px;

/*
.fixed_headers {
  width: @table_width;
  table-layout: fixed;
  border-collapse: collapse;
  
  th { text-decoration: underline; }
  th, td {
    padding: 5px;
    text-align: left;
  }
  
  td:nth-child(1), th:nth-child(1) { min-width: @column_one_width; }
  td:nth-child(2), th:nth-child(2) { min-width: @column_two_width; }
  td:nth-child(3), th:nth-child(3) { width: @column_three_width; }

  thead {
    background: @header_background_color;
    color: @header_text_color;
    tr {
      display: block;
      position: relative;
    }
  }
  tbody {
    display: block;
    overflow: auto;
    width: 100%;
    height: @table_body_height;
    tr:nth-child(even) {
      background: @alternate_row_background_color;
    }
  }
}

.old_ie_wrapper {
  height: @table_body_height;
  width: @table_width;
  overflow-x: hidden;
  overflow-y: auto;
  tbody { height: auto; }
}	
input[type="text"] {
     width: 100%; 
     box-sizing: border-box;
     -webkit-box-sizing:border-box;
     -moz-box-sizing: border-box;
}
.blinking{
	animation:blinkingText 0.8s infinite;
}
@keyframes blinkingText{
	0%{		color: #000;	}
	49%{	color: transparent;	}
	50%{	color: transparent;	}
	99%{	color:transparent;	}
	100%{	color: #000;	}
}*/
</style>

<!-- footer //-->
<?php //require(DIR_WS_INCLUDES . 'footer.php'); ?>
<?php //require('includes/footer.php'); ?>
<!-- footer_eof //-->
<br />
<!--<h1 style="font-size: 350px">ROBERTO <span class="blinking">SMELLS</span></H1>
<h2 style="font-size: 275px">LIAM<span class="blinking">POOPS</span></H2>-->
</body>
</html>
<?php //require('includes/application_bottom.php'); ?>