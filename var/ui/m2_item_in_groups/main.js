ui.m2_item_in_groups.main = Ext.extend(Ext.Panel, {
	/**
	 * @constructor
	 */
	constructor: function(config)
	{
		var list = new ui.m2_item_group.mailer_list({
			region: 'west',
			split: true,
			width: 500
		});
		var mem = new ui.m2_item_in_groups.subscribers({
			region: 'center'
		});
		list.on({
			rowclick: function(list, rowIndex, e){
				var id = list.getSelectionModel().getSelected().get('id');
				mem.setParams({_slid: id}, true);
			}
		});
		Ext.apply(this, {
			layout: 'border',
			items: [list, mem]
		});
		config = config || {};
		Ext.apply(this, config);
		ui.m2_item_in_groups.main.superclass.constructor.call(this, config);
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
