ui.m2_manufacturer_group.grid2 = Ext.extend(Ext.grid.EditorGridPanel, {
	cTitle: 'Рассылка',
	pagerSize: 500,
	pagerEmptyMsg: 'Нет рассылок',
	pagerDisplayMsg: 'рассылки с {0} по {1}. Всего: {2}',

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
						 read: 'di/m2_manufacturer_group/choice.js'
						 /*
						,create: 'di/mailer_contacts/set.js'
						,update: 'di/mailer_contacts/mset.js'
						,destroy: 'di/mailer_contacts/unset.js'
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
					{header: this.cTitle,  dataIndex: 'title', id: 'expand'}
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
		ui.m2_manufacturer_group.grid2.superclass.constructor.call(this, config);
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
