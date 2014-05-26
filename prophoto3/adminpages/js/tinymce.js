(function(){
	tinymce.create('tinymce.plugins.p3InsertBreak', {
		init : function(ed, url){
			ed.addCommand('insertP3Br', function(){
				ed.selection.setContent('<br class="p3br"/>');
			});
			ed.addButton('p3InsertBreak', {
				title:'Force a new line when left/right aligning images', 
				cmd:'insertP3Br', 
				image: 'http://prophoto.s3.amazonaws.com/img/break.gif'
			});
		}
	});
	tinymce.PluginManager.add('p3InsertBreak', tinymce.plugins.p3InsertBreak);
})();