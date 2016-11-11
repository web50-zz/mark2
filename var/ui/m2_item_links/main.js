ui.m2_item_links.main = Ext.extend(ui.m2_item_links.grid, {
	bttAdd: "Добавить",
	bttEdit: "Редактировать",
	bttDelete: "Удалить",
	bttDelete_sel: "Удалить выбранное",

	addTitle: "Добавление",
	editTitle: "Редактирование",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить?",

	pagerEmptyMsg: 'Нет записей',
	pagerDisplayMsg: 'Фотографии с {0} по {1}. Всего: {2}',
	Load: function(data){
		this.setParams({}, true);
	},
	Add: function(){
		var app = new App({waitMsg: this.frmLoading});
		var pid = this.getKey();
		app.on({
			apploaded: function(){
				var f = new ui.m2_item_links.form();
				var w = new Ext.Window({iconCls: this.iconCls, title: this.titleAdd, maximizable: true, modal: true, layout: 'fit', width: f.formWidth, height: f.formHeight, items: f});
				f.on({
					data_saved: function(){this.store.reload(); w.destroy();},
					cancelled: function(){w.destroy()},
					scope: this
				});
				w.show(null, function(){f.Load({item_id: pid})});
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_item_links', 'form');
	},
	Edit: function(){
		var row = this.getSelectionModel().getSelected();
		var id = row.get('id');
		var app = new App({waitMsg: this.frmLoading});
		app.on({
			apploaded: function(){
				var f = new ui.m2_item_links.form();
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
		app.Load('m2_item_links', 'form');
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
	Delete_sel: function(){
		var s = this.getSelectionModel().getSelections();
		if (!s) return false;

		Ext.Msg.confirm(this.cnfrmTitle, this.cnfrmMsg, function(btn){
			if (btn == "yes"){
				var id = new Array();
				for (var i = 0; i < s.length; i++){id.push(s[i].get('id'));}
				Ext.Ajax.request({
					url: 'di/m2_item_links/unset.do',
					params: {'_sid[]': id},
					disableCaching: true,
					callback: function(options, success, response){
						var d = Ext.util.JSON.decode(response.responseText);
						if (success && d.success)
							this.getStore().reload();
						else
							showError(d.error);
					},
					scope: this
				});
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
				{iconCls: 'note_delete', handler: this.Delete_sel, text: this.bttDelete_sel, scope: this},
				'->', {iconCls: 'help', handler: function(){showHelp('m2_item_links')}}
			]
		});
		config = config || {};
		Ext.apply(this, config);
		ui.m2_item_links.main.superclass.constructor.call(this, config);
		this.on({
			rowcontextmenu: function(grid, rowIndex, e){
				grid.getSelectionModel().selectRow(rowIndex);
				var cmenu = new Ext.menu.Menu({items: [
					{iconCls: 'note_edit', text: this.bttEdit, handler: this.Edit, scope: this},
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
