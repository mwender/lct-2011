<form name="donateForm" id="donateForm" method="post" action="<?php the_permalink() ?>" onsubmit="return validate_form(this)">

<fieldset>
<legend>
Please enter the amount of your donation:
</legend>
<label for="x_amount" id="x_amount">$ <input type="text" size="10" name="x_amount" id="x_amount" value="" class="required" /></label>

	<div class="" id="regular">
		<p style="margin-top: 20px"><label class="top" for="regular-comments">Additional Comments:</label>
		<textarea name="regular-comments" id="regular-comments"></textarea>
		</p>		
	</div>	
</fieldset>

<input class="submit" type="submit" name="step1" value="Step 1: Confirm Donation Amount" onclick="document.donateForm.x_amount.disabled=false"/>

</form>