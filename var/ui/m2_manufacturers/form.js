ui.m2_manufacturers.form = Ext.extend(Ext.form.FormPanel, {
	formWidth: 400,
	formHeight: 350,

	loadText: 'Загрузка данных формы',

	lblTitle: 'Название',
	lblAvailable: 'Доступен',
	lblType: 'Тип',
	lblId: "Id",
	saveText: 'Сохранение...',
	blankText: 'Необходимо заполнить',
	maxLengthText: 'Не больше 256 символов',

	bttSave: 'Сохранить',
	bttCancel: 'Отмена',
	bttFiles: 'Файлы',
	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером",
	msgNotDefined: 'Операция не активна, пока не сохранена форма',

	Load: function(data){
		var f = this.getForm();
		f.load({
			url: 'di/m2_manufacturers/get.json',
			params: {_sid: data._sid},
			waitMsg: this.loadText,
			success: function(frm, act){
				var d = Ext.util.JSON.decode(act.response.responseText);
				this.fireEvent("data_loaded", d.data, data.id);
			},
			scope:this
		});
		f.setValues(data);
	},

	Save: function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/m2_manufacturers/set.do',
				waitMsg: this.saveText,
				success: function(form, action){
					var d = Ext.util.JSON.decode(action.response.responseText);
					if (d.success)
						this.fireEvent('data_saved', d.data, d.data.id);
					else
						showError(d.errors);
				},
				failure: function(form, action){
					switch (action.failureType){
						case Ext.form.Action.CLIENT_INVALID:
							showError(this.errInputText);
						break;
						case Ext.form.Action.CONNECT_FAILURE:
							showError(this.errConnectionText);
						break;
						case Ext.form.Action.SERVER_INVALID:
							showError(action.result.errors);
					}
				},
				scope: this
			});
		}
	},
	
	Cancel: function(){
		this.fireEvent('cancelled');
	},

	/**
	 * @constructor
	 */
	constructor: function(config){
		config = config || {};
		var tb = new Ext.Toolbar({
			enableOverflow: true,
			items: [
				{iconCls: 'application_view_tile', text: this.bttFiles, handler: this.filesList, scope: this}
			]
		});

		Ext.apply(this, {
			layout: 'fit',
			tbar: tb,
			items: [{
				layout: 'form',
				frame: true, 
				labelWidth: 100,
				labelAlign: 'right',
				autoScroll: true,
				defaults: {xtype: 'textfield', width: 80, anchor: '98%'},
				items: [
					{name: '_sid', xtype: 'hidden'},
					{fieldLabel: this.lblId, name: 'id', xtype: 'displayfield'},
					{fieldLabel:this.lblTitle, name: 'title',allowBlank: false},
					{fieldLabel: this.lblType, hiddenName: 'type', xtype: 'combo',
							valueField: 'id', displayField: 'title', value: '', emptyText: '', 
							store: new Ext.data.JsonStore({url: 'di/m2_manufacturer_types/type_list.json', root: 'records', fields: ['id', 'title'], autoLoad: true,
								listeners: {
									load: function(store,ops){
										var f = this.getForm().findField('type');
										f.setValue(f.getValue());
									}, 
									beforeload:function(store,ops){
									},
									scope: this
								}
							}),
							mode: 'local', triggerAction: 'all', selectOnFocus: true, editable: false
					},
					{fieldLabel: this.lblAvailable, hiddenName: 'not_available', value: 0, xtype: 'combo', anchor: '90%',
									store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [[0, 'Доступен'],[1, 'Не доступен']]}),
									valueField: 'value', displayField: 'title', mode: 'local',
									triggerAction: 'all', selectOnFocus: true, editable: false
					}
				]
			}],
			buttonAlign: 'right',
			buttons: [
				{iconCls: 'disk', text: this.bttSave, handler: this.Save, scope: this},
				{iconCls: 'cancel', text: this.bttCancel, handler: this.Cancel, scope: this}
			]
		});
		Ext.apply(this, config);
		ui.m2_manufacturers.form.superclass.constructor.call(this, config);
		this.on({
			data_saved: function(data, id){
				this.getForm().setValues([{id: '_sid', value: id}]);
			},
			data_loaded: function(data, id){
			},
			scope: this
		})
	},
	filesList: function(b, e){
		var fm = this.getForm();
		var vals = fm.getValues();
		if(!(vals._sid>0)){
			showError(this.msgNotDefined);
			return;
		}
		var app = new App({waitMsg: 'Загрузка формы'});
		app.on({
			apploaded: function(){
				var f = new ui.m2_manufacturer_files.main();
				f.setParams({'_sitem_id':vals._sid});
				var w = new Ext.Window({iconCls: b.iconCls, title: b.text, maximizable: true, modal: true, layout: 'fit', width: 500, height: 400, items: f});
				f.on({
					cancelled: function(){w.destroy()},
					scope: this
				});
				w.show(null, function(){});
			},
			apperror: showError,
			scope: this
		});
		app.Load('m2_manufacturer_files', 'main');
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
