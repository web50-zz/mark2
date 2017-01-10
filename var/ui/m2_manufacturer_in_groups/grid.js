ui.m2_manufacturer_in_groups.grid = Ext.extend(Ext.grid.EditorGridPanel, {
	cCompany: 'Компания',
	cEmail: 'Производитель',

	pagerSize: 50,
	pagerEmptyMsg: 'Нет подписчиков',
	pagerDisplayMsg: 'Подписчики с {0} по {1}. Всего: {2}',

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
		return this.getStore().baseParams._slid;
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
						read: 'di/m2_manufacturer_in_groups/list.js',
						create: 'di/m2_manufacturer_in_groups/set.js',
						update: 'di/m2_manufacturer_in_groups/mset.js',
						destroy: 'di/m2_manufacturer_in_groups/unset.js'
					}
				}),
				reader: new Ext.data.JsonReader({
						totalProperty: 'total',
						successProperty: 'success',
						idProperty: 'id',
						root: 'records',
						messageProperty: 'errors'
					},
					[
						{name: 'id', type: 'int'},
						{name: 'company_id', type: 'int'},
						'title',
						'email',
					]
				),
				writer: new Ext.data.JsonWriter({
					encode: true,
					listful: true,
					writeAllFields: false
				}),
				remoteSort: true,
				sortInfo: {field: 'title', direction: 'ASC'}
			})
		});
		var chk = new Ext.grid.CheckboxSelectionModel({
			width: 20
		});
		Ext.apply(this, {
			loadMask: true,
			stripeRows: true,
			autoScroll: true,
			autoExpandColumn: 'expand',
			selModel: chk,
			colModel: new Ext.grid.ColumnModel({
				defaults: {
					sortable: true,
					width: 200
				},
				columns: [
					chk,
					{header: this.cEmail, dataIndex: 'title',id:'expand'}
				]
			}),
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
		ui.m2_manufacturer_in_groups.grid.superclass.constructor.call(this, config);
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
