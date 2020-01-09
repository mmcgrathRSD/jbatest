

<h1>Cardswipe Demo</h1>
	<p>Plug in your card reader and scan a card. This page uses the default card data parser, which recognizes up to three lines of scanned data on a card.</p>

	<h2>Scan result:</h2>
	<div class="result">
		<ol>
			<li><span id="line1" class="data"></span></li>
			<li><span id="line2" class="data"></span></li>
			<li><span id="line3" class="data"></span></li>
		</ol>
		<p>Status: <span id="status"></span></p>
	</div>

	<h2>Form on the Page</h2>
	<p>Scanning a card will not interfere with form fields. Type a few characters into the field below and start a scan.</p>
	<form>
		<label>Form input field: <input type="text" name="field1" /></label>
	</form>

	<script type="text/javascript">
		var success = function (data) {
			$("#status").text("Success!");
			$("#line1").text(data.line1);
			$("#line2").text(data.line2);
			$("#line3").text(data.line3);
		}
		var error = function () {
			$("#status").text("Failed!");
			$(".line").text("");
		}
		// Initialize the plugin with default parser and callbacks.
		//
		// Set debug to true to watch the characters get captured and the state machine transitions
		// in the javascript console. This requires a browser that supports the console.log function.
		//
		// Set firstLineOnly to true to invoke the parser after scanning the first line. This will speed up the
		// time from the start of the scan to invoking your success callback.
		$.cardswipe({
			firstLineOnly: false,
			success: success,
			error: error,
			debug: false
		});
	</script>