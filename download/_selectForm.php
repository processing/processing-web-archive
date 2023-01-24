<h1 class="large-header"><span class="black">Download Processing.</span> Please consider making a donation to the Processing Foundation before downloading the software.</h1>

<noscript>
	<p>JavaScript is required for the donation process. Please click <a href="/download/?processing">here</a> to go directly to Downloads...</p>
	<style type="text/css">
		.donate-box { display: none; }
	</style>
</noscript>

<div class="donate-box">

	<p>Processing is open source, free software. All donations fund the <a href="/foundation/">Processing Foundation</a>, a nonprofit organization devoted to advancing the role of programming within the visual arts through developing Processing.</p>

	<!--<p>To celebrate the launch of the new Processing <a href="https://github.com/atduskgreg/opencv-processing">OpenCV library</a>, <a href="http://www.oreilly.com/">O'Reilly Media</a> is matching donations to the Processing Foundation up to $3000 for the remainder of 2013.</p>-->

	<div class="messages"></div>

	
	<form method="post" action="/download/" id="selectForm">
		<input type="hidden" name="form" value="1" />
		<input type="hidden" name="radioChecked" value="0" />

		<div class="select-amount">
			<label><input type="radio" name="selectAmount" value="0"> No Donation</label>
			<label><input type="radio" name="selectAmount" value="10"> $10</label>
			<label><input type="radio" name="selectAmount" value="25"> $25</label>
			<label><input type="radio" name="selectAmount" value="50"> $50</label>
			<label><input type="radio" name="selectAmount" value="100"> $100</label>
			<label class="last"><input type="radio" name="selectAmount" value="other" id="wildcard"></label><span>$ <input type="text" value="" id="otra" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></span>
		</div>

		<input type="submit" name="submit" value="Donate & Download">
	</form>

</div>