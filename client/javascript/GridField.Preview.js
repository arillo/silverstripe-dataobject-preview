(function($) {
	$.entwine('ss.preview', function($){
			$('.cms-preview').entwine({
					// DefaultMode: 'content',
					getSizes: function() {
							var sizes = this._super();
							sizes.desktop.width = '1200px';
							return sizes;
					}
			});
	});
}(jQuery));
