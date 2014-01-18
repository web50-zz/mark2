ui.m2_chars.form = Ext.extend(Ext.form.FormPanel, {
	formWidth: 500,
	formHeight: 400,

	loadText: 'Загрузка данных формы',

	lblFile: 'Файл',
	lblType: 'Параметр',
	lblVal: 'Фиксированное значение',
	lblIsCustom: 'Параметр из справочника',
	lblContent: 'Контент',
	fldVariableVal:'Значение в вольной форме',
	saveText: 'Сохранение...',
	blankText: 'Необходимо заполнить',
	maxLengthText: 'Не больше 256 символов',

	bttSave: 'Сохранить',
	bttCancel: 'Отмена',

	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером",

	Load: function(data){
		var f = this.getForm();
		f.load({
			url: 'di/m2_chars/get.json',
			params: {_sid: data._sid,target_type:data.target_type,is_custom:0},
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
				url: 'di/m2_chars/set.do',
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
	refreshType: function(){
			var f = this.getForm();
			var t = f.findField('type_value');
			var type_value = t.getValue();
			var type_id = f.findField('type_id').getValue();
			var char_type = f.findField('char_type').getValue();
			var vv = f.findField('variable_value');
			if(char_type == 1)
			{
					t.show();
					t.store.setBaseParam('node',type_id);
					t.store.reload();
					t.setValue(type_value);
					vv.hide();
			}else{
				vv.show();
				t.hide();
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
		Ext.apply(this, {
			labelAlign: 'right', 
			labelWidth: 170,
			frame: true,
			border: false,
			defaults: {xtype: 'textfield', width: 150, anchor: '100%'},
			items: [
				{name: '_sid', xtype: 'hidden'},
				{name: 'm2_id', xtype: 'hidden'},
				{name: 'target_type', xtype: 'hidden'},
				{name: 'is_custom', xtype: 'hidden'},
				{name: 'type_id', xtype: 'hidden'},
				{name: 'char_type', xtype: 'hidden'},
				{name: 'type_value_str', xtype: 'hidden'},
				{fieldLabel: this.lblType, xtype: 'compositefield', items: [
					{xtype: 'button', iconCls: 'add', listeners: {click: function(){this.fireEvent('select_type')}, scope: this}},
					{xtype: 'displayfield', name: 'str_title'}
				]},
				{fieldLabel: this.lblVal, hiddenName: 'type_value', xtype: 'combo',hidden: true,
						valueField: 'id', displayField: 'title', value: '', emptyText: '', 
						store: new Ext.data.JsonStore({url: 'di/m2_chars_types/chld_list.json', root: 'records', fields: ['id', 'title'], autoLoad: false,
							listeners: {
								load: function(store,ops){
									var f = this.getForm().findField('type_value');
									f.setValue(f.getValue());
								}, 
								beforeload:function(store,ops){
								},
								change: function(o,oldValue,newValue){
									console.log(newValue);
								},
								scope: this
							}
						}),
						listeners:{
								select: function(o,oldValue,newValue){
									var f = this.getForm().findField('type_value_str');
									var  n = o.getRawValue();
									f.setValue(n);
								},
								scope: this
						},
						mode: 'local', triggerAction: 'all', selectOnFocus: true, editable: false
				},
				{fieldLabel: this.fldVariableVal, name: 'variable_value', hidden: true, xtype: 'textarea', maxLength: 255, maxLengthText: 'Не больше 255 символов'},
			],
			buttonAlign: 'right',
			buttons: [
				{iconCls: 'disk', text: this.bttSave, handler: this.Save, scope: this},
				{iconCls: 'cancel', text: this.bttCancel, handler: this.Cancel, scope: this}
			],
			keys: [
				{key: [Ext.EventObject.ENTER], handler: this.Save, scope: this}
			]
		});
		Ext.apply(this, config);
		ui.m2_chars.form.superclass.constructor.call(this, config);
		this.on({
			data_saved: function(data, id){
				this.getForm().setValues([{id: '_sid', value: id}]);
			},
			data_loaded: function(data, id){
				this.refreshType();
			},
			select_type: function(){
				var app = new App();
				app.on({
					apploaded: function(){
						var bf = this.getForm();
						var obj = this;
						var f = new ui.m2_chars_types.types_selector();
						var w = new Ext.Window({title: "Выбор Характеристики", maximizable: true, modal: true, layout: 'fit', width: 640, height: 480, items: f});
						f.on({
							selected2: function(data){
								bf.setValues([
									{id: 'type_id', value: data.id},
									{id: 'char_type',value: data.char_type},
									{id: 'str_title', value: data.title}
								]);
								obj.refreshType();
								w.close();
							},
							scope: this
						});
						w.show();
					},
					apperror: showError,
					scope: this
				});
				app.Load('m2_chars_types', 'types_selector');
			},

			scope: this
		})
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
