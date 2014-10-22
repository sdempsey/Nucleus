BreakpointController = (function($){
	var ret = {},
		SMALL = 600,
		MEDIUM = 760,
		LARGE = 1000,
		X_LARGE = 1280,
		XX_LARGE = 1400,
		win,
		currentBreakpoint,
		breakpoints = [
			{ label: "small", width: SMALL },
			{ label: "medium", width: MEDIUM },
			{ label: "large", width: LARGE },
			{ label: "x-large", width: X_LARGE },
			{ label: "xx-large", width: XX_LARGE }
		];

	function onDocumentReady(){
		win = $(window);
		win.on('resize', checkWindow);
		checkWindow();
	}

	function checkWindow(){
		var w = win.width(),
			ret;

		for(var i=0; i<breakpoints.length; i++)
		{
			breakpoint = breakpoints[i];
			if(w >= breakpoint.width)
			{
				ret = breakpoint.width;
			}
			else
			{
				break;
			}
		}

		setBreakpoint(ret);
	}

	function setBreakpoint(breakpoint){
		if(breakpoint !== currentBreakpoint)
		{
			currentBreakpoint = breakpoint;
			$(BreakpointController).trigger('crossBreakpoint');
		}
		else
		{
			currentBreakpoint = breakpoint;
		}
	}

	ret = {
		getBreakpoint: function(){
			return currentBreakpoint;
		},
		SMALL: SMALL,
		MEDIUM: MEDIUM,
		LARGE: LARGE,
		X_LARGE: X_LARGE,
		XX_LARGE: XX_LARGE
	};

	$(onDocumentReady);

	return ret;
})(jQuery);