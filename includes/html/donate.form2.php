<?php
//*
//Place these in wp-config.php:
define('AUTHORIZE_LOGIN', '8S2chH27');
define('AUTHORIZE_KEY', '45H3N2vZe62k94a4' );
/**/
$login = AUTHORIZE_LOGIN;
$key = AUTHORIZE_KEY;
$invoice	= current_time('mysql'); // date(YmdHis); // generate invoice number using the date and time
function prepareAddress($address = ''){
	if(stristr($address,"\n")){
		$array = explode("\n",$address);
		$array = array_map('trim',$array);
		$address = implode(', ',$array);
	}
	return $address;
}

// Receive the posted amount and format it as a dollar amount without the currency symbol
$amount = number_format(trim($_POST["x_amount"],"$"),2);

// Generate a random sequence number (required by SIM API)
$sequence  = rand(1, 1000);

// Generate a timestamp
$timestamp    = time ();

// Build description
if( !empty( $_POST['regular-comments'] ) ){
	$description = 'Living Christmas Tree Donation (Comment: '.esc_attr($_POST['regular-comments']).')';
}
if(empty($description)) $description = 'Living Christmas Tree Donation';

// The following lines generate the SIM fingerprint.  PHP versions 5.1.2 and
// newer have the necessary hmac function built in.  For older versions, it
// will try to use the mhash library.
if( phpversion() >= '5.1.2' )
{ $fingerprint = hash_hmac("md5", $login . "^" . $sequence . "^" . $timestamp . "^" . $amount . "^", $key); }
else 
{ $fingerprint = bin2hex(mhash(MHASH_MD5, $login . "^" . $sequence . "^" . $timestamp . "^" . $amount . "^", $key)); }
?>

<form name='donateForm' method='post' action="https://secure.authorize.net/gateway/transact.dll" onsubmit="return validate_form(this)">

<!-- Invoice and description are specified in the vars.php file -->
<input type='hidden' name='x_invoice_num' value='<?php echo $invoice; ?>' />
<input type='hidden' name='x_description' value='<?php echo $description; ?>' />

<!-- Amount is hidden here, but there is also a field for display only below -->
<input type='hidden' name='x_amount' value='<?php echo $amount; ?>' />
<h3>Donation Amount:<div style="margin-top: 10px; color: #090; font-size: 32px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">$<?php echo $amount; ?></span><br /><span style="font-size: 12px; font-weight: normal"><a href="<?php the_permalink() ?>">Click here to change donation amount.</a></div></h3>
<input type="hidden" name='display_amount' value='<?php echo $amount; ?>' /> 

<!-- Insert the remaining required fields -->
<input type='hidden' name='x_fp_sequence' value='<?php echo $sequence; ?>' />
<input type='hidden' name='x_fp_timestamp' value='<?php echo $timestamp; ?>' />
<input type='hidden' name='x_fp_hash' value='<?php echo $fingerprint; ?>' />
<input type='hidden' name='x_login' value='<?php echo $login; ?>' />
<input type='hidden' name='x_show_form' value='PAYMENT_FORM' />
<input type="hidden" name="x_header_html_payment_form" value='<div style="text-align: center"><img src="https://static.e-junkie.com/sslpic/58497.a0dfb721a4db33a29f3cf4c429febebe.gif" width="570" height="90" alt="Sevier Heights Baptist Church - Knoxville, TN" /></div>' />
<input class="submit" style="" type='submit' value='Step 2: Continue to Secure Server' onClick="if (document.donateForm.i_am_also_a.value=='other (please specify)') {document.donateForm.i_am_also_a.disabled=true;}"/>
<br />
<img src="<?php bloginfo('template_directory') ?>/includes/images/securecheckout.gif" style="margin: 10px 0 0 4px; width: 136px; height: 45px;" alt="Make your donation securely via our form at Authorize.net" />
</form>
<br style="clear: both;" />