ui.m2_item_in_groups.subscribed_to = Ext.extend(Ext.grid.EditorGridPanel, {
	cTitle: 'Группы рассылок',

	pagerSize: 50,
	pagerEmptyMsg: 'Нет рассылок',
	pagerDisplayMsg: 'Рассылки с {0} по {1}. Всего: {2}',
	cnfrmMsg: 'Вы действительно хотите отписать?',
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
	// для внешнх операции  по созданию
	addExt: function(list_id,message_id){
		if (list_id > 0 && message_id >0)
		{
			Ext.Ajax.request({
				url: 'di/m2_item_in_groups/set.do',
				params: {mailer_list_id: list_id, contact_id: message_id},
				disableCaching: true,
				callback: function(options, success, response){
					var d = Ext.util.JSON.decode(response.responseText);
					if (success && d.success){
						this.fireEvent('subscribed');
						this.getStore().reload();
					}else
						showError(d.error);
				},
				scope: this
			});
		}
	},
	Delete: function(){
		var s = this.getSelectionModel().getSelections();
		if (!s) return false;
		Ext.Msg.confirm(this.cnfrmTitle, this.cnfrmMsg, function(btn){
			if (btn == "yes"){
				var id = new Array();
				for (var i = 0; i < s.length; i++){id.push(s[i].get('id'));}
				Ext.Ajax.request({
					url: 'di/m2_item_in_groups/unset.do',
					params: {'_sid[]': id},
					disableCaching: true,
					callback: function(options, success, response){
						var d = Ext.util.JSON.decode(response.responseText);
						if (success && d.success)
							this.getStore().reload();
						else
							showError(d.error);
					},
					scope: this
				});
			}
		}, this);
	},
	constructor: function(config)
	{
		Ext.apply(this, {
			store: new Ext.data.Store({
				proxy: new Ext.data.HttpProxy({
					api: {
						read: 'di/m2_item_in_groups/list_subs.js',
						create: 'di/m2_item_in_groups/set.js',
						update: 'di/m2_item_in_groups/mset.js',
						destroy: 'di/m2_item_in_groups/unset.js'
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
						'list_id',
						'mail_id',
						'title',
					]
				),
				writer: new Ext.data.JsonWriter({
					encode: true,
					listful: true,
					writeAllFields: false
				}),
				remoteSort: true,
				sortInfo: {field: 'created_datetime', direction: 'DESC'}
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
					{header: this.cTitle, dataIndex: 'title',id:'expand'}
				]
			}),
			tbar: [
				{iconCls: 'delete', handler: this.Delete, scope: this}
			],
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
		ui.m2_item_in_groups.subscribed_to.superclass.constructor.call(this, config);

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
