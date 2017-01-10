ui.m2_manufacturer_group.mailer_list_chooser = Ext.extend(ui.m2_manufacturer_group.grid, {
	bttChoose: 'Выбрать',
	bttSearch: 'Найти',
	bttCancel: 'Сбросить',
	/**
	 * @constructor
	 */
	Selection: function(node, e){
		var r = node;
		var s = this.getSelectionModel().getSelected();
		this.fireEvent('list_choosen', s.get('id'), s.get('title'));
		this.getStore().reload();
	},
	constructor: function(config)
	{
		var srchField = new Ext.form.TextField({
			width: 200,
			listeners: {
				specialkey: function(field, e){
					if (e.getKey() == e.ENTER)
						this.applyParams({_stitle: '%'+srchField.getValue()+'%'}, true);
				},
				scope: this
			}
		});
		var srchBttOk = new Ext.Toolbar.Button({
			text: this.bttSearch,
			iconCls: 'find',
			handler: function search_submit(){
				this.applyParams({_stitle: '%'+srchField.getValue()+'%'}, true);
			},
			scope: this
		});
		var srchBttCancel = new Ext.Toolbar.Button({
			text: this.bttCancel,
			iconCls: 'cancel',
			handler: function search_submit(){
				srchField.setValue('');
				this.applyParams({_stitle: '%%'}, true);
			},
			scope: this
		});
		Ext.apply(this, {
			tbar: [
				srchField, srchBttOk, srchBttCancel,
				'->', {iconCls: 'help', handler: function(){showHelp('mailer_list_chooser')}}
			],
			listeners: {
				rowcontextmenu: function(grid, rowIndex, e){
					grid.getSelectionModel().selectRow(rowIndex);
					var cmenu = new Ext.menu.Menu({items: [
						{iconCls: 'cog_go', text: this.bttChoose, handler: function(){
							var s = this.getSelectionModel().getSelected();
							this.fireEvent('list_choosen', s.get('id'), s.get('title'));
						}, scope: this}
					]});
					e.stopEvent();  
					cmenu.showAt(e.getXY());
				},
				dblclick: this.Selection,
				render: function(){this.getStore().load({params:{start: 0, limit: this.pagerSize}})},
				scope: this
			}
		});
		config = config || {};
		Ext.apply(this, config);
		ui.m2_manufacturer_group.mailer_list_chooser.superclass.constructor.call(this, config);
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
