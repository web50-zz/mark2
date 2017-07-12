ui.m2_search.main_filter= Ext.extend(Ext.form.FormPanel, {
	lblID:'ID',
	lblTitle:'Название',
	lblArticle:' Артикул',
	lblCreatedDate:'Дата внесения',
	lblChangedDate:'Дата изменения',
	lblApply:'Применить',
	lblReset:'Сбросить',
	lblFrom:'с',
	lblTo:'по',
	ApplyFilter: function(){
		this.fireEvent('applied', this.getForm().getValues());
	},
	/**
	 * @constructor
	 */
	constructor: function(config)
	{
		Ext.apply(this, {
			layout: 'form',
			border: false,
			frame: true,
			autoScroll: true,
			defaults: {xtype: 'textfield', width: 100, anchor: '100%'},
			labelWidth: 100,
			labelAlign: 'right',
			items: [
				{fieldLabel: this.lblID, name: '_sid'},
				/*
				{title: this.lblCreatedDate, xtype: 'fieldset', defaults: {xtype: 'datefield', width: 100, anchor: null, format: 'Y-m-d'}, items: [
					{fieldLabel: this.lblFrom, name: 'created_date_from'},
					{fieldLabel: this.lblTo, name: 'created_date_to'}
				]},
				{title: this.lblChangedDate, xtype: 'fieldset', defaults: {xtype: 'datefield', width: 100, anchor: null, format: 'Y-m-d'}, items: [
					{fieldLabel: this.lblFrom, name: 'changed_date_from'},
					{fieldLabel: this.lblTo, name: 'changed_date_to'}
				]},
				*/
				{fieldLabel: this.lblTitle, name: '_stitle'},
				{fieldLabel: this.lblArticle, name: '_sarticle'},
				{fieldLabel: 'В продаже', hiddenName: 'not_available', value: 'n', xtype: 'combo', width: '170',anchor: '80%',
								store: new Ext.data.SimpleStore({ fields: ['value', 'title'], data: [['n','Все'],[0, 'В продаже'],[1, 'Не доступен']]}),
								valueField: 'value', displayField: 'title', mode: 'local',
								triggerAction: 'all', selectOnFocus: true, editable: false
				}

			],
			buttonAlign: 'center',
			buttons: [
				{iconCls: 'clean', text: this.lblApply, handler: this.ApplyFilter, scope: this},
				{iconCls: 'cancel', text: this.lblReset, handler: function(){
					this.getForm().reset();
					this.ApplyFilter();
				}, scope: this}
			],
			keys: [
				{key: [Ext.EventObject.ENTER], handler: this.ApplyFilter, scope: this}
			]
		});
		config = config || {};
		Ext.apply(this, config);
		ui.m2_search.main_filter.superclass.constructor.call(this, config);
	},

	/**
	 * To manually set default properties.
	 * 
	 * @param {Object} config Object containing all config options.
	 */
	configure: function(config)
	{
		config = config || {};
		Ext.apply(this, config, config);
	},

	/**
	 * @private
	 * @param {Object} o Object containing all options.
	 *
	 * Initializes the box by inserting into DOM.
	 */
	init: function(o)
	{
	}
});
