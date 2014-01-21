ui.m2_item_variation_chars.main = Ext.extend(ui.m2_item_variation_chars.grid, {
	bttAdd: "Добавить из справочника",
	bttAddCustom: "Добавить и вольной форме",
	bttEdit: "Редактировать",
	bttDelete: "Удалить",

	addTitle: "Добавление ",
	editTitle: "Редактирование",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить?",


	Add: function(){
		var app = new App({waitMsg: this.frmLoading});
		var pid = this.getKey();
		var params = this.getStore().baseParams;
		var target_type = params._starget_type;
		app.on({
			apploaded: function(){
				var f = new ui.m2_item_variation_chars.form();
				var w = new Ext.Window({iconCls: this.iconCls, title: this.titleAdd, maximizable: true, modal: true, layout: 'fit', width: f.formWidth, height: f.formHeight, items: f});
				f.on({
					data_saved: function(){this.store.reload(); w.destroy();},
					cancelled: function(){w.destroy()},
					scope: this
				});
				w.show(null, function(){f.Load({m2_id: pid,target_type: target_type})});
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_item_variation_chars', 'form');
	},
	AddCustom: function(){
		var app = new App({waitMsg: this.frmLoading});
		var pid = this.getKey();
		var params = this.getStore().baseParams;
		var target_type = params._starget_type;
		app.on({
			apploaded: function(){
				var f = new ui.m2_item_variation_chars.form_custom();
				var w = new Ext.Window({iconCls: this.iconCls, title: this.titleAdd, maximizable: true, modal: true, layout: 'fit', width: f.formWidth, height: f.formHeight, items: f});
				f.on({
					data_saved: function(){this.store.reload(); w.destroy();},
					cancelled: function(){w.destroy()},
					scope: this
				});
				w.show(null, function(){f.Load({m2_id: pid,target_type: target_type})});
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_item_variation_chars', 'form_custom');
	},
	Edit: function(){
		var row = this.getSelectionModel().getSelected();
		var id = row.get('id');
		var app = new App({waitMsg: this.frmLoading});
		app.on({
			apploaded: function(){
				var f = new ui.m2_item_variation_chars.form();
				var w = new Ext.Window({iconCls: this.iconCls, title: this.titleEdit, maximizable: true, modal: true, layout: 'fit', width: f.formWidth, height: f.formHeight, items: f});
				f.on({
					data_saved: function(){this.store.reload(); w.destroy();},
					cancelled: function(){w.destroy()},
					scope: this
				});
				w.show(null, function(){f.Load({_sid: id})});
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_item_variation_chars', 'form');
	},
	EditCustom: function(){
		var row = this.getSelectionModel().getSelected();
		var id = row.get('id');
		var app = new App({waitMsg: this.frmLoading});
		app.on({
			apploaded: function(){
				var f = new ui.m2_item_variation_chars.form_custom();
				var w = new Ext.Window({iconCls: this.iconCls, title: this.titleEdit, maximizable: true, modal: true, layout: 'fit', width: f.formWidth, height: f.formHeight, items: f});
				f.on({
					data_saved: function(){this.store.reload(); w.destroy();},
					cancelled: function(){w.destroy()},
					scope: this
				});
				w.show(null, function(){f.Load({_sid: id})});
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_item_variation_chars', 'form_custom');
	},

	multiSave: function(){
		this.store.save();
	},
	Delete: function(){
		var record = this.getSelectionModel().getSelections();
		if (!record) return false;

		Ext.Msg.confirm(this.cnfrmTitle, this.cnfrmMsg, function(btn){
			if (btn == "yes"){
				this.store.remove(record);
			}
		}, this);
	},

	/**
	 * @constructor
	 */
	constructor: function(config)
	{
		Ext.apply(this, {
			tbar: [
				{iconCls: 'note_add', text: this.bttAdd, handler: this.Add, scope: this},
				{iconCls: 'note_add', text: this.bttAddCustom, handler: this.AddCustom, scope: this},
				'->', {iconCls: 'help', handler: function(){showHelp('m2_item_variation_chars')}}
			]
		});
		config = config || {};
		Ext.apply(this, config);
		ui.m2_item_variation_chars.main.superclass.constructor.call(this, config);
		this.on({
			rowcontextmenu: function(grid, rowIndex, e){
				grid.getSelectionModel().selectRow(rowIndex);
				var type = grid.getSelectionModel().getSelected().data.is_custom;
				if(type == 1){
					c = {iconCls: 'note_edit', text: this.bttEdit, handler: this.EditCustom, scope: this};
				}else {
					c = {iconCls: 'note_edit', text: this.bttEdit, handler: this.Edit, scope: this};
				}
				var cmenu = new Ext.menu.Menu({items: [
					c,
					{iconCls: 'note_delete', text: this.bttDelete, handler: this.Delete, scope: this},
					'-'
				]});
				e.stopEvent();  
				cmenu.showAt(e.getXY());
			},
			dblrowclick: this.Edit,
			scope: this
		});
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
