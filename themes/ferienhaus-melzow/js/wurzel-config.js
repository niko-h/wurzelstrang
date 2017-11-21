CKEDITOR.stylesSet.addExternal( 'default', [
	{
		name: 'Galerie',
		element: 'div',
		attributes: {
			'class': 'fotorama',
			'data-ratio': '16/10',
			'data-width': '100%',
			'data-nav': 'thumbs',
			'data-thumbheight': '64px',
			'data-allowfullscreen': 'native',
			'data-fit': 'cover',
			'data-loop': 'true',
			'data-autoplay': '7000',
			'data-keyboard': 'true'
		},
		styles: {
			background: '#eee',
			border: '1px solid #ccc'
		}
	}
]);
