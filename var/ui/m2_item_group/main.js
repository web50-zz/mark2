ui.m2_item_group.main = Ext.extend(Ext.Panel, {
	/**
	 * @constructor
	 */
	constructor: function(config)
	{
		var list = new ui.m2_item_group.mailer_list({
			region: 'center',
		});
		/*
		var subscriber = new ui.mailer_list_subscriber.main({
			region: 'east',
			split: true,
			width: 500
		});
		list.on({
			rowclick: function(list, rowIndex, e){
				var id = list.getSelectionModel().getSelected().get('id');
				subscriber.setParams({_slid: id}, true);
			}
		});
		*/
		Ext.apply(this, {
			layout: 'border',
			items: [list/*, subscriber*/]
		});
		config = config || {};
		Ext.apply(this, config);
		ui.m2_item_group.main.superclass.constructor.call(this, config);
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
