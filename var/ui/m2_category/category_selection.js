ui.m2_category.category_selection = Ext.extend(Ext.tree.TreePanel, {
	msgLoad: "Загрузка данных...",
	menuSelect: 'Выбрать',
	Selection: function(node, e){
		var r = node;
		if(r.attributes.type == 2){
			return;
		}
		this.fireEvent('selected', {
			category_id: r.id,
			category_title: r.text
		});
	},
	/**
	 * @constructor
	 */
	constructor: function(config){
		config = config || {};
		Ext.apply(this, {
			loader: new Ext.tree.TreeLoader({url: 'di/m2_category/slice.json'}),
			root: new Ext.tree.AsyncTreeNode({id: 1, draggable: false, expanded: true}),
			rootVisible: false,
			autoScroll: true,
			loadMask: new Ext.LoadMask(Ext.getBody(), {msg: this.msgLoad}),
			tbar: [
				'->', {iconCls: 'help', handler: function(){showHelp('category_selection')}}
			]
		});
		Ext.apply(this, config);
		ui.m2_category.category_selection.superclass.constructor.call(this, config);
		this.on({
			contextmenu: function(node, e){
				if(node.attributes.type == 2){
					return;
				}
				var cmenu = new Ext.menu.Menu({items: [
					{iconCls: 'accept', text: this.menuSelect, handler: function(){this.Selection(node, e)}, scope: this}
				]});
				e.stopEvent();
				cmenu.showAt(e.getXY());
			},
			dblclick: this.Selection,
			scope: this
		})
	},
	/**
	 * To manually set default properties.
	 * 
	 * @param {Object} config Object containing all config options.
	 */
	configure: function(config){
		config = config || {};
		Ext.apply(this, config, config);
	},
	/**
	 * @private
	 * @param {Object} o Object containing all options.
	 *
	 * Initializes the box by inserting into DOM.
	 */
	init: function(o){
	}
});
