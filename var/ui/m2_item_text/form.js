ui.m2_item_text.form = Ext.extend(Ext.form.FormPanel, {
	formWidth: 900,
	formHeight: 600,

	loadText: 'Загрузка данных формы',

	lblFile: 'Файл',
	lblTitle: 'Название',
	lblContent: 'Контент',
	lblType:'Тип',

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
			url: 'di/m2_item_text/get.json',
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
				url: 'di/m2_item_text/set.do',
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
		Ext.apply(this, {
			labelAlign: 'right', 
			labelWidth: 70,
			frame: true,
			border: false,
			defaults: {xtype: 'textfield', width: 150, anchor: '100%'},
			items: [
				{name: '_sid', xtype: 'hidden'},
				{name: 'item_id', xtype: 'hidden'},
				{fieldLabel:this.lblTitle, name: 'title', xtype: 'textfield'},
				{fieldLabel: this.lblType, hiddenName: 'type', xtype: 'combo',
						valueField: 'id', displayField: 'title', value: '', emptyText: '', 
						store: new Ext.data.JsonStore({url: 'di/m2_text_types/type_list.json', root: 'records', fields: ['id', 'title'], autoLoad: true,
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
				{fieldLabel:this.lblContent,  name: 'content', xtype: 'ckeditor', CKConfig: {
					height: 260,
					filebrowserImageBrowseUrl: 'ui/file_manager/browser.html'
				}}
			],
			buttonAlign: 'right',
			buttons: [
				{iconCls: 'disk', text: this.bttSave, handler: this.Save, scope: this},
				{iconCls: 'cancel', text: this.bttCancel, handler: this.Cancel, scope: this}
			]
		});
		Ext.apply(this, config);
		ui.m2_item_text.form.superclass.constructor.call(this, config);
		this.on({
			data_saved: function(data, id){
				this.getForm().setValues([{id: '_sid', value: id}]);
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
