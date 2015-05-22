ui.m2_import.item_form = Ext.extend(Ext.form.FormPanel, {
	Load: function(data){
		var f = this.getForm();
		f.load({
			url: 'di/m2_import/get.json',
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
	},

	Save: function(){
		var f = this.getForm();
		if (f.isValid()){
			f.submit({
				url: 'di/m2_export_xls/import_xls.do',
				waitMsg: this.saveText,
				success: function(form, action){
					var d = Ext.util.JSON.decode(action.response.responseText);
					if (d.success){
						this.fireEvent('data_saved', d, d);
						this.Cancel();
					}
					else{

						showError(d.errors);
					}
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
			formWidth: 400,
			formHeight: 240,

			lblTitle: 'Наименование',
			lblType: 'Тип',

			loadText: 'Загрузка данных формы',

			saveText: 'Сохранение...',
			lblFile: 'Файл',
			lblDescr: 'Описание',

			bttSave: 'Сохранить',
			bttCancel: 'Отмена',
			errSaveText: 'Ошибка во время сохранения',
			errInputText: 'Корректно заполните все необходимые поля',
			errConnectionText: "Ошибка связи с сервером"
		});
		
		Ext.apply(this, {
			frame: true, 
			fileUpload: true,
			defaults: {xtype: 'textfield', width: 150, anchor: '100%'},
			items: [
				{name: '_sid', inputType: 'hidden'},
				{name: 'item_id', inputType: 'hidden'},
				{fieldLabel: this.lblFile, name: 'file', xtype: 'fileuploadfield', buttonCfg: {text: '', iconCls: 'folder'}}
			],
			buttonAlign: 'right',
			buttons: [
				{iconCls: 'disk', text: this.bttSave, handler: this.Save, scope: this},
				{iconCls: 'cancel', text: this.bttCancel, handler: this.Cancel, scope: this}
			],
		});
		Ext.apply(this, config);
		ui.m2_import.item_form.superclass.constructor.call(this, config);
		this.on({
			data_saved: function(data, id){
				this.getForm().setValues([{id: '_sid', value: id}]);
				this._sid = data.id;
				this.reloadServices(data, id);
			},
			data_loaded: function(data, id){
				this.reloadServices(data, id);
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
	},

	reloadServices: function(data, id){
	}
});
