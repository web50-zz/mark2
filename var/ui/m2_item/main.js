ui.m2_item.main = Ext.extend(ui.m2_item.grid, {
	bttAdd: "Добавить",
	bttEdit: "Редактировать",
	bttDelete: "Удалить",
	bttReindex: "Реиндексировать",
	bttXls:"Получить в формате эксель",
	addTitle: "Добавление",
	editTitle: "Редактирование",
	bttImportXls:"Импорт XLS",
	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить?",


	Add: function(){
		var app = new App({waitMsg: this.frmLoading});
		var pid = this.getKey();
		app.on({
			apploaded: function(){
				var f = new ui.m2_item.form();
				var w = new Ext.Window({iconCls: this.iconCls, title: this.titleAdd, maximizable: true, modal: true, layout: 'fit', width: f.formWidth, height: f.formHeight, items: f});
				f.on({
					data_saved: function(){this.store.reload();},
					cancelled: function(){w.destroy()},
					scope: this
				});
				w.show(null, function(){f.Load({m2_category_id: pid})});
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_item', 'form');
	},
	Edit: function(){
		var row = this.getSelectionModel().getSelected();
		var id = row.get('id');
		var app = new App({waitMsg: this.frmLoading});
		app.on({
			apploaded: function(){
				var f = new ui.m2_item.form();
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
		app.Load('m2_item', 'form');
	},
	xls: function(){
		document.location = 'di/m2_export_xls/list.js';
	},
	importXls: function(){
		var app = new App({waitMsg: this.frmLoading});
		var pid = this.getKey();
		app.on({
			apploaded: function(){
				var f = new ui.m2_import.item_form();
				var w = new Ext.Window({iconCls: this.iconCls, title: this.titleAdd, maximizable: true, modal: true, layout: 'fit', width: f.formWidth, height: 100, items: f});
				f.on({
					data_saved: function(){},
					cancelled: function(){w.destroy()},
					scope: this
				});
				w.show(null, function(){});
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_import', 'item_form');

//		document.location = 'di/m2_export_xls/import_xls.html';
	},
	batchReindex: function(id, title){
		Ext.Msg.confirm(this.cnfrmTitle, 'Вы хотите переиндексировать каталог?', function(btn){
			if (btn == "yes") {
			 Ext.MessageBox.show({
				title: 'Статус',
				progressText: 'В процессе ...',
				width:300,
				progress:true,
				closable:false,
				wait: true,
				waitConfig : 
				        {
						duration : 20000,
						increment : 15,
						text : 'В процессе .... ',
						scope : this,
						fn : function(){
							Ext.MessageBox.hide();
						}
					}
		             });
			Ext.Ajax.request({
					url: 'di/m2_item_indexer/batch_reindex.do',
					params: {_sid: id},
					callback: function(options, success, response){
						var d = Ext.util.JSON.decode(response.responseText);
						if (d.success){
							Ext.Msg.alert('',d.msg);
							this.fireEvent('batch_reindexed', id);
						}else{
							showError('Во время индексации возникли ошибки.');
						}
					},
					scope: this
				});
		}
		}, this);
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
				{iconCls: 'note_add', text: this.bttXls, handler: this.xls, scope: this},
				{iconCls: 'note_add', text: this.bttImportXls, handler: this.importXls, scope: this},
				{iconCls: 'note_add', text: this.bttReindex, handler: this.batchReindex, scope: this},
				'->', {iconCls: 'help', handler: function(){showHelp('m2_item')}}
			]
		});
		config = config || {};
		Ext.apply(this, config);
		ui.m2_item.main.superclass.constructor.call(this, config);
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
