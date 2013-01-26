(function($){

	// Init menu
	$('#main-navigation').addClass('hidden');
	$('#main-navigation').before('<a href="#menu" class="navicon" title="Show Navigation" />');

	$('.navicon').click(function() {
		$('#main-navigation').toggleClass('hidden', 'show');
		$('.navicon').toggleClass('hidden');
	});

	// This is a work-around until #21534 is fixed
	$('#mainnav li').not('.menu-item-has-children').each(function() {
		var $el = $(this);
		if ( $el.find('ul').length > 0 ) {
			$el.addClass('menu-item-has-children');
		}
	});

	// Add speakerdeck to fitvids
	$("article").fitVids({customSelector: "iframe[src^='//speakerdeck.com'], iframe[src^='http://www.hulu.com'], iframe[src^='http://w.soundcloud.com']"});

	// Style <pre><code> blocks with Google's prettyPrint (syntax highlighting)
	function styleCode() {
		if (typeof disableStyleCode !== "undefined") {
			return;
		}

		var a = false;

		$("pre code").parent().each(function() {
			if (!$(this).hasClass("prettyprint")) {
				$(this).addClass("prettyprint").addClass("linenums");
				a = true;
			}
		});

		if (a) { prettyPrint(); }
	}
	styleCode();
})(jQuery);
