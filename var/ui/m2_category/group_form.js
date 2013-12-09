ui.m2_category.group_form = Ext.extend(Ext.form.FormPanel, {
	formWidth: 1000,
	formHeight: 600,

	fldTitle: 'Наименование',
	fldVisible: 'Видимый',
	fldName: 'Лат. Имя стр.',
	fldURI: 'URI',

	loadText: 'Загрузка данных формы',
	saveText: 'Сохранение...',

	bttSave: 'Сохранить',
	bttCancel: 'Отмена',

	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером",

	Load: function(data){
		var f = this.getForm();
		f.load({
			url: 'di/m2_category/get.json',
			params: {_sid: data.id},
			waitMsg: this.loadText,
			success: function(frm, act){
				var d = Ext.util.JSON.decode(act.response.responseText);
				if (data.id > 0) f.setValues([{id: '_sid', value: data.id}]);
				if (data.pid > 0) f.setValues([{id: 'pid', value: data.pid}]);
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
				url: 'di/m2_category/set.do',
				waitMsg: this.saveText,
				success: function(form, action){
					var d = Ext.util.JSON.decode(action.response.responseText);
					if (d.success)
						this.fireEvent('data_saved', !(f.findField('_sid').getValue() > 0), d.data, f.getValues());
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
		Ext.apply(this, {
			frame: true, 
			labelWidth: 100, 
			defaults: {xtype: 'textfield', width: 200, anchor: '100%'},
			items: [
				{name: '_sid', inputType: 'hidden'},
				{name: 'pid', xtype: 'hidden'},
				{name: 'type', xtype: 'hidden', value: '0'},
				{fieldLabel: this.fldTitle, name: 'title', maxLength: 255},
				{fieldLabel: this.fldVisible, hiddenName: 'visible', value: 1, xtype: 'combo', width: 50, anchor: null,
					store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [[1, 'Да'], [0, 'Нет']] }),
					valueField: 'value', displayField: 'title', mode: 'local', triggerAction: 'all', selectOnFocus: true, editable: false
				},
				{fieldLabel: this.fldName, name: 'name', allowBlank: false, maxLength: 32},
				{fieldLabel: this.fldURI, name: 'uri', disabled: true},
				{hideLabel: true, name: 'content', xtype: 'ckeditor', CKConfig: {
					height: 350,
					filebrowserImageBrowseUrl: 'ui/file_manager/browser.html'
				}}
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
		ui.m2_category.group_form.superclass.constructor.call(this, config);
		this.on({
			data_saved: function(isNew, data, id){
				this.getForm().setValues([{id: '_sid', value: data.id}, {id: 'uri', value: data.uri}]);
			},
			data_loaded: function(data, id){
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
