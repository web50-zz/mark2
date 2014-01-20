ui.m2_item_variation.form = Ext.extend(Ext.form.FormPanel, {
	formWidth: 600,
	formHeight: 700,

	loadText: 'Загрузка данных формы',

	lblArticle: 'Артикул',
	lblTitle: 'Название',
	lblName: 'URI',
	lblContent: 'Описание',
	lblContentShort: 'Описание коротко',
	lblPrice: 'Цена',
	lblQty:'Кол-во',
	lblPrice2: 'Цена 2',
	lblAvailable: 'В продаже',
	lblId: "Id",
	saveText: 'Сохранение...',
	blankText: 'Необходимо заполнить',
	maxLengthText: 'Не больше 256 символов',

	bttSave: 'Сохранить',
	bttCancel: 'Отмена',

	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером",
	msgNotDefined: 'Операция не активна, пока не сохранена форма',
	bttFiles: 'Файлы',
	bttTexts: 'Текстовые блоки',
	bttChars: 'Характеристики',
	bttCategory: 'Входит в категории',

	Load: function(data){
		var f = this.getForm();
		f.load({
			url: 'di/m2_item_variation/get.json',
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
				url: 'di/m2_item_variation/set.do',
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
			]
		});
		this.p = new ui.m2_item_variation_files.main({height:200,title:'Файлы',autoLoad:false});
		this.m = new ui.m2_chars.main({height:200,title:'Характеристики',autoLoad:false});
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
						{name: 'item_id', xtype: 'hidden'},
						{fieldLabel: this.lblId, name: 'id', xtype: 'displayfield'},
						{fieldLabel:this.lblArticle, name: 'article'},
						{fieldLabel:this.lblPrice, name: 'price'},
						{fieldLabel:this.lblQty, name: 'qty'},
						{fieldLabel:this.lblTitle, name: 'title',allowBlank:false},
					//	{fieldLabel:this.lblName, name: 'name'},
						{fieldLabel: this.lblAvailable, hiddenName: 'not_available', value: 0, xtype: 'combo', anchor: '90%',
										store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [[0, 'В продаже'],[1, 'Не доступен']]}),
										valueField: 'value', displayField: 'title', mode: 'local',
										triggerAction: 'all', selectOnFocus: true, editable: false
						},
						this.m,
						this.p
					]
			}],
			buttonAlign: 'right',
			buttons: [
				{iconCls: 'disk', text: this.bttSave, handler: this.Save, scope: this},
				{iconCls: 'cancel', text: this.bttCancel, handler: this.Cancel, scope: this}
			]
		});
		Ext.apply(this, config);
		ui.m2_item_variation.form.superclass.constructor.call(this, config);
		this.on({
			data_saved: function(data, id){
				var prev = this.getForm().findField('_sid').getValue();
				if(!(prev>0)){
					this.p.setParams({'_sitem_id':id},true);
					this.m.setParams({'_spid':id,'_starget_type':'3'},true);
				}
				this.getForm().setValues([{id: '_sid', value: id}]);
			},
			data_loaded: function(data, id){
				this.p.setParams({'_sitem_id':data.id},true);
				this.m.setParams({'_spid':data.id,'_starget_type':'3'},true);
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
