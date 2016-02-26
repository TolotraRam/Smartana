(function() {
	'use strict';

	angular
		.module('replace.filter', [])
		.filter("replace", function(){
		 	return function(str, pattern, replacement, global){
		    	global = (typeof global === 'undefined' ? true : global);
			    try {
				  	str = str ? (typeof global === 'string' ? str : str.toString()) : '';
			      	return str.replace(new RegExp(pattern,global ? "g": ""),function() {
			        	return replacement;
			      	});  
			    } catch(e) {
			      return (str || '');
			    }     
		  	};
		});
})();