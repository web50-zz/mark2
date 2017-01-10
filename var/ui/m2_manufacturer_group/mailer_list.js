ui.m2_manufacturer_group.mailer_list = Ext.extend(ui.m2_manufacturer_group.grid, {
	titleAdd: 'Создать список',

	bttAdd: 'Добавить',
	bttEdit: 'Редактировать',
	bttDelete: 'Удалить',

	bttSearch: 'Найти',
	bttCancel: 'Сбросить',

	cnfrmTitle: 'Удаление списка рассылки',
	cnfrmMsg: 'Вы действительно хотите удалить данный список?',

	Add: function(){
		var app = new App({waitMsg: 'Загрузка формы'});
		app.on({
			apploaded: function(){
				var f = new ui.m2_manufacturer_group.item_form();
				var w = new Ext.Window({iconCls: this.iconCls, title: this.titleAdd, maximizable: true, modal: true, layout: 'fit', width: f.formWidth, height: f.formHeight, items: f});
				f.on({
					data_saved: function(){this.getStore().reload()},
					cancelled: function(){w.destroy()},
					scope: this
				});
				w.show(null, function(){f.Load({})});
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_manufacturer_group', 'item_form');
	},
	Edit: function(){
		var app = new App({waitMsg: 'Загрузка формы'});
		app.on({
			apploaded: function(){
				var id = this.getSelectionModel().getSelected().get('id');
				var f = new ui.m2_manufacturer_group.item_form();
				var w = new Ext.Window({iconCls: this.iconCls, title: this.titleEdt, maximizable: true, modal: true, layout: 'fit', width: f.formWidth, height: f.formHeight, items: f});
				f.on({
					data_saved: function(){this.getStore().reload()},
					cancelled: function(){w.destroy()},
					scope: this
				});
				w.show(null, function(){f.Load({id: id})});
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_manufacturer_group', 'item_form');
	},
	multiSave: function(){
		this.getStore().save();
	},
	Delete: function(){
		var record = this.getSelectionModel().getSelections();
		if (!record) return false;

		Ext.Msg.confirm(this.cnfrmTitle, this.cnfrmMsg, function(btn){
			if (btn == "yes"){
				this.getStore().remove(record);
			}
		}, this);
	},
	/**
	 * @constructor
	 */
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
				{iconCls: 'cog_add', text: this.bttAdd, handler: this.Add, scope: this},
				'-',
				srchField, srchBttOk, srchBttCancel,
				'->', {iconCls: 'help', handler: function(){showHelp('trip')}}
			]
		});
		Ext.apply(this, {
			listeners: {
				rowcontextmenu: function(grid, rowIndex, e){
					grid.getSelectionModel().selectRow(rowIndex);
					var cmenu = new Ext.menu.Menu({items: [
						{iconCls: 'cog_edit', text: this.bttEdit, handler: this.Edit, scope: this},
						{iconCls: 'cog_delete', text: this.bttDelete, handler: this.Delete, scope: this}
					]});
					e.stopEvent();  
					cmenu.showAt(e.getXY());
				},
				render: function(){this.getStore().load({params:{start: 0, limit: this.pagerSize}})},
				scope: this
			}
		});
		config = config || {};
		Ext.apply(this, config);
		ui.m2_manufacturer_group.mailer_list.superclass.constructor.call(this, config);
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
