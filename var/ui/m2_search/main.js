ui.m2_search.main = Ext.extend(Ext.Panel, {
	/**
	 * @constructor
	 */
	constructor: function(config)
	{
		var grid = new ui.m2_item.main({
			region: 'center'
		});
		var recm = new ui.m2_category.main({
			region: 'north',
			title: 'Разделы каталога',
			split: true,
			collapsible: true,
			collapsed: true,
			height: 300,
			listeners: {
				node_changed: function(node_id,node){
					grid.setParams({'category_id':node_id,'by_category':'true'},true);
				}
			}

		});
		var filter = new ui.m2_search.main_filter({
			region: 'west',
			width: 300,
			split: true,
			collapsible: true,
			listeners: {
				applied: function(data){
					console.log(data);
					var  ttl = data._stitle;
					data._stitle =  '%'+ttl+'%',
					grid.setParams(data, true);
				}
			}
		});
		grid.on({
			recommended: function(){
				recm.getStore().reload();
			},
			unrecommended: function(){
				recm.getStore().reload();
			},
			scope: this
		});
		recm.on({
			unrecommended: function(){
				grid.getStore().reload();
			},
			scope: this
		});
		Ext.apply(this, {
			layout: 'border',
			items: [
				filter,
				grid,
				recm
			]
		});
		config = config || {};
		Ext.apply(this, config);
		ui.m2_search.main.superclass.constructor.call(this, config);
	},

	/**
	 * To manually set default properties.
	 * 
	 * @param {Object} config Object containing all config options.
	 */
	configure: function(config)
	{
		config = config || {};
		Ext.apply(this, config, config);
	},

	/**
	 * @private
	 * @param {Object} o Object containing all options.
	 *
	 * Initializes the box by inserting into DOM.
	 */
	init: function(o)
	{
	}
});
