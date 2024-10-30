jQuery(document).ready(function($){
	$('.pwa-icon-upload').click(function(e) {	// Application Icon upload
		e.preventDefault();
		var superpwa_meda_uploader = wp.media({
			title: 'Application Icon',
			button: {
				text: 'Select Icon'
			},
			multiple: false  // Set this to true to allow multiple files to be selected
		})
		.on('select', function() {
			var attachment = superpwa_meda_uploader.state().get('selection').first().toJSON();
			//console.log(attachment.mime);
			if(attachment.mime === 'image/png' ){
				if(parseInt(attachment.height) > 191)
				{
					$('.pwa-icon').val(attachment.url);
				} else { alert('file must be greter then 192x192 ')}
			}else { alert ('select only .png file'); }
			
			
		})
		.open();
	});
});