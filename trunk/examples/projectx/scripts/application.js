/**
 * Application class.  This holds all models, controllers, and views,
 * communicates between them, and monitors URL hash changes.
 */
(function ($) {

	/**
	 * Properties
	 */
	function Application() {
		// application ID
		this.id = ("app-" + (new Date()).getTime());
		
		// how often to check the URL hash
		this.locationMonitorInterval = null;
		this.locationMonitorDelay = 150;
		
		// current map location
		this.currentLocation = null;
		
		// all map routes to controllers
		this.routeMappings = [];
		
		// all application controllers
		this.controllers = [];
		
		// all application models
		this.models = {
			cache: {},
			classes: {}
		};
		
		// all application views
		this.views = {
			cache: {},
			classes: {}
		};

		// TODO find out what this is
		this.locationEvent = null;
		
		// TODO find out what this is
		$(this).bind("locationchange", this.proxyCallback(this.onLocationChange));
		
		// indicates that the application has not started
		this.isRunning = false;
	}
	
	/**
	 * Stores a class in the Application object
	 *
	 * @param target The list to add the class to
	 * @param value The class to add
	 */
	Application.prototype.addClass = function (target, value){
		// Get the constructor of our value class.
		var constructor = value.constructor;

		var className = constructor.toString().match(new RegExp("^function\\s+([^\\s\\(]+)", "i"))[1];
		
		// if argument is a class definition
		if (constructor == Function){
			
			// store the constructor
			target.classes[className] = value;
			
		} else {
		
			// store the constructor
			target.classes[className] = constructor;
			
			// store the instance as a singleton
			target.cache[className] = value;
			
			if (this.isRunning){
				this.initClass( value );
			}
			
		}

		return this;
	};
	
	/**
	 * Add a controller singleton instance to the list kept internally
	 *
	 * @param controller Controller to add to the list
	 */
	Application.prototype.addController = function(controller){
		
		this.controllers.push(controller);
		
		// initalize if necessary
		if (this.isRunning){
			this.initClass( controller );
		}

		return this;
	};
	
	/**
	 * Add model to internal list
	 *
	 * @param model Model to store.  Either an instance (to act as a singleton)
	 * or the class itself.
	 */
	Application.prototype.addModel = function (model) {
		
		this.addClass(this.models, model);

		return this;
	};
	
	/**
	 * Add view to internal list.
	 *
	 * @param view View to store.  Either an instance (to act as a singleton) or
	 * the class itself.
	 */
	Application.prototype.addView = function (view) {
		
		this.addClass(this.views, view);

		return this;
	};
	
	/**
	 * Process location and change it if necessary
	 */
	Application.prototype.checkLocationForChange = function () {

		// get hash location and clean it
		var liveLocation = this.normalizeHash( window.location.hash );
			
		// if location is actually different
		if (this.currentLocation == null || this.currentLocation != liveLocation){
		
			// direct the application to the new location
			this.setLocation(liveLocation);
			
		}
	};
	
	/**
	 * Get the singleton or a new instance of the specified class
	 *
	 * @param target List of classes to get from
	 * @param className Name of the class to load
	 * @param initArguments Arguments to pass to the class
	 */
	Application.prototype.getClass = function (target, className, initArguments) {

		var object;
		// check to see if it's a singleton
		if (target.cache[className]) {
		
			// return the singleton
			object = target.cache[className];
		
		} else {
		
			// create new instance of the class
			var newInstance = new (target.classes[ className ])();
			
			// TODO figure out how this works
			target.classes[ className ].apply( newInstance, initArguments );
			
			object = newInstance;
			
		}

		return object;
	};
	
	/**
	 * Get a model, whether singleton or a new instance
	 *
	 * @param className Name of the class to get
	 * @param initArguments Arguments to pass to the constructor
	 */
	Application.prototype.getModel = function (className, initArguments) {
		
		return this.getClass(this.models, className, initArguments);
	};

	/**
	 * Get a view, whether singleton or a new instance
	 *
	 * @param className Name of the class to get
	 * @param initArguments Arguments to pass to the constructor
	 */
	Application.prototype.getView = function( className, initArguments ){
		return( this.getClass( this.views, className, initArguments ) );
	};

	/**
	 * Initialize a class instance
	 *
	 * @param instance Object to call init() on
	 */
	Application.prototype.initClass = function( instance ){
		// Check to see if the target instance has an init method.
		if (instance.init){
			// Invoke the init method.
			instance.init();
		}
	};
	
	/**
	 * Initialize a list of classes
	 *
	 * @param classes Classes to initialize
	 */
	Application.prototype.initClasses = function( classes ){
		
		var self = this;
		
		// loop over the classes
		$.each(
			classes,
			function (index, instance) {
				// initialize class
				self.initClass( instance );
			}
		);
	};
	
	
	/**
	 * Initialize all controller singletons
	 */
	Application.prototype.initControllers = function () {

		this.initClasses(this.controllers);
	};
		
	/**
	 * Initialize all model singletons
	 */
	Application.prototype.initModels = function () {

		this.initClasses( this.models.cache );
	};
	
	
	/**
	 * Initialize all view singletons
	 */
	Application.prototype.initViews = function () {

		this.initClasses( this.views.cache );
	};
	
	/**
	 * Clean a hash location value
	 *
	 * @param hash Hash location
	 */
	Application.prototype.normalizeHash = function (hash) {

		// change "#/controller/" to "controller"
		return hash.replace(new RegExp("^[#/]+|/$", "g"), "");
	};
	
	/**
	 * I handle the location changes.
	 *
	 * @todo find out what this does
	 */
	Application.prototype.onLocationChange = function( locationChangeEvent ){
		var self = this;
		
		// I am used to determine if the application should continue routing the request.
		// Depending on the return value of a given event handler, the current routing
		// can be cancelled. 
		var keepRouting = true;
		
		// I am used to determine if a route was found for the given even. If not, a 
		// 404 - page not found route will be executed.
		var routeFound = false;
		
		// Turn off monitoring while we route the location. We are doing this
		// to allow the application time to process the route without being 
		// interupted. This will prevent someone clicking rappidly around the
		// application from causing unexpected effects.
		this.stopLocationMonitor();
		
		// Iterate over the route mappings.
		$.each(
			this.routeMappings,
			function( index, mapping ){
				var matches = null;
				
				// Check to see if routing has been cancelled.
				if (!keepRouting){
					return;
				}
			
				// Define the default event context. 
				var eventContext = {
					application: self,
					fromLocation: locationChangeEvent.fromLocation,
					toLocation: locationChangeEvent.toLocation,
					parameters: $.extend( {}, locationChangeEvent.parameters )
				};
				
				// Get the matches from the location (if the route mapping does 
				// not match, this will return null) and check to see if this route 
				// mapping applies to the current location (if no matches are returned,
				// matches array will be null).
				if (matches = locationChangeEvent.toLocation.match( mapping.test )){
					
					// The route mapping will handle this location change. Now, we
					// need to prepare the event context and invoke the route handler.
					
					// Remove the first array (the entire location match). This is 
					// irrelevant information. What we want are the captured groups 
					// which will be in the subsequent indices.
					matches.shift();
					
					// Map the captured group matches to the ordered parameters defined
					// in the route mapping.
					$.each(
						matches,
						function( index, value ){
							eventContext.parameters[ mapping.parameters[ index ] ] = value;
						}
					);
					
					// Check to see if this controller has a pre-handler.
					if (mapping.controller.preHandler){
						// Execute the pre-handler.
						mapping.controller.preHandler( eventContext );
					}
					
					// Execute the handler in the context of the controller. Even though the
					// event parameteres are getting passed as part of the event context, I
					// am going to pass them through as part of the argument collection for
					// conveinence in the handler's method signature.
					var result = mapping.handler.apply(
						mapping.controller,
						[ eventContext ].concat( matches )
					);
					
					// Check to see if this controller has a post-handler.
					if (mapping.controller.postHandler){
						// Execute the post-handler.
						mapping.controller.postHandler( eventContext );
					}
						
					// Check the controller handler's result value to see if we need to stop 
					// routing. If the controller returns false, we are going to stop routing.
					if (
						(typeof( result ) == "boolean") &&
						!result						
						){
						// Cancel routing.

						keepRouting = false;
					}
					
					// Flag that a route was found.
					routeFound = true;			
				}
			}
		);
		
		// Turn monitoring back on now that the routing has completed.
		this.startLocationMonitor();
		
		// Check to see if a route was found. If not, then we need to trigger a 404 event.
		if (!routeFound){
			
			// Trigger a 404 location. 
			// NOTE: This will currently break the ability to use the back button on the 
			// browser (since it will keep trying to relocate to the 404 on back). Will need
			// to rethink the way this is handled later.
			this.relocateTo( "404" );
			
		}
	};
	
	
	/**
	 * I create a proxy for the callback so that given callback executes in the 
	 * context of the application object, overriding any context provided by the
	 * calling context.
	 *
	 * @todo find out why
	 */
	Application.prototype.proxyCallback = function( callback ){
		var self = this;
		
		// Return a proxy that will apply the callback in the THIS context.
		return(
			function(){
				return( callback.apply( self, arguments ) );
			}	
		);
	}
	
	
	/**
	 * Change the application route location.
	 *
	 * @param newLocation The new route to go to
	 * @param parameters Arguments to be passed to the controller
	 * @todo merge with setLocation
	 */
	Application.prototype.relocateTo = function (newLocation, parameters) {
		
		this.setLocation( newLocation, parameters );
	};
	
	
	/**
	 * Start the application
	 */
	Application.prototype.run = function () {
		
		// Initialize the model.
		this.initModels();
		
		// Initialize the views.
		this.initViews();
		
		// Initialize the controllers.
		this.initControllers();
	
		// Initialize the location monitor.
		this.initLocationMonitor();
		
		// Turn on location monitor.
		this.startLocationMonitor();
		
		// Flag that the application is running.
		this.isRunning = true;
	};
		
		
	// I set the location of the application. I don't do anything explicitly - 
	// I let the location monitoring handle this change implicitly. You can also
	// pass through additional parameters that will be added to the location event
	// that gets triggered.
	Application.prototype.setLocation = function( location, parameters ){
		// Clearn the location.
		location = this.normalizeHash( location );
	
		// Create variables to hold the new and old hashes.
		var oldLocation = this.currentLocation;
		var newLocation = location;
		
		// Store the new location.
		this.currentLocation = location;
		
		// Make sure the hash is the same as the location (this way, we don't
		// get circular logic as the monitor keeps pinging the hash).
		window.location.hash = ("#/" + location );
	
		// The location has changed - trigger the change event on the application
		// object so that anyone monitoring it can react.
		$( this ).trigger({
			type: "locationchange",
			fromLocation: oldLocation,
			toLocation: newLocation,
			parameters: parameters
		});
	};
	
	
	// I turn on the location monitoring.
	Application.prototype.startLocationMonitor = function(){
		var self = this;
		
		// Create the interval function.
		this.locationMonitorInterval = setInterval(
			function(){				
				self.checkLocationForChange();
			},
			this.locationMonitorDelay
		);
	};
	
	
	// I turn off the location monitoring.
	Application.prototype.stopLocationMonitor = function(){
		clearInterval( this.locationMonitorInterval );
	};
	
	
	// ----------------------------------------------------------------------- //
	// ----------------------------------------------------------------------- //
	
	
	// I am the prototype for the application controllers. This is so they
	// can leverage some binding magic without the overhead of the implimentation.
	Application.prototype.Controller = function(){
		// ...
	};
	
	
	// I am the prototype for the Controller prototype.
	Application.prototype.Controller.prototype = {
	
		// I route the given pseudo location to the given controller method.
		route: function( path, handler ){
			// Strip of any trailing and leading slashes.
			path = application.normalizeHash( path );
		
			// We will need to extract the parameters into an array - these will be used 
			// to create the event object when the location changes get routed.
			var parameters = [];
			
			// Extract the parameters and replace with capturing groups at the same
			// time (such that when the pattern is tested, we can map the captured
			// groups to the ordered paramters above.
			var pattern = path.replace(
				new RegExp( "(/):([^/]+)", "gi" ),
				function( $0, $1, $2 ){
					// Add the named parameter.
					parameters.push( $2 );
					
					// Replace with a capturing group. This captured group will be used
					// to create a named parameter if this route gets matched.
					return( $1 + "([^/]+)" );
				}
				);
			
			// Now that we have our parameters and our test pattern, we can create our 
			// route mapping (which will be used by the application to match location 
			// changes to controllers).
			application.routeMappings.push({
				controller: this,
				parameters: parameters,
				test: new RegExp( ("^" + pattern + "$"), "i" ),
				handler : handler
			});
		}		
		
	};
	
	
	// ----------------------------------------------------------------------- //
	// ----------------------------------------------------------------------- //
	
	
	// Create a new instance of the application and store it in the window.
	window.application = new Application();
	
	// When the DOM is ready, run the application.
	$(function(){
		window.application.run();
	});
	
	// Return a new application instance.
	return( window.application );
	
})( jQuery );
