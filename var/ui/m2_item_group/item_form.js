ui.m2_item_group.item_form = Ext.extend(Ext.form.FormPanel, {
	formWidth: 800,
	formHeight: 320,

	fldTitle: 'Наименование',
	fldComment: 'Комментарий',
	loadText: 'Загрузка данных формы',
	saveText: 'Сохранение...',

	bttSave: 'Сохранить',
	bttCancel: 'Отмена',

	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером",

	Load: function(data){
		if (data){
			var f = this.getForm();
			f.load({
				url: 'di/m2_item_group/get.json',
				params: {_sid: data.id},
				waitMsg: this.loadText,
				success: function(frm, act){
					var d = Ext.util.JSON.decode(act.response.responseText);
					f.setValues([{id: '_sid', value: d.data.id}]);
					this.fireEvent("data_loaded", d.data, d.data.id);
				},
				scope:this
			});
			f.setValues(data);
		}
	},

	Save: function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/m2_item_group/set.do',
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
		Ext.apply(this, {
			frame: true, 
			buttonAlign: 'right',
			autoScroll: true,
			labelAlign: 'right',
			labelWidth: 150,
			defaults: {xtype: 'textfield', width: 100, anchor: '95%'},
			items: [
				{name: '_sid', inputType: 'hidden'},
				{fieldLabel: this.fldTitle, name: 'title', allowBlank: false, maxLength: 255},
				{fieldLabel: this.fldComment, name: 'comment', xtype: 'htmleditor',height: 100}
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
		config = config || {};
		Ext.apply(this, config);
		ui.m2_item_group.item_form.superclass.constructor.call(this, config);
		this.on({
			data_saved: function(data, id){
				this.getForm().setValues([{id: '_sid', value: id}]);
			},
			data_loaded: function(data, id){
				this.getForm().setValues([{id: '_sid', value: id}]);
			},
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
