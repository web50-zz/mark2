ui.m2_item_category.main = Ext.extend(ui.m2_item_category.grid, {
	bttAdd: "Добавить",
	bttEdit: "Редактировать",
	bttDelete: "Удалить",

	addTitle: "Добавление",
	editTitle: "Редактирование",

	cnfrmTitle: "Подтверждение",
	cnfrmMsg: "Вы действительно хотите удалить?",

	Load: function(data){
		this.setParams({}, true);
	},
	/*
	Add_back: function(){
		var app = new App({waitMsg: this.frmLoading});
		var pid = this.getKey();
		app.on({
			apploaded: function(){
				var f = new ui.m2_item_category.form();
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
		app.Load('m2_item_category', 'form');
	},
	*/
	Add: function(){
			var app = new App();
			var pid = this.getKey();
			var sl = this;
			app.on({
				apploaded: function(){
					var f = new ui.m2_category.category_selection();
					var w = new Ext.Window({title: "Выбор категории", maximizable: true, modal: true, layout: 'fit', width: 640, height: 480, items: f});
					f.on({
						selected: function(data){
							Ext.Ajax.request({
								url: 'di/m2_item_category/set.do',
								success: function(){
									sl.store.reload();
								},
								failure: function(){

								},
								params: { item_id: pid, category_id: data.category_id, category_tite: data.category_title}
							});
							w.close();
						},
						scope: this
					});
					w.show();
				},
				apperror: showError,
				scope: this
			});
			app.Load('m2_category', 'category_selection');
	},

	Edit: function(){
		var row = this.getSelectionModel().getSelected();
		var id = row.get('id');
		var app = new App({waitMsg: this.frmLoading});
		app.on({
			apploaded: function(){
				var f = new ui.m2_item_category.form();
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
		app.Load('m2_item_category', 'form');
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
				'->', {iconCls: 'help', handler: function(){showHelp('m2_item_category')}}
			]
		});
		config = config || {};
		Ext.apply(this, config);
		ui.m2_item_category.main.superclass.constructor.call(this, config);
		this.on({
			rowcontextmenu: function(grid, rowIndex, e){
				grid.getSelectionModel().selectRow(rowIndex);
				var cmenu = new Ext.menu.Menu({items: [
				//	{iconCls: 'note_edit', text: this.bttEdit, handler: this.Edit, scope: this},
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
