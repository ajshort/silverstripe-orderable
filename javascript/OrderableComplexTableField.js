;(function($) {
	$('.OrderableComplexTableField').live('hover', function() {
		$(this).sortable({
			axis: 'y',
			containment: this,
			cursor: 'move',
			items: 'tbody tr',
			handle: 'a.drag',
			update: function(e, ui) {
				var table = $(this);
				var item  = $(ui.item);
				
				table.sortable('disable');
				item.find('a.drag img').attr('src', 'cms/images/network-save.gif');

				var ids = $('tbody tr', this).map(function() {
					var id    = $(this).attr('id');
					var match = id.match(/record-[a-zA-Z0-9_]*-([0-9]+)/);

					if (match) return match[1];
				});

				$(this).load(
					$('a.drag', item).attr('href'),
					{ 'ids[]': ids.get() },
					function() {
						Behaviour.apply(table.get(0));
						table.sortable('enable');
					});
			}
		});
	});
})(jQuery);