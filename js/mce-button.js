(function() {
    tinymce.PluginManager.add('my_mce_button', function( editor, url ) {
        editor.addButton( 'my_mce_button', {
            title: 'Add Magic Carousel',
            type: 'menubutton',
            icon: 'a-carousel',  //add this text in mce-icons.css file to add shortcode img
            menu: [
                {
                    text: 'Magic Carousel Shortcode',
                    onclick: function() {
                        editor.insertContent(this.value());
                    },
					menu: [
						{
							text: 'Default Style ',
							onclick: function() {
								editor.windowManager.open( {
									title: 'Default Style Shortcode PopUp',
									body: [{
                                                type: 'textbox',
                                                name: 'cTitle',
                                                label: 'Add Carousel Area Title ( add a title here )',
                                        
                                            },
											{
												type: 'textbox',
												name: 'cText',
												label: 'Add Carousel Area Text ( add a short discription here,50-60 word is the best )',
                                                multiline: true,
                                                minWidth: 300,
                                                minHeight: 100,                                            
											}
                                          ],
									onsubmit: function( e ) {
										editor.insertContent( '[magic_carousel title="' + e.data.cTitle + '" text="' + e.data.cText + '"]');}
								});
							}
							
						},
                     

                    ]
                },

                
                   

           ]
        });
    });
})();