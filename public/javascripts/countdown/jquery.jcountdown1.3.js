/* 
* jCountdown 1.4.3 jQuery Plugin
* Copyright 2012 Tom Ellis http://www.webmuse.co.uk | MIT Licensed (license.txt)
*/
(function($) {
$.fn.countdown = function( method /*, options*/ ) {

	var defaults = {
			date: null,
			updateTime: 1000,
			htmlTemplate: "%d <span class='cd-time'>days</span> %h <span class='cd-time'>hours</span> %i <span class='cd-time'>mins</span> %s <span class='cd-time'>sec</span>",
			minus: false,
			onChange: null,
			onComplete: null,
			onResume: null,
			onPause: null,
			leadingZero: false,
			offset: null,
			servertime:null,
			hoursOnly: false,
			minsOnly: false,
			secsOnly: false,
			weeks: false,
			hours: false,
			yearsAndMonths: false,
			direction: "down",
			stopwatch: false
		},
		slice = Array.prototype.slice,
		clear = window.clearInterval,
		floor = Math.floor,
		msPerHr = 3600000,
		secPerYear = 31556926,
		secPerMonth = 2629743.83,
		secPerWeek = 604800,
		secPerDay = 86400,
		secPerHr = 3600,
		secPerMin = 60,
		secPerSec = 1,
		rDate = /(%y|%m|%w|%d|%h|%i|%s)/g,
		rYears = /%y/,
		rMonths = /%m/,
		rWeeks = /%w/,
		rDays = /%d/,
		rHrs = /%h/,
		rMins = /%i/,
		rSecs = /%s/,
		dateNow = function( $this ) {
			var now = new Date(),
				settings = $this.data("jcdData");
			
			if( !settings ) {
				return new Date();
			}
			
			if( settings.offset !== null ) {
				now = getTZDate( settings.offset );
			} else {
				now = getTZDate( null, settings.difference ); //Date now
			}
			
			now.setMilliseconds(0);
			
			return now;
		},
		getTZDate = function( offset, difference ) {		
			var hrs,
				dateMS,
				curHrs,
				tmpDate = new Date();
			
			if( offset === null ) {
				dateMS = tmpDate.getTime() - difference;
			} else {				
				hrs = offset * msPerHr;
				curHrs = tmpDate.getTime() - ( ( -tmpDate.getTimezoneOffset() / 60 ) * msPerHr ) + hrs;
				dateMS = tmpDate.setTime( curHrs );
			}
			return new Date( dateMS );
		},			
		timerFunc = function() {
			//Function runs at set interval updating countdown
			var $this = this,
				template,
				now,
				date,
				timeLeft,
				yearsLeft,
				monthsLeft,
				weeksLeft,
				//eDaysLeft,
				daysLeft,
				//eHrsLeft,
				hrsLeft,
				minsLeft,					
				//eMinsleft,
				secLeft,
				time = "",
				diff,
				extractSection = function( numSecs ) {
					var amount;
	
					amount = floor( diff / numSecs );
					diff -= amount * numSecs;
					
					return amount;
				},
				settings = $this.data("jcdData");
				
			if( !settings ) {
				return false;
			}
			
			template = settings.htmlTemplate;
			
			now = dateNow( $this );
			
			date = settings.dateObj; //Date to countdown to
			
			date.setMilliseconds(0);
			
			timeLeft = ( settings.direction === "down" ) ? date.getTime() - now.getTime() : now.getTime() - date.getTime();
			
			diff = Math.round( timeLeft / 1000 );

			daysLeft = extractSection( secPerDay );			
			hrsLeft = extractSection( secPerHr );			
			minsLeft = extractSection( secPerMin );
			secLeft = extractSection( secPerSec );
												
			if( settings.yearsAndMonths ) {

				//Add days back on so we can calculate years easier
				diff += ( daysLeft * secPerDay );
				
				yearsLeft = extractSection( secPerYear );				
				monthsLeft = extractSection( secPerMonth );				
				daysLeft = extractSection( secPerDay );
			}

			if( settings.weeks ) {
				//Add days back on so we can calculate weeks easier				
				diff += ( daysLeft * secPerDay );

				weeksLeft = extractSection( secPerWeek );
				daysLeft = extractSection( secPerDay );
			}
						
			//Assumes you are using dates within a month 
			//as years and months aren't taken into account
			if( settings.hoursOnly ) {
				hrsLeft += daysLeft * 24;
				daysLeft = 0;
			}
			
			//Assumes you are only using dates in the near future 
			//as years and months aren't taken into account
			if( settings.minsOnly ) {
				minsLeft += ( hrsLeft * 60 ) + ( ( daysLeft * 24 ) * 60 );
				daysLeft = hrsLeft = 0;
			}

			//Assumes you are only using dates in the near future 
			//as years, months and days aren't taken into account
			if( settings.secsOnly ) {
				secLeft += ( minsLeft * 60 );
				daysLeft = hrsLeft = minsLeft = 0;
			}
						
			settings.yearsLeft = yearsLeft;
			settings.monthsLeft = monthsLeft;
			settings.weeksLeft = weeksLeft;
			settings.daysLeft = daysLeft;
			settings.hrsLeft = hrsLeft;
			settings.minsLeft = minsLeft;
			settings.secLeft = secLeft;
			
			if( secLeft === 60 ) { 
				secLeft = 0;
			}
			
			if ( settings.leadingZero ) {			
				
				if ( daysLeft < 10 && !settings.hoursOnly ) {
					daysLeft = "0" + daysLeft;
				}
				
				if ( yearsLeft < 10 ) {
					yearsLeft = "0" + yearsLeft;
				}
				
				if ( monthsLeft < 10 ) {
					monthsLeft = "0" + monthsLeft;
				}
				
				if ( weeksLeft  < 10 ) {
					weeksLeft = "0" + weeksLeft;
				}
				
				if ( hrsLeft < 10 ) {
					hrsLeft = "0" + hrsLeft;
				}
				if ( minsLeft < 10 ) {
					minsLeft = "0" + minsLeft;
				}
				if ( secLeft < 10 ) {
					secLeft = "0" + secLeft;
				}
			}

			if ( ( settings.direction === "down" && ( now < date || settings.minus ) ) || ( settings.direction === "up" && ( date < now || settings.minus )  ) ) {
							
				time = template.replace( rYears, yearsLeft ).replace( rMonths, monthsLeft ).replace( rWeeks, weeksLeft );
				time = time.replace( rDays, daysLeft ).replace( rHrs, hrsLeft ).replace( rMins, minsLeft ).replace( rSecs, secLeft );

			} else {
				time = template.replace( rDate, "00");
				settings.hasCompleted = true;
			}
							
			$this.html( time ).trigger("change.jcdevt", [settings] ).trigger("countChange", [settings] );
						
			if ( settings.hasCompleted ) {
				$this.trigger("complete.jcdevt").trigger("countComplete");
				clear( settings.timer );
			}
			
			$this.data("jcdData", settings);
		},			
		methods = {		
			init: function( options ) {
				
				var opts = $.extend( {}, defaults, options ),
					local,
					testDate;
				
				return this.each(function() {
					var $this = $(this),
						settings = {},
						func;

					//If this element already has a countdown timer, just change the settings
					if( $this.data("jcdData") ) {
						$this.countdown("changeSettings", options, true);
						opts = $this.data("jcdData");
					}
					
					if( opts.date === null ) {
						$.error("No Date passed to jCountdown. date option is required.");
						return true;
					}
										
					testDate = new Date(opts.date);
					
					if( testDate.toString() === "Invalid Date" ) {
						$.error("Invalid Date passed to jCountdown: " + opts.date);
					}
					
					testDate = null;
					
					//Add event handlers where set
					if( opts.onChange ) {
						$this.on("change.jcdevt", opts.onChange );
					}
					
					if( opts.onComplete ) {
						$this.on("complete.jcdevt", opts.onComplete );
					}
					
					if( opts.onPause ) {
						$this.on("pause.jcdevt", opts.onPause );
					}

					if( opts.onResume ) {
						$this.on("resume.jcdevt", opts.onResume );
					}
					
					settings = $.extend( {}, opts );
					
					settings.originalHTML = $this.html();
					settings.dateObj = new Date( opts.date );
					settings.hasCompleted = false;
					settings.timer = 0;
					settings.yearsLeft = settings.monthsLeft = settings.weeksLeft = settings.daysLeft = settings.hrsLeft = settings.minsLeft = settings.secLeft = 0;
					settings.difference = null;
					
					if( opts.servertime !== null ) {
						var tempTime;
						local = new Date();
						
						tempTime = ( $.isFunction( settings.servertime ) ) ? settings.servertime() : settings.servertime;
						settings.difference = local.getTime() - tempTime;
						
						tempTime = null;
					}

					func = $.proxy( timerFunc, $this );
					settings.timer = setInterval( func, settings.updateTime );

					$this.data( "jcdData", settings );
					
					func();
				});
			},
			changeSettings: function( options, internal ) {
				//Like resume but with resetting/changing options				
				return this.each(function() {
					var $this  = $(this),
						settings,
						testDate,
						func = $.proxy( timerFunc, $this );
						
					if( !$this.data("jcdData") ) {
						return true;
					}
					
					settings = $.extend( {}, $this.data("jcdData"), options );

					if( options.hasOwnProperty("date") ) {
						testDate = new Date(options.date);
						
						if( testDate.toString() === "Invalid Date" ) {
							$.error("Invalid Date passed to jCountdown: " + options.date);
						}
					}
					
					settings.hasCompleted = false;
					settings.dateObj  = new Date( options.date );
					
					//Clear the timer, as it might not be needed
					clear( settings.timer );					
					$this.off(".jcdevt").data("jcdData", settings);	
					
					//As this can be accessed via the init method as well,
					//we need to check how this method is being accessed
					if( !internal ) {
						
						if( settings.onChange ) {
							$this.on("change.jcdevt", settings.onChange);
						}

						if( settings.onComplete ) {
							$this.on("complete.jcdevt", settings.onComplete);
						}
				
						if( settings.onPause ) {
							$this.on("pause.jcdevt", settings.onPause );
						}

						if( settings.onResume ) {
							$this.on("resume.jcdevt", settings.onResume );
						}
				
						settings.timer = setInterval( func, settings.updateTime );
						$this.data("jcdData", settings);
						func(); //Needs to run straight away when changing settings
					}
					
					settings = null;
				});
			},
			resume: function() {			
				//Resumes a countdown timer
				return this.each(function() {
					var $this = $(this),
						settings = $this.data("jcdData"),
						func = $.proxy( timerFunc, $this );
					
					if( !settings ) {
						return true;
					}

					$this.data("jcdData", settings).trigger("resume.jcdevt", [settings] ).trigger("countResume", [settings] );
					//We only want to resume a countdown that hasn't finished
					if( !settings.hasCompleted ) {
						settings.timer = setInterval( func, settings.updateTime );						
																		
						if( settings.stopwatch && settings.direction === "up" ) {

							var t = dateNow( $this ).getTime() - settings.pausedAt.getTime(),
								d = new Date();
							d.setTime( settings.dateObj.getTime() + t );
							
							settings.dateObj = d; //This is internal date
						}					
						
						func();
					}
				});
			},
			pause: function() {	
				//Pause a countdown timer			
				return this.each(function() {
					var $this = $(this),
						settings = $this.data("jcdData");

					if( !settings ) {
						return true;
					}
					
					if( settings.stopwatch ) {
						settings.pausedAt = dateNow( $this );
					}
					//Clear interval (Will be started on resume)
					clear( settings.timer );
					//Trigger pause event handler
					$this.data("jcdData", settings).trigger("pause.jcdevt", [settings] ).trigger("countPause", [settings] );				
				});
			},
			complete: function() {
				return this.each(function() {
					var $this = $(this),
						settings = $this.data("jcdData");

					if( !settings ) {
						return true;
					}
					//Clear timer
					clear( settings.timer );
					settings.hasCompleted = true;
					//Update setting, trigger complete event handler, then unbind all events
					//We don"t delete the settings in case they need to be checked later on
					$this.data("jcdData", settings).trigger("complete.jcdevt").trigger("countComplete", [settings] ).off(".jcdevt");
				});		
			},
			destroy: function() {
				return this.each(function() {
					var $this = $(this),
						settings = $this.data("jcdData");
					
					if( !settings ) {
						return true;
					}
					//Clear timer
					clear( settings.timer );
					//Unbind all events, remove data and put DOM Element back to its original state (HTML wise)
					$this.off(".jcdevt").removeData("jcdData").html( settings.originalHTML );
				});
			},
			getSettings: function( name ) {
				var $this = $(this),
					settings = $this.data("jcdData");
				
				//If an individual setting is required
				if( name && settings ) {
					//If it exists, return it
					if( settings.hasOwnProperty( name ) ) {
						return settings[name];
					}
					return undefined;
				}
				//Return all settings or undefined
				return settings;
			}
		};
	
	if( methods[ method ] ) {
		return methods[ method ].apply( this, slice.call( arguments, 1 ) );
	} else if ( typeof method === "object" || !method ) {
		return methods.init.apply( this, arguments );
	} else {
		$.error("Method "+ method +" does not exist in the jCountdown Plugin");
	}
};

})(jQuery);