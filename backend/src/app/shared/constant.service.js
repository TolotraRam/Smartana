(function() {
	'use.strict'

	angular
		.module('constants.service', [])

		.service('Constants', Constants);

		function Constants(){
  	
		  	var _API = {
		  		baseUrl: "http://api.dev/api/admin"
		    	//baseUrl: "http://localhost:3000"
		  	}

  			var _img = {
    			avatar : "img/avatar.png",
    			profile_bg : "img/bg_new.png"
  			}
  
			var constants = {
				DEBUGMODE : false,
				SHOWBROADCAST_EVENTS : true,
				API: _API,
				IMG: _img
			};



  			return constants;
		}
})();