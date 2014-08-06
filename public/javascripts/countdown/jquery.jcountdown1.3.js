/*! jCountdown jQuery Plugin - v1.5.0 - 2013-09-21
* https://github.com/tomgrohl/jCountdown/
* Copyright (c) 2013 Tom Ellis; Licensed MIT */

(function($) {
$.fn.countdown = function( method /*, options*/ ) {

	var msPerHr = 3600000,
		secPerYear = 31557600,
		secPerMonth = 2629800, 
		secPerWeek = 604800,
		secPerDay = 86400,
		secPerHr = 3600,
		secPerMin = 60,
		secPerSec = 1,
		rDigitGlobal = /\d/g,
		localNumber = function( numToConvert, settings ) {
			
			var arr = numToConvert.toString().match(rDigitGlobal),
				localeNumber = "";
			
			$.each( arr, function(i,num) {
				num = Number(num);				
				localeNumber += (""+ settings.digits[num]) || ""+num;
			});
			
			return localeNumber;
		},
		generateTemplateCustom = function( settings ) {
			var template = settings.template,
				$parent = $('<div>'),
				$timeWrapElement = settings.dom.$time.addClass( settings.timeWrapClass ),
				$textWrapElement = $("<"+settings.textWrapElement+">").addClass( settings.textWrapClass ),
				
				sep = settings.timeSeparator,
				
				yearsLeft = settings.yearsLeft,
				monthsLeft = settings.monthsLeft,
				weeksLeft = settings.weeksLeft,
				daysLeft = settings.daysLeft,
				hrsLeft = settings.hrsLeft,
				minsLeft = settings.minsLeft,
				secLeft = settings.secLeft,
				
				hideYears = false,
				hideMonths = false,
				hideWeeks = false,
				hideDays = false,
				hideHours = false,
				hideMins = false,
				hideSecs = false,
				timeTasks = [],
				formatTime = function(time, text, showSeparator) {
					var timeStr = $timeWrapElement.clone().html( time + settings.spaceCharacter ).html();
					timeStr += $textWrapElement.clone().html( text + settings.spaceCharacter ).html();
					if( showSeparator )
					{
						timeStr += $textWrapElement.clone().html( settings.spaceCharacter + sep + settings.spaceCharacter ).html();
					}
					return timeStr;
				};

			if( settings.omitZero ) {
				
				if( settings.yearsAndMonths ) {

					if( !settings.yearsLeft ) {
						hideYears = true;
					}
					if( !settings.monthsLeft ) {
						hideMonths = true;
					}				
				}
			
				if( settings.weeks && ( ( settings.yearsAndMonths && hideMonths && !settings.weeksLeft ) || ( !settings.yearsAndMonths && !settings.weeksLeft ) ) ) {	
					hideWeeks = true;
				}
			
				if( hideWeeks && !daysLeft ) {
					hideDays = true;
				}

				if( hideDays && !hrsLeft ) {
					hideHours = true;
				}
								
				if( hideHours && !minsLeft ) {
					hideMins = true;
				}
							
			}		
			
			if( settings.leadingZero ) {
				
				if( yearsLeft < 10 ) {
					yearsLeft = "0" + yearsLeft;
				}

				if( monthsLeft < 10 ) {
					monthsLeft = "0" + monthsLeft;
				}

				if( weeksLeft < 10 ) {
					weeksLeft = "0" + weeksLeft;
				}
				
				if( daysLeft < 10 ) {
					daysLeft = "0" + daysLeft;
				}
				
				if( hrsLeft < 10 ) {
					hrsLeft = "0" + hrsLeft;
				}
				
				if( minsLeft < 10 ) {
					minsLeft = "0" + minsLeft;
				}
				
				if( secLeft < 10 ) {
					secLeft = "0" + secLeft;
				}								
			}	
			
			yearsLeft = localNumber( yearsLeft, settings );
			monthsLeft = localNumber( monthsLeft, settings );
			weeksLeft = localNumber( weeksLeft, settings );
			daysLeft = localNumber( daysLeft, settings );
			hrsLeft = localNumber( hrsLeft, settings );
			minsLeft = localNumber( minsLeft, settings );
			secLeft = localNumber( secLeft, settings );
			
			if( settings.yearsAndMonths ) {
								
				if( !settings.omitZero || !hideYears  ) {
					template = template.replace('%y', formatTime(yearsLeft, settings.yearText, true));
				}
				
				//Only hide months if years is at 0 as well as months
				if( !settings.omitZero || ( !hideYears && monthsLeft ) || ( !hideYears && !hideMonths ) ) {
					template = template.replace('%m', formatTime(monthsLeft, settings.monthText, true));
				} else {
					template = template.replace('%m', '');
				}

			}
			
			if( settings.weeks && !hideWeeks ) {
				template = template.replace('%w', formatTime(weeksLeft, settings.weekText, true));
			} else {
				template = template.replace('%w', '');
			}

			if( !hideDays ) {
				template = template.replace('%d', formatTime(daysLeft, settings.dayText, true));
			} else {
				template = template.replace('%d', '');
			}

			if( !hideHours ) {
				template = template.replace('%h', formatTime(hrsLeft, settings.hourText, true));
			} else {
				template = template.replace('%h', '');
			}
			
			if( !hideMins ) {
				template = template.replace('%i', formatTime(minsLeft, settings.minText, true));
			} else {
				template = template.replace('%i', '');
			}
		
			template = template.replace('%s', formatTime(secLeft, settings.secText));

			return template;
		},
		generateTemplate = function( settings ) {
			
			var $parent = $('<div>'),
				$timeWrapElement = $("<"+settings.timeWrapElement+">").addClass( settings.timeWrapClass ),
				$textWrapElement = $("<"+settings.textWrapElement+">").addClass( settings.textWrapClass ),
				
				sep = settings.timeSeparator,
				
				yearsLeft = settings.yearsLeft,
				monthsLeft = settings.monthsLeft,
				weeksLeft = settings.weeksLeft,
				daysLeft = settings.daysLeft,
				hrsLeft = settings.hrsLeft,
				minsLeft = settings.minsLeft,
				secLeft = settings.secLeft,
				template = settings.template,
				hasTemplate = !!settings.template,
				hideYears = false,
				hideMonths = false,
				hideWeeks = false,
				hideDays = false,
				hideHours = false,
				hideMins = false,
				hideSecs = false,
				timeTasks = [],
				addTime = function( time ) {
					timeTasks.push(function() {
						$parent.append( $timeWrapElement.clone().html( time + settings.spaceCharacter ) );
					});					
				},
				addText = function( text ) {
					timeTasks.push(function() {
						$parent.append( $textWrapElement.clone().html( text + settings.spaceCharacter ) );
					});					
				},
				addSeparator = function() {
					timeTasks.push(function() {
						$parent.append( $textWrapElement.clone().html( settings.spaceCharacter + sep + settings.spaceCharacter) );
					});				
				},
				formatTime = function(time, text, showSeparator) {
					var timeStr = $timeWrapElement.clone().html( time + settings.spaceCharacter ).html();
					timeStr += $textWrapElement.clone().html( text + settings.spaceCharacter ).html();
					if( showSeparator )
					{
						timeStr += $textWrapElement.clone().html( settings.spaceCharacter + sep + settings.spaceCharacter ).html();
					}
					return timeStr;
				};
			
			if( settings.template )
			{
				return generateTemplateCustom( settings );
			}		

			if( settings.omitZero ) {
				
				if( settings.yearsAndMonths ) {

					if( !settings.yearsLeft ) {
						hideYears = true;
					}
					if( !settings.monthsLeft ) {
						hideMonths = true;
					}				
				}
			
				if( settings.weeks && ( ( settings.yearsAndMonths && hideMonths && !settings.weeksLeft ) || ( !settings.yearsAndMonths && !settings.weeksLeft ) ) ) {	
					hideWeeks = true;
				}
			
				if( hideWeeks && !daysLeft ) {
					hideDays = true;
				}

				if( hideDays && !hrsLeft ) {
					hideHours = true;
				}
								
				if( hideHours && !minsLeft ) {
					hideMins = true;
				}
							
			}		
			
			if( settings.leadingZero ) {
				
				if( yearsLeft < 10 ) {
					yearsLeft = "0" + yearsLeft;
				}

				if( monthsLeft < 10 ) {
					monthsLeft = "0" + monthsLeft;
				}

				if( weeksLeft < 10 ) {
					weeksLeft = "0" + weeksLeft;
				}
				
				if( daysLeft < 10 ) {
					daysLeft = "0" + daysLeft;
				}
				
				if( hrsLeft < 10 ) {
					hrsLeft = "0" + hrsLeft;
				}
				
				if( minsLeft < 10 ) {
					minsLeft = "0" + minsLeft;
				}
				
				if( secLeft < 10 ) {
					secLeft = "0" + secLeft;
				}								
			}	
			
			yearsLeft = localNumber( yearsLeft, settings );
			monthsLeft = localNumber( monthsLeft, settings );
			weeksLeft = localNumber( weeksLeft, settings );
			daysLeft = localNumber( daysLeft, settings );
			hrsLeft = localNumber( hrsLeft, settings );
			minsLeft = localNumber( minsLeft, settings );
			secLeft = localNumber( secLeft, settings );
			
			if( settings.yearsAndMonths ) {
								
				if( !settings.omitZero || !hideYears  ) {
										
					addTime( yearsLeft );					
					addText( settings.yearText );
					addSeparator();

					/*if( hasTemplate )
					{
						template = template.replace('%y', formatTime(yearsLeft, settings.yearText, true));
					}*/
				}
				
				//Only hide months if years is at 0 as well as months
				if( !settings.omitZero || ( !hideYears && monthsLeft ) || ( !hideYears && !hideMonths ) ) {

					addTime( monthsLeft );
					addText( settings.monthText );
					addSeparator();
				}
			}
			
			if( settings.weeks && !hideWeeks ) {
				addTime( weeksLeft );
				addText( settings.weekText );
				addSeparator();
			}

			if( !hideDays ) {
				addTime( daysLeft );
				addText( settings.dayText );
				addSeparator();
			}

			if( !hideHours ) {
				addTime( hrsLeft );
				addText( settings.hourText );
				addSeparator();
			}
			
			if( !hideMins ) {
				addTime( minsLeft );
				addText( settings.minText );
				addSeparator();
			}
			
			
			addTime( secLeft );			
			addText( settings.secText );
						
			if( settings.isRTL === true ) {
				timeTasks.reverse();
			}
			
			$.each( timeTasks, function(i,task ) {
				task();
			});
			
			template = $parent.html();
			
			return template;
		},
		dateNow = function( $this ) {
			var now = new Date(), //Default to local time
				settings = $this.data("jcdData");
			
			if( !settings ) {
				return new Date();
			}
			
			if( settings.offset !== null ) {
				now = getTZDate( settings.offset );
			}
			
			now.setMilliseconds(0);
			return now;
		},
		getTZDate = function( offset ) {
			// Returns date now based on timezone/offset
			var hrs,
				dateMS,
				curHrs,
				tmpDate = new Date();
				
			if( offset !== null ) {
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
				yearsLeft = 0,
				monthsLeft = 0,
				weeksLeft = 0,
				daysLeft = 0,
				hrsLeft = 0,
				minsLeft = 0,
				secLeft = 0,
				time = "",
				diff,

				hideYears = false,
				hideMonths = false,
				hideWeeks = false,
				hideDays = false,
				hideHours = false,
				hideMins = false,
				hideSecs = false,

				extractSection = function( numSecs ) {
					var amount;
	
					amount = Math.floor( diff / numSecs );
					diff -= amount * numSecs;
					
					return amount;
				},
				settings = $this.data("jcdData");
				
			if( !settings ) {
				return false;
			}
			
			template = settings.htmlTemplate;
			now = dateNow( $this );
			
			if( settings.serverDiff !== null ) {
				date = new Date( settings.serverDiff + settings.clientdateNow.getTime() );
			} else {
				date = settings.dateObj; //Date to countdown to
			}
			
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

			/**
			* The following 3 option should never be used together!
			* MAKE them work for any time
			*/
			
			if( settings.hoursOnly || settings.minsOnly || settings.secsOnly )
			{
				
				if( settings.yearsAndMonths ) {
					//Add years, months, weeks and days back on so we can calculate dates easier
					diff += ( yearsLeft * secPerYear );
					diff += ( monthsLeft * secPerMonth );

					yearsLeft = monthsLeft = 0;
				}

				if( settings.weeks ) {
					diff += ( weeksLeft * secPerWeek );

					weeksLeft = 0;
				}


				//minsLeft = extractSection( secPerMin );
				//secLeft = extractSection( secPerSec );				
			}	

			//Assumes you are using dates within a month  ( ~ 30 days )
			//as years and months aren't taken into account
			if( settings.hoursOnly ) {

				// Add days back on
				diff += ( daysLeft * secPerDay );

				// Add hours back on
				diff += ( hrsLeft * secPerHr );
				hrsLeft = extractSection( secPerHr );

			}
			
			//Assumes you are only using dates in the near future ( <= 24 hours )
			//as years and months aren't taken into account
			if( settings.minsOnly ) {

				
				// Add days back on
				diff += ( daysLeft * secPerDay );
				daysLeft = 0;

				// Add hours back on
				diff += ( hrsLeft * secPerHr );
				hrsLeft = 0;

				diff += ( minsLeft * secPerMin );
				
				minsLeft = extractSection( secPerMin );
				

				
				//minsLeft += ( hrsLeft * 60 ) + ( ( daysLeft * 24 ) * 60 );
				//daysLeft = hrsLeft = 0;
				
			}

			//Assumes you are only using dates in the near future ( <= 60 minutes )
			//as years, months and days aren't taken into account
			if( settings.secsOnly ) {

				
				// Add days back on
				diff += ( daysLeft * secPerDay );
				daysLeft = 0;

				// Add hours back on
				diff += ( hrsLeft * secPerHr );
				hrsLeft = 0;

				// Add minutes back on
				diff += ( minsLeft * secPerMin );
				minsLeft = 0;

				// Add seconds back on
				diff += secLeft;
				//minsLeft = 0;

				secLeft = extractSection( secPerSec );
				

				/*
				secLeft += ( minsLeft * 60 );
				daysLeft = hrsLeft = minsLeft = 0;
				*/
			}
						
			settings.yearsLeft = yearsLeft;
			settings.monthsLeft = monthsLeft;
			settings.weeksLeft = weeksLeft;
			settings.daysLeft = daysLeft;
			settings.hrsLeft = hrsLeft;
			settings.minsLeft = minsLeft;
			settings.secLeft = secLeft;

			$this.data("jcdData", settings);
			
			if ( ( settings.direction === "down" && ( now < date || settings.minus ) ) || ( settings.direction === "up" && ( date < now || settings.minus )  ) ) {
				time = generateTemplate( settings );
			} else {
				settings.yearsLeft = settings.monthsLeft = settings.weeksLeft = settings.daysLeft = settings.hrsLeft = settings.minsLeft = settings.secLeft = 0;
								
				time = generateTemplate( settings );
				settings.hasCompleted = true;
			}
							
			$this.html( time ).triggerMulti("change.jcdevt,countChange", [settings]);
			
			if ( settings.hasCompleted ) {
				$this.triggerMulti("complete.jcdevt,countComplete");
				clearInterval( settings.timer );
			}
			
			$this.data("jcdData", settings);
			
		},			
		methods = {		
			init: function( options ) {
				
				var opts = $.extend( {}, $.fn.countdown.defaults, options ),
					local = null,
					testDate,
					testString;
				
				return this.each(function() {
					var $this = $(this),
						settings = {},
						func;

					//If this element already has a countdown timer, just change the settings
					if( $this.data("jcdData") ) {
						$this.countdown("changeSettings", options, true);
						opts = $this.data("jcdData");
					}

					if( opts.date === null && opts.dataAttr === null ) {
						$.error("No Date passed to jCountdown. date option is required.");
						return true;
					}
					
					if( opts.date ) {					
						testString = opts.date;
					} else {
						testString = $this.data(opts.dataAttr);
					}
					
					
					testDate = new Date(testString);
					
					if( testDate.toString() === "Invalid Date" ) {
						$.error("Invalid Date passed to jCountdown: " + testString);
					}
					
					//Add event handlers where set
					if( opts.onStart ) {
						$this.on("start.jcdevt", opts.onStart );
					}
					
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


					if( opts.onLocaleChange ) {
						$this.on("locale.jcdevt", opts.onLocaleChange );
					}
					
					settings = $.extend( {}, opts );

					
					// Cache DOM elements for templating
					settings.dom = {};

					settings.dom.$time = $("<"+settings.timeWrapElement+">").addClass( settings.timeWrapClass );
					settings.dom.$text = $("<"+settings.textWrapElement+">").addClass( settings.textWrapClass );
					
					//$timeWrapElement = $("<"+settings.timeWrapElement+">").addClass( settings.timeWrapClass ),
					//$textWrapElement = $("<"+settings.textWrapElement+">")

					settings.clientdateNow = new Date();
					settings.clientdateNow.setMilliseconds(0);					
					settings.originalHTML = $this.html();
					settings.dateObj = new Date( testString );
					settings.dateObj.setMilliseconds(0);
					settings.hasCompleted = false;
					settings.timer = 0;
					settings.yearsLeft = settings.monthsLeft = settings.weeksLeft = settings.daysLeft = settings.hrsLeft = settings.minsLeft = settings.secLeft = 0;
					settings.difference = null;
					
					func = $.proxy( timerFunc, $this );
					settings.timer = setInterval( func, settings.updateTime );

					$this.data("jcdData", settings).triggerMulti("start.jcdevt,countStart", [settings]);
					
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
					
					settings.completed = false;
					settings.dateObj  = new Date( options.date );
					
					//Clear the timer, as it might not be needed
					clearInterval( settings.timer );					
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

					//$this.data("jcdData", settings).trigger("resume.jcdevt", [settings] ).trigger("countResume", [settings] );
					$this.data("jcdData", settings).triggerMulti("resume.jcdevt,countResume", [settings] );
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
					clearInterval( settings.timer );
					//Trigger pause event handler
					$this.data("jcdData", settings).triggerMulti("pause.jcdevt,countPause", [settings] );
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
					clearInterval( settings.timer );
					settings.hasCompleted = true;
					//Update setting, trigger complete event handler, then unbind all events
					//We don"t delete the settings in case they need to be checked later on

					$this.data("jcdData", settings).triggerMulti("complete.jcdevt,countComplete", [settings]);
					
					//$this.off(".jcdevt, co");
					
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
					clearInterval( settings.timer );
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
			},
			changeLocale: function( locale ) { //new in v1.5.0
				var $this = $(this),
					settings = $this.data("jcdData");
				
				// If no locale exists error and return false
				if( !$.fn.countdown.locale[locale] ) {
					$.error("Locale '" + locale + "' does not exist");
					return false;
				}
					
				$.extend( settings, $.fn.countdown.locale[locale] );
				
				$this.data("jcdData", settings).triggerMulti("locale.jcdevt,localeChange", [settings]);
				
				return true;
			}
		};
	
	if( methods[ method ] ) {
		return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ) );
	} else if ( typeof method === "object" || !method ) {
		return methods.init.apply( this, arguments );
	} else {
		$.error("Method "+ method +" does not exist in the jCountdown Plugin");
	}
};

// new in v1.5.0
$.fn.countdown.defaults = {
	date: null,
	dataAttr: null,
	updateTime: 1000,			
	yearText: 'years',
	monthText: 'months',
	weekText: 'weeks',
	dayText: 'days',
	hourText: 'hours',
	minText: 'mins',
	secText: 'sec',
	digits : [0,1,2,3,4,5,6,7,8,9],
	timeWrapElement: 'span',
	textWrapElement: 'span',
	timeWrapClass: '',
	textWrapClass: 'cd-time',
	timeSeparator: '',
	isRTL: false,
	minus: false,
	onStart: null,
	onChange: null,
	onComplete: null,
	onResume: null,
	onPause: null,
	onLocaleChange: null,
	leadingZero: false,
	offset: null,
	serverDiff:null,
	spaceCharacter: ' ',
	hoursOnly: false,
	minsOnly: false,
	secsOnly: false,
	weeks: false,
	hours: false,
	yearsAndMonths: false,
	direction: "down",
	stopwatch: false,
	omitZero: false,
	template: null
};

//Create an array to store new locales
// new in v1.5.0
$.fn.countdown.locale = [];

// new in v1.5.0
$.fn.triggerMulti = function( eventTypes, extraParameters ) {
	var events = eventTypes.split(",");
		
	return this.each(function() {
		var $this = $(this);

		for( var i = 0; i < events.length; i++) {
			$this.trigger( events[i], extraParameters );
		}	
	});
};

})(jQuery);