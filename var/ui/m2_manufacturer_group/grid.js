ui.m2_manufacturer_group.grid = Ext.extend(Ext.grid.EditorGridPanel, {
	cTitle: 'Наименование',

	pagerSize: 50,
	pagerEmptyMsg: 'Нет списков',
	pagerDisplayMsg: 'Списки с {0} по {1}. Всего: {2}',

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
	set_list_source: function(url){
		var s = this.getStore();
		this.getStore().proxy.api.read.url = url;
	},
	constructor: function(config)
	{
		Ext.apply(this, {
			store: new Ext.data.Store({
				proxy: new Ext.data.HttpProxy({
					api: {
						read: 'di/m2_manufacturer_group/list.js',
						create: 'di/m2_manufacturer_group/set.js',
						update: 'di/m2_manufacturer_group/mset.js',
						destroy: 'di/m2_manufacturer_group/unset.js'
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
						'title'
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
		Ext.apply(this, {
		});
		Ext.apply(this, {
			loadMask: true,
			stripeRows: true,
			autoScroll: true,
			autoExpandColumn: 'expand',
			selModel: new Ext.grid.RowSelectionModel({singleSelect: true}),
			colModel: new Ext.grid.ColumnModel({
				defaults: {
					sortable: true,
					width: 120
				},
				columns: [
					{header: 'ID', dataIndex: 'id'},
					{header: this.cTitle, dataIndex: 'title', id: 'expand'}
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
		ui.m2_manufacturer_group.grid.superclass.constructor.call(this, config);
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
