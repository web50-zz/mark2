ui.m2_item.grid2 = Ext.extend(Ext.grid.EditorGridPanel, {
	cContact: 'id',
	cCompany: 'Производитель',
	pagerSize: 500,
	pagerEmptyMsg: 'Нет контактов',
	pagerDisplayMsg: 'Контакты с {0} по {1}. Всего: {2}',

	setParams: function(params, reload){
		var s = this.getStore();
		params = params || {};
		for (var i in params){if(params[i] === ''){delete params[i]}}
		s.baseParams = params;
		if (reload) s.load({params:{start: 0, limit: this.pagerSize}});
	},
	applyParams: function(params, reload){
		var s = this.getStore();
		params = params || {};
		for (var i in params){if(params[i] === ''){delete params[i]}}
		Ext.apply(s.baseParams, params);
		if (reload) s.load({params:{start: 0, limit: this.pagerSize}});
	},
	getKey: function(){
		return this.getStore().baseParams._scompany_id;
	},
	/**
	 * @constructor
	 */
	constructor: function(config)
	{
		Ext.apply(this, {
			store: new Ext.data.Store({
				proxy: new Ext.data.HttpProxy({
					api: {
						 read: 'di/m2_item/choice.js'
						 /*
						,create: 'di/m2_item/set.js'
						,update: 'di/m2_item/mset.js'
						,destroy: 'di/m2_item/unset.js'
						*/
					}
				}),
				reader: new Ext.data.JsonReader({
						totalProperty: 'total',
						successProperty: 'success',
						idProperty: 'id',
						root: 'records',
						messageProperty: 'errors'
					}, [
						{name: 'id', type: 'int'},
						'title'
					]
				),
				writer: new Ext.data.JsonWriter({
					encode: true,
					listful: true,
					writeAllFields: false
				}),
				autoLoad: false,
				remoteSort: true,
				sortInfo: {field: 'title', direction: 'ASC'}
			}),
		});
		var chk = new Ext.grid.CheckboxSelectionModel({
			width: 20
		});
		Ext.apply(this, {
			loadMask: true,
			autoScroll: true,
			autoExpandColumn: 'expand',
			selModel: chk,
			colModel: new Ext.grid.ColumnModel({
				defaults: {
					sortable: true
				},
				columns: [
					chk,
					{header: this.cContact,  dataIndex: 'id', width: 70},
					{header: this.cCompany,  dataIndex: 'title', id: 'expand'}
				]
			})
		});
		Ext.apply(this, {
			bbar: new Ext.PagingToolbar({
				pageSize: this.pagerSize,
				store: this.store,
				displayInfo: true,
				displayMsg: this.pagerDisplayMsg,
				emptyMsg: this.pagerEmptyMsg
			})
		});
		config = config || {};
		Ext.apply(this, config);
		ui.m2_item.grid2.superclass.constructor.call(this, config);
		this.init();
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
