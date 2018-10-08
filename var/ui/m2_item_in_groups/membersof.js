ui.m2_item_in_groups.membersof = Ext.extend(ui.m2_item_in_groups.grid, {
	titleAdd: 'Выбор',

	bttAdd: 'Добавить',
	bttDelete: 'Удалить выбранных',

	bttSearch: 'Найти',
	bttCancel: 'Сбросить',
	bttUnSubscribeAll: 'Удалить всех',
	cnfrmTitle: 'Удаление',
	cnfrmMsg: 'Вы действительно хотите удалить?',
	errLidUndefined:'Надо выбрать группу сначала',
	Selector: function(){
		var app = new App();
		var lid  = this.getKey();
		if(!(lid>0)){
			showError(this.errLidUndefined);
			return;
		}
		app.on({
			apploaded: function(){
				var f = new ui.m2_item.choice();
				var w = new Ext.Window({title: this.titleAdd, maximizable: true, modal: true, layout: 'fit', width: 1024, height: 480, items: f});
				f.on({
					choosen: this.Add,
					choosen_all: this.addAll,
					scope: this
				});
				w.show(null, function(){f.applyParams({_scontact_type: 5, lid: this.getKey(), _scid: 'null'})}, this);
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_item', 'choice');
	},
	Add: function(contacts, id, cid, record){
		var lid = this.getKey();
		if (lid > 0)
		{
			Ext.Ajax.request({
				url: 'di/m2_item_in_groups/add.do',
				params: {lid: this.getKey(), 'id[]': id, 'cid[]': cid},
				disableCaching: true,
				callback: function(options, success, response){
					var d = Ext.util.JSON.decode(response.responseText);
					if (success && d.success){
						this.fireEvent('subscribed', cid);
						this.getStore().reload();
						contacts.getStore().reload();
					}else
						showError(d.error);
				},
				scope: this
			});
		}
	},
	addAll: function(store){
		var lid = this.getKey();
		if (lid > 0)
		{
			Ext.Ajax.request({
				url: 'di/m2_item_in_groups/add_all.do',
				params: store.baseParams,
				disableCaching: true,
				callback: function(options, success, response){
					var d = Ext.util.JSON.decode(response.responseText);
					if (success && d.success){
						this.fireEvent('subscribed', lid);
						this.getStore().reload();
						store.reload();
					}else
						showError(d.error);
				},
				scope: this
			});
		}
	},
	removeAll: function(store){
		var lid = this.getKey();
		var store = this.getStore();
		if (lid > 0)
		{
			Ext.Ajax.request({
				url: 'di/m2_item_in_groups/remove_all.do',
				params: store.baseParams,
				disableCaching: true,
				callback: function(options, success, response){
					var d = Ext.util.JSON.decode(response.responseText);
					if (success && d.success){
						this.fireEvent('unsubscribed', lid);
						this.getStore().reload();
						store.reload();
					}else
						showError(d.error);
				},
				scope: this
			});
		}
	},

	multiSave: function(){
		this.getStore().save();
	},
	Delete: function(){
		var s = this.getSelectionModel().getSelections();
		if (!s) return false;

		Ext.Msg.confirm(this.cnfrmTitle, this.cnfrmMsg, function(btn){
			if (btn == "yes"){
				var id = new Array();
				for (var i = 0; i < s.length; i++){id.push(s[i].get('id'));}
				Ext.Ajax.request({
					url: 'di/m2_item_in_groups/unset.do',
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
		var srchType = new Ext.form.ComboBox({fieldLabel: 'Поиск', width: 150,
			store: new Ext.data.SimpleStore({fields: ['value', 'title'], data: [
				['email', 'Email']
			]}), value: 'email', valueField: 'value', displayField: 'title', triggerAction: 'all', mode: 'local', editable: false
		});
		var srchField = new Ext.form.TextField({
			width: 200,
			listeners: {
				specialkey: function(field, e){
					if (e.getKey() == e.ENTER){
						var s = this.getStore();
						Ext.apply(s.baseParams, {query: field.getValue(), field: srchType.getValue()});
						s.reload();
					}
				},
				scope: this
			}
		});
		var srchBttOk = new Ext.Toolbar.Button({
			text: this.bttSearch,
			iconCls: 'find',
			handler: function search_submit(){
				var s = this.getStore();
				Ext.apply(s.baseParams, {query: srchField.getValue(), field: srchType.getValue()});
				s.reload();
			},
			scope: this
		});
		var srchBttCancel = new Ext.Toolbar.Button({
			text: this.bttCancel,
			iconCls: 'cancel',
			handler: function search_submit(){
				var s = this.getStore();
				srchField.setValue('');
				Ext.apply(s.baseParams, {query: null, field: null});
				s.reload();
			},
			scope: this
		});
		Ext.apply(this, {
			tbar: [
				{iconCls: 'user_delete', handler: this.Delete, text: this.bttDelete, scope: this},
				{iconCls: 'group_delete', handler: this.removeAll, text: this.bttUnSubscribeAll, scope: this},
				{iconCls: 'user_add', text: this.bttAdd, handler: this.Selector, scope: this},
				'-',
				srchType, srchField, srchBttOk, srchBttCancel,
				'->', {iconCls: 'help', handler: function(){showHelp('trip')}}
			]
		});
		Ext.apply(this, {
			listeners: {
				rowcontextmenu: function(grid, rowIndex, e){
					grid.getSelectionModel().selectRow(rowIndex);
					var cmenu = new Ext.menu.Menu({items: [
						{iconCls: 'user_delete', text: this.bttDelete, handler: this.Delete, scope: this}
					]});
					e.stopEvent();  
					cmenu.showAt(e.getXY());
				},
				scope: this
			}
		});
		config = config || {};
		Ext.apply(this, config);
		ui.m2_item_in_groups.membersof.superclass.constructor.call(this, config);
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
