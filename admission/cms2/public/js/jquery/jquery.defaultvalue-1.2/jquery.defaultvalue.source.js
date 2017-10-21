/**
*	@name							Defaultvalue
*	@descripton						Gives value to empty inputs
*	@version						1.2
*	@requires						Jquery 1.3.2
*
*	@author							Jan Jarfalk
*	@author-email					jan.jarfalk@unwrongest.com
*	@author-website					http://www.unwrongest.com
*
*	@licens							MIT License - http://www.opensource.org/licenses/mit-license.php
*
*	@param {String} str				The default value
*	@param {Function} callback		Callback function
*/

(function(jQuery){
     jQuery.fn.extend({
         defaultValue: function(str, callback) {	
            return this.each(function() {
				
				var $input				=	$(this),
					defaultValue		=	str || $input.attr('rel'),
					callbackArguments 	=	{'input':$input};
					
					
				if( $input.attr('type') == 'password' ) {
					handlePasswordInput();
				} else {
					handleTextInputs();
				}
				
				function handlePasswordInput(){
				
					// Create clone and switch
					var $clone = createClone();
					
					// Add clone to callback arguments
					callbackArguments.clone = $clone;
					
					$clone.insertAfter($input);
					$input.hide();
					
					// Events for password fields
					$input.blur(function(){
						if( $input.val().length <= 0 ){
							$clone.show();
							$input.hide();
						}
					});
					
				}
				
				function handleTextInputs(){
					
					// Set current state
					setState();
					
					// Events for non-password fields
					$input.keypress( function () {
						if( $input.val().length > 0 ) {
							setState();
						}
					}).blur(setState).focus( function () {
						$input.val() == defaultValue && $input.val('');
					});
					
					// Remove default values from fields on submit
					$input.closest("form").submit(function() {
  						$input.val() == defaultValue && $input.val('');
					});
					
				}
				
				function setState(){
					val = jQuery.trim($input.val());
					if( val.length <= 0 || val == defaultValue ) {
						$input.val(defaultValue);
						$input.addClass('empty');
					} else {
						$input.removeClass('empty');
					}
				}
				
				
				// Create a text clone of password fields
				function createClone(){
					
					var $el = jQuery("<input />").attr({
						'type'	: 'text',
						'value'	: defaultValue,
						'class'	: $input.attr('class')+' empty',
						'style'	: $input.attr('style'),
						'tabindex' : $input.attr('tabindex')
					});
					
					$el.focus(function(){
					
						// Hide text clone and show real password field
						$el.hide();
						$input.show();
						
						// Webkit and Moz need some extra time
						// BTW $input.show(0,function(){$input.focus();}); doesn't work.
						setTimeout(function () {
							$input.focus();
						}, 1);
					
					});				
					
					return $el;
				}
				
				if(callback){
					callback(callbackArguments);
				}	
				
            });
        }
    });
})(jQuery);