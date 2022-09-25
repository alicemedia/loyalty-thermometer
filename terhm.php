<?php 
/*
Plugin Name:  thermometer
Plugin URI:
Description:
Version:      1.0
Author:       Alice Media
Author URI:
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wpb-tutorial
Domain Path:  /languages
*/

  // Ignore cancelled order and count 500ml variation as half

error_reporting(E_ALL ^ E_NOTICE); 
//include('../../../wp-config.php');
global $wpdb;

add_shortcode("therm", "thermometer");
add_shortcode("soup12", "soup12");

function thermometer() {
	
  global $wpdb;
  //get logged in user email
  
if ( is_user_logged_in() ) {
  $uid = get_current_user_id();

  
  // findout how many times they bought specific item  
  $orders = $wpdb->get_results ("SELECT object_id FROM wp_fn9jp5cqtr_term_relationships WHERE term_taxonomy_id = 115 OR term_taxonomy_id = 117", ARRAY_A);

  $uid = $wpdb->get_results ( "SELECT customer_id FROM wp_fn9jp5cqtr_wc_customer_lookup WHERE user_id = $uid" );
  $uid = $uid[0]->customer_id;
  
  if($uid == "") {
	$uid = '0';
  }

	
  $orders2 = $wpdb->get_results ( "SELECT product_id, product_qty, variation_id FROM wp_fn9jp5cqtr_wc_order_product_lookup WHERE customer_id = $uid" );
	
		
  //echo jquery with div/css for thermometer
  $soups2 = array();
	
  foreach($orders as $item2) {
    $soups2[] = $item2['object_id'];

  }
	
  $pid = "";
  $ordershalf = array();
	
  $ordershalf2 = $wpdb->get_results ( "SELECT variation_id FROM wp_fn9jp5cqtr_wc_order_product_lookup WHERE customer_id = $uid" );
	
  foreach($ordershalf2 as $item) {
		$pid = $item->variation_id;
	  	$ordershalf[] = $wpdb->get_results ( "SELECT post_title FROM wp_fn9jp5cqtr_posts WHERE id = $pid" );
  }
	
  $soups = array();
  $soupsfinal2 = array();
  $soups10 = array();
  $souphalf = array();
  $soupsfinal = array();
  $i = 0;
  $ordershalf3 = array();
  $half = 0;

//   if($orders2 > 0) {
	  foreach($orders2 as $item) {
		$soups[] = $item->product_id;
		$soupsfinal2[] = $item->product_id;
		$soups10[] = $item->product_qty;
		$soupsfinal[] = array($item->product_id, $item->product_qty);
		$souphalf[] = $item->variation_id;
	
		$ordershalf[] = $wpdb->get_results ( "SELECT post_title FROM wp_fn9jp5cqtr_posts WHERE id = $souphalf[$i]" );
	
		  
		if(strpos($ordershalf[$i][0]->post_title, '500') !== false){
			$ordershalf3[] = array_push($ordershalf3, $ordershalf[$i][0]->post_title);
			$half = $half + 1;
			
		}
			 
		 if ($soups10[$i] == "2") {
		   $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
			 $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
		 }

		 else if ($soups10[$i] == "3") {
		   $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
			 $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
			 $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
		 }

		 else if ($soups10[$i] == "4") {
		   $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
			 $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
			 $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
		 }

		 else if ($soups10[$i] == "5") {
		   $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
			 $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
			 $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
		 }

		 $i = $i + 1;
	  }
//   }

	$half = $half /2;
  //var_dump($soupsfinal2);


  $soups3 = array_intersect($soupsfinal2, $soups2);
  $soup = count($soups3);
 
  $soup = $soup - $half;
	$current_user_id = get_current_user_id();	
	$updated_soup_balance = $wpdb->get_results ( "SELECT soup_count FROM wp_fn9jp5cqtr_users WHERE ID = $current_user_id" );
    $updated_soup_balance = $updated_soup_balance[0]->soup_count;	
	$soup = $soup + $updated_soup_balance;
  //count in sections of 12
  $total = 12;
		
  //width for 1/12 soup	
	 if($soup <= 1) {		
  if($soup <= 12) {
  	$amount = round(($soup/12) * 70, 2);
  }else {
	$amount = round($soup/12, 2);
	$amount = substr($amount, -2);
  }
		 }
		  //width for 2/12 soup					
		elseif(($soup > 1) && ($soup < 3)) {		
  if($soup <= 12) {
  	$amount = round(($soup/12) * 91, 2);
  }else {
	$amount = round($soup/12, 2);
	$amount = substr($amount, -2);
  }
		 }	
  //width for  3/12 4/12 5/12 soup					
		elseif(($soup >= 3) && ($soup < 5)) {		
  if($soup <= 12) {
  	$amount = round(($soup/12) * 96.5, 2);
  }else {
	$amount = round($soup/12, 2);
	$amount = substr($amount, -2);
  }
		 }	
		  //width for 6/12 7/12 8/12 9/12 soup	
	 elseif(($soup >= 5) && ($soup < 10)) {		
  if($soup <= 12) {
  	$amount = round(($soup/12) * 101.4, 2);
  }else {
	$amount = round($soup/12, 2);
	$amount = substr($amount, -2);
  }
		 }
		  //width for 10/12 11/12 soup			
elseif(($soup >= 10) && ($soup < 12)) {	
	if($soup <= 12) {
  	$amount = round(($soup/12) * 102.6, 2);
  }else {
	$amount = round($soup/12, 2);
	$amount = substr($amount, -2);
  }	
	}
				  //width for 12/12 soup	
		else {
	if($soup <= 12) {
  	$amount = round(($soup/12) * 100, 2);
  }else {
	$amount = round($soup/12, 2);
	$amount = substr($amount, -2);
  }
	}
		 		 
	
  while ($soup > 12) {
	  if($amount >= 83) {
	  	$soup = substr($soup - 12, -2);
	  }else {
		$soup = substr($soup - 12, -1);  
	  }
  }

  $soupspecial = $soup;
  if($uid == '0') {
	  $uid = "empty";
  }	
	
  if($uid){ 
	  pll_register_string('Votre prochain litre de soupe gratuite', 'Votre prochain litre de soupe gratuite');
	  echo('<div style="width: 250px; margin:auto; text-align:center;"><h2>');
	  if(pll_current_language() == 'fr'){
   //do your work here

	  echo pll__('Votre prochain litre de soupe gratuite');
	  } else{
   //do your work here

	  echo pll__('Your next free liter of soup');
	  }
	  
	 echo('</h2><div class="fillouter" style="width:250px; height:40px; background:#f1f1f1; border:solid 2px #003380; border-radius:10px;">
	  
	  <div class="lines">
	 	 <span style="letter-spacing: 10px; font-size: 40px; margin-top: 5px; display: block; margin-left: 9px; position:absolute; z-index:999;"> |||||||||||</span
	  </div>
	  
			<div class="fill" style="height:38px; width:' . $amount . '%; background:#003380;  border-radius:8px 0px 0px 8px;"></div>
			' . $soup . '/12
		  </div>
		  </div>
		  <br />
	  </div>');
  }

}
	}




//email thermometor

function soup12() {
	
  global $wpdb;
  //get logged in user email
  
	if ( is_user_logged_in() ) {
	  $uid = get_current_user_id();


	  // findout how many times they bought specific item  
	  $orders = $wpdb->get_results ("SELECT object_id FROM wp_fn9jp5cqtr_term_relationships WHERE term_taxonomy_id = 115 OR term_taxonomy_id = 117", ARRAY_A);

	  $uid = $wpdb->get_results ( "SELECT customer_id FROM wp_fn9jp5cqtr_wc_customer_lookup WHERE user_id = $uid" );
	  $uid = $uid[0]->customer_id;

	  if($uid == "") {
		$uid = '0';
	  }


	  $orders2 = $wpdb->get_results ( "SELECT product_id, product_qty, variation_id FROM wp_fn9jp5cqtr_wc_order_product_lookup WHERE customer_id = $uid" );


	  //echo jquery with div/css for thermometer
	  $soups2 = array();

	  foreach($orders as $item2) {
		$soups2[] = $item2['object_id'];

	  }

	  $pid = "";
	  $ordershalf = array();

	  $ordershalf2 = $wpdb->get_results ( "SELECT variation_id FROM wp_fn9jp5cqtr_wc_order_product_lookup WHERE customer_id = $uid" );

	  foreach($ordershalf2 as $item) {
			$pid = $item->variation_id;
			$ordershalf[] = $wpdb->get_results ( "SELECT post_title FROM wp_fn9jp5cqtr_posts WHERE id = $pid" );
	  }

	  $soups = array();
	  $soupsfinal2 = array();
	  $soups10 = array();
	  $souphalf = array();
	  $soupsfinal = array();
	  $i = 0;
	  $ordershalf3 = array();
	  $half = 0;

	//   if($orders2 > 0) {
		  foreach($orders2 as $item) {
			$soups[] = $item->product_id;
			$soupsfinal2[] = $item->product_id;
			$soups10[] = $item->product_qty;
			$soupsfinal[] = array($item->product_id, $item->product_qty);
			$souphalf[] = $item->variation_id;

			$ordershalf[] = $wpdb->get_results ( "SELECT post_title FROM wp_fn9jp5cqtr_posts WHERE id = $souphalf[$i]" );


			if(strpos($ordershalf[$i][0]->post_title, '500') !== false){
				$ordershalf3[] = array_push($ordershalf3, $ordershalf[$i][0]->post_title);
				$half = $half + 1;

			}

			 if ($soups10[$i] == "2") {
			   $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
				 $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
			 }

			 else if ($soups10[$i] == "3") {
			   $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
				 $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
				 $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
			 }

			 else if ($soups10[$i] == "4") {
			   $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
				 $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
				 $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
			 }

			 else if ($soups10[$i] == "5") {
			   $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
				 $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
				 $soupsfinal2[] = array_push($soupsfinal2, $item->product_id);
			 }

			 $i = $i + 1;
		  }
	//   }

		$half = $half /2;
	  //var_dump($soupsfinal2);


	$soups3 = array_intersect($soupsfinal2, $soups2);
	$soup = count($soups3);

	$soup = $soup - $half;
		
    $current_user_id = get_current_user_id();	
	$updated_soup_balance = $wpdb->get_results ( "SELECT soup_count FROM wp_fn9jp5cqtr_users WHERE ID = $current_user_id" );
    $updated_soup_balance = $updated_soup_balance[0]->soup_count;	
	$soup = $soup + $updated_soup_balance;
    
	//count in sections of 12
    $total = 12;

	  //width for 1/12 soup	
		 if($soup <= 1) {		
	  if($soup <= 12) {
		$amount = round(($soup/12) * 70, 2);
	  }else {
		$amount = round($soup/12, 2);
		$amount = substr($amount, -2);
	  }
			 }
			  //width for 2/12 soup					
			elseif(($soup > 1) && ($soup < 3)) {		
	  if($soup <= 12) {
		$amount = round(($soup/12) * 91, 2);
	  }else {
		$amount = round($soup/12, 2);
		$amount = substr($amount, -2);
	  }
			 }	
	  //width for  3/12 4/12 5/12 soup					
			elseif(($soup >= 3) && ($soup < 5)) {		
	  if($soup <= 12) {
		$amount = round(($soup/12) * 96.5, 2);
	  }else {
		$amount = round($soup/12, 2);
		$amount = substr($amount, -2);
	  }
			 }	
			  //width for 6/12 7/12 8/12 9/12 soup	
		 elseif(($soup >= 5) && ($soup < 10)) {		
	  if($soup <= 12) {
		$amount = round(($soup/12) * 101.4, 2);
	  }else {
		$amount = round($soup/12, 2);
		$amount = substr($amount, -2);
	  }
			 }
			  //width for 10/12 11/12 soup			
	elseif(($soup >= 10) && ($soup < 12)) {	
		if($soup <= 12) {
		$amount = round(($soup/12) * 102.6, 2);
	  }else {
		$amount = round($soup/12, 2);
		$amount = substr($amount, -2);
	  }	
		}
					  //width for 12/12 soup	
			else {
		if($soup <= 12) {
		$amount = round(($soup/12) * 100, 2);
	  }else {
		$amount = round($soup/12, 2);
		$amount = substr($amount, -2);
	  }
		}


	  while ($soup > 12) {
		  if($amount >= 83) {
			$soup = substr($soup - 12, -2);
		  }else {
			$soup = substr($soup - 12, -1);  
		  }
	  }


		  if($uid == '0') {
			  $uid = "empty";
		  }	

		  if($uid){ 
			
			  if(0 == $soup) {
				 //send email here 
				 var_dump($soup);
			  }
		  }

	}
}


function sendfreesoup() {
	  global $wpdb;
	
	  //user posted variables
	  $name = $_POST['message_name'];
	  $email = $_POST['message_email'];
	  $message = $_POST['message_text'];

	  //php mailer variables
	  $to = get_option('admin_email');
	  $subject = "Un litre de soups gratuite";
	  $headers = 'From: '. $email . "\r\n" .
		'Reply-To: ' . $email . "\r\n";


	  //Here put your Validation and send mail
	  $sent = wp_mail($to, $subject, strip_tags($message), $headers);
	  
	  if($sent){
	
	  }else {
        
	  } 
}
