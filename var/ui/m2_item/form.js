ui.m2_item.form = Ext.extend(Ext.form.FormPanel, {
	formWidth: 900,
	formHeight: 600,

	loadText: 'Загрузка данных формы',

	lblArticle: 'Артикул',
	lblTitle: 'Название',
	lblName: 'URI',
	lblContent: 'Описание',
	lblContentShort: 'Описание коротко',
	lblPrice: 'Цена',
	lblPrice2: 'Цена 2',
	lblAvailable: 'В продаже',
	lblId: "Id",
	saveText: 'Сохранение...',
	blankText: 'Необходимо заполнить',
	maxLengthText: 'Не больше 256 символов',

	bttSave: 'Сохранить',
	bttCancel: 'Отмена',
	fldMetaTitle:'META Title',
	errSaveText: 'Ошибка во время сохранения',
	errInputText: 'Корректно заполните все необходимые поля',
	errConnectionText: "Ошибка связи с сервером",
	msgNotDefined: 'Операция не активна, пока не сохранена форма',
	bttFiles: 'Файлы',
	bttTexts: 'Текстовые блоки',
	bttChars: 'Характеристики',
	bttCategory: 'Входит в категории',
	bttVariation:' Варианты',
	bttLinks:'Связанные товары',

	Load: function(data){
		var f = this.getForm();
		f.load({
			url: 'di/m2_item/get.json',
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
				url: 'di/m2_item/set.do',
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
				{iconCls: 'chart_organisation', text: this.bttCategory, handler: this.itemCategory, scope: this},
				{iconCls: 'application_form', text: this.bttChars, handler: this.itemChars, scope: this},
				{iconCls: 'application_view_tile', text: this.bttFiles, handler: this.filesList, scope: this},
				{iconCls: 'page_white_gear', text: this.bttTexts, handler: this.itemTexts, scope: this},
				{iconCls: 'page_white_gear', text: this.bttLinks, handler: this.itemLinks, scope: this},
				{iconCls: 'application_form', text: this.bttVariation, handler: this.itemVariation, scope: this}
			]
		});
		this.p = new ui.m2_item_price.main({height:150,title:'Цены'});
		this.m = new ui.m2_item_manufacturer.main({height:150,title:'Производитель'});
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
						{fieldLabel:this.lblArticle, name: 'article'},
						{fieldLabel:this.lblTitle, name: 'title',allowBlank: false},
						{fieldLabel:this.lblName, name: 'name'},
						{fieldLabel: this.lblAvailable, hiddenName: 'not_available', value: 0, xtype: 'combo', anchor: '90%',
										store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [[0, 'В продаже'],[1, 'Не доступен']]}),
										valueField: 'value', displayField: 'title', mode: 'local',
										triggerAction: 'all', selectOnFocus: true, editable: false
						},
						{fieldLabel: this.fldMetaTitle, name: 'meta_title',xtype: 'textarea'},
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
		ui.m2_item.form.superclass.constructor.call(this, config);
		this.on({
			data_saved: function(data, id){
				var prev = this.getForm().findField('_sid').getValue();
				if(!(prev>0)){
					this.p.setParams({'_sitem_id':id},true);
					this.m.setParams({'_sitem_id':id},true);
				}
				this.getForm().setValues([{id: '_sid', value: id}]);
			},
			data_loaded: function(data, id){
				this.p.setParams({'_sitem_id':data.id},true);
				this.m.setParams({'_sitem_id':data.id},true);
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
				var f = new ui.m2_item_files.main();
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
		app.Load('m2_item_files', 'main');
	},

	itemTexts: function(b, e){
		var fm = this.getForm();
		var vals = fm.getValues();
		if(!(vals._sid>0)){
			showError(this.msgNotDefined);
			return;
		}
		var app = new App({waitMsg: 'Загрузка формы'});
		app.on({
			apploaded: function(){
				var f = new ui.m2_item_text.main();
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
		app.Load('m2_item_text', 'main');
	},
	itemChars: function(b, e){
		var fm = this.getForm();
		var vals = fm.getValues();
		if(!(vals._sid>0)){
			showError(this.msgNotDefined);
			return;
		}
		var app = new App({waitMsg: 'Загрузка формы'});
		app.on({
			apploaded: function(){
				var f = new ui.m2_chars.main();
				f.setParams({'_spid':vals._sid,'_starget_type':'2'},true);
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
		app.Load('m2_chars', 'main');
	},
	itemCategory: function(b, e){
		var fm = this.getForm();
		var vals = fm.getValues();
		if(!(vals._sid>0)){
			showError(this.msgNotDefined);
			return;
		}
		var app = new App({waitMsg: 'Загрузка формы'});
		app.on({
			apploaded: function(){
				var f = new ui.m2_item_category.main();
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
		app.Load('m2_item_category', 'main');
	},
	itemLinks: function(b, e){
		var fm = this.getForm();
		var vals = fm.getValues();
		if(!(vals._sid>0)){
			showError(this.msgNotDefined);
			return;
		}
		var app = new App({waitMsg: 'Загрузка формы'});
		app.on({
			apploaded: function(){
				var f = new ui.m2_item_links.main();
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
		app.Load('m2_item_links', 'main');
	},

	itemVariation: function(b, e){
		var fm = this.getForm();
		var vals = fm.getValues();
		if(!(vals._sid>0)){
			showError(this.msgNotDefined);
			return;
		}
		var app = new App({waitMsg: 'Загрузка формы'});
		app.on({
			apploaded: function(){
				var f = new ui.m2_item_variation.main();
				f.setParams({'_sitem_id':vals._sid},true);
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
		app.Load('m2_item_variation', 'main');
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
